<?php

use App\Classes\PermsSeed;
use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EntrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned()->nullable()->default(null);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating roles to users (Many-to-Many)
        Schema::create('role_user', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->string('module_name')->nullable()->default(null);
            $table->timestamps();
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->bigInteger('permission_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        if (app_type() == 'non-saas') {
            $company = Company::where('is_global', 0)->first();

            $adminRoleId = DB::table('roles')->insertGetId([
                'company_id' => $company->id,
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Admin is allowed to manage everything of the app.'
            ]);

            DB::table('users')->where('name', 'Admin')->update([
                'role_id' => $adminRoleId,
            ]);

            $admin = DB::table('users')->where('name', 'Admin')->first();
            DB::table('role_user')->insert([
                'user_id' => $admin->id,
                'role_id' => $adminRoleId,
            ]);

            DB::table('roles')->insert([
                'company_id' => $company->id,
                'name' => 'supervisor',
                'display_name' => 'Supervisor',
                'description' => 'Supervisor is responsible for overseeing the work and ensuring compliance with safety standards in all areas, including accidents, audits, documents, trainings, and courses.'
            ]);

            DB::table('roles')->insert([
                'company_id' => $company->id,
                'name' => 'worker',
                'display_name' => 'Worker',
                'description' => 'Worker is responsible for performing tasks and following safety protocols in all areas, including accidents, audits, documents, trainings, and courses.'
            ]);

            DB::table('roles')->insert([
                'company_id' => $company->id,
                'name' => 'auditor',
                'display_name' => 'Auditor',
                'description' => 'Auditor is responsible for conducting audits and ensuring compliance with safety standards in all areas, including accidents, audits, documents, trainings, and courses.'
            ]);

            DB::table('roles')->insert([
                'company_id' => $company->id,
                'name' => 'trainer',
                'display_name' => 'Trainer',
                'description' => 'Trainer is responsible for conducting trainings and courses to ensure that all employees are knowledgeable about safety protocols and procedures in all areas, including accidents, audits, documents, trainings, and courses.'
            ]);
        }

        // Seeding Permissions
        PermsSeed::seedMainPermissions();
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('permission_role');
        Schema::drop('permissions');
        Schema::drop('role_user');
        Schema::drop('roles');
    }
}
