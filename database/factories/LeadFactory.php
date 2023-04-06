<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Lead;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lead::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'user_id' => fake()->numberBetween(2,4),
            'email' => fake()->safeEmail(),
            'zip_code' => '57080-030',
            'address' => fake()->address(),
            'number' => fake()->numberBetween(5, 100),
            'district' => fake()->country(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'tag' => 1
        ];
    }
}
