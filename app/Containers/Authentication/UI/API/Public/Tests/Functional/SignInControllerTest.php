<?php

namespace App\Containers\Authentication\UI\API\Public\Tests\Functional;

use App\Containers\Authentication\Tests\ApiTestCase;
use App\Containers\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Laravel\Passport\Database\Factories\ClientFactory;

class SignInControllerTest extends ApiTestCase
{
    use DatabaseTransactions;

    private User $user;
    private Collection|Model $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
        $this->user->phone_number = '996772119663';
        $this->user->confirm_code = '0000';
        $this->user->save();

        $this->client = ClientFactory::new()->asPasswordClient()->create();
    }

    protected function tearDown(): void
    {
        unset($this->user, $this->client);

        parent::tearDown();
    }

    public function testSuccessfullySignInSendSmsRegister()
    {
        $response = $this
            ->withHeaders([
                'client-id' => $this->client->id,
                'client-secret' => $this->client->secret
            ])
            ->postJson(route('public.auth.send'), [
                'phone' => "996777123456",
            ]);

        dd($response->json());
    }

    public function testSuccessfullySignInSendSmsLogin()
    {
        $response = $this
            ->withHeaders([
                'client-id' => $this->client->id,
                'client-secret' => $this->client->secret
            ])
            ->postJson(route('public.auth.send'), [
                'phone' => $this->user->phone_number,
            ]);
    }

    public function testSuccessfullySignIn()
    {
        $response = $this
            ->withHeaders([
                'client-id' => $this->client->id,
                'client-secret' => $this->client->secret
            ])
            ->postJson(route('public.auth.signIn'), [
                'phone' => $this->user->phone_number,
                'code' => $this->user->confirm_code
            ]);

        dd($response->json());

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
