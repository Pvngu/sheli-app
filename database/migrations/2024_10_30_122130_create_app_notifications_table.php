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
        Schema::create('app_notifications', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                'sale_transfer',
                'follow_up_share',
                'payment',
                'signed_document',
                'follow_up_reminder',
                'follow_up_missed',
            ]);
            $table->bigInteger('individual_id')->unsigned()->nullable()->default(null);
            $table->foreign('individual_id')->references('id')->on('individuals')->onDelete('cascade');
            // $table->bigInteger('enrollment_id')->unsigned()->nullable()->default(null);
            // $table->foreign('enrollment_id')->references('id')->on('enrollments')->onDelete('cascade');
            $table->bigInteger('sender_id')->unsigned()->nullable()->default(null);
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('document_id')->unsigned()->nullable()->default(null);
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->bigInteger('individual_log_id')->unsigned()->nullable()->default(null);
            $table->foreign('individual_log_id')->references('id')->on('individual_logs')->onDelete('cascade');
            $table->string('extra')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_notifications');
    }
};
