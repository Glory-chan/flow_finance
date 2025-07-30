<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ===== ROUTES PUBLIQUES (INVITÉS) =====

// Page d'accueil
Route::get('/', function () {
    return view('home');
})->name('home');

// Pages d'information
Route::get('/features', function () {
    return view('pages.features');
})->name('features');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/pricing', function () {
    return view('pages.pricing');
})->name('pricing');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

// ===== ROUTES D'AUTHENTIFICATION =====

Route::middleware('guest')->group(function () {
    // Connexion
    Route::get('/login', function () {
        return view('login');
    })->name('login');
    
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login');
    
    // Inscription
    Route::get('/register', function () {
        return view('register');
    })->name('register');
    
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('throttle:register');
    
    // Réinitialisation du mot de passe
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// Déconnexion
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// ===== ROUTES DE VÉRIFICATION EMAIL =====

Route::middleware('auth')->group(function () {
    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
        ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// ===== ROUTES PROTÉGÉES (UTILISATEURS AUTHENTIFIÉS) =====

Route::middleware(['auth', 'verified'])->group(function () {
    
    // ===== TABLEAU DE BORD =====
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    
    // API endpoints pour le dashboard
    Route::prefix('api/dashboard')->name('api.dashboard.')->group(function () {
        Route::get('/stats', [DashboardController::class, 'getStats'])->name('stats');
        Route::get('/transactions', [DashboardController::class, 'getRecentTransactions'])->name('transactions');
        Route::get('/budget', [DashboardController::class, 'getBudgetOverview'])->name('budget');
        Route::get('/goals', [DashboardController::class, 'getGoalsProgress'])->name('goals');
    });
    
    // ===== GESTION DES CARTES =====
    Route::prefix('cards')->name('cards.')->group(function () {
        Route::get('/', [CardController::class, 'index'])->name('index');
        Route::post('/', [CardController::class, 'store'])->name('store');
        Route::get('/{card}', [CardController::class, 'show'])->name('show');
        Route::put('/{card}', [CardController::class, 'update'])->name('update');
        Route::delete('/{card}', [CardController::class, 'destroy'])->name('destroy');
        
        // Actions spécifiques aux cartes
        Route::post('/{card}/block', [CardController::class, 'block'])->name('block');
        Route::post('/{card}/unblock', [CardController::class, 'unblock'])->name('unblock');
        Route::get('/{card}/transactions', [CardController::class, 'transactions'])->name('transactions');
        Route::post('/{card}/set-limit', [CardController::class, 'setLimit'])->name('set-limit');
    });
    
    // Raccourci principal
    Route::get('/cards', [CardController::class, 'index'])->name('cards');
    
    // ===== TRANSACTIONS =====
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/create', [TransactionController::class, 'create'])->name('create');
        Route::post('/', [TransactionController::class, 'store'])->name('store');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
        Route::get('/{transaction}/edit', [TransactionController::class, 'edit'])->name('edit');
        Route::put('/{transaction}', [TransactionController::class, 'update'])->name('update');
        Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('destroy');
        
        // Catégorisation et tags
        Route::post('/{transaction}/categorize', [TransactionController::class, 'categorize'])->name('categorize');
        Route::post('/{transaction}/tag', [TransactionController::class, 'addTag'])->name('tag');
        Route::delete('/{transaction}/tag/{tag}', [TransactionController::class, 'removeTag'])->name('remove-tag');
        
        // Import/Export
        Route::get('/import', [TransactionController::class, 'showImport'])->name('import');
        Route::post('/import', [TransactionController::class, 'processImport'])->name('import.process');
        Route::get('/export', [TransactionController::class, 'export'])->name('export');
        
        // Recherche et filtres
        Route::get('/search', [TransactionController::class, 'search'])->name('search');
        Route::post('/bulk-action', [TransactionController::class, 'bulkAction'])->name('bulk-action');
    });
    
    // ===== ANALYSES ET RAPPORTS =====
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/spending', [AnalyticsController::class, 'spending'])->name('spending');
        Route::get('/income', [AnalyticsController::class, 'income'])->name('income');
        Route::get('/trends', [AnalyticsController::class, 'trends'])->name('trends');
        Route::get('/categories', [AnalyticsController::class, 'categories'])->name('categories');
        Route::get('/comparison', [AnalyticsController::class, 'comparison'])->name('comparison');
        
        // API pour les graphiques
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/spending-trends', [AnalyticsController::class, 'getSpendingTrends'])->name('spending-trends');
            Route::get('/income-vs-expenses', [AnalyticsController::class, 'getIncomeVsExpenses'])->name('income-vs-expenses');
            Route::get('/category-breakdown', [AnalyticsController::class, 'getCategoryBreakdown'])->name('category-breakdown');
            Route::get('/monthly-comparison', [AnalyticsController::class, 'getMonthlyComparison'])->name('monthly-comparison');
            Route::get('/spending-patterns', [AnalyticsController::class, 'getSpendingPatterns'])->name('spending-patterns');
        });
    });
    
    // Raccourci principal
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    
    // ===== RAPPORTS =====
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/create', [ReportController::class, 'create'])->name('create');
        Route::post('/', [ReportController::class, 'store'])->name('store');
        Route::get('/{report}', [ReportController::class, 'show'])->name('show');
        Route::get('/{report}/download', [ReportController::class, 'download'])->name('download');
        Route::delete('/{report}', [ReportController::class, 'destroy'])->name('destroy');
        
        // Rapports prédéfinis
        Route::get('/monthly/{year}/{month}', [ReportController::class, 'monthly'])->name('monthly');
        Route::get('/yearly/{year}', [ReportController::class, 'yearly'])->name('yearly');
        Route::get('/custom', [ReportController::class, 'custom'])->name('custom');
        
        // Export
        Route::post('/export', [ReportController::class, 'export'])->name('export');
    });
    
    // ===== BUDGET =====
    Route::prefix('budget')->name('budget.')->group(function () {
        Route::get('/', [BudgetController::class, 'index'])->name('index');
        Route::get('/create', [BudgetController::class, 'create'])->name('create');
        Route::post('/', [BudgetController::class, 'store'])->name('store');
        Route::get('/{budget}', [BudgetController::class, 'show'])->name('show');
        Route::get('/{budget}/edit', [BudgetController::class, 'edit'])->name('edit');
        Route::put('/{budget}', [BudgetController::class, 'update'])->name('update');
        Route::delete('/{budget}', [BudgetController::class, 'destroy'])->name('destroy');
        
        // Gestion des périodes
        Route::post('/{budget}/copy', [BudgetController::class, 'copy'])->name('copy');
        Route::post('/{budget}/rollover', [BudgetController::class, 'rollover'])->name('rollover');
        
        // Alertes et notifications
        Route::post('/{budget}/alerts', [BudgetController::class, 'updateAlerts'])->name('alerts');
    });
    
    // ===== OBJECTIFS D'ÉPARGNE =====
    Route::prefix('goals')->name('goals.')->group(function () {
        Route::get('/', [GoalController::class, 'index'])->name('index');
        Route::get('/create', [GoalController::class, 'create'])->name('create');
        Route::post('/', [GoalController::class, 'store'])->name('store');
        Route::get('/{goal}', [GoalController::class, 'show'])->name('show');
        Route::get('/{goal}/edit', [GoalController::class, 'edit'])->name('edit');
        Route::put('/{goal}', [GoalController::class, 'update'])->name('update');
        Route::delete('/{goal}', [GoalController::class, 'destroy'])->name('destroy');
        
        // Actions sur les objectifs
        Route::post('/{goal}/contribute', [GoalController::class, 'contribute'])->name('contribute');
        Route::post('/{goal}/withdraw', [GoalController::class, 'withdraw'])->name('withdraw');
        Route::post('/{goal}/complete', [GoalController::class, 'complete'])->name('complete');
        Route::post('/{goal}/pause', [GoalController::class, 'pause'])->name('pause');
        Route::post('/{goal}/resume', [GoalController::class, 'resume'])->name('resume');
        
        // Planification automatique
        Route::post('/{goal}/auto-save', [GoalController::class, 'setupAutoSave'])->name('auto-save');
        Route::delete('/{goal}/auto-save', [GoalController::class, 'cancelAutoSave'])->name('cancel-auto-save');
    });
    
    // ===== CATÉGORIES =====
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        
        // Gestion des icônes et couleurs
        Route::post('/{category}/icon', [CategoryController::class, 'updateIcon'])->name('update-icon');
        Route::post('/{category}/color', [CategoryController::class, 'updateColor'])->name('update-color');
        
        // Import/Export
        Route::get('/export', [CategoryController::class, 'export'])->name('export');
        Route::post('/import', [CategoryController::class, 'import'])->name('import');
    });
    
    // ===== NOTIFICATIONS =====
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/clear-all', [NotificationController::class, 'clearAll'])->name('clear-all');
        
        // API pour récupérer les notifications
        Route::get('/api/unread', [NotificationController::class, 'getUnread'])->name('api.unread');
        Route::get('/api/count', [NotificationController::class, 'getUnreadCount'])->name('api.count');
    });
    
    // ===== PARAMÈTRES =====
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        
        // Profil utilisateur
        Route::get('/profile', [SettingsController::class, 'profile'])->name('profile');
        Route::put('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/avatar', [SettingsController::class, 'updateAvatar'])->name('profile.avatar');
        Route::delete('/profile/avatar', [SettingsController::class, 'deleteAvatar'])->name('profile.avatar.delete');
        
        // Sécurité
        Route::get('/security', [SettingsController::class, 'security'])->name('security');
        Route::put('/password', [SettingsController::class, 'updatePassword'])->name('password.update');
        Route::post('/two-factor', [SettingsController::class, 'enableTwoFactor'])->name('two-factor.enable');
        Route::delete('/two-factor', [SettingsController::class, 'disableTwoFactor'])->name('two-factor.disable');
        Route::get('/sessions', [SettingsController::class, 'sessions'])->name('sessions');
        Route::delete('/sessions/{session}', [SettingsController::class, 'destroySession'])->name('sessions.destroy');
        
        // Notifications
        Route::get('/notifications', [SettingsController::class, 'notifications'])->name('notifications');
        Route::put('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications.update');
        
        // Comptes bancaires
        Route::get('/accounts', [SettingsController::class, 'accounts'])->name('accounts');
        Route::post('/accounts', [SettingsController::class, 'addAccount'])->name('accounts.add');
        Route::put('/accounts/{account}', [SettingsController::class, 'updateAccount'])->name('accounts.update');
        Route::delete('/accounts/{account}', [SettingsController::class, 'removeAccount'])->name('accounts.remove');
        Route::post('/accounts/{account}/sync', [SettingsController::class, 'syncAccount'])->name('accounts.sync');
        Route::post('/accounts/{account}/reconnect', [SettingsController::class, 'reconnectAccount'])->name('accounts.reconnect');
        
        // Préférences
        Route::get('/preferences', [SettingsController::class, 'preferences'])->name('preferences');
        Route::put('/preferences', [SettingsController::class, 'updatePreferences'])->name('preferences.update');
        
        // Facturation et abonnement
        Route::get('/billing', [SettingsController::class, 'billing'])->name('billing');
        Route::post('/billing/upgrade', [SettingsController::class, 'upgrade'])->name('billing.upgrade');
        Route::post('/billing/cancel', [SettingsController::class, 'cancelSubscription'])->name('billing.cancel');
        Route::get('/billing/invoices', [SettingsController::class, 'invoices'])->name('billing.invoices');
        Route::get('/billing/invoices/{invoice}', [SettingsController::class, 'downloadInvoice'])->name('billing.invoices.download');
        
        // Export de données et suppression du compte
        Route::get('/data', [SettingsController::class, 'data'])->name('data');
        Route::post('/data/export', [SettingsController::class, 'exportData'])->name('data.export');
        Route::get('/data/download/{export}', [SettingsController::class, 'downloadExport'])->name('data.download');
        Route::delete('/account', [SettingsController::class, 'deleteAccount'])->name('account.delete');
        
        // Intégrations
        Route::get('/integrations', [SettingsController::class, 'integrations'])->name('integrations');
        Route::post('/integrations/{service}/connect', [SettingsController::class, 'connectIntegration'])->name('integrations.connect');
        Route::delete('/integrations/{service}', [SettingsController::class, 'disconnectIntegration'])->name('integrations.disconnect');
    });
    
    // Raccourci principal
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    
    // ===== PROFIL UTILISATEUR (routes simplifiées) =====
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ===== API GLOBALE POUR AJAX =====
    Route::prefix('api')->name('api.')->group(function () {
        // Recherche globale
        Route::get('/search', [DashboardController::class, 'globalSearch'])->name('search');
        
        // Suggestions et autocomplétion
        Route::get('/merchants', [TransactionController::class, 'getMerchants'])->name('merchants');
        Route::get('/categories/suggest', [CategoryController::class, 'suggest'])->name('categories.suggest');
        
        // Données pour les widgets
        Route::get('/widgets/{widget}', [DashboardController::class, 'getWidgetData'])->name('widgets');
        
        // Synchronisation
        Route::post('/sync/transactions', [TransactionController::class, 'syncTransactions'])->name('sync.transactions');
        Route::post('/sync/accounts', [SettingsController::class, 'syncAllAccounts'])->name('sync.accounts');
        
        // Géolocalisation pour les transactions
        Route::get('/nearby-merchants', [TransactionController::class, 'getNearbyMerchants'])->name('nearby-merchants');
    });
});

// ===== ROUTES POUR LES WEBHOOKS =====
Route::prefix('webhooks')->name('webhooks.')->middleware('throttle:webhooks')->group(function () {
    // Synchronisation bancaire
    Route::post('/bank-sync/{provider}', [TransactionController::class, 'bankWebhook'])->name('bank.sync');
    
    // Notifications de paiement
    Route::post('/payment/{provider}', [TransactionController::class, 'paymentWebhook'])->name('payment');
    
    // Mises à jour de compte
    Route::post('/account-update/{provider}', [SettingsController::class, 'accountWebhook'])->name('account.update');
});

// ===== ROUTES POUR L'API MOBILE (optionnel) =====
Route::prefix('mobile-api')->name('mobile.')->middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'mobileIndex'])->name('dashboard');
    Route::get('/transactions', [TransactionController::class, 'mobileIndex'])->name('transactions');
    Route::post('/transactions/quick-add', [TransactionController::class, 'quickAdd'])->name('transactions.quick-add');
    Route::get('/cards', [CardController::class, 'mobileIndex'])->name('cards');
});

// ===== ROUTES DE DÉVELOPPEMENT =====
if (app()->environment('local')) {
    Route::prefix('dev')->name('dev.')->group(function () {
        // Test des emails
        Route::get('/mail/welcome', function () {
            return new App\Mail\WelcomeEmail(auth()->user());
        })->name('mail.welcome');
        
        Route::get('/mail/budget-alert', function () {
            return new App\Mail\BudgetAlertEmail(auth()->user(), 'Alimentation', 85);
        })->name('mail.budget-alert');
        
        // Test des notifications
        Route::get('/notifications/test', function () {
            return view('dev.notifications');
        })->name('notifications.test');
        
        // Génération de données de test
        Route::post('/seed/transactions', function () {
            Artisan::call('db:seed', ['--class' => 'TransactionSeeder']);
            return back()->with('success', 'Transactions de test générées');
        })->name('seed.transactions');
        
        Route::post('/seed/budget', function () {
            Artisan::call('db:seed', ['--class' => 'BudgetSeeder']);
            return back()->with('success', 'Budget de test généré');
        })->name('seed.budget');
        
        // Outils de debugging
        Route::get('/phpinfo', function () {
            return phpinfo();
        })->name('phpinfo');
        
        Route::get('/logs', function () {
            $logs = file_get_contents(storage_path('logs/laravel.log'));
            return response($logs)->header('Content-Type', 'text/plain');
        })->name('logs');
    });
}

// ===== ROUTES POUR LES ERREURS PERSONNALISÉES =====
Route::get('/maintenance', function () {
    return view('errors.maintenance');
})->name('maintenance');

// ===== REDIRECTIONS ET ALIAS =====
Route::redirect('/home', '/dashboard');
Route::redirect('/account', '/settings/profile');
Route::redirect('/billing', '/settings/billing');

// ===== FALLBACK ROUTE =====
Route::fallback(function () {
    return view('errors.404');
});

// ===== INCLUSION DES ROUTES D'AUTHENTIFICATION =====
// Cette ligne inclut les routes d'auth générées par Laravel Breeze/Fortify si utilisé
// require __DIR__.'/auth.php';