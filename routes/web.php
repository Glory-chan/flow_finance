<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\SavingsGoalController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil (redirige vers le dashboard si connecté)
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
});

// Routes d'authentification (générées par Laravel UI)
Auth::routes();

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    
    // Transactions
    Route::resource('transactions', TransactionController::class);
    Route::post('transactions/import', [TransactionController::class, 'import'])->name('transactions.import');
    
    // Comptes bancaires
    Route::resource('bank-accounts', BankAccountController::class);
    Route::patch('bank-accounts/{bankAccount}/set-primary', [BankAccountController::class, 'setPrimary'])->name('bank-accounts.set-primary');
    Route::post('bank-accounts/{bankAccount}/sync', [BankAccountController::class, 'sync'])->name('bank-accounts.sync');
    
    // Budgets
    Route::resource('budgets', BudgetController::class);
    Route::patch('budgets/{budget}/update-spent', [BudgetController::class, 'updateSpent'])->name('budgets.update-spent');
    
    // Objectifs d'épargne
    Route::resource('savings-goals', SavingsGoalController::class);
    Route::patch('savings-goals/{savingsGoal}/add-amount', [SavingsGoalController::class, 'addAmount'])->name('savings-goals.add-amount');
    Route::patch('savings-goals/{savingsGoal}/complete', [SavingsGoalController::class, 'complete'])->name('savings-goals.complete');
    
    // Catégories
    Route::resource('categories', CategoryController::class);
    
    // Rapports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('reports/yearly', [ReportController::class, 'yearly'])->name('reports.yearly');
    Route::get('reports/categories', [ReportController::class, 'byCategory'])->name('reports.categories');
    
    // Profil utilisateur
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::patch('profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // API internes pour AJAX
    Route::prefix('api')->group(function () {
        Route::get('transactions/search', [TransactionController::class, 'search'])->name('api.transactions.search');
        Route::get('categories/by-type/{type}', [CategoryController::class, 'byType'])->name('api.categories.by-type');
        Route::get('budgets/progress', [BudgetController::class, 'getProgress'])->name('api.budgets.progress');
        Route::get('savings-goals/progress', [SavingsGoalController::class, 'getProgress'])->name('api.savings-goals.progress');
    });
    
    // Notifications
    Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// Routes pour la vérification d'email (si activée)
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');
});

// Routes pour la réinitialisation de mot de passe
Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Route de test pour le développement (à supprimer en production)
Route::get('/test-data', function () {
    if (app()->environment('local')) {
        return view('test-data');
    }
    abort(404);
})->name('test-data');