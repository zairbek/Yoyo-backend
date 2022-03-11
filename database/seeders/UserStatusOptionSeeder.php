<?php

namespace Database\Seeders;

use App\Containers\Option\UserStatus\Exceptions\UserStatusExistsException;
use App\Containers\Option\UserStatus\Repositories\UserStatusOptionRepository;
use Illuminate\Database\Seeder;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Throwable;

class UserStatusOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param UserStatusOptionRepository $repository
     * @return void
     * @throws UnknownProperties
     * @throws UserStatusExistsException
     * @throws Throwable
     */
    public function run(UserStatusOptionRepository $repository): void
    {
        collect([
            UserStatusOptionRepository::ACTIVE,
            UserStatusOptionRepository::BLOCK,
        ])->each(function ($status) use ($repository) {
            $repository->createStatus(name: $status, title: ucfirst($status));
        });
    }
}
