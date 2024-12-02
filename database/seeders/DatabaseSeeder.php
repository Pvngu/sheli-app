<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (env('APP_ENV') == 'testing') {
            $this->call(AreasTableSeeder::class);
            $this->call(AccidentsTableSeeder::class);
            $this->call(AuditsTableSeeder::class);
        }
    }
}
