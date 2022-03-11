<?php

namespace App\Containers\Option\UserStatus\Repositories;

use App\Containers\Authorization\Models\UserStatus;
use App\Containers\Option\Repositories\OptionRepository;
use App\Containers\Option\UserStatus\Exceptions\UserStatusExistsException;
use App\Containers\Option\UserStatus\Structures\UserStatusStructure;
use App\Containers\User\Models\User;
use App\Ship\Providers\ModelServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Throwable;

class UserStatusOptionRepository extends OptionRepository
{
    public const ACTIVE = 'active';
    public const BLOCK = 'block';

    protected function afterMakeBuilder(): void
    {
        $this->query
            ->where('model', '=', ModelServiceProvider::MAPPING[User::class])
            ->where('name', 'status')
        ;
    }

    /**
     * @param string $name
     * @param string|null $title
     * @return Model|UserStatus
     * @throws Throwable
     * @throws UnknownProperties
     * @throws UserStatusExistsException
     */
    public function createStatus(string $name, string|null $title): Model|UserStatus
    {
        DB::beginTransaction();
        try {
            $this->existsValidation($name);

            $model = clone $this->model;
            /** @var User $user */
            $userStatus = $model->fill([
                'model' => ModelServiceProvider::MAPPING[User::class],
                'name' => 'status',
                'value' => serialize(new UserStatusStructure(title: $title, name: $name))
            ]);
            $userStatus->save();
            $userStatus = $userStatus->refresh();

            DB::commit();

            return $userStatus;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function getAllStatuses()
    {
        return $this->query->get(['id', 'value']);
    }

    /**
     * @param string $name
     * @return void
     * @throws UserStatusExistsException
     */
    private function existsValidation(string $name): void
    {
        $all = $this->getAllStatuses();
        foreach ($all as $status) {
            /** @var UserStatusStructure $structure */

            if (! empty($status->value)) {
                $structure = unserialize(data: $status->value);
                if ($structure->name === $name) {
                    throw new UserStatusExistsException('User status is already exists: ' . $name);
                }
            }
        }
    }
}
