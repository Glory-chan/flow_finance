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
        Schema::table('users', function (Blueprint $table) {
            // Informations personnelles
            $table->string('phone')->nullable()->after('email');
            $table->date('date_of_birth')->nullable()->after('phone');
            $table->string('address')->nullable()->after('date_of_birth');
            $table->string('city')->nullable()->after('address');
            $table->string('postal_code')->nullable()->after('city');
            $table->string('country')->default('France')->after('postal_code');
            
            // Profil
            $table->string('avatar')->nullable()->after('country');
            $table->string('timezone')->default('Europe/Paris')->after('avatar');
            $table->string('currency')->default('EUR')->after('timezone');
            $table->string('language')->default('fr')->after('currency');
            
            // Préférences et notifications
            $table->json('preferences')->nullable()->after('language');
            $table->json('notification_preferences')->nullable()->after('preferences');
            
            // Abonnement
            $table->enum('subscription_type', ['free', 'premium'])->default('free')->after('notification_preferences');
            
            // Two-Factor Authentication
            $table->boolean('two_factor_enabled')->default(false)->after('subscription_type');
            $table->timestamp('two_factor_enabled_at')->nullable()->after('two_factor_enabled');
            $table->text('two_factor_secret')->nullable()->after('two_factor_enabled_at');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            
            // Activité
            $table->timestamp('last_activity_at')->nullable()->after('two_factor_recovery_codes');
            
            // Soft deletes
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'date_of_birth',
                'address',
                'city',
                'postal_code',
                'country',
                'avatar',
                'timezone',
                'currency',
                'language',
                'preferences',
                'notification_preferences',
                'subscription_type',
                'two_factor_enabled',
                'two_factor_enabled_at',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'last_activity_at',
                'deleted_at'
            ]);
        });
    }
};