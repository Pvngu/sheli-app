<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\Salesman;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Model::unguard();

        $company = Company::where('is_global', 0)->first();

        // Worker
        $workerRole = Role::withoutGlobalScope(CompanyScope::class)
        ->where('name', 'worker')->first();
        User::factory()->count(100)->create([
        'company_id' => $company->id,
        'role_id' => $workerRole->id,
        'user_type' => 'staff_members'
        ])->each(function ($user) use ($workerRole) {
        $user->attachRole($workerRole->id);
        });

        // Auditor
        $auditorRole = Role::withoutGlobalScope(CompanyScope::class)
        ->where('name', 'auditor')->first();
        User::factory()->count(10)->create([
        'company_id' => $company->id,
        'role_id' => $auditorRole->id,
        'user_type' => 'staff_members'
        ])->each(function ($user) use ($auditorRole) {
        $user->attachRole($auditorRole->id);
        });

        // Trainer
        $trainerRole = Role::withoutGlobalScope(CompanyScope::class)
        ->where('name', 'trainer')->first();
        User::factory()->count(2)->create([
        'company_id' => $company->id,
        'role_id' => $trainerRole->id,
        'user_type' => 'staff_members'
        ])->each(function ($user) use ($trainerRole) {
        $user->attachRole($trainerRole->id);
        });

        // Supervisor
        $supervisorRole = Role::withoutGlobalScope(CompanyScope::class)
        ->where('name', 'supervisor')->first();
        User::factory()->count(2)->create([
        'company_id' => $company->id,
        'role_id' => $supervisorRole->id,
        'user_type' => 'staff_members'
        ])->each(function ($user) use ($supervisorRole) {
        $user->attachRole($supervisorRole->id);
        });
    }
}
