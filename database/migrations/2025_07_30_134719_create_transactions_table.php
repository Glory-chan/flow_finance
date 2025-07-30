<?php
// database/migrations/xxxx_xx_xx_create_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bank_account_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['income', 'expense'])->default('expense');
            $table->date('transaction_date');
            $table->string('merchant')->nullable();
            $table->string('reference')->nullable(); // Référence bancaire
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_frequency', ['daily', 'weekly', 'monthly', 'yearly'])->nullable();
            $table->json('tags')->nullable(); // Tags personnalisés
            $table->boolean('is_verified')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'transaction_date']);
            $table->index(['category_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};