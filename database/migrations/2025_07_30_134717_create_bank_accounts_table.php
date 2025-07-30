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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Bank information
            $table->string('bank_name');
            $table->string('account_name')->nullable();
            $table->enum('account_type', ['checking', 'savings', 'credit', 'investment', 'loan']);
            
            // Account details (encrypted)
            $table->text('account_number')->nullable(); // Encrypted
            $table->text('routing_number')->nullable(); // Encrypted
            $table->string('iban')->nullable();
            $table->string('bic')->nullable();
            
            // Balance and currency
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('currency', 3)->default('EUR');
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_primary')->default(false);
            
            // Sync information
            $table->timestamp('last_synced_at')->nullable();
            $table->enum('sync_status', ['success', 'pending', 'error', 'disconnected'])->default('pending');
            $table->text('sync_error')->nullable();
            
            // External service integration
            $table->string('external_id')->nullable(); // ID from banking API
            $table->string('connection_id')->nullable(); // Connection ID for banking service
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['user_id', 'is_active']);
            $table->index(['user_id', 'is_primary']);
            $table->index('sync_status');
            $table->index('external_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};