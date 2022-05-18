<?php

namespace App\Containers\Account\UI\API\Public\Tests\Functional;

use App\Containers\Authentication\Adapters\Passport;
use App\Containers\Authorization\Models\Permission;
use App\Containers\Authorization\Models\Role;
use App\Containers\User\Enums\Status;
use App\Containers\User\Models\User;
use App\Containers\User\Tests\ApiTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Database\Factories\ClientFactory;

class GetAccountControllerTest extends ApiTestCase
{
    use DatabaseMigrations;

    private User $user;

    private mixed $tokens;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::factory()
            ->has(Permission::factory()->state(['name' => 'user@read', 'guard_name' => 'api']))
            ->state(['name' => 'user', 'guard_name' => 'api'])
        ;

        $this->user = User::factory()
            ->state(['status' => Status::Active->value])
            ->has($role)
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
            ->getJson(route('public.account'));

        dd($response->json());
        $response->assertSuccessful();
        $this->assertEquals($this->user->email, $response['email']);
    }
}
