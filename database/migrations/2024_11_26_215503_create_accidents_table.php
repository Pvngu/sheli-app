<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accidents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('injured_person')->unsigned();
            $table->bigInteger('reporting_user')->unsigned();
            $table->bigInteger('area_id')->unsigned();
            $table->integer('days_absent')->nullable();
            $table->dateTime('date_of_accident');
            $table->string('description');
            $table->string('status');
            $table->timestamps();

            $table->foreign('injured_person')->references('id')->on('users');
            $table->foreign('reporting_user')->references('id')->on('users');
            $table->foreign('area_id')->references('id')->on('areas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accidents');
    }
};
