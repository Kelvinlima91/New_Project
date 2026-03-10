<?php
// database/migrations/2024_01_01_000009_create_user_limits_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('daily_counter')->default(0);
            $table->integer('monthly_counter')->default(0);
            $table->boolean('blocked')->default(false);
            $table->timestamp('blocked_until')->nullable();
            $table->timestamps();

            // Ensure one record per user per day
            $table->unique(['user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_limits');
    }
};
