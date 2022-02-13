<?php

namespace App\Containers\User\UI\API\Backoffice\Tests\Functional;

use App\Containers\Authentication\Adapters\Passport;
use App\Containers\User\Models\User;
use App\Containers\User\Tests\ApiTestCase;
use Laravel\Passport\Database\Factories\ClientFactory;

class GetAccountControllerTest extends ApiTestCase
{
    private User $user;

    private mixed $tokens;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $client = ClientFactory::new()->asPasswordClient()->create();

        $this->tokens = Passport::getTokenAndRefreshToken([
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'email' => $this->user->email,
            'password' => 'password'
        ]);
    }

    protected function tearDown(): void
    {
        unset($this->user, $this->tokens);

        parent::tearDown();
    }

    public function test(): void
    {
        $response = $this
            ->withHeaders([
                'Authorization' => $this->tokens['token_type'] . ' ' . $this->tokens['access_token']
            ])
            ->getJson(route('backoffice.account'));

        dd($response->json());
        $response->assertSuccessful();
        $this->assertEquals($this->user->email, $response['email']);
    }
}
