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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // Personal information
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            
            // Profile
            $table->string('avatar')->nullable();
            $table->string('timezone')->default('Europe/Paris');
            $table->string('currency', 3)->default('EUR');
            $table->string('language', 2)->default('fr');
            
            // Preferences (JSON)
            $table->json('preferences')->nullable();
            $table->json('notification_preferences')->nullable();
            
            // Security
            $table->boolean('two_factor_enabled')->default(false);
            $table->timestamp('two_factor_enabled_at')->nullable();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            
            // Subscription
            $table->string('subscription_type')->default('free');
            $table->timestamp('subscription_expires_at')->nullable();
            
            // Activity tracking
            $table->timestamp('last_activity_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            
            // Standard Laravel fields
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['email', 'deleted_at']);
            $table->index('subscription_type');
            $table->index('last_activity_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};