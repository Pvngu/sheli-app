<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $completionDate = $this->faker->optional()->dateTimeBetween('now', '+1 year');

        return [
            'user_id' => $this->faker->numberBetween(2, 101),
            'course_id' => $this->faker->numberBetween(1, 6),
            'enrollment_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'completion_date' => $completionDate ? $completionDate->format('Y-m-d') : null,
            'status' => $this->faker->randomElement(['completed', 'pending']),
        ];
    }
}