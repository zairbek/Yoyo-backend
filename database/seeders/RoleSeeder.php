<?php

namespace Database\Seeders;

use App\Containers\Authorization\Repositories\RoleRepository;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(RoleRepository $repository)
    {
        $roles = collect([
            ['name' => 'admin', 'title' => 'Администратор'],
            ['name' => 'manager', 'title' => 'Менеджер'],
            ['name' => 'user', 'title' => 'Пользователь'],
        ]);

        $roles->each(function ($item) use ($repository) {
            $repository->updateOrCreate(
                ['name' => $item['name']],
                ['title' => $item['title']]
            );
        });
    }
}
