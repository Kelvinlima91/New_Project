<?php
// database/migrations/2024_01_01_000007_create_protocol_counters_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Creates the protocol counters table.
     * Ensures unique protocol numbers per year and island.
     * Protocol format: CV-YYYY-Island-XXXXXX
     */
    public function up(): void
    {
        Schema::create('protocol_counters', function (Blueprint $table) {
            $table->id();

            $table->integer('year');
            $table->string('island', 10);
            $table->unsignedInteger('last_number')->default(0);

            $table->timestamps();

            // Ensure one counter per year/island combination
            $table->unique(['year', 'island']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('protocol_counters');
    }
};
