<?php
// database/migrations/xxxx_xx_xx_create_budgets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained();
            $table->string('name');
            $table->decimal('amount', 15, 2); // Montant budgété
            $table->decimal('spent', 15, 2)->default(0.00); // Montant dépensé
            $table->enum('period', ['weekly', 'monthly', 'yearly'])->default('monthly');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->boolean('alert_enabled')->default(true);
            $table->integer('alert_threshold')->default(80); // Seuil d'alerte en pourcentage
            $table->timestamps();

            $table->index(['user_id', 'start_date', 'end_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('budgets');
    }
};