<?php

namespace App\Containers\Authentication\Actions;

use App\Containers\Authentication\Adapters\Passport;
use App\Containers\User\Models\User;
use App\Ship\Core\Abstracts\Actions\Action;
use JsonException;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;

class GenerateTokensByUserAction extends Action
{
    /**
     * @throws UniqueTokenIdentifierConstraintViolationException
     * @throws OAuthServerException
     * @throws JsonException
     */
    public function run(User $user, string $oauthClientId): array
    {
        return Passport::getBearerTokenByUser($user, $oauthClientId);
    }
}
