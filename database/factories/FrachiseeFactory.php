<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FrachiseeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'type' => "F",
            'phone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'zip_code' => '57080-030',
            'address' => fake()->address(),
            'number' => fake()->numberBetween(5, 100),
            'district' => fake()->country(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'email' => fake()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            //'remember_token' => Str::random(10),
        ];
    }
}
