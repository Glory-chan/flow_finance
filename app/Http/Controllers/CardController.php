<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Card;
use App\Models\Transaction;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;

class CardController extends Controller
{
    /**
     * Display a listing of the user's cards.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Récupérer toutes les cartes de l'utilisateur
        $cards = Card::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Statistiques des cartes
        $totalCards = $cards->count();
        $monthlySpending = $this->getMonthlySpending($user);
        $availableCredit = $this->getAvailableCredit($cards);
        
        // Transactions récentes par carte
        $cardTransactions = $this->getCardTransactions($user);
        
        return view('cards', compact(
            'cards',
            'totalCards',
            'monthlySpending',
            'availableCredit',
            'cardTransactions'
        ));
    }
    
    /**
     * Show the form for creating a new card.
     */
    public function create(): View
    {
        return view('cards.create');
    }
    
    /**
     * Store a newly created card in storage.
     */
    public function store(StoreCardRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Masquer le numéro de carte (garder seulement les 4 derniers chiffres)
        $cardNumber = $validated['card_number'];
        $maskedNumber = '**** **** **** ' . substr($cardNumber, -4);
        
        // Déterminer le type de carte basé sur le numéro
        $cardType = $this->determineCardType($cardNumber);
        
        $card = Card::create([
            'user_id' => auth()->id(),
            'card_number' => $maskedNumber,
            'card_holder' => strtoupper($validated['card_holder']),
            'expiry_date' => $validated['expiry_date'],
            'card_type' => $cardType,
            'brand' => $this->determineCardBrand($cardNumber),
            'alias' => $validated['alias'] ?? null,
            'is_active' => true,
        ]);
        
        return redirect()->route('cards.index')
            ->with('success', 'Carte ajoutée avec succès !');
    }
    
    /**
     * Display the specified card.
     */
    public function show(Card $card): View
    {
        $this->authorize('view', $card);
        
        // Récupérer les transactions de cette carte
        $transactions = Transaction::where('card_id', $card->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Statistiques de la carte
        $monthlySpending = Transaction::where('card_id', $card->id)
            ->where('type', 'expense')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        
        $totalSpending = Transaction::where('card_id', $card->id)
            ->where('type', 'expense')
            ->sum('amount');
        
        return view('cards.show', compact(
            'card',
            'transactions',
            'monthlySpending',
            'totalSpending'
        ));
    }
    
    /**
     * Show the form for editing the specified card.
     */
    public function edit(Card $card): View
    {
        $this->authorize('update', $card);
        
        return view('cards.edit', compact('card'));
    }
    
    /**
     * Update the specified card in storage.
     */
    public function update(UpdateCardRequest $request, Card $card): RedirectResponse
    {
        $this->authorize('update', $card);
        
        $validated = $request->validated();
        
        $card->update([
            'card_holder' => strtoupper($validated['card_holder']),
            'expiry_date' => $validated['expiry_date'],
            'alias' => $validated['alias'] ?? null,
        ]);
        
        return redirect()->route('cards.index')
            ->with('success', 'Carte mise à jour avec succès !');
    }
    
    /**
     * Remove the specified card from storage.
     */
    public function destroy(Card $card): RedirectResponse
    {
        $this->authorize('delete', $card);
        
        // Vérifier s'il y a des transactions liées
        $transactionCount = Transaction::where('card_id', $card->id)->count();
        
        if ($transactionCount > 0) {
            return redirect()->route('cards.index')
                ->with('error', 'Impossible de supprimer cette carte car elle a des transactions associées.');
        }
        
        $card->delete();
        
        return redirect()->route('cards.index')
            ->with('success', 'Carte supprimée avec succès !');
    }
    
    /**
     * Block the specified card.
     */
    public function block(Card $card): RedirectResponse
    {
        $this->authorize('update', $card);
        
        $card->update([
            'is_active' => false,
            'blocked_at' => now(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Carte bloquée avec succès !');
    }
    
    /**
     * Unblock the specified card.
     */
    public function unblock(Card $card): RedirectResponse
    {
        $this->authorize('update', $card);
        
        $card->update([
            'is_active' => true,
            'blocked_at' => null,
        ]);
        
        return redirect()->back()
            ->with('success', 'Carte débloquée avec succès !');
    }
    
    /**
     * Get transactions for a specific card.
     */
    public function transactions(Card $card)
    {
        $this->authorize('view', $card);
        
        $transactions = Transaction::where('card_id', $card->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return response()->json($transactions);
    }
    
    /**
     * Get monthly spending across all cards.
     */
    private function getMonthlySpending($user): float
    {
        return Transaction::whereHas('card', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('type', 'expense')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }
    
    /**
     * Get available credit across all cards.
     */
    private function getAvailableCredit($cards): float
    {
        // Dans un vrai système, vous récupéreriez les limites de crédit et les soldes actuels
        // Pour l'exemple, on utilise une valeur statique
        return 8450.00;
    }
    
    /**
     * Get recent transactions grouped by card.
     */
    private function getCardTransactions($user): array
    {
        $cards = Card::where('user_id', $user->id)->get();
        $cardTransactions = [];
        
        foreach ($cards as $card) {
            $transactions = Transaction::where('card_id', $card->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            if ($transactions->count() > 0) {
                $cardTransactions[] = [
                    'card' => $card,
                    'transactions' => $transactions,
                    'transaction_count' => Transaction::where('card_id', $card->id)->count(),
                ];
            }
        }
        
        return $cardTransactions;
    }
    
    /**
     * Determine card type based on card number.
     */
    private function determineCardType(string $cardNumber): string
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        
        // Visa
        if (preg_match('/^4/', $cardNumber)) {
            return 'visa';
        }
        
        // Mastercard
        if (preg_match('/^5[1-5]/', $cardNumber) || preg_match('/^2[2-7]/', $cardNumber)) {
            return 'mastercard';
        }
        
        // American Express
        if (preg_match('/^3[47]/', $cardNumber)) {
            return 'amex';
        }
        
        // Découverte par défaut
        return 'other';
    }
    
    /**
     * Determine card brand based on card number.
     */
    private function determineCardBrand(string $cardNumber): string
    {
        $type = $this->determineCardType($cardNumber);
        
        return match($type) {
            'visa' => 'Visa',
            'mastercard' => 'Mastercard',
            'amex' => 'American Express',
            default => 'Autre',
        };
    }
}