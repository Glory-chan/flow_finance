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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Goal information
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('target_amount', 15, 2);
            $table->decimal('current_amount', 15, 2)->default(0);
            $table->date('target_date')->nullable();
            
            // Status and priority
            $table->enum('status', ['active', 'completed', 'paused', 'cancelled'])->default('active');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Customization
            $table->string('icon')->nullable();
            $table->string('color', 7)->nullable(); // Hex color
            
            // Auto-save settings
            $table->boolean('auto_save_enabled')->default(false);
            $table->decimal('auto_save_amount', 15, 2)->nullable();
            $table->enum('auto_save_frequency', ['daily', 'weekly', 'monthly'])->nullable();
            
            // Additional notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'target_date']);
            $table->index(['user_id', 'priority', 'status']);
            $table->index(['status', 'target_date']);
            $table->index('auto_save_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};