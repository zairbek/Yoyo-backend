<?php

namespace Database\Seeders;

use App\Containers\User\Models\User;
use App\Containers\User\Repositories\UserRepository;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public const USERS = [
        'admin' =>      ['email' => 'admin@gmail.com', 'first_name' => 'Админ', 'password' => '12345678'],
        'manager' =>    ['email' => 'manager@gmail.com', 'first_name' => 'Менеджер', 'password' => '12345678'],
        'user' =>       ['email' => 'user@gmail.com', 'first_name' => 'Пользователь', 'password' => '12345678'],
    ];

    /**
     * Run the database seeds.
     *
     * @param UserRepository $repository
     * @return void
     */
    public function run(UserRepository $repository): void
    {
        collect(self::USERS)->each(function ($item, $role) use ($repository) {
            $repository->updateOrCreate(
                ['login' => explode('@', $item['email'])[0], 'email' => $item['email']],
                ['first_name' => $item['first_name'], 'password' => $item['password']],
                $role
            );
        });
    }
}
