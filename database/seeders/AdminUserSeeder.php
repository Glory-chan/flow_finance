<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Supprimer les utilisateurs existants pour éviter les doublons
        User::truncate();
        
        // Créer l'utilisateur admin
        User::create([
            'name' => 'Admin FlowFinance',
            'email' => 'admin@flowfinance.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'phone' => '+33 1 23 45 67 89',
            'city' => 'Paris',
            'country' => 'France',
            'timezone' => 'Europe/Paris',
            'currency' => 'EUR',
            'language' => 'fr',
            'subscription_type' => 'premium',
            'preferences' => [
                'theme' => 'light',
                'notifications' => true,
                'dashboard_layout' => 'default'
            ],
            'notification_preferences' => [
                'email' => true,
                'push' => true,
                'budget_alerts' => true,
                'transaction_alerts' => true
            ],
            'last_activity_at' => now(),
        ]);

        // Créer Robert Martin
        User::create([
            'name' => 'Robert Martin',
            'email' => 'robert@flowfinance.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'phone' => '+33 6 12 34 56 78',
            'city' => 'Lyon',
            'country' => 'France',
            'timezone' => 'Europe/Paris',
            'currency' => 'EUR',
            'language' => 'fr',
            'subscription_type' => 'premium',
            'preferences' => [
                'theme' => 'light',
                'notifications' => true,
                'dashboard_layout' => 'compact'
            ],
            'notification_preferences' => [
                'email' => true,
                'push' => false,
                'budget_alerts' => true,
                'transaction_alerts' => false
            ],
            'last_activity_at' => now()->subHours(2),
        ]);

        // Créer Marie Dubois
        User::create([
            'name' => 'Marie Dubois',
            'email' => 'marie@flowfinance.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'phone' => '+33 7 98 76 54 32',
            'city' => 'Marseille',
            'country' => 'France',
            'timezone' => 'Europe/Paris',
            'currency' => 'EUR',
            'language' => 'fr',
            'subscription_type' => 'free',
            'preferences' => [
                'theme' => 'dark',
                'notifications' => true,
                'dashboard_layout' => 'default'
            ],
            'notification_preferences' => [
                'email' => true,
                'push' => true,
                'budget_alerts' => true,
                'transaction_alerts' => true
            ],
            'last_activity_at' => now()->subDays(1),
        ]);

        echo "✅ Utilisateurs créés avec succès !\n";
        echo "📧 admin@flowfinance.com / admin123 (Premium)\n";
        echo "📧 robert@flowfinance.com / password123 (Premium)\n";
        echo "📧 marie@flowfinance.com / password123 (Free)\n";
    }
}