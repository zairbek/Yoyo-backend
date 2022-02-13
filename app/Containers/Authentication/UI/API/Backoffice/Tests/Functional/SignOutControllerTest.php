<?php

namespace App\Containers\Authentication\UI\API\Backoffice\Tests\Functional;

use App\Containers\Authentication\Adapters\Passport;
use App\Containers\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Passport\Database\Factories\ClientFactory;
use Laravel\Passport\Passport as PassportModel;
use Symfony\Component\HttpFoundation\Cookie;

class SignOutControllerTest extends \App\Containers\Authentication\Tests\ApiTestCase
{
    private mixed $tokens;

    private Collection|Model $client;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
        $this->user->login = 'test';
        $this->user->email = 'test@gmail.com';
        $this->user->password = '12345678';
        $this->user->save();

        $this->client = ClientFactory::new()->asPasswordClient()->create();

        $this->tokens = Passport::getTokenAndRefreshToken([
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'email' => $this->user->email,
            'password' => '12345678'
        ]);
    }

    protected function tearDown(): void
    {
        unset($this->user, $this->client, $this->tokens);

        parent::tearDown();
    }

    public function test()
    {
        $this->encryptCookies = false;

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => $this->tokens['token_type'] . ' ' . $this->tokens['access_token']
            ])
            ->withCookie('refresh-token', $this->tokens['refresh_token'])
            ->getJson(route('backoffice.signOut'))
        ;

        $response->assertSuccessful();

        // Testing Cookie Refresh-Token
        $response->assertCookie('refresh-token');

        foreach ($response->headers->getCookies() as $cookie) {
            if ($cookie->getName() === 'refresh-token') {
                $this->assertNull($cookie->getValue());
                $this->assertTrue($cookie->getExpiresTime() < Carbon::now()->timestamp);
            }
        }

        // Testing Access-Token
        $accessToken = $this->user->tokens()->first(['id', 'revoked']);
        $this->assertNotNull($accessToken);
        $this->assertTrue($accessToken['revoked']);

        // Testing Refresh-Token
        $passportModel = PassportModel::refreshToken()->where('access_token_id', $accessToken['id'])->first();
        $this->assertTrue($passportModel->revoked);
    }

}
