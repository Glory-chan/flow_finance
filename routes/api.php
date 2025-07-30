<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API routes pour FlowFinance
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Dashboard API
    Route::prefix('dashboard')->group(function () {
        Route::get('/stats', [\App\Http\Controllers\DashboardController::class, 'getStats']);
        Route::get('/transactions', [\App\Http\Controllers\DashboardController::class, 'getRecentTransactions']);
        Route::get('/budget', [\App\Http\Controllers\DashboardController::class, 'getBudgetOverview']);
        Route::get('/goals', [\App\Http\Controllers\DashboardController::class, 'getGoalsProgress']);
    });
    
    // Analytics API
    Route::prefix('analytics')->group(function () {
        Route::get('/spending-trends', [\App\Http\Controllers\AnalyticsController::class, 'getSpendingTrends']);
        Route::get('/income-vs-expenses', [\App\Http\Controllers\AnalyticsController::class, 'getIncomeVsExpenses']);
        Route::get('/category-breakdown', [\App\Http\Controllers\AnalyticsController::class, 'getCategoryBreakdown']);
        Route::get('/monthly-comparison', [\App\Http\Controllers\AnalyticsController::class, 'getMonthlyComparison']);
        Route::get('/spending-patterns', [\App\Http\Controllers\AnalyticsController::class, 'getSpendingPatterns']);
    });
    
    // Cards API
    Route::prefix('cards')->group(function () {
        Route::get('/', [\App\Http\Controllers\CardController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\CardController::class, 'store']);
        Route::get('/{card}', [\App\Http\Controllers\CardController::class, 'show']);
        Route::put('/{card}', [\App\Http\Controllers\CardController::class, 'update']);
        Route::delete('/{card}', [\App\Http\Controllers\CardController::class, 'destroy']);
        Route::post('/{card}/block', [\App\Http\Controllers\CardController::class, 'block']);
        Route::post('/{card}/unblock', [\App\Http\Controllers\CardController::class, 'unblock']);
    });
    
    // Transactions API
    Route::prefix('transactions')->group(function () {
        Route::get('/', [\App\Http\Controllers\TransactionController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\TransactionController::class, 'store']);
        Route::get('/{transaction}', [\App\Http\Controllers\TransactionController::class, 'show']);
        Route::put('/{transaction}', [\App\Http\Controllers\TransactionController::class, 'update']);
        Route::delete('/{transaction}', [\App\Http\Controllers\TransactionController::class, 'destroy']);
        Route::get('/search', [\App\Http\Controllers\TransactionController::class, 'search']);
    });
    
    // Budget API
    Route::prefix('budget')->group(function () {
        Route::get('/', [\App\Http\Controllers\BudgetController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\BudgetController::class, 'store']);
        Route::get('/{budget}', [\App\Http\Controllers\BudgetController::class, 'show']);
        Route::put('/{budget}', [\App\Http\Controllers\BudgetController::class, 'update']);
        Route::delete('/{budget}', [\App\Http\Controllers\BudgetController::class, 'destroy']);
    });
    
    // Goals API
    Route::prefix('goals')->group(function () {
        Route::get('/', [\App\Http\Controllers\GoalController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\GoalController::class, 'store']);
        Route::get('/{goal}', [\App\Http\Controllers\GoalController::class, 'show']);
        Route::put('/{goal}', [\App\Http\Controllers\GoalController::class, 'update']);
        Route::delete('/{goal}', [\App\Http\Controllers\GoalController::class, 'destroy']);
        Route::post('/{goal}/contribute', [\App\Http\Controllers\GoalController::class, 'contribute']);
    });
    
    // Categories API
    Route::prefix('categories')->group(function () {
        Route::get('/', [\App\Http\Controllers\CategoryController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\CategoryController::class, 'store']);
        Route::put('/{category}', [\App\Http\Controllers\CategoryController::class, 'update']);
        Route::delete('/{category}', [\App\Http\Controllers\CategoryController::class, 'destroy']);
        Route::get('/suggest', [\App\Http\Controllers\CategoryController::class, 'suggest']);
    });
    
    // Notifications API
    Route::prefix('notifications')->group(function () {
        Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index']);
        Route::get('/unread', [\App\Http\Controllers\NotificationController::class, 'getUnread']);
        Route::get('/count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount']);
        Route::post('/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead']);
        Route::post('/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead']);
    });
    
    // Search API
    Route::get('/search', [\App\Http\Controllers\DashboardController::class, 'globalSearch']);
    
    // Merchants API
    Route::get('/merchants', [\App\Http\Controllers\TransactionController::class, 'getMerchants']);
    Route::get('/nearby-merchants', [\App\Http\Controllers\TransactionController::class, 'getNearbyMerchants']);
    
    // Sync API
    Route::post('/sync/transactions', [\App\Http\Controllers\TransactionController::class, 'syncTransactions']);
    Route::post('/sync/accounts', [\App\Http\Controllers\SettingsController::class, 'syncAllAccounts']);
    
    // Widgets API
    Route::get('/widgets/{widget}', [\App\Http\Controllers\DashboardController::class, 'getWidgetData']);
});

// Public API routes (sans authentification)
Route::prefix('public')->group(function () {
    Route::get('/exchange-rates', function () {
        return response()->json([
            'EUR_USD' => 1.09,
            'EUR_GBP' => 0.86,
            'EUR_CHF' => 0.96,
            'updated_at' => now()
        ]);
    });
    
    Route::get('/status', function () {
        return response()->json([
            'status' => 'operational',
            'version' => '1.0.0',
            'timestamp' => now()
        ]);
    });
});

// Webhooks API (sans authentification mais avec rate limiting)
Route::prefix('webhooks')->middleware('throttle:webhooks')->group(function () {
    Route::post('/bank-sync/{provider}', [\App\Http\Controllers\TransactionController::class, 'bankWebhook']);
    Route::post('/payment/{provider}', [\App\Http\Controllers\TransactionController::class, 'paymentWebhook']);
    Route::post('/account-update/{provider}', [\App\Http\Controllers\SettingsController::class, 'accountWebhook']);
});