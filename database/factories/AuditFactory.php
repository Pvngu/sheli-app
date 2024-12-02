<?php

namespace Database\Factories;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditFactory extends Factory
{
    protected $model = Audit::class;

    public function definition()
    {
        return [
            'audit_name' => $this->faker->sentence,
            'audit_date' => $this->faker->date,
            'auditor_id' => \App\Models\User::factory(),
            'area_id' => $this->faker->numberBetween(1, 6),
            'findings' => $this->faker->paragraph,
            'corrective_actions' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['pending', 'completed', 'in-progress']),
        ];
    }
}