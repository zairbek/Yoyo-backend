<?php

namespace App\Containers\Account\UI\API\Backoffice\Tests\Functional;

use App\Containers\Authentication\Adapters\Passport;
use App\Containers\Authorization\Models\Role;
use App\Containers\Authorization\Models\UserStatus;
use App\Containers\User\Models\User;
use App\Containers\User\Tests\ApiTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Database\Factories\ClientFactory;
use function dd;
use function route;

class GetAccountControllerTest extends ApiTestCase
{
    use DatabaseMigrations;

    private User $user;

    private mixed $tokens;

    protected function setUp(): void
    {
        parent::setUp();

        $roles = Role::where('name', 'user')->get();

        $this->user = User::factory()
            ->for(UserStatus::factory()->state(['name' => UserStatus::ACTIVE]))
//            ->has($roles)
            ->create();

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
