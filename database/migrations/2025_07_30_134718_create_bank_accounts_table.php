<?php
// database/migrations/2024_01_01_000002_create_bank_accounts_table.php

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
            $table->string('name'); // Nom du compte (ex: "Compte Courant")
            $table->string('bank_name'); // Nom de la banque
            $table->string('account_number')->nullable();
            $table->string('account_type')->default('checking'); // checking, savings, credit
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('currency', 3)->default('EUR');
            $table->boolean('is_active')->default(true);
            $table->string('card_type')->nullable(); // visa, mastercard, etc.
            $table->string('card_last_four')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
};