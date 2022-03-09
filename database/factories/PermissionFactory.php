<?php

namespace Database\Factories;

use App\Containers\Authorization\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;
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
