<?php

namespace App\Containers\Authentication\UI\API\Backoffice\Tests\Functional;

use App\Containers\Authentication\Tests\ApiTestCase;
use App\Containers\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Passport\Database\Factories\ClientFactory;

class SignInControllerTest extends ApiTestCase
{
    private User $user;
    private Collection|Model $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
        $this->user->login = 'test';
        $this->user->email = 'test@gmail.com';
        $this->user->password = '12345678';
        $this->user->save();

        $this->client = ClientFactory::new()->asPasswordClient()->create();
    }

    protected function tearDown(): void
    {
        unset($this->user, $this->client);

        parent::tearDown();
    }

    public function testSuccessfullySignIn()
    {
        $response = $this
            ->withHeaders([
                'client-id' => $this->client->id,
                'client-secret' => $this->client->secret
            ])
            ->postJson(route('backoffice.signIn'), [
                'email' => $this->user->email,
                'password' => '12345678'
            ]);

        $response->assertSuccessful();

        // Testing tokens
        $this->assertArrayHasKey('token', $response->json());
        $this->assertArrayHasKey('token_type', $response->json('token'));
        $this->assertArrayHasKey('expires_in', $response->json('token'));
        $this->assertArrayHasKey('access_token', $response->json('token'));

        // Testing cookies
        $response->assertCookie('refresh-token');
    }

//    public function test()
//    {
//        $response = $this
//            ->withHeaders([
//                'Client-Id' => '2',
//                'Client-Secret' => 'le6iZZLYOo1JksFJ2SyANxDUvtRLYBNayuRtIRvS',
//            ])
//            ->postJson(route('backoffice.signIn'), [
//                'email' => 'admin@gmail.com',
//                'password' => '12345678'
//            ]);
//
//        dd($response->json());
//    }
}
