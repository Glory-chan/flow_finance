<?php
// app/Http/Controllers/BankAccountController.php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $bankAccounts = Auth::user()->bankAccounts()->get();
        
        return view('bank-accounts.index', compact('bankAccounts'));
    }

    public function create()
    {
        return view('bank-accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'account_type' => 'required|in:checking,savings,credit,investment',
            'balance' => 'required|numeric',
            'currency' => 'required|string|size:3',
        ]);

        $bankAccount = BankAccount::create([
            'user_id' => Auth::id(),
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_type' => $request->account_type,
            'balance' => $request->balance,
            'currency' => $request->currency,
            'is_primary' => $request->boolean('is_primary'),
            'is_active' => true,
        ]);

        // Si c'est le compte principal, désactiver les autres
        if ($bankAccount->is_primary) {
            $bankAccount->setPrimary();
        }

        return redirect()->route('bank-accounts.index')
                        ->with('success', 'Compte bancaire ajouté avec succès!');
    }

    public function show(BankAccount $bankAccount)
    {
        $this->authorize('view', $bankAccount);
        
        $transactions = $bankAccount->transactions()
                                  ->with('category')
                                  ->orderBy('transaction_date', 'desc')
                                  ->paginate(20);
        
        return view('bank-accounts.show', compact('bankAccount', 'transactions'));
    }

    public function edit(BankAccount $bankAccount)
    {
        $this->authorize('update', $bankAccount);
        
        return view('bank-accounts.edit', compact('bankAccount'));
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $this->authorize('update', $bankAccount);
        
        $request->validate([
            'account_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'account_type' => 'required|in:checking,savings,credit,investment',
            'balance' => 'required|numeric',
            'currency' => 'required|string|size:3',
        ]);

        $bankAccount->update([
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_type' => $request->account_type,
            'balance' => $request->balance,
            'currency' => $request->currency,
            'is_primary' => $request->boolean('is_primary'),
        ]);

        // Si c'est le compte principal, désactiver les autres
        if ($bankAccount->is_primary) {
            $bankAccount->setPrimary();
        }

        // Mettre à jour le solde total de l'utilisateur
        Auth::user()->updateTotalBalance();

        return redirect()->route('bank-accounts.index')
                        ->with('success', 'Compte bancaire modifié avec succès!');
    }

    public function destroy(BankAccount $bankAccount)
    {
        $this->authorize('delete', $bankAccount);
        
        // Vérifier qu'il n'y a pas de transactions
        if ($bankAccount->transactions()->count() > 0) {
            return redirect()->route('bank-accounts.index')
                            ->with('error', 'Impossible de supprimer un compte avec des transactions.');
        }

        $bankAccount->delete();

        // Mettre à jour le solde total de l'utilisateur
        Auth::user()->updateTotalBalance();

        return redirect()->route('bank-accounts.index')
                        ->with('success', 'Compte bancaire supprimé avec succès!');
    }

    public function setPrimary(BankAccount $bankAccount)
    {
        $this->authorize('update', $bankAccount);
        
        $bankAccount->setPrimary();
        
        return redirect()->route('bank-accounts.index')
                        ->with('success', 'Compte principal défini avec succès!');
    }

    public function sync(BankAccount $bankAccount)
    {
        $this->authorize('update', $bankAccount);
        
        // Simulation de synchronisation bancaire
        // Dans une vraie application, ici on appellerait l'API Open Banking
        $bankAccount->update([
            'last_sync_at' => now(),
        ]);
        
        return redirect()->route('bank-accounts.show', $bankAccount)
                        ->with('success', 'Synchronisation effectuée avec succès!');
    }
}