<?php

namespace Database\Factories;

use App\Containers\Authorization\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomLetter,
            'guard_name' => $this->faker->randomElement(['api', 'web']),
        ];
    }
}
