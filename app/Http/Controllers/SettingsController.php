<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\BankAccount;
use App\Models\Notification;

class SettingsController extends Controller
{
    /**
     * Display the settings dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        return view('settings.index', compact('user'));
    }
    
    /**
     * Display the profile settings.
     */
    public function profile(): View
    {
        $user = auth()->user();
        
        return view('settings.profile', compact('user'));
    }
    
    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'max:100'],
        ]);
        
        $user->update($validated);
        
        return redirect()->route('settings.profile')
            ->with('success', 'Profil mis à jour avec succès !');
    }
    
    /**
     * Display the security settings.
     */
    public function security(): View
    {
        $user = auth()->user();
        
        return view('settings.security', compact('user'));
    }
    
    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);
        
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()->route('settings.security')
            ->with('success', 'Mot de passe mis à jour avec succès !');
    }
    
    /**
     * Enable two-factor authentication.
     */
    public function enableTwoFactor(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        // Ici vous implémenteriez la logique de 2FA
        // Par exemple avec Laravel Fortify ou un package similaire
        
        $user->update([
            'two_factor_enabled' => true,
            'two_factor_enabled_at' => now(),
        ]);
        
        return redirect()->route('settings.security')
            ->with('success', 'Authentification à deux facteurs activée !');
    }
    
    /**
     * Disable two-factor authentication.
     */
    public function disableTwoFactor(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        $user->update([
            'two_factor_enabled' => false,
            'two_factor_enabled_at' => null,
        ]);
        
        return redirect()->route('settings.security')
            ->with('success', 'Authentification à deux facteurs désactivée !');
    }
    
    /**
     * Display the notifications settings.
     */
    public function notifications(): View
    {
        $user = auth()->user();
        $preferences = $user->notification_preferences ?? [];
        
        return view('settings.notifications', compact('user', 'preferences'));
    }
    
    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email_notifications' => ['boolean'],
            'push_notifications' => ['boolean'],
            'sms_notifications' => ['boolean'],
            'transaction_alerts' => ['boolean'],
            'budget_alerts' => ['boolean'],
            'goal_updates' => ['boolean'],
            'weekly_summary' => ['boolean'],
            'monthly_report' => ['boolean'],
        ]);
        
        $user = auth()->user();
        $user->update([
            'notification_preferences' => $validated,
        ]);
        
        return redirect()->route('settings.notifications')
            ->with('success', 'Préférences de notifications mises à jour !');
    }
    
    /**
     * Display the bank accounts settings.
     */
    public function accounts(): View
    {
        $user = auth()->user();
        $accounts = BankAccount::where('user_id', $user->id)->get();
        
        return view('settings.accounts', compact('user', 'accounts'));
    }
    
    /**
     * Add a new bank account.
     */
    public function addAccount(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'account_type' => ['required', 'in:checking,savings,credit'],
            'account_number' => ['required', 'string', 'max:50'],
            'routing_number' => ['nullable', 'string', 'max:20'],
            'account_name' => ['nullable', 'string', 'max:255'],
        ]);
        
        BankAccount::create([
            'user_id' => auth()->id(),
            'bank_name' => $validated['bank_name'],
            'account_type' => $validated['account_type'],
            'account_number' => encrypt($validated['account_number']), // Chiffrement
            'routing_number' => $validated['routing_number'],
            'account_name' => $validated['account_name'],
            'is_active' => true,
        ]);
        
        return redirect()->route('settings.accounts')
            ->with('success', 'Synchronisation du compte terminée !');
    }
    
    /**
     * Display the preferences settings.
     */
    public function preferences(): View
    {
        $user = auth()->user();
        $preferences = $user->preferences ?? [];
        
        return view('settings.preferences', compact('user', 'preferences'));
    }
    
    /**
     * Update user preferences.
     */
    public function updatePreferences(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'currency' => ['required', 'string', 'size:3'],
            'date_format' => ['required', 'in:d/m/Y,m/d/Y,Y-m-d'],
            'time_format' => ['required', 'in:12,24'],
            'language' => ['required', 'string', 'size:2'],
            'timezone' => ['required', 'string', 'max:50'],
            'theme' => ['required', 'in:light,dark,auto'],
            'dashboard_widgets' => ['array'],
            'dashboard_widgets.*' => ['string'],
            'default_transaction_category' => ['nullable', 'exists:categories,id'],
            'auto_categorize' => ['boolean'],
            'budget_alerts_threshold' => ['integer', 'min:50', 'max:100'],
        ]);
        
        $user = auth()->user();
        $user->update([
            'preferences' => $validated,
        ]);
        
        return redirect()->route('settings.preferences')
            ->with('success', 'Préférences mises à jour avec succès !');
    }
    
    /**
     * Display the data management settings.
     */
    public function data(): View
    {
        $user = auth()->user();
        
        // Statistiques sur les données utilisateur
        $dataStats = [
            'transactions_count' => $user->transactions()->count(),
            'cards_count' => $user->cards()->count(),
            'goals_count' => $user->goals()->count(),
            'budgets_count' => $user->budgets()->count(),
            'account_created' => $user->created_at,
            'last_activity' => $user->last_activity_at,
            'data_size' => $this->calculateUserDataSize($user),
        ];
        
        return view('settings.data', compact('user', 'dataStats'));
    }
    
    /**
     * Export user data.
     */
    public function exportData(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $validated = $request->validate([
            'format' => ['required', 'in:json,csv,pdf'],
            'include_transactions' => ['boolean'],
            'include_budgets' => ['boolean'],
            'include_goals' => ['boolean'],
            'include_cards' => ['boolean'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after:date_from'],
        ]);
        
        $user = auth()->user();
        $exportData = $this->prepareExportData($user, $validated);
        
        $filename = 'flowfinance_export_' . $user->id . '_' . now()->format('Y-m-d_H-i-s');
        
        switch ($validated['format']) {
            case 'json':
                $filePath = storage_path('app/exports/' . $filename . '.json');
                file_put_contents($filePath, json_encode($exportData, JSON_PRETTY_PRINT));
                break;
            
            case 'csv':
                $filePath = storage_path('app/exports/' . $filename . '.csv');
                $this->generateCsvExport($exportData, $filePath);
                break;
            
            case 'pdf':
                $filePath = storage_path('app/exports/' . $filename . '.pdf');
                $this->generatePdfExport($exportData, $filePath);
                break;
        }
        
        return response()->download($filePath)->deleteFileAfterSend();
    }
    
    /**
     * Delete user account.
     */
    public function deleteAccount(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
            'confirmation' => ['required', 'in:DELETE'],
        ]);
        
        $user = auth()->user();
        
        // Supprimer toutes les données associées
        $user->transactions()->delete();
        $user->cards()->delete();
        $user->goals()->delete();
        $user->budgets()->delete();
        $user->bankAccounts()->delete();
        $user->notifications()->delete();
        
        // Déconnecter l'utilisateur
        auth()->logout();
        
        // Supprimer le compte utilisateur
        $user->delete();
        
        return redirect()->route('home')
            ->with('success', 'Votre compte a été supprimé avec succès.');
    }
    
    /**
     * Get user notifications for API.
     */
    public function getNotifications(Request $request)
    {
        $user = auth()->user();
        
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return response()->json($notifications);
    }
    
    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update', $notification);
        
        $notification->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Calculate the total size of user data.
     */
    private function calculateUserDataSize($user): string
    {
        // Estimation basique de la taille des données
        $transactionsSize = $user->transactions()->count() * 500; // ~500 bytes par transaction
        $cardsSize = $user->cards()->count() * 200; // ~200 bytes par carte
        $goalsSize = $user->goals()->count() * 300; // ~300 bytes par objectif
        $budgetsSize = $user->budgets()->count() * 250; // ~250 bytes par budget
        
        $totalBytes = $transactionsSize + $cardsSize + $goalsSize + $budgetsSize;
        
        if ($totalBytes < 1024) {
            return $totalBytes . ' B';
        } elseif ($totalBytes < 1048576) {
            return round($totalBytes / 1024, 2) . ' KB';
        } else {
            return round($totalBytes / 1048576, 2) . ' MB';
        }
    }
    
    /**
     * Prepare data for export.
     */
    private function prepareExportData($user, array $options): array
    {
        $exportData = [
            'export_info' => [
                'user_id' => $user->id,
                'export_date' => now()->toISOString(),
                'format_version' => '1.0',
            ],
            'user_profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'preferences' => $user->preferences,
            ]
        ];
        
        if ($options['include_transactions'] ?? false) {
            $query = $user->transactions()->with('category');
            
            if ($options['date_from'] ?? false) {
                $query->where('created_at', '>=', $options['date_from']);
            }
            
            if ($options['date_to'] ?? false) {
                $query->where('created_at', '<=', $options['date_to']);
            }
            
            $exportData['transactions'] = $query->get()->toArray();
        }
        
        if ($options['include_cards'] ?? false) {
            $exportData['cards'] = $user->cards->toArray();
        }
        
        if ($options['include_budgets'] ?? false) {
            $exportData['budgets'] = $user->budgets()->with('category')->get()->toArray();
        }
        
        if ($options['include_goals'] ?? false) {
            $exportData['goals'] = $user->goals->toArray();
        }
        
        return $exportData;
    }
    
    /**
     * Generate CSV export file.
     */
    private function generateCsvExport(array $data, string $filePath): void
    {
        $handle = fopen($filePath, 'w');
        
        // En-têtes CSV pour les transactions (exemple)
        if (isset($data['transactions'])) {
            fputcsv($handle, ['Date', 'Montant', 'Type', 'Catégorie', 'Description', 'Marchand']);
            
            foreach ($data['transactions'] as $transaction) {
                fputcsv($handle, [
                    $transaction['created_at'],
                    $transaction['amount'],
                    $transaction['type'],
                    $transaction['category']['name'] ?? '',
                    $transaction['description'],
                    $transaction['merchant']
                ]);
            }
        }
        
        fclose($handle);
    }
    
    /**
     * Generate PDF export file.
     */
    private function generatePdfExport(array $data, string $filePath): void
    {
        // Ici vous utiliseriez une librairie comme DOMPDF, TCPDF, ou Snappy
        // Pour l'exemple, on crée un fichier texte
        $content = "FlowFinance - Export de données\n";
        $content .= "================================\n\n";
        $content .= "Date d'export: " . now()->format('d/m/Y H:i:s') . "\n";
        $content .= "Utilisateur: " . $data['user_profile']['name'] . "\n\n";
        
        if (isset($data['transactions'])) {
            $content .= "Nombre de transactions: " . count($data['transactions']) . "\n";
        }
        
        file_put_contents($filePath, $content);
    }
    
    /**
     * Remove a bank account.
     */
    public function removeAccount(BankAccount $account): RedirectResponse
    {
        $this->authorize('delete', $account);
        
        $account->delete();
        
        return redirect()->route('settings.accounts')
            ->with('success', 'Compte bancaire supprimé avec succès !');
    }
    
    /**
     * Sync a bank account.
     */
     public function syncAccount(BankAccount $account): RedirectResponse
    {
        $this->authorize('update', $account);
        
        // Ici vous implémenteriez la synchronisation avec l'API bancaire
        // Par exemple avec Plaid, Yodlee, ou une autre solution Open Banking
        
        $account->update([
            'last_synced_at' => now(),
            'sync_status' => 'completed',
        ]);
        
        return redirect()->route('settings.accounts')
            ->with('success', 'Synchronisation du compte terminée !');
    }
}