<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Goal;
use App\Models\Card;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Données pour les statistiques principales
        $totalBalance = $this->calculateTotalBalance($user);
        $monthlyIncome = $this->getMonthlyIncome($user);
        $monthlyExpenses = $this->getMonthlyExpenses($user);
        $monthlySavings = $monthlyIncome - $monthlyExpenses;
        
        // Transactions récentes
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Budget du mois en cours
        $budgetOverview = $this->getBudgetOverviewData($user);
        
        // Objectifs d'épargne
        $savingsGoals = Goal::where('user_id', $user->id)
            ->where('status', 'active')
            ->orderBy('target_date', 'asc')
            ->limit(3)
            ->get();
        
        return view('dashboard', compact(
            'totalBalance',
            'monthlyIncome',
            'monthlyExpenses',
            'monthlySavings',
            'recentTransactions',
            'budgetOverview',
            'savingsGoals'
        ));
    }
    
    /**
     * Get dashboard stats via API.
     */
    public function getStats(Request $request)
    {
        $user = auth()->user();
        
        return response()->json([
            'total_balance' => $this->calculateTotalBalance($user),
            'monthly_income' => $this->getMonthlyIncome($user),
            'monthly_expenses' => $this->getMonthlyExpenses($user),
            'monthly_savings' => $this->getMonthlyIncome($user) - $this->getMonthlyExpenses($user),
            'cards_count' => Card::where('user_id', $user->id)->count(),
            'goals_count' => Goal::where('user_id', $user->id)->where('status', 'active')->count(),
        ]);
    }
    
    /**
     * Get recent transactions via API.
     */
    public function getRecentTransactions(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $transactions = Transaction::where('user_id', auth()->id())
            ->with(['category', 'card'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
        return response()->json($transactions);
    }
    
    /**
     * Get budget overview via API.
     */
    public function getBudgetOverview(Request $request)
    {
        $user = auth()->user();
        return response()->json($this->getBudgetOverviewData($user));
    }
    
    /**
     * Calculate total balance across all accounts and cards.
     */
    private function calculateTotalBalance($user): float
    {
        // Ici vous calculeriez le solde réel depuis les comptes bancaires
        // Pour l'exemple, on utilise une valeur statique
        return 4790.05;
    }
    
    /**
     * Get monthly income for the current month.
     */
    private function getMonthlyIncome($user): float
    {
        return Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }
    
    /**
     * Get monthly expenses for the current month.
     */
    private function getMonthlyExpenses($user): float
    {
        return Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }
    
    /**
     * Get budget overview data for the current month.
     */
    private function getBudgetOverviewData($user): array
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Récupérer les budgets du mois en cours
        $budgets = Budget::where('user_id', $user->id)
            ->whereMonth('period_start', $currentMonth)
            ->whereYear('period_start', $currentYear)
            ->with('category')
            ->get();
        
        $budgetData = [];
        
        foreach ($budgets as $budget) {
            // Vérifier que la catégorie existe
            if (!$budget->category) {
                continue;
            }
            
            // Calculer les dépenses réelles pour cette catégorie
            $actualSpent = Transaction::where('user_id', $user->id)
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->sum('amount');
            
            // S'assurer que les valeurs sont numériques
            $budgetAmount = (float) $budget->amount;
            $spentAmount = (float) $actualSpent;
            
            // Calculer le pourcentage en évitant la division par zéro
            $percentage = $budgetAmount > 0 ? round(($spentAmount / $budgetAmount) * 100, 2) : 0;
            
            $budgetData[] = [
                'category' => $budget->category->name ?? 'Sans catégorie',
                'category_icon' => $budget->category->icon ?? '💰',
                'budgeted' => $budgetAmount,
                'spent' => $spentAmount,
                'percentage' => $percentage,
                'status' => $spentAmount > $budgetAmount ? 'over' : 'under',
            ];
        }
        
        return $budgetData;
    }
}