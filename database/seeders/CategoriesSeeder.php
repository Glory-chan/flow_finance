<?php
// database/seeders/CategoriesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // Catégories de dépenses
            ['name' => 'Alimentation', 'slug' => 'alimentation', 'icon' => '🍽️', 'color' => '#28a745', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Transport', 'slug' => 'transport', 'icon' => '🚗', 'color' => '#007bff', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Loisirs', 'slug' => 'loisirs', 'icon' => '🎮', 'color' => '#17a2b8', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Shopping', 'slug' => 'shopping', 'icon' => '🛍️', 'color' => '#e83e8c', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Logement', 'slug' => 'logement', 'icon' => '🏠', 'color' => '#6f42c1', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Santé', 'slug' => 'sante', 'icon' => '🏥', 'color' => '#dc3545', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Éducation', 'slug' => 'education', 'icon' => '📚', 'color' => '#fd7e14', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Électricité', 'slug' => 'electricite', 'icon' => '⚡', 'color' => '#ffc107', 'type' => 'expense', 'is_default' => true],
            
            // Catégories de revenus
            ['name' => 'Salaire', 'slug' => 'salaire', 'icon' => '💰', 'color' => '#28a745', 'type' => 'income', 'is_default' => true],
            ['name' => 'Freelance', 'slug' => 'freelance', 'icon' => '💻', 'color' => '#17a2b8', 'type' => 'income', 'is_default' => true],
            ['name' => 'Investissements', 'slug' => 'investissements', 'icon' => '📈', 'color' => '#28a745', 'type' => 'income', 'is_default' => true],
            ['name' => 'Bonus', 'slug' => 'bonus', 'icon' => '🎁', 'color' => '#ffc107', 'type' => 'income', 'is_default' => true],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert(array_merge($category, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}