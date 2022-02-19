<?php

namespace Database\Factories;

use App\Containers\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Containers\User\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $email = $this->faker->unique()->safeEmail();

        return [
            'login' => explode('@', $email)[0],
            'email' => $email,
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'middle_name' => null,
            'phone_number' => $this->faker->numerify('7#########'),
            'birthday' => $this->faker->dateTime,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'active' => $this->faker->boolean,
            'online_at' => $this->faker->dateTime,
            'created_at' => $this->faker->dateTime,
            'updated_at' => $this->faker->dateTime,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
