<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\BankAccount;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Goal;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        $user = User::create([
            'name' => 'Robert Martin',
            'email' => 'robert@flowfinance.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'phone' => '+33 1 23 45 67 89',
            'city' => 'Paris',
            'country' => 'France',
            'currency' => 'EUR',
            'timezone' => 'Europe/Paris',
        ]);

        // Create system categories
        $categories = $this->createCategories($user);

        // Create bank account
        $bankAccount = BankAccount::create([
            'user_id' => $user->id,
            'bank_name' => 'Crédit Agricole',
            'account_name' => 'Compte Principal',
            'account_type' => 'checking',
            'balance' => 4790.05,
            'currency' => 'EUR',
            'is_active' => true,
            'is_primary' => true,
            'sync_status' => 'success',
            'last_synced_at' => now(),
        ]);

        // Create cards
        $this->createCards($user, $bankAccount);

        // Create transactions
        $this->createTransactions($user, $categories);

        // Create budgets
        $this->createBudgets($user, $categories);

        // Create goals
        $this->createGoals($user);
    }

    private function createCategories(User $user): array
    {
        $systemCategories = [
            // Income categories
            [
                'name' => 'Salaire',
                'icon' => '💼',
                'color' => '#10b981',
                'type' => 'income',
                'is_system' => true,
            ],
            [
                'name' => 'Freelance',
                'icon' => '💻',
                'color' => '#3b82f6',
                'type' => 'income',
                'is_system' => true,
            ],
            [
                'name' => 'Investissements',
                'icon' => '📈',
                'color' => '#8b5cf6',
                'type' => 'income',
                'is_system' => true,
            ],
            
            // Expense categories
            [
                'name' => 'Alimentation',
                'icon' => '🛒',
                'color' => '#f59e0b',
                'type' => 'expense',
                'is_system' => true,
            ],
            [
                'name' => 'Transport',
                'icon' => '🚗',
                'color' => '#ef4444',
                'type' => 'expense',
                'is_system' => true,
            ],
            [
                'name' => 'Logement',
                'icon' => '🏠',
                'color' => '#6366f1',
                'type' => 'expense',
                'is_system' => true,
            ],
            [
                'name' => 'Divertissement',
                'icon' => '🎬',
                'color' => '#ec4899',
                'type' => 'expense',
                'is_system' => true,
            ],
            [
                'name' => 'Santé',
                'icon' => '🏥',
                'color' => '#14b8a6',
                'type' => 'expense',
                'is_system' => true,
            ],
            [
                'name' => 'Énergie',
                'icon' => '⚡',
                'color' => '#f97316',
                'type' => 'expense',
                'is_system' => true,
            ],
        ];

        $categories = [];
        foreach ($systemCategories as $categoryData) {
            $categoryData['user_id'] = $user->id;
            $categoryData['slug'] = \Str::slug($categoryData['name']);
            $categories[] = Category::create($categoryData);
        }

        return $categories;
    }

    private function createCards(User $user, BankAccount $bankAccount): void
    {
        // Visa card
        Card::create([
            'user_id' => $user->id,
            'bank_account_id' => $bankAccount->id,
            'card_holder' => 'ROBERT MARTIN',
            'expiry_date' => now()->addYears(2)->endOfMonth(),
            'card_type' => 'debit',
            'brand' => 'visa',
            'last_four' => '1234',
            'is_active' => true,
            'is_primary' => true,
        ]);

        // Mastercard
        Card::create([
            'user_id' => $user->id,
            'bank_account_id' => $bankAccount->id,
            'card_holder' => 'ROBERT MARTIN',
            'expiry_date' => now()->addYear()->endOfMonth(),
            'card_type' => 'credit',
            'brand' => 'mastercard',
            'last_four' => '5678',
            'credit_limit' => 3000,
            'current_balance' => 450,
            'is_active' => true,
        ]);

        // LCL card
        Card::create([
            'user_id' => $user->id,
            'card_holder' => 'ROBERT MARTIN',
            'expiry_date' => now()->addYears(3)->endOfMonth(),
            'card_type' => 'debit',
            'brand' => 'other',
            'last_four' => '9012',
            'alias' => 'Carte LCL',
            'is_active' => true,
        ]);
    }

    private function createTransactions(User $user, array $categories): void
    {
        $expenseCategories = collect($categories)->where('type', 'expense');
        $incomeCategories = collect($categories)->where('type', 'income');
        
        $cards = $user->cards;

        // Sample transactions for current month
        $transactions = [
            // Income
            [
                'type' => 'income',
                'amount' => 2450.00,
                'description' => 'Virement Salaire',
                'merchant' => 'Entreprise ABC',
                'category' => $incomeCategories->where('name', 'Salaire')->first(),
                'date' => now()->subDays(2),
            ],
            
            // Expenses
            [
                'type' => 'expense',
                'amount' => 75.00,
                'description' => 'Facture électricité',
                'merchant' => 'EDF',
                'category' => $expenseCategories->where('name', 'Énergie')->first(),
                'date' => now(),
            ],
            [
                'type' => 'expense',
                'amount' => 135.20,
                'description' => 'Courses hebdomadaires',
                'merchant' => 'Carrefour',
                'category' => $expenseCategories->where('name', 'Alimentation')->first(),
                'date' => now()->subDay(),
            ],
            [
                'type' => 'expense',
                'amount' => 62.30,
                'description' => 'Plein essence',
                'merchant' => 'Station Total',
                'category' => $expenseCategories->where('name', 'Transport')->first(),
                'date' => now()->subDays(2),
            ],
            [
                'type' => 'expense',
                'amount' => 15.99,
                'description' => 'Abonnement Netflix',
                'merchant' => 'Netflix',
                'category' => $expenseCategories->where('name', 'Divertissement')->first(),
                'date' => now()->subDays(3),
            ],
            [
                'type' => 'expense',
                'amount' => 89.99,
                'description' => 'Achat Amazon',
                'merchant' => 'Amazon France',
                'category' => $expenseCategories->where('name', 'Divertissement')->first(),
                'date' => now()->subDays(1),
            ],
        ];

        foreach ($transactions as $transactionData) {
            Transaction::create([
                'user_id' => $user->id,
                'card_id' => $cards->random()->id,
                'category_id' => $transactionData['category']->id,
                'amount' => $transactionData['amount'],
                'type' => $transactionData['type'],
                'description' => $transactionData['description'],
                'merchant' => $transactionData['merchant'],
                'transaction_date' => $transactionData['date'],
                'status' => 'completed',
                'currency' => 'EUR',
            ]);
        }

        // Add more random transactions for the past 3 months
        for ($i = 0; $i < 50; $i++) {
            $isIncome = rand(1, 10) <= 2; // 20% chance of income
            $category = $isIncome 
                ? $incomeCategories->random() 
                : $expenseCategories->random();

            Transaction::create([
                'user_id' => $user->id,
                'card_id' => $cards->random()->id,
                'category_id' => $category->id,
                'amount' => $isIncome ? rand(800, 3000) : rand(10, 500),
                'type' => $isIncome ? 'income' : 'expense',
                'description' => $isIncome ? 'Virement' : 'Achat ' . $category->name,
                'merchant' => $isIncome ? 'Employeur' : 'Marchand ' . $category->name,
                'transaction_date' => now()->subDays(rand(1, 90)),
                'status' => 'completed',
                'currency' => 'EUR',
            ]);
        }
    }

    private function createBudgets(User $user, array $categories): void
    {
        $expenseCategories = collect($categories)->where('type', 'expense');
        
        $budgets = [
            [
                'name' => 'Budget Alimentation',
                'category' => 'Alimentation',
                'amount' => 600,
                'alert_threshold' => 80,
            ],
            [
                'name' => 'Budget Transport',
                'category' => 'Transport',
                'amount' => 300,
                'alert_threshold' => 85,
            ],
            [
                'name' => 'Budget Divertissement',
                'category' => 'Divertissement',
                'amount' => 200,
                'alert_threshold' => 75,
            ],
            [
                'name' => 'Budget Énergie',
                'category' => 'Énergie',
                'amount' => 120,
                'alert_threshold' => 90,
            ],
        ];

        foreach ($budgets as $budgetData) {
            $category = $expenseCategories->where('name', $budgetData['category'])->first();
            
            if ($category) {
                Budget::create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'name' => $budgetData['name'],
                    'amount' => $budgetData['amount'],
                    'period_type' => 'monthly',
                    'period_start' => now()->startOfMonth(),
                    'period_end' => now()->endOfMonth(),
                    'status' => 'active',
                    'alert_threshold' => $budgetData['alert_threshold'],
                    'alert_enabled' => true,
                ]);
            }
        }
    }

    private function createGoals(User $user): void
    {
        $goals = [
            [
                'name' => 'Apport maison',
                'description' => 'Constituer un apport pour l\'achat d\'une maison',
                'target_amount' => 50000,
                'current_amount' => 34000,
                'target_date' => now()->addYear(),
                'icon' => '🏠',
                'color' => '#10b981',
                'priority' => 'high',
            ],
            [
                'name' => 'Vacances Japon',
                'description' => 'Voyage au Japon pour 2 personnes',
                'target_amount' => 5000,
                'current_amount' => 2150,
                'target_date' => now()->addMonths(8),
                'icon' => '✈️',
                'color' => '#3b82f6',
                'priority' => 'medium',
            ],
            [
                'name' => 'Nouveau MacBook',
                'description' => 'MacBook Pro 16 pouces pour le travail',
                'target_amount' => 2500,
                'current_amount' => 2500,
                'target_date' => now()->addMonths(6),
                'icon' => '💻',
                'color' => '#8b5cf6',
                'priority' => 'medium',
                'status' => 'completed',
            ],
            [
                'name' => 'Fonds d\'urgence',
                'description' => 'Réserve pour les imprévus (6 mois de charges)',
                'target_amount' => 10000,
                'current_amount' => 3500,
                'target_date' => now()->addMonths(18),
                'icon' => '🛡️',
                'color' => '#ef4444',
                'priority' => 'high',
                'auto_save_enabled' => true,
                'auto_save_amount' => 200,
                'auto_save_frequency' => 'monthly',
            ],
        ];

        foreach ($goals as $goalData) {
            $goal = Goal::create([
                'user_id' => $user->id,
                'name' => $goalData['name'],
                'description' => $goalData['description'],
                'target_amount' => $goalData['target_amount'],
                'current_amount' => $goalData['current_amount'],
                'target_date' => $goalData['target_date'],
                'status' => $goalData['status'] ?? 'active',
                'priority' => $goalData['priority'],
                'icon' => $goalData['icon'],
                'color' => $goalData['color'],
                'auto_save_enabled' => $goalData['auto_save_enabled'] ?? false,
                'auto_save_amount' => $goalData['auto_save_amount'] ?? null,
                'auto_save_frequency' => $goalData['auto_save_frequency'] ?? null,
            ]);

            // Add some contributions for active goals
            if ($goal->current_amount > 0 && $goal->status !== 'completed') {
                $contributionsCount = rand(3, 8);
                $totalContributed = 0;
                
                for ($i = 0; $i < $contributionsCount; $i++) {
                    $amount = rand(100, 1000);
                    $totalContributed += $amount;
                    
                    if ($totalContributed <= $goal->current_amount) {
                        $goal->contributions()->create([
                            'user_id' => $user->id,
                            'amount' => $amount,
                            'description' => 'Contribution #' . ($i + 1),
                            'contributed_at' => now()->subDays(rand(1, 90)),
                        ]);
                    }
                }
            }
        }
    }
}