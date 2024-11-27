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
            $this->call(LangTableSeeder::class);
            $this->call(CompanyTableSeeder::class);
            $this->call(RolesTableSeeder::class);
            $this->call(UsersTableSeeder::class);
            $this->call(SettingTableSeeder::class);
            $this->call(FormsTableSeeder::class);
        }
    }
}
