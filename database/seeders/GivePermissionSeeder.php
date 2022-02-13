<?php

namespace Database\Seeders;

use App\Containers\Authorization\Models\Role;
use App\Containers\Authorization\Repositories\PermissionRepository;
use App\Containers\Authorization\Repositories\RoleRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GivePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        /** @var Role $admin */
        $admin = $roleRepository->findByField('name', 'admin')->first();
        $permissions = $permissionRepository->all();
        $admin->givePermissionTo($permissions);
    }
}
