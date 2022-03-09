<?php

namespace Database\Factories;

use App\Containers\Authorization\Models\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserStatusFactory extends Factory
{
    protected $model = UserStatus::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $selected = $this->faker->randomElement([UserStatus::BLOCK, UserStatus::ACTIVE]);

        return [
            'title' => ucfirst($selected),
            'name' => $selected,
        ];
    }
}
