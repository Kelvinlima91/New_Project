<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Creates the operators table.
     * Stores system administrators from regulatory bodies.
     * Each operator belongs to a Cape Verdean institution.
     */
    public function up(): void
    {
        Schema::create('operators', function (Blueprint $table) {
            $table->id();


            $table->string('nif', 20)->unique()->comment('Operator NIF');
            $table->string('full_name', 150);
            $table->string('institutional_email', 100)->unique();
            $table->string('institutional_phone', 20)->nullable();


            $table->enum('institution', [
                'ARME',
                'Banco de Cabo Verde',
                'ASAE',
                'INCV',
                'ICVM',
                'Other'
            ]);
            $table->string('department', 100)->nullable();
            $table->string('position', 100)->nullable();

            // Permissions (RBAC - Role Based Access Control)
            $table->enum('access_profile', [
                'administrator',
                'supervisor',
                'sector_analyst',
                'attendant',
                'auditor',
                'read_only'
            ]);


            $table->json('responsible_sectors')->nullable();


            $table->boolean('active')->default(true);
            $table->date('hired_at');
            $table->date('terminated_at')->nullable();


            $table->string('password_hash');
            $table->string('salt');
            $table->timestamp('last_login_at')->nullable();


            $table->boolean('requires_2fa')->default(true);
            $table->integer('login_attempts')->default(0);


            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('operators');

            $table->index('institution');
            $table->index('access_profile');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operators');
    }
};
