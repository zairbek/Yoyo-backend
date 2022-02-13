<?php

namespace Database\Seeders;

use App\Containers\Authorization\Repositories\PermissionRepository;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(PermissionRepository $repository)
    {
        $permissions = collect([
            'admin' => [
                ['name' => 'show',      'title' => 'Админ панель']
            ],
            'profile' => [
                ['name' => 'show',      'title' => 'Просмотр'],
                ['name' => 'edit',      'title' => 'Изменение'],
                ['name' => 'delete',    'title' => 'Удаление'],
            ],
            'users' => [
                ['name' => 'show',      'title' => 'Просмотр'],
                ['name' => 'create',    'title' => 'Создание'],
                ['name' => 'edit',      'title' => 'Изменение'],
                ['name' => 'delete',    'title' => 'Удаление'],
            ],
            'roles' => [
                ['name' => 'show',      'title' => 'Просмотр'],
                ['name' => 'create',    'title' => 'Создание'],
                ['name' => 'edit',      'title' => 'Изменение'],
                ['name' => 'delete',    'title' => 'Удаление'],
            ],
            'permissions' => [
                ['name' => 'show',      'title' => 'Просмотр'],
                ['name' => 'create',    'title' => 'Создание'],
                ['name' => 'edit',      'title' => 'Изменение'],
            ],
        ]);

        $permissions->each(function ($item, $key) use ($repository) {
            foreach ($item as $permission) {
                $name = $key . '@' . $permission['name'];

                $repository->updateOrCreate(
                    ['name' => $name],
                    ['title' => $permission['title']]
                );
            }
        });
    }
}
