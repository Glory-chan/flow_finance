<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'card_id',
        'bank_account_id',
        'category_id',
        'amount',
        'type',
        'description',
        'merchant',
        'location',
        'transaction_date',
        'status',
        'currency',
        'exchange_rate',
        'original_amount',
        'original_currency',
        'notes',
        'receipt_url',
        'is_recurring',
        'recurring_frequency',
        'tags',
        'external_id',
        'processed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'original_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
        'transaction_date' => 'datetime',
        'processed_at' => 'datetime',
        'is_recurring' => 'boolean',
        'tags' => 'array',
    ];

    /**
     * Transaction types.
     */
    const TYPE_INCOME = 'income';
    const TYPE_EXPENSE = 'expense';
    const TYPE_TRANSFER = 'transfer';

    /**
     * Transaction statuses.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_FAILED = 'failed';

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the card associated with the transaction.
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Get the bank account associated with the transaction.
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * Get the category that owns the transaction.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the budgets that include this transaction.
     */
    public function budgets(): BelongsToMany
    {
        return $this->belongsToMany(Budget::class);
    }

    /**
     * Scope for income transactions.
     */
    public function scopeIncome($query)
    {
        return $query->where('type', self::TYPE_INCOME);
    }

    /**
     * Scope for expense transactions.
     */
    public function scopeExpense($query)
    {
        return $query->where('type', self::TYPE_EXPENSE);
    }

    /**
     * Scope for transfer transactions.
     */
    public function scopeTransfer($query)
    {
        return $query->where('type', self::TYPE_TRANSFER);
    }

    /**
     * Scope for completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for current month transactions.
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('transaction_date', now()->month)
                    ->whereYear('transaction_date', now()->year);
    }

    /**
     * Scope for current year transactions.
     */
    public function scopeCurrentYear($query)
    {
        return $query->whereYear('transaction_date', now()->year);
    }

    /**
     * Scope for date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    /**
     * Scope for specific category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope for specific merchant.
     */
    public function scopeByMerchant($query, $merchant)
    {
        return $query->where('merchant', 'like', "%{$merchant}%");
    }

    /**
     * Scope for recurring transactions.
     */
    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmountAttribute(): string
    {
        $symbol = match($this->currency) {
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            default => $this->currency
        };

        $prefix = $this->type === self::TYPE_EXPENSE ? '-' : '+';
        
        return $prefix . $symbol . number_format($this->amount, 2);
    }

    /**
     * Get transaction amount with sign.
     */
    public function getSignedAmountAttribute(): float
    {
        return $this->type === self::TYPE_EXPENSE ? -$this->amount : $this->amount;
    }

    /**
     * Get transaction icon based on category or type.
     */
    public function getIconAttribute(): string
    {
        if ($this->category && $this->category->icon) {
            return $this->category->icon;
        }

        return match($this->type) {
            self::TYPE_INCOME => '💰',
            self::TYPE_EXPENSE => '💸',
            self::TYPE_TRANSFER => '🔄',
            default => '💳'
        };
    }

    /**
     * Get transaction color based on type.
     */
    public function getColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_INCOME => '#10b981',
            self::TYPE_EXPENSE => '#ef4444',
            self::TYPE_TRANSFER => '#3b82f6',
            default => '#6b7280'
        };
    }

    /**
     * Check if transaction is income.
     */
    public function isIncome(): bool
    {
        return $this->type === self::TYPE_INCOME;
    }

    /**
     * Check if transaction is expense.
     */
    public function isExpense(): bool
    {
        return $this->type === self::TYPE_EXPENSE;
    }

    /**
     * Check if transaction is transfer.
     */
    public function isTransfer(): bool
    {
        return $this->type === self::TYPE_TRANSFER;
    }

    /**
     * Check if transaction is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if transaction is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Add tag to transaction.
     */
    public function addTag(string $tag): void
    {
        $tags = $this->tags ?? [];
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->update(['tags' => $tags]);
        }
    }

    /**
     * Remove tag from transaction.
     */
    public function removeTag(string $tag): void
    {
        $tags = $this->tags ?? [];
        $tags = array_diff($tags, [$tag]);
        $this->update(['tags' => array_values($tags)]);
    }

    /**
     * Check if transaction has specific tag.
     */
    public function hasTag(string $tag): bool
    {
        return in_array($tag, $this->tags ?? []);
    }

    /**
     * Get human readable date.
     */
    public function getHumanDateAttribute(): string
    {
        return $this->transaction_date->diffForHumans();
    }

    /**
     * Get short description.
     */
    public function getShortDescriptionAttribute(): string
    {
        return strlen($this->description) > 50 
            ? substr($this->description, 0, 47) . '...' 
            : $this->description;
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (!$transaction->transaction_date) {
                $transaction->transaction_date = now();
            }
            
            if (!$transaction->currency) {
                $transaction->currency = $transaction->user->getPreferredCurrency();
            }
            
            if (!$transaction->status) {
                $transaction->status = self::STATUS_COMPLETED;
            }
        });
    }
}