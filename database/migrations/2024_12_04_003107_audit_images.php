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
        Schema::create('audit_images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('audit_id')->unsigned();
            $table->string('file');
            $table->foreign('audit_id')->references('id')->on('audits')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_images');
    }
};
