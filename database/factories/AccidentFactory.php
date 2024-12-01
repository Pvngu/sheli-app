<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Accident>
 */
class AccidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'injured_person' => $this->faker->numberBetween(1, 1),
            'reporting_user' => $this->faker->numberBetween(1, 1),
            'area_id' => $this->faker->numberBetween(1, 6),
            'days_absent' => $this->faker->numberBetween(1, 10),
            'description' => $this->faker->text,
            'status' => $this->faker->randomElement(['reported', 'in_progress', 'resolved']),
            'date_of_accident' => $this->faker->dateTimeBetween('2023-01-01', '2024-11-28')->format('Y-m-d'),
        ];
    }
}
