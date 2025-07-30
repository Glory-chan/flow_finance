<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'amount',
        'period_type',
        'period_start',
        'period_end',
        'status',
        'alert_threshold',
        'alert_enabled',
        'rollover_enabled',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
        'alert_threshold' => 'integer',
        'alert_enabled' => 'boolean',
        'rollover_enabled' => 'boolean',
    ];

    /**
     * Period types.
     */
    const PERIOD_WEEKLY = 'weekly';
    const PERIOD_MONTHLY = 'monthly';
    const PERIOD_QUARTERLY = 'quarterly';
    const PERIOD_YEARLY = 'yearly';
    const PERIOD_CUSTOM = 'custom';

    /**
     * Budget statuses.
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_PAUSED = 'paused';
    const STATUS_COMPLETED = 'completed';
    const STATUS_EXCEEDED = 'exceeded';

    /**
     * Get the user that owns the budget.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the budget.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the transactions for this budget.
     */
    public function transactions(): BelongsToMany
    {
        return $this->belongsToMany(Transaction::class);
    }

    /**
     * Scope for active budgets.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for current period budgets.
     */
    public function scopeCurrentPeriod($query)
    {
        return $query->where('period_start', '<=', now())
                    ->where('period_end', '>=', now());
    }

    /**
     * Scope for specific category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope for specific period type.
     */
    public function scopeByPeriodType($query, $periodType)
    {
        return $query->where('period_type', $periodType);
    }

    /**
     * Get spent amount for this budget.
     */
    public function getSpentAmountAttribute(): float
    {
        return Transaction::where('user_id', $this->user_id)
            ->where('category_id', $this->category_id)
            ->where('type', Transaction::TYPE_EXPENSE)
            ->whereBetween('transaction_date', [$this->period_start, $this->period_end])
            ->sum('amount');
    }

    /**
     * Get remaining amount for this budget.
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->amount - $this->spent_amount);
    }

    /**
     * Get spent percentage for this budget.
     */
    public function getSpentPercentageAttribute(): float
    {
        if ($this->amount <= 0) {
            return 0;
        }
        
        return round(($this->spent_amount / $this->amount) * 100, 2);
    }

    /**
     * Get budget status based on spending.
     */
    public function getCurrentStatusAttribute(): string
    {
        if ($this->spent_percentage >= 100) {
            return self::STATUS_EXCEEDED;
        }
        
        if ($this->period_end < now()) {
            return self::STATUS_COMPLETED;
        }
        
        return $this->status;
    }

    /**
     * Check if budget is exceeded.
     */
    public function isExceeded(): bool
    {
        return $this->spent_amount > $this->amount;
    }

    /**
     * Check if budget alert should be triggered.
     */
    public function shouldAlert(): bool
    {
        if (!$this->alert_enabled || !$this->alert_threshold) {
            return false;
        }
        
        return $this->spent_percentage >= $this->alert_threshold;
    }

    /**
     * Check if budget is in current period.
     */
    public function isCurrentPeriod(): bool
    {
        $now = now();
        return $this->period_start <= $now && $this->period_end >= $now;
    }

    /**
     * Get budget progress color.
     */
    public function getProgressColorAttribute(): string
    {
        if ($this->spent_percentage >= 100) {
            return '#ef4444'; // Red - exceeded
        } elseif ($this->spent_percentage >= ($this->alert_threshold ?? 80)) {
            return '#f59e0b'; // Yellow - warning
        } else {
            return '#10b981'; // Green - on track
        }
    }

    /**
     * Get days remaining in budget period.
     */
    public function getDaysRemainingAttribute(): int
    {
        if ($this->period_end < now()) {
            return 0;
        }
        
        return now()->diffInDays($this->period_end, false);
    }

    /**
     * Get average daily spending for this budget.
     */
    public function getAverageDailySpendingAttribute(): float
    {
        $daysElapsed = max(1, $this->period_start->diffInDays(now()));
        return $this->spent_amount / $daysElapsed;
    }

    /**
     * Get projected spending at end of period.
     */
    public function getProjectedSpendingAttribute(): float
    {
        $totalDays = $this->period_start->diffInDays($this->period_end) + 1;
        return $this->average_daily_spending * $totalDays;
    }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmountAttribute(): string
    {
        $currency = $this->user->getPreferredCurrency();
        $symbol = match($currency) {
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            default => $currency
        };
        
        return $symbol . number_format($this->amount, 2);
    }

    /**
     * Get formatted spent amount.
     */
    public function getFormattedSpentAmountAttribute(): string
    {
        $currency = $this->user->getPreferredCurrency();
        $symbol = match($currency) {
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            default => $currency
        };
        
        return $symbol . number_format($this->spent_amount, 2);
    }

    /**
     * Get formatted remaining amount.
     */
    public function getFormattedRemainingAmountAttribute(): string
    {
        $currency = $this->user->getPreferredCurrency();
        $symbol = match($currency) {
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            default => $currency
        };
        
        return $symbol . number_format($this->remaining_amount, 2);
    }

    /**
     * Create next period budget.
     */
    public function createNextPeriod(): self
    {
        $nextStart = $this->getNextPeriodStart();
        $nextEnd = $this->getNextPeriodEnd($nextStart);
        
        return static::create([
            'user_id' => $this->user_id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->rollover_enabled ? $this->amount + $this->remaining_amount : $this->amount,
            'period_type' => $this->period_type,
            'period_start' => $nextStart,
            'period_end' => $nextEnd,
            'status' => self::STATUS_ACTIVE,
            'alert_threshold' => $this->alert_threshold,
            'alert_enabled' => $this->alert_enabled,
            'rollover_enabled' => $this->rollover_enabled,
        ]);
    }

    /**
     * Get next period start date.
     */
    private function getNextPeriodStart(): \Carbon\Carbon
    {
        return match($this->period_type) {
            self::PERIOD_WEEKLY => $this->period_end->addDay(),
            self::PERIOD_MONTHLY => $this->period_start->addMonth(),
            self::PERIOD_QUARTERLY => $this->period_start->addMonths(3),
            self::PERIOD_YEARLY => $this->period_start->addYear(),
            default => $this->period_end->addDay(),
        };
    }

    /**
     * Get next period end date.
     */
    private function getNextPeriodEnd(\Carbon\Carbon $startDate): \Carbon\Carbon
    {
        return match($this->period_type) {
            self::PERIOD_WEEKLY => $startDate->copy()->addWeek()->subDay(),
            self::PERIOD_MONTHLY => $startDate->copy()->endOfMonth(),
            self::PERIOD_QUARTERLY => $startDate->copy()->addMonths(3)->subDay(),
            self::PERIOD_YEARLY => $startDate->copy()->endOfYear(),
            default => $startDate->copy()->addMonth()->subDay(),
        };
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($budget) {
            if (!$budget->status) {
                $budget->status = self::STATUS_ACTIVE;
            }
            
            if (!$budget->alert_threshold) {
                $budget->alert_threshold = 80; // 80% by default
            }
            
            // Set period dates if not provided
            if (!$budget->period_start) {
                $budget->period_start = now()->startOfMonth();
            }
            
            if (!$budget->period_end && $budget->period_type === self::PERIOD_MONTHLY) {
                $budget->period_end = $budget->period_start->copy()->endOfMonth();
            }
        });

        static::updated(function ($budget) {
            // Update status based on spending
            if ($budget->isExceeded() && $budget->status !== self::STATUS_EXCEEDED) {
                $budget->update(['status' => self::STATUS_EXCEEDED]);
            }
        });
    }

    /**
     * Get default budget templates.
     */
    public static function getDefaultTemplates(): array
    {
        return [
            [
                'name' => 'Budget Alimentation',
                'period_type' => self::PERIOD_MONTHLY,
                'amount' => 600,
                'alert_threshold' => 80,
                'category_name' => 'Alimentation',
            ],
            [
                'name' => 'Budget Transport',
                'period_type' => self::PERIOD_MONTHLY,
                'amount' => 300,
                'alert_threshold' => 85,
                'category_name' => 'Transport',
            ],
            [
                'name' => 'Budget Divertissement',
                'period_type' => self::PERIOD_MONTHLY,
                'amount' => 200,
                'alert_threshold' => 75,
                'category_name' => 'Divertissement',
            ],
            [
                'name' => 'Budget Logement',
                'period_type' => self::PERIOD_MONTHLY,
                'amount' => 1200,
                'alert_threshold' => 90,
                'category_name' => 'Logement',
            ],
        ];
    }
}