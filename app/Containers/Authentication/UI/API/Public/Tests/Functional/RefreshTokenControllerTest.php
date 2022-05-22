<?php

namespace App\Containers\Authentication\UI\API\Public\Tests\Functional;

use App\Containers\Authentication\Adapters\Passport;
use App\Containers\Authentication\Tests\ApiTestCase;
use App\Containers\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;
use Laravel\Passport\Database\Factories\ClientFactory;

class RefreshTokenControllerTest extends ApiTestCase
{
    use DatabaseMigrations;

    private array $tokens;
    private Collection|Model $client;

    protected function setUp(): void
    {
        parent::setUp();

        $user = new User();
        $user->login = 'test';
        $user->email = 'test@gmail.com';
        $user->password = '12345678';
        $user->save();

        $this->client = ClientFactory::new()->asPasswordClient()->create();

        $this->tokens = Passport::getTokenAndRefreshToken([
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'email' => $user->email,
            'password' => '12345678'
        ]);
    }

    protected function tearDown(): void
    {
        unset($this->client, $this->tokens);

        parent::tearDown();
    }

    public function test(): void
    {
        $this->encryptCookies = false;

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Client-Id' => $this->client->id,
                'Client-Secret' => $this->client->secret
            ])
            ->withCookie('refresh-token', $this->tokens['refresh_token'])
            ->post(route('public.refreshToken'))
        ;

        $response->assertSuccessful();

        // Testing tokens
        $this->assertArrayHasKey('token', $response->json());
        $this->assertArrayHasKey('token_type', $response->json('token'));
        $this->assertArrayHasKey('expires_in', $response->json('token'));
        $this->assertArrayHasKey('access_token', $response->json('token'));

        // Testing cookies
        $response->assertCookie('refresh-token');
    }

}
