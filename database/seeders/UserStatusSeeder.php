<?php

namespace Database\Seeders;

use App\Containers\Authorization\Models\UserStatus;
use App\Containers\Authorization\Repositories\UserStatusRepository;
use Illuminate\Database\Seeder;

class UserStatusSeeder extends Seeder
{
    public const USER_STATUSES = [
        UserStatus::ACTIVE => ['title' => 'Active'],
        UserStatus::BLOCK => ['title' => 'Block'],
    ];

    /**
     * Run the database seeds.
     *
     * @param UserStatusRepository $repository
     * @return void
     */
    public function run(UserStatusRepository $repository): void
    {
        collect(self::USER_STATUSES)->each(function ($item, $statusName) use ($repository) {
            $repository->updateOrCreate(
                ['name' => $statusName],
                ['title' => $item['title']]
            );
        });
    }
}
