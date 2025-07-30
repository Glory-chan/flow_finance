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
        Schema::create('goal_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goal_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Contribution details
            $table->decimal('amount', 15, 2); // Can be negative for withdrawals
            $table->string('description')->nullable();
            $table->datetime('contributed_at');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['goal_id', 'contributed_at']);
            $table->index(['user_id', 'contributed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_contributions');
    }
};