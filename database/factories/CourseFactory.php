<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_name' => $this->generateCourseName(),
            'description' => $this->generateCourseDescription(),
            'trainer_id' => $this->faker->numberBetween(112, 113),
            'status' => $this->faker->randomElement(['active', 'inactive', 'finished']),
            'start_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
        ];
    }

    private function generateCourseName()
    {
        $courseNames = [
            'Factory Safety Training',
            'Lean Manufacturing Principles',
            'Quality Control Techniques',
            'Supply Chain Management',
            'Industrial Maintenance',
            'Production Planning and Control',
            'Workplace Ergonomics',
            'Inventory Management',
            'Six Sigma Fundamentals',
            'Environmental Compliance',
        ];

        return $this->faker->randomElement($courseNames);
    }

    private function generateCourseDescription()
    {
        $descriptions = [
            'This course covers essential safety practices and protocols to ensure a safe working environment in the factory.',
            'Learn the principles of lean manufacturing to improve efficiency and reduce waste in production processes.',
            'Understand various quality control techniques to maintain high standards and minimize defects in products.',
            'Gain insights into effective supply chain management to optimize the flow of materials and products.',
            'Develop skills in industrial maintenance to ensure machinery and equipment are in optimal working condition.',
            'Learn how to plan and control production processes to meet demand and maximize efficiency.',
            'Understand the importance of workplace ergonomics to enhance employee comfort and productivity.',
            'Gain knowledge in inventory management to maintain optimal stock levels and reduce carrying costs.',
            'Explore the fundamentals of Six Sigma to improve processes and eliminate defects.',
            'Learn about environmental compliance to ensure the factory meets regulatory requirements and minimizes its environmental impact.',
        ];

        return $this->faker->randomElement($descriptions);
    }
}