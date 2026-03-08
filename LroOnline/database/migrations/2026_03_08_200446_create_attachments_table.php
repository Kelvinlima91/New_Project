<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Creates the attachments table.
     * Stores files uploaded with complaints (receipts, photos, documents).
     * Includes file integrity verification via SHA-256 hash.
     */
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('complaint_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users');

            $table->string('file_name', 255);
            $table->enum('file_type', [
                'invoice', 'photo', 'contract', 'email', 'other'
            ]);
            $table->string('extension', 10);
            $table->bigInteger('size_bytes');


            $table->string('file_path', 500);
            $table->string('bucket', 100)->nullable(); // If using cloud storage

            // Integrity verification
            $table->string('file_hash', 64); // SHA-256
            $table->boolean('hash_verified')->default(true);


            $table->boolean('contains_sensitive_data')->default(false);
            $table->timestamp('sensitive_data_processed_at')->nullable();

            $table->timestamp('uploaded_at')->useCurrent();
            $table->foreignId('uploaded_by')->nullable()->constrained('users');

            $table->index(['complaint_id', 'file_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
