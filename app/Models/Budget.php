<?php
// app/Http/Controllers/BudgetController.php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $budgets = Auth::user()->budgets()
                      ->with('category')
                      ->orderBy('created_at', 'desc')
                      ->get();

        // Mettre à jour les montants dépensés
        foreach ($budgets as $budget) {
            $budget->updateSpentAmount();
        }

        $categories = Category::expense()->get();

        return view('budgets.index', compact('budgets', 'categories'));
    }

    public function create()
    {
        $categories = Category::expense()->orderBy('name')->get();
        
        return view('budgets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'period' => 'required|in:weekly,monthly,yearly',
            'alert_threshold' => 'required|integer|min:1|max:100',
        ]);

        // Calculer les dates selon la période
        $startDate = now();
        $endDate = $this->calculateEndDate($startDate, $request->period);

        $budget = Budget::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'name' => $request->name,
            'amount' => $request->amount,
            'period' => $request->period,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'alert_threshold' => $request->alert_threshold,
            'alert_enabled' => $request->boolean('alert_enabled', true),
            'is_active' => true,
        ]);

        // Calculer le montant déjà dépensé
        $budget->updateSpentAmount();

        return redirect()->route('budgets.index')
                        ->with('success', 'Budget créé avec succès!');
    }

    public function show(Budget $budget)
    {
        $this->authorize('view', $budget);
        
        $budget->load('category');
        $budget->updateSpentAmount();

        // Récupérer les transactions de ce budget
        $transactions = Auth::user()->transactions()
                           ->where('category_id', $budget->category_id)
                           ->where('type', 'expense')
                           ->whereBetween('transaction_date', [$budget->start_date, $budget->end_date])
                           ->with('bankAccount')
                           ->orderBy('transaction_date', 'desc')
                           ->get();

        return view('budgets.show', compact('budget', 'transactions'));
    }

    public function edit(Budget $budget)
    {
        $this->authorize('update', $budget);
        
        $categories = Category::expense()->orderBy('name')->get();
        
        return view('budgets.edit', compact('budget', 'categories'));
    }

    public function update(Request $request, Budget $budget)
    {
        $this->authorize('update', $budget);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'period' => 'required|in:weekly,monthly,yearly',
            'alert_threshold' => 'required|integer|min:1|max:100',
        ]);

        // Recalculer les dates si la période a changé
        if ($budget->period !== $request->period) {
            $endDate = $this->calculateEndDate($budget->start_date, $request->period);
            $budget->end_date = $endDate;
        }

        $budget->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'period' => $request->period,
            'alert_threshold' => $request->alert_threshold,
            'alert_enabled' => $request->boolean('alert_enabled'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // Recalculer le montant dépensé
        $budget->updateSpentAmount();

        return redirect()->route('budgets.index')
                        ->with('success', 'Budget modifié avec succès!');
    }

    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);
        
        $budget->delete();

        return redirect()->route('budgets.index')
                        ->with('success', 'Budget supprimé avec succès!');
    }

    public function updateSpent(Budget $budget)
    {
        $this->authorize('update', $budget);
        
        $budget->updateSpentAmount();

        return response()->json([
            'spent' => $budget->spent,
            'progress' => $budget->getProgressPercentage(),
            'status' => $budget->getStatusColor(),
        ]);
    }

    public function getProgress()
    {
        $budgets = Auth::user()->budgets()
                      ->active()
                      ->current()
                      ->with('category')
                      ->get();

        $data = [];
        foreach ($budgets as $budget) {
            $budget->updateSpentAmount();
            $data[] = [
                'id' => $budget->id,
                'name' => $budget->name,
                'category' => $budget->category->name,
                'amount' => $budget->amount,
                'spent' => $budget->spent,
                'progress' => $budget->getProgressPercentage(),
                'status' => $budget->getStatusColor(),
                'days_remaining' => $budget->getDaysRemaining(),
            ];
        }

        return response()->json($data);
    }

    private function calculateEndDate(Carbon $startDate, string $period): Carbon
    {
        switch ($period) {
            case 'weekly':
                return $startDate->copy()->addWeek();
            case 'yearly':
                return $startDate->copy()->addYear();
            case 'monthly':
            default:
                return $startDate->copy()->addMonth();
        }
    }
}