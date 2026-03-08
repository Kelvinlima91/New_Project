<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Creates tables for rate limiting and abuse prevention.
     * Protects the system from malicious users.
     */
    public function up(): void
    {
        // Phone-based limits (for anonymous complainants)
        Schema::create('phone_limits', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->index();
            $table->date('date');
            $table->integer('daily_counter')->default(0);
            $table->integer('monthly_counter')->default(0);
            $table->boolean('blocked')->default(false);
            $table->timestamp('blocked_until')->nullable();
            $table->timestamps();

            $table->unique(['phone', 'date']);
        });

        // User-based limits (for registered citizens)
        Schema::create('user_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('daily_counter')->default(0);
            $table->integer('monthly_counter')->default(0);
            $table->boolean('blocked')->default(false);
            $table->timestamp('blocked_until')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'date']);
        });

        // Suspicious activity tracking
        Schema::create('suspicious_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('ip_address', 45);
            $table->string('action', 50);
            $table->boolean('blocked')->default(false);
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index(['ip_address', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suspicious_attempts');
        Schema::dropIfExists('user_limits');
        Schema::dropIfExists('phone_limits');
    }
};
