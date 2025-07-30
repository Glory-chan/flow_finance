<?php
// database/migrations/xxxx_xx_xx_create_bank_accounts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('account_name');
            $table->string('account_number')->nullable();
            $table->string('bank_name');
            $table->enum('account_type', ['checking', 'savings', 'credit', 'investment'])->default('checking');
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->string('currency', 3)->default('EUR');
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('api_credentials')->nullable(); // Pour l'intégration Open Banking
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
};