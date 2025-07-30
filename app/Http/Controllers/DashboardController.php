<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Budget;
use App\Models\SavingsGoal;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Statistiques du mois en cours
        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');
        
        $monthlyIncome = $user->getMonthlyIncome($currentMonth, $currentYear);
        $monthlyExpenses = $user->getMonthlyExpenses($currentMonth, $currentYear);
        $monthlyBalance = $monthlyIncome - $monthlyExpenses;
        
        // Transactions récentes (7 derniers jours)
        $recentTransactions = $user->transactions()
            ->with(['category', 'bankAccount'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Budgets actifs du mois
        $activeBudgets = $user->budgets()
            ->with('category')
            ->active()
            ->current()
            ->get();
        
        // Mettre à jour les montants dépensés des budgets
        foreach ($activeBudgets as $budget) {
            $budget->updateSpentAmount();
        }
        
        // Objectifs d'épargne actifs
        $savingsGoals = $user->savingsGoals()
            ->inProgress()
            ->orderBy('target_date')
            ->take(5)
            ->get();
        
        // Dépenses par catégorie ce mois
        $expensesByCategory = $user->transactions()
            ->with('category')
            ->where('type', 'expense')
            ->thisMonth()
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->get();
        
        // Comptes bancaires
        $bankAccounts = $user->bankAccounts()->active()->get();
        
        // Alertes
        $alerts = $this->getAlerts($user);
        
        return view('dashboard', compact(
            'user',
            'monthlyIncome',
            'monthlyExpenses',
            'monthlyBalance',
            'recentTransactions',
            'activeBudgets',
            'savingsGoals',
            'expensesByCategory',
            'bankAccounts',
            'alerts'
        ));
    }
    
    private function getAlerts($user)
    {
        $alerts = [];
        
        // Alertes budgets dépassés
        $overBudgets = $user->budgets()
            ->active()
            ->current()
            ->get()
            ->filter(function ($budget) {
                $budget->updateSpentAmount();
                return $budget->shouldAlert();
            });
        
        foreach ($overBudgets as $budget) {
            $alerts[] = [
                'type' => $budget->isOverBudget() ? 'danger' : 'warning',
                'message' => $budget->isOverBudget() 
                    ? "Budget '{$budget->name}' dépassé de " . number_format($budget->spent - $budget->amount, 2) . "€"
                    : "Attention: Budget '{$budget->name}' à " . round($budget->getProgressPercentage()) . "%",
                'icon' => 'fas fa-exclamation-triangle'
            ];
        }
        
        // Alertes objectifs d'épargne en retard
        $overdueGoals = $user->savingsGoals()
            ->inProgress()
            ->get()
            ->filter(function ($goal) {
                return $goal->isOverdue();
            });
        
        foreach ($overdueGoals as $goal) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "Objectif d'épargne '{$goal->name}' en retard",
                'icon' => 'fas fa-clock'
            ];
        }
        
        return $alerts;
    }
    
    public function getChartData(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', 'month'); // month, year
        
        if ($period === 'year') {
            // Données par mois pour l'année en cours
            $data = [];
            for ($i = 1; $i <= 12; $i++) {
                $income = $user->getMonthlyIncome($i, date('Y'));
                $expenses = $user->getMonthlyExpenses($i, date('Y'));
                
                $data[] = [
                    'period' => date('M', mktime(0, 0, 0, $i, 1)),
                    'income' => $income,
                    'expenses' => $expenses,
                    'balance' => $income - $expenses
                ];
            }
        } else {
            // Données par jour pour le mois en cours
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
            $data = [];
            
            for ($date = $startDate; $date <= $endDate; $date->addDay()) {
                $dayIncome = $user->transactions()
                    ->where('type', 'income')
                    ->whereDate('transaction_date', $date)
                    ->sum('amount');
                    
                $dayExpenses = $user->transactions()
                    ->where('type', 'expense')
                    ->whereDate('transaction_date', $date)
                    ->sum('amount');
                
                $data[] = [
                    'period' => $date->format('d'),
                    'income' => $dayIncome,
                    'expenses' => $dayExpenses,
                    'balance' => $dayIncome - $dayExpenses
                ];
            }
        }
        
        return response()->json($data);
    }
}