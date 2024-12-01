<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Audit;

class AuditsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Audit::factory()->count(10)->create();
    }
}