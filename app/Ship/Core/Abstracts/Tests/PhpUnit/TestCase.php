<?php

namespace App\Ship\Core\Abstracts\Tests\PhpUnit;

use App\Containers\User\Models\User;
use App\Ship\Kernels\ConsoleKernel;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as LaravelTestCase;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends LaravelTestCase
{
    use DatabaseTransactions;

    protected Generator $faker;

    /**
     * @inheritDoc
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../../../bootstrap/app.php';

        $app->make(ConsoleKernel::class)->bootstrap();

        $this->faker = $app->make(Generator::class);

        return $app;
    }


    /**
     * Что бы запросы отправлялись с токеном пользователя
     *
     * @param User|string|null $user
     * @return $this
     */
    protected function loginAs($user = null): self
    {
        $password = '123456';

        if (is_string($user)) {
            $user = User::whereEmail($user)->first();
        }

        if (! $user) {
            $user = User::factory([
                'password' => Hash::make($password),
                'active' => true
            ])->create();
        }

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        $this->withHeader('Authorization', 'Bearer ' . $token);

        return $this;
    }
}
