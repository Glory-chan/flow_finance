<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Transaction;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Données pour les graphiques principaux
        $spendingTrends = $this->getSpendingTrends($user);
        $incomeVsExpenses = $this->getIncomeVsExpenses($user);
        $categoryBreakdown = $this->getCategoryBreakdown($user);
        $monthlyComparison = $this->getMonthlyComparison($user);
        
        // Statistiques rapides
        $stats = [
            'total_transactions' => Transaction::where('user_id', $user->id)->count(),
            'avg_daily_spending' => $this->getAverageDailySpending($user),
            'top_category' => $this->getTopSpendingCategory($user),
            'savings_rate' => $this->getSavingsRate($user),
        ];
        
        return view('analytics.index', compact(
            'spendingTrends',
            'incomeVsExpenses',
            'categoryBreakdown',
            'monthlyComparison',
            'stats'
        ));
    }
    
    /**
     * Display spending analytics.
     */
    public function spending(): View
    {
        $user = auth()->user();
        
        $spendingByCategory = $this->getSpendingByCategory($user);
        $spendingTrends = $this->getSpendingTrends($user, 12); // 12 derniers mois
        $topMerchants = $this->getTopMerchants($user);
        $spendingPatterns = $this->getSpendingPatterns($user);
        
        return view('analytics.spending', compact(
            'spendingByCategory',
            'spendingTrends',
            'topMerchants',
            'spendingPatterns'
        ));
    }
    
    /**
     * Display income analytics.
     */
    public function income(): View
    {
        $user = auth()->user();
        
        $incomeTrends = $this->getIncomeTrends($user);
        $incomeBySource = $this->getIncomeBySource($user);
        $incomeGrowth = $this->getIncomeGrowth($user);
        
        return view('analytics.income', compact(
            'incomeTrends',
            'incomeBySource',
            'incomeGrowth'
        ));
    }
    
    /**
     * Display trends analytics.
     */
    public function trends(): View
    {
        $user = auth()->user();
        
        $yearOverYear = $this->getYearOverYearComparison($user);
        $seasonalTrends = $this->getSeasonalTrends($user);
        $growthRates = $this->getGrowthRates($user);
        
        return view('analytics.trends', compact(
            'yearOverYear',
            'seasonalTrends',
            'growthRates'
        ));
    }
    
    /**
     * Display reports page.
     */
    public function reports(): View
    {
        return view('analytics.reports');
    }
    
    /**
     * Generate a custom report.
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:spending,income,budget,full',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after:date_from',
            'format' => 'required|in:pdf,excel,csv'
        ]);
        
        $user = auth()->user();
        $reportData = $this->generateReportData($user, $request->all());
        
        return $this->exportReport($reportData, $request->format);
    }
    
    /**
     * Export data in specified format.
     */
    public function export(Request $request, string $type)
    {
        $user = auth()->user();
        
        switch ($type) {
            case 'transactions':
                return $this->exportTransactions($user, $request);
            case 'budget':
                return $this->exportBudget($user, $request);
            case 'goals':
                return $this->exportGoals($user, $request);
            default:
                abort(404);
        }
    }
    
    /**
     * Get spending trends data for API.
     */
    public function getSpendingTrends($user = null, int $months = 6): array
    {
        $user = $user ?? auth()->user();
        
        $trends = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('created_at', '>=', now()->subMonths($months))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return $trends->map(function ($trend) {
            return [
                'month' => Carbon::createFromFormat('Y-m', $trend->month)->format('M Y'),
                'amount' => $trend->total
            ];
        })->toArray();
    }
    
    /**
     * Get income vs expenses comparison.
     */
    public function getIncomeVsExpenses($user = null): array
    {
        $user = $user ?? auth()->user();
        
        $data = Transaction::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMonths(12))
            ->selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                type,
                SUM(amount) as total
            ')
            ->groupBy('month', 'type')
            ->orderBy('month')
            ->get()
            ->groupBy('month');
        
        $result = [];
        foreach ($data as $month => $transactions) {
            $income = $transactions->where('type', 'income')->sum('total');
            $expenses = $transactions->where('type', 'expense')->sum('total');
            
            $result[] = [
                'month' => Carbon::createFromFormat('Y-m', $month)->format('M Y'),
                'income' => $income,
                'expenses' => $expenses,
                'net' => $income - $expenses
            ];
        }
        
        return $result;
    }
    
    /**
     * Get category breakdown for spending.
     */
    public function getCategoryBreakdown($user = null): array
    {
        $user = $user ?? auth()->user();
        
        return Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('created_at', now()->month)
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, categories.icon, SUM(transactions.amount) as total')
            ->groupBy('categories.id', 'categories.name', 'categories.icon')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->name,
                    'icon' => $item->icon,
                    'amount' => $item->total
                ];
            })
            ->toArray();
    }
    
    /**
     * Get monthly comparison data.
     */
    private function getMonthlyComparison($user): array
    {
        $currentMonth = Transaction::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');
        
        $previousMonth = Transaction::where('user_id', $user->id)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');
        
        return [
            'current' => [
                'income' => $currentMonth['income'] ?? 0,
                'expenses' => $currentMonth['expense'] ?? 0,
            ],
            'previous' => [
                'income' => $previousMonth['income'] ?? 0,
                'expenses' => $previousMonth['expense'] ?? 0,
            ]
        ];
    }
    
    /**
     * Get average daily spending.
     */
    private function getAverageDailySpending($user): float
    {
        $daysInMonth = now()->daysInMonth;
        $monthlyExpenses = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        
        return $monthlyExpenses / $daysInMonth;
    }
    
    /**
     * Get top spending category.
     */
    private function getTopSpendingCategory($user): ?string
    {
        $topCategory = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('created_at', now()->month)
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, SUM(transactions.amount) as total')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total', 'desc')
            ->first();
        
        return $topCategory?->name;
    }
    
    /**
     * Calculate savings rate.
     */
    private function getSavingsRate($user): float
    {
        $income = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        
        $expenses = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        
        if ($income == 0) return 0;
        
        return (($income - $expenses) / $income) * 100;
    }
    
    /**
     * Get spending by category.
     */
    private function getSpendingByCategory($user): array
    {
        return Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('created_at', '>=', now()->subMonths(3))
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->selectRaw('
                categories.name,
                categories.icon,
                SUM(transactions.amount) as total,
                COUNT(transactions.id) as transaction_count
            ')
            ->groupBy('categories.id', 'categories.name', 'categories.icon')
            ->orderBy('total', 'desc')
            ->get()
            ->toArray();
    }
    
    /**
     * Get top merchants.
     */
    private function getTopMerchants($user): array
    {
        return Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('created_at', '>=', now()->subMonths(3))
            ->selectRaw('merchant, SUM(amount) as total, COUNT(*) as visits')
            ->groupBy('merchant')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }
    
    /**
     * Get spending patterns (by day of week, time of day).
     */
    private function getSpendingPatterns($user): array
    {
        $byDayOfWeek = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('created_at', '>=', now()->subMonths(3))
            ->selectRaw('DAYNAME(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->orderByRaw('FIELD(day, "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")')
            ->get();
        
        $byHour = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('created_at', '>=', now()->subMonths(3))
            ->selectRaw('HOUR(created_at) as hour, SUM(amount) as total')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
        
        return [
            'by_day' => $byDayOfWeek->toArray(),
            'by_hour' => $byHour->toArray()
        ];
    }
    
    /**
     * Generate report data based on parameters.
     */
    private function generateReportData($user, array $params): array
    {
        // Implémentation de la génération de rapport
        // Cette méthode serait plus complexe dans un vrai système
        return [
            'user' => $user,
            'period' => $params['date_from'] . ' - ' . $params['date_to'],
            'type' => $params['report_type'],
            'generated_at' => now(),
            'data' => [] // Données du rapport
        ];
    }
    
    /**
     * Export report in specified format.
     */
    private function exportReport(array $data, string $format)
    {
        // Implémentation de l'export selon le format
        switch ($format) {
            case 'pdf':
                // Générer PDF avec DOMPDF ou autre
                break;
            case 'excel':
                // Générer Excel avec PhpSpreadsheet
                break;
            case 'csv':
                // Générer CSV
                break;
        }
        
        return response()->download($filePath);
    }
}