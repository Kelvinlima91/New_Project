<?php
// database/migrations/2024_01_01_000001_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the users table for registered citizens.
     * In Cape Verde, each citizen has a unique NIF (Tax Identification Number).
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();


            $table->string('nif', 20)->unique()->comment('Tax ID');


            $table->enum('document_type', [
                'BI',
                'CNI',
                'Passport',
                'Residence Card',
                'Other'
            ]);
            $table->string('document_number', 50);


            $table->string('full_name', 150);
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['M', 'F', 'O'])->nullable(); // M=Male, F=Female, O=Other


            $table->string('email', 100)->unique();
            $table->string('primary_phone', 20);
            $table->string('secondary_phone', 20)->nullable();

            // Cape Verdean address structure
            $table->string('island', 50);
            $table->string('county', 50);                   // Concelho
            $table->string('parish', 50)->nullable();       // Freguesia
            $table->string('locality', 100)->nullable();    // Localidade
            $table->text('street_address')->nullable();      // Rua e número
            $table->string('postal_code', 10)->nullable();   // Código Postal


            $table->string('password');
            $table->rememberToken();


            $table->enum('preferred_contact', ['email', 'sms'])->default('email');
            $table->enum('preferred_language', ['pt', 'cv'])->default('pt'); // Portuguese or Cape Verdean Creole


            $table->boolean('verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->string('verification_method', 20)->nullable(); // 'email', 'sms'


            $table->integer('total_complaints')->default(0);
            $table->timestamp('last_login_at')->nullable();


            $table->integer('login_attempts')->default(0);
            $table->boolean('blocked')->default(false);
            $table->timestamp('blocked_until')->nullable();


            $table->boolean('accepted_terms')->default(false);
            $table->timestamp('terms_accepted_at')->nullable();
            $table->boolean('allows_notifications')->default(true);
            $table->boolean('allows_statistical_sharing')->default(true);

            $table->timestamps();
            $table->softDeletes();


            $table->index('nif');
            $table->index('email');
            $table->index('primary_phone');
            $table->index(['island', 'county']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
