<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Creates the interactions table.
     * Tracks all communication and status changes for each complaint.
     * Provides full audit trail for transparency.
     */
    public function up(): void
    {
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('complaint_id')->constrained()->onDelete('cascade');

            $table->enum('interaction_type', [
                'creation',
                'update',
                'company_response',
                'user_response',
                'forwarding',
                'status_change',
                'deadline_alert',
                'notification'
            ]);


            $table->enum('source', [
                'user', 'company', 'system', 'regulator', 'operator'
            ]);
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->foreignId('operator_id')->nullable()->constrained('operators');


            $table->text('message')->nullable();
            $table->string('action_performed', 100)->nullable();

            // Related attachments (JSON array of attachment IDs)
            $table->json('attachment_ids')->nullable();


            $table->timestamp('interacted_at')->useCurrent();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            // For company responses
            $table->boolean('satisfactory')->nullable();
            $table->text('settlement_proposal')->nullable();

            $table->index(['complaint_id', 'interacted_at']);
            $table->index(['source', 'interacted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};
