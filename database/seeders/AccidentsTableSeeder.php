<?php

namespace Database\Seeders;

use App\Models\Accident;
use Illuminate\Database\Seeder;

class AccidentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Accident::factory()
            ->count(200)
            ->create();
    }
}