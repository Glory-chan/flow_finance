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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->onDelete('set null');
            
            // Card information
            $table->text('card_number')->nullable(); // Encrypted full number
            $table->string('card_number_hash')->nullable(); // Hash for uniqueness
            $table->string('card_holder');
            $table->date('expiry_date');
            $table->datetime('expires_at')->nullable(); // Calculated from expiry_date
            
            // Card details
            $table->enum('card_type', ['debit', 'credit', 'prepaid'])->default('debit');
            $table->enum('brand', ['visa', 'mastercard', 'amex', 'discover', 'other'])->default('other');
            $table->string('last_four', 4); // Last 4 digits for display
            
            // Customization
            $table->string('alias')->nullable(); // User-defined name
            $table->string('color', 7)->nullable(); // Hex color
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_primary')->default(false);
            $table->timestamp('blocked_at')->nullable();
            
            // Credit card specific
            $table->decimal('credit_limit', 15, 2)->nullable();
            $table->decimal('current_balance', 15, 2)->nullable();
            $table->decimal('available_credit', 15, 2)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['user_id', 'is_active']);
            $table->index(['user_id', 'is_primary']);
            $table->index('card_number_hash');
            $table->index('expires_at');
            $table->index(['brand', 'card_type']);
            
            // Unique constraints
            $table->unique('card_number_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};