<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Creates the companies table.
     * Each company in Cape Verde has a unique NIF and falls under a specific regulatory body.
     * Major regulators: ARME, Banco de Cabo Verde, ASAE, etc.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();


            $table->string('nif', 20)->unique()->comment('Company Tax ID');
            $table->string('legal_name', 200);
            $table->string('trading_name', 200)->nullable(); // Nome Comercial


            $table->enum('main_sector', [
                'Water and Sanitation',        // Água e Saneamento
                'Electricity',                  // Energia Elétrica
                'Telecommunications',           // Telecomunicações
                'Financial Services',           // Serviços Financeiros
                'Healthcare',                   // Saúde
                'Education',                    // Educação
                'Transportation',                // Transportes
                'Tourism and Hospitality',       // Turismo e Hotelaria
                'Commerce',                      // Comércio
                'Food Services',                 // Restauração
                'Public Services',               // Serviços Públicos
                'Other'
            ]);


            $table->enum('regulatory_body', [
                'ARME',          // Agência Reguladora Multissectorial da Economia
                'Banco de Cabo Verde',  // Central Bank
                'ASAE',          // Economic and Food Safety Authority
                'INCV',          // National Health Institute
                'ICVM',          // Maritime Transport Institute
                'Other'
            ]);


            $table->string('contact_email', 100)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('website', 100)->nullable();


            $table->string('island', 50);
            $table->string('county', 50);
            $table->string('parish', 50)->nullable();
            $table->string('locality', 100)->nullable();
            $table->text('street_address')->nullable();


            $table->string('representative_name', 150)->nullable();
            $table->string('representative_email', 100)->nullable();
            $table->string('representative_phone', 20)->nullable();


            $table->boolean('active')->default(true);


            $table->integer('total_complaints')->default(0);
            $table->integer('complaints_last_30_days')->default(0);
            $table->decimal('resolution_rate', 5, 2)->default(0.00); // Percentage

            $table->timestamps();
            $table->softDeletes();


            $table->index('nif');
            $table->index('main_sector');
            $table->index('regulatory_body');
            $table->index('island');
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
