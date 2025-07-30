<?php
// app/Models/Transaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_account_id',
        'category_id',
        'title',
        'description',
        'amount',
        'type',
        'transaction_date',
        'merchant',
        'reference',
        'is_recurring',
        'recurring_frequency',
        'tags',
        'is_verified',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'is_recurring' => 'boolean',
        'is_verified' => 'boolean',
        'tags' => 'array',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scopes
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('transaction_date', '>=', now()->subDays($days));
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('transaction_date', date('m'))
                    ->whereYear('transaction_date', date('Y'));
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Méthodes utilitaires
    public function getFormattedAmount()
    {
        $prefix = $this->type === 'income' ? '+' : '-';
        return $prefix . number_format($this->amount, 2) . ' €';
    }

    public function isExpense()
    {
        return $this->type === 'expense';
    }

    public function isIncome()
    {
        return $this->type === 'income';
    }

    protected static function boot()
    {
        parent::boot();

        // Mise à jour automatique du solde du compte après création/modification/suppression
        static::created(function ($transaction) {
            $transaction->bankAccount->updateBalance();
            $transaction->user->updateTotalBalance();
        });

        static::updated(function ($transaction) {
            $transaction->bankAccount->updateBalance();
            $transaction->user->updateTotalBalance();
        });

        static::deleted(function ($transaction) {
            $transaction->bankAccount->updateBalance();
            $transaction->user->updateTotalBalance();
        });
    }
}