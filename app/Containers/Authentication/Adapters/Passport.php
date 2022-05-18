<?php

namespace App\Containers\Authentication\Adapters;

use App\Containers\User\Models\User;
use DateTimeImmutable;
use Error;
use Exception;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Psr7\ServerRequest as GuzzleRequest;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\App;
use JsonException;
use Laravel\Passport\Bridge\AccessToken;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\Client;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport as LaravelPassport;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;
use Psr\Http\Message\ResponseInterface;
use TypeError;

class Passport
{
    /**
     * @param array $request
     * @return array
     * @throws JsonException
     * @throws OAuthServerException
     */
    public static function getTokenAndRefreshToken(array $request): array
    {
        /** @var AuthorizationServer $server */
        $server = App::make(AuthorizationServer::class);

        $psrResponse = $server
            ->respondToAccessTokenRequest(
                (new GuzzleRequest('POST', ''))
                    ->withParsedBody([
                        'grant_type' => 'password',
                        'client_id' => $request['client_id'],
                        'client_secret' => $request['client_secret'],
                        'username' => $request['email'],
                        'password' => $request['password'],
                        'scope' => '',
                    ]),
                new GuzzleResponse()
            )
        ;

        return (array) json_decode((string)$psrResponse->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param array $request
     * @param $refreshToken
     * @return array
     * @throws JsonException
     * @throws OAuthServerException
     */
    public static function generateRefreshToken(array $request, $refreshToken): array
    {
        /** @var AuthorizationServer $server */
        $server = App::make(AuthorizationServer::class);

        $psrResponse = $server
            ->respondToAccessTokenRequest(
                (new GuzzleRequest('POST', ''))
                    ->withParsedBody([
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $refreshToken,
                        'client_id' => $request['client_id'],
                        'client_secret' => $request['client_secret'],
                        'scope' => '',
                    ]),
                new GuzzleResponse()
            )
        ;

        return json_decode((string)$psrResponse->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param string $tokenId
     */
    public static function revokeAccessAndRefreshTokens(string $tokenId): void
    {
        $tokenRepository = app(TokenRepository::class);
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        $tokenRepository->revokeAccessToken($tokenId);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);
    }

    /**
     * @param User $user
     * @param $clientId
     * @return array
     * @throws OAuthServerException
     * @throws UniqueTokenIdentifierConstraintViolationException
     * @throws JsonException
     */

    public static function getBearerTokenByUser(User $user, $clientId): array
    {
        $passportToken = self::createPassportTokenByUser($user, $clientId);
        $bearerToken = self::sendBearerTokenResponse($passportToken['access_token'], $passportToken['refresh_token']);

        $tokens = json_decode($bearerToken->getBody()->__toString(), true, 512, JSON_THROW_ON_ERROR);

        return [
            'access_token' => [
                'token_type' => $tokens['token_type'],
                'expires_in' => $tokens['expires_in'],
                'access_token' => $tokens['access_token'],
            ],
            'refresh_token' => $tokens['refresh_token']
        ];
    }

    /**
     * @param User $user
     * @param $clientId
     * @return array
     * @throws OAuthServerException
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    private static function createPassportTokenByUser(User $user, $clientId): array
    {
        $client = new Client($clientId, null, null);
        $accessToken = new AccessToken($user->id, [], $client);
        $accessToken->setIdentifier(self::generateUniqueIdentifier());

        if (config('passport.private_key')) {
            $privateKey = new CryptKey(config('passport.private_key'), null, false);
        } else {
            $privateKey = new CryptKey('file://' . LaravelPassport::keyPath('oauth-private.key'), null, false);
        }

        $accessToken->setPrivateKey($privateKey);
        $accessToken->setExpiryDateTime((new DateTimeImmutable())->add(LaravelPassport::tokensExpireIn()));

        $dispatch = app(\Illuminate\Contracts\Events\Dispatcher::class);
        $dispatch->dispatch(new AccessTokenCreated(
            $accessToken->getIdentifier(),
            $accessToken->getUserIdentifier(),
            $accessToken->getClient()->getIdentifier()
        ));

        $accessTokenRepository = new AccessTokenRepository(new TokenRepository(), new Dispatcher());
        $accessTokenRepository->persistNewAccessToken($accessToken);
        $refreshToken = self::issueRefreshToken($accessToken);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }

    /**
     * @param $accessToken
     * @param $refreshToken
     * @return ResponseInterface
     */
    private static function sendBearerTokenResponse($accessToken, $refreshToken): ResponseInterface
    {
        $response = new BearerTokenResponse();
        $response->setAccessToken($accessToken);
        $response->setRefreshToken($refreshToken);
        $response->setEncryptionKey(app('encrypter')->getKey());
        return $response->generateHttpResponse(new GuzzleResponse());
    }

    /**
     * Generate a new unique identifier.
     *
     * @param int $length
     * @throws OAuthServerException
     * @return string
     */
    private static function generateUniqueIdentifier(int $length = 40): string
    {
        try {
            return bin2hex(random_bytes($length));
        } catch (TypeError | Error $e) {
            throw OAuthServerException::serverError('An unexpected error has occurred');
        } catch (Exception $e) {
            // If you get this message, the CSPRNG failed hard.
            throw OAuthServerException::serverError('Could not generate a random string');
        }
    }

    /**
     * @throws UniqueTokenIdentifierConstraintViolationException
     * @throws OAuthServerException
     */
    private static function issueRefreshToken(AccessTokenEntityInterface $accessToken)
    {
        $maxGenerationAttempts = 10;
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        $refreshToken = $refreshTokenRepository->getNewRefreshToken();
        if (is_null($refreshToken)) {
            throw OAuthServerException::serverError('Refresh token is null from RefreshTokenRepository');
        }
        $refreshToken->setExpiryDateTime((new DateTimeImmutable())->add(LaravelPassport::refreshTokensExpireIn()));
        $refreshToken->setAccessToken($accessToken);

        while ($maxGenerationAttempts-- > 0) {
            $refreshToken->setIdentifier(self::generateUniqueIdentifier());
            try {
                $refreshTokenRepository->persistNewRefreshToken($refreshToken);

                return $refreshToken;
            } catch (UniqueTokenIdentifierConstraintViolationException $e) {
                if ($maxGenerationAttempts === 0) {
                    throw $e;
                }
            }
        }
    }
}
