<?php

namespace App\Containers\Authentication\UI\API\Backoffice\Tests\Functional;

use App\Containers\Authentication\Tests\ApiTestCase;

class SignInControllerTest extends ApiTestCase
{
    public function test()
    {
        $response = $this
            ->withHeaders([
                'Client-Id' => '2',
                'Client-Secret' => 'le6iZZLYOo1JksFJ2SyANxDUvtRLYBNayuRtIRvS',
            ])
            ->postJson(route('backoffice.signIn'), [
                'email' => 'admin@gmail.com',
                'password' => '12345678'
            ]);

        dd($response->json());
    }
}
