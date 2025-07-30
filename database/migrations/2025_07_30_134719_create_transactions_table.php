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
        Schema::create('transactions', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('card_id')->nullable()->constrained('cards')->onDelete('set null');
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

            
            // Transaction details
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['income', 'expense', 'transfer']);
            $table->string('description');
            $table->string('merchant')->nullable();
            $table->string('location')->nullable();
            
            // Dates
            $table->datetime('transaction_date');
            $table->timestamp('processed_at')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'completed', 'cancelled', 'failed'])->default('completed');
            
            // Currency and exchange
            $table->string('currency', 3)->default('EUR');
            $table->decimal('exchange_rate', 10, 6)->nullable();
            $table->decimal('original_amount', 15, 2)->nullable();
            $table->string('original_currency', 3)->nullable();
            
            // Additional information
            $table->text('notes')->nullable();
            $table->string('receipt_url')->nullable();
            $table->json('tags')->nullable(); // Array of tags
            
            // Recurring transactions
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_frequency', ['daily', 'weekly', 'monthly', 'yearly'])->nullable();
            
            // External integration
            $table->string('external_id')->nullable(); // ID from banking API
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index(['user_id', 'transaction_date']);
            $table->index(['user_id', 'type', 'transaction_date']);
            $table->index(['user_id', 'category_id', 'transaction_date']);
            $table->index(['card_id', 'transaction_date']);
            $table->index(['bank_account_id', 'transaction_date']);
            $table->index(['merchant', 'transaction_date']);
            $table->index(['status', 'transaction_date']);
            $table->index('external_id');
            
            // Composite indexes for common queries
            $table->index(['user_id', 'type', 'status', 'transaction_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};