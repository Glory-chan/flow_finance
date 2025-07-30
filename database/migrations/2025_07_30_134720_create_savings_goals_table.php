<?php
// database/migrations/xxxx_xx_xx_create_savings_goals_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('savings_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('target_amount', 15, 2); // Objectif à atteindre
            $table->decimal('current_amount', 15, 2)->default(0.00); // Montant actuel
            $table->date('target_date')->nullable(); // Date objectif
            $table->string('icon')->nullable();
            $table->string('color', 7)->default('#4CAF50');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('savings_goals');
    }
};