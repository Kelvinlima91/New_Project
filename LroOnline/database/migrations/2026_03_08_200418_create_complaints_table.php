<?php
// database/migrations/2024_01_01_000003_create_complaints_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Creates the complaints table - the heart of the system.
     * Stores all complaints made by citizens against companies.
     * Supports both registered users and anonymous complaints.
     */
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();


            $table->string('protocol_number', 50)->unique()
                  ->comment('Format: CV-YYYY-Island-123456');
            $table->string('access_code', 20)->unique()
                  ->comment('Random code for anonymous tracking');


            $table->foreignId('user_id')->nullable()->constrained('users')
                  ->comment('NULL if anonymous complaint');
            $table->foreignId('company_id')->constrained('companies');


            $table->string('anonymous_phone', 20)->nullable()
                  ->comment('Phone of anonymous complainant');
            $table->boolean('sms_verified')->default(false);


            $table->enum('category', [
                'Billing/Pricing',           // Faturação/Preços
                'Service Quality',            // Qualidade do Serviço
                'Service Interruption',       // Cortes/Interrupções
                'Customer Service',            // Atendimento ao Cliente
                'Misleading Advertising',      // Publicidade Enganosa
                'Defective Product',           // Produto Defeituoso
                'Delay/Delivery',              // Atrasos/Entregas
                'Safety',                      // Segurança
                'Data Privacy',                // Privacidade de Dados
                'Other'
            ]);


            $table->string('title', 200);
            $table->text('description');
            $table->date('incident_date');
            $table->string('incident_location', 100)->nullable();


            $table->text('previous_attempts')->nullable();
            $table->enum('attempt_channel', [
                'phone', 'email', 'in_person', 'social_media'
            ])->nullable();


            $table->text('expected_resolution');
            $table->enum('urgency', ['low', 'medium', 'high', 'critical'])->default('medium');

            // Status and workflow
            $table->enum('status', [
                'pending',                    // Pendente
                'under_analysis',              // Em análise
                'forwarded_to_company',        // Encaminhada à empresa
                'company_responded',           // Respondida pela empresa
                'under_negotiation',           // Em negociação
                'resolved',                    // Resolvida
                'unsatisfactory',               // Insatisfatória
                'forwarded_to_regulator',       // Encaminhada ao regulador
                'archived'                     // Arquivada
            ])->default('pending');


            $table->integer('company_response_deadline')->default(15);
            $table->integer('final_resolution_deadline')->default(30);


            $table->timestamp('sent_to_company_at')->nullable();
            $table->timestamp('company_responded_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('due_date')->nullable();


            $table->integer('days_open')->default(0);
            $table->decimal('response_time_hours', 10, 2)->nullable();


            $table->enum('entry_channel', [
                'web', 'phone'
            ])->default('web');


            $table->boolean('anonymous')->default(false);
            $table->boolean('allow_company_contact')->default(true);


            $table->integer('priority_score')->default(0);

            $table->timestamps();
            $table->softDeletes();


            $table->index('protocol_number');
            $table->index('status');
            $table->index(['company_id', 'status']);
            $table->index(['user_id', 'created_at']);
            $table->index('due_date');
            $table->index('urgency');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
