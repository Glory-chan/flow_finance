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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            // Budget information
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            
            // Period information
            $table->enum('period_type', ['weekly', 'monthly', 'quarterly', 'yearly', 'custom'])->default('monthly');
            $table->date('period_start');
            $table->date('period_end');
            
            // Status
            $table->enum('status', ['active', 'paused', 'completed', 'exceeded'])->default('active');
            
            // Alert settings
            $table->integer('alert_threshold')->default(80); // Percentage
            $table->boolean('alert_enabled')->default(true);
            
            // Rollover settings
            $table->boolean('rollover_enabled')->default(false);
            
            // Notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'period_start', 'period_end']);
            $table->index(['category_id', 'period_start', 'period_end']);
            $table->index(['period_type', 'status']);
            
            // Unique constraint for category per period
            $table->unique(['user_id', 'category_id', 'period_start', 'period_end'], 'unique_budget_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};