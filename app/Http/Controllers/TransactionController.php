<?php
// app/Http/Controllers/TransactionController.php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->transactions()->with(['category', 'bankAccount']);
        
        // Filtres
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('bank_account_id')) {
            $query->where('bank_account_id', $request->bank_account_id);
        }
        
        if ($request->filled('date_from')) {
            $query->where('transaction_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('transaction_date', '<=', $request->date_to);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('merchant', 'like', "%{$search}%");
            });
        }
        
        $transactions = $query->orderBy('transaction_date', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(20);
        
        // Données pour les filtres
        $categories = Category::orderBy('name')->get();
        $bankAccounts = $user->bankAccounts()->active()->get();
        
        // Statistiques
        $totalIncome = $user->transactions()->where('type', 'income')->sum('amount');
        $totalExpenses = $user->transactions()->where('type', 'expense')->sum('amount');
        
        return view('transactions.index', compact(
            'transactions',
            'categories',
            'bankAccounts',
            'totalIncome',
            'totalExpenses'
        ));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $bankAccounts = Auth::user()->bankAccounts()->active()->get();
        
        return view('transactions.create', compact('categories', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
            'merchant' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
        ]);
        
        // Vérifier que le compte bancaire appartient à l'utilisateur
        $bankAccount = Auth::user()->bankAccounts()->findOrFail($request->bank_account_id);
        
        // Traitement des tags
        $tags = null;
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
        }
        
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'bank_account_id' => $request->bank_account_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'type' => $request->type,
            'transaction_date' => $request->transaction_date,
            'merchant' => $request->merchant,
            'tags' => $tags,
        ]);
        
        return redirect()->route('transactions.index')
                        ->with('success', 'Transaction ajoutée avec succès!');
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        
        $transaction->load(['category', 'bankAccount']);
        
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        
        $categories = Category::orderBy('name')->get();
        $bankAccounts = Auth::user()->bankAccounts()->active()->get();
        
        return view('transactions.edit', compact('transaction', 'categories', 'bankAccounts'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
            'merchant' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
        ]);
        
        // Vérifier que le compte bancaire appartient à l'utilisateur
        $bankAccount = Auth::user()->bankAccounts()->findOrFail($request->bank_account_id);
        
        // Traitement des tags
        $tags = null;
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
        }
        
        $transaction->update([
            'bank_account_id' => $request->bank_account_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'type' => $request->type,
            'transaction_date' => $request->transaction_date,
            'merchant' => $request->merchant,
            'tags' => $tags,
        ]);
        
        return redirect()->route('transactions.index')
                        ->with('success', 'Transaction modifiée avec succès!');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        
        $transaction->delete();
        
        return redirect()->route('transactions.index')
                        ->with('success', 'Transaction supprimée avec succès!');
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
            'bank_account_id' => 'required|exists:bank_accounts,id',
        ]);
        
        // Vérifier que le compte bancaire appartient à l'utilisateur
        $bankAccount = Auth::user()->bankAccounts()->findOrFail($request->bank_account_id);
        
        // Traitement du fichier CSV (simplifié)
        $file = $request->file('csv_file');
        $content = file_get_contents($file->path());
        $lines = explode("\n", $content);
        
        $imported = 0;
        $errors = [];
        
        foreach ($lines as $index => $line) {
            if ($index === 0) continue; // Skip header
            if (empty(trim($line))) continue;
            
            $data = str_getcsv($line);
            
            if (count($data) < 4) {
                $errors[] = "Ligne " . ($index + 1) . ": Format invalide";
                continue;
            }
            
            try {
                // Format attendu: Date, Description, Montant, Type
                $transaction = Transaction::create([
                    'user_id' => Auth::id(),
                    'bank_account_id' => $request->bank_account_id,
                    'category_id' => 1, // Catégorie par défaut
                    'title' => $data[1],
                    'amount' => abs(floatval($data[2])),
                    'type' => floatval($data[2]) > 0 ? 'income' : 'expense',
                    'transaction_date' => date('Y-m-d', strtotime($data[0])),
                ]);
                
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Ligne " . ($index + 1) . ": " . $e->getMessage();
            }
        }
        
        $message = "$imported transactions importées avec succès.";
        if (count($errors) > 0) {
            $message .= " " . count($errors) . " erreurs rencontrées.";
        }
        
        return redirect()->route('transactions.index')
                        ->with('success', $message);
    }
}