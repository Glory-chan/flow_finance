<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'target_amount',
        'current_amount',
        'target_date',
        'status',
        'priority',
        'icon',
        'color',
        'auto_save_enabled',
        'auto_save_amount',
        'auto_save_frequency',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'auto_save_amount' => 'decimal:2',
        'target_date' => 'date',
        'auto_save_enabled' => 'boolean',
    ];

    /**
     * Goal statuses.
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_PAUSED = 'paused';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Goal priorities.
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Auto-save frequencies.
     */
    const FREQUENCY_DAILY = 'daily';
    const FREQUENCY_WEEKLY = 'weekly';
    const FREQUENCY_MONTHLY = 'monthly';

    /**
     * Get the user that owns the goal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the contributions for this goal.
     */
    public function contributions(): HasMany
    {
        return $this->hasMany(GoalContribution::class);
    }

    /**
     * Scope for active goals.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for completed goals.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for paused goals.
     */
    public function scopePaused($query)
    {
        return $query->where('status', self::STATUS_PAUSED);
    }

    /**
     * Scope for goals by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for goals with auto-save enabled.
     */
    public function scopeWithAutoSave($query)
    {
        return $query->where('auto_save_enabled', true);
    }

    /**
     * Scope for overdue goals.
     */
    public function scopeOverdue($query)
    {
        return $query->where('target_date', '<', now())
                    ->where('status', '!=', self::STATUS_COMPLETED);
    }

    /**
     * Get progress percentage.
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }
        
        return round(($this->current_amount / $this->target_amount) * 100, 2);
    }

    /**
     * Get remaining amount.
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    /**
     * Get days remaining.
     */
    public function getDaysRemainingAttribute(): int
    {
        if (!$this->target_date) {
            return 0;
        }
        
        return max(0, now()->diffInDays($this->target_date, false));
    }

    /**
     * Get months remaining.
     */
    public function getMonthsRemainingAttribute(): int
    {
        if (!$this->target_date) {
            return 0;
        }
        
        return max(0, now()->diffInMonths($this->target_date, false));
    }

    /**
     * Get required monthly saving.
     */
    public function getRequiredMonthlySavingAttribute(): float
    {
        if ($this->months_remaining <= 0) {
            return $this->remaining_amount;
        }
        
        return $this->remaining_amount / $this->months_remaining;
    }

    /**
     * Get required daily saving.
     */
    public function getRequiredDailySavingAttribute(): float
    {
        if ($this->days_remaining <= 0) {
            return $this->remaining_amount;
        }
        
        return $this->remaining_amount / $this->days_remaining;
    }

    /**
     * Check if goal is completed.
     */
    public function isCompleted(): bool
    {
        return $this->current_amount >= $this->target_amount || $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if goal is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->target_date && $this->target_date < now() && !$this->isCompleted();
    }

    /**
     * Check if goal is on track.
     */
    public function isOnTrack(): bool
    {
        if (!$this->target_date || $this->isCompleted()) {
            return true;
        }
        
        $totalDays = now()->diffInDays($this->target_date, false);
        $elapsedDays = $this->created_at->diffInDays(now());
        
        if ($totalDays <= 0) {
            return $this->isCompleted();
        }
        
        $expectedProgress = ($elapsedDays / ($elapsedDays + $totalDays)) * 100;
        
        return $this->progress_percentage >= $expectedProgress;
    }

    /**
     * Get goal status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => $this->isOnTrack() ? '#10b981' : '#f59e0b',
            self::STATUS_COMPLETED => '#10b981',
            self::STATUS_PAUSED => '#6b7280',
            self::STATUS_CANCELLED => '#ef4444',
            default => '#6b7280'
        };
    }

    /**
     * Get priority color.
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => '#10b981',
            self::PRIORITY_MEDIUM => '#f59e0b',
            self::PRIORITY_HIGH => '#f97316',
            self::PRIORITY_URGENT => '#ef4444',
            default => '#6b7280'
        };
    }

    /**
     * Get display icon with fallback.
     */
    public function getDisplayIconAttribute(): string
    {
        return $this->icon ?: '🎯';
    }

    /**
     * Get display color with fallback.
     */
    public function getDisplayColorAttribute(): string
    {
        return $this->color ?: '#10b981';
    }

    /**
     * Get formatted target amount.
     */
    public function getFormattedTargetAmountAttribute(): string
    {
        $currency = $this->user->getPreferredCurrency();
        $symbol = match($currency) {
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            default => $currency
        };
        
        return $symbol . number_format($this->target_amount, 2);
    }

    /**
     * Get formatted current amount.
     */
    public function getFormattedCurrentAmountAttribute(): string
    {
        $currency = $this->user->getPreferredCurrency();
        $symbol = match($currency) {
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            default => $currency
        };
        
        return $symbol . number_format($this->current_amount, 2);
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
     * Add contribution to goal.
     */
    public function contribute(float $amount, string $description = null): GoalContribution
    {
        $contribution = $this->contributions()->create([
            'user_id' => $this->user_id,
            'amount' => $amount,
            'description' => $description,
            'contributed_at' => now(),
        ]);

        $this->increment('current_amount', $amount);

        // Check if goal is completed
        if ($this->current_amount >= $this->target_amount && $this->status !== self::STATUS_COMPLETED) {
            $this->complete();
        }

        return $contribution;
    }

    /**
     * Withdraw from goal.
     */
    public function withdraw(float $amount, string $description = null): GoalContribution
    {
        $amount = min($amount, $this->current_amount); // Can't withdraw more than current amount

        $contribution = $this->contributions()->create([
            'user_id' => $this->user_id,
            'amount' => -$amount,
            'description' => $description ?: 'Retrait',
            'contributed_at' => now(),
        ]);

        $this->decrement('current_amount', $amount);

        // If goal was completed, change status back to active
        if ($this->status === self::STATUS_COMPLETED && $this->current_amount < $this->target_amount) {
            $this->update(['status' => self::STATUS_ACTIVE]);
        }

        return $contribution;
    }

    /**
     * Complete the goal.
     */
    public function complete(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'current_amount' => $this->target_amount,
        ]);

        // Disable auto-save when completed
        if ($this->auto_save_enabled) {
            $this->update(['auto_save_enabled' => false]);
        }
    }

    /**
     * Pause the goal.
     */
    public function pause(): void
    {
        $this->update(['status' => self::STATUS_PAUSED]);
    }

    /**
     * Resume the goal.
     */
    public function resume(): void
    {
        $this->update(['status' => self::STATUS_ACTIVE]);
    }

    /**
     * Cancel the goal.
     */
    public function cancel(): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'auto_save_enabled' => false,
        ]);
    }

    /**
     * Setup auto-save for this goal.
     */
    public function setupAutoSave(float $amount, string $frequency): void
    {
        $this->update([
            'auto_save_enabled' => true,
            'auto_save_amount' => $amount,
            'auto_save_frequency' => $frequency,
        ]);
    }

    /**
     * Cancel auto-save for this goal.
     */
    public function cancelAutoSave(): void
    {
        $this->update([
            'auto_save_enabled' => false,
            'auto_save_amount' => null,
            'auto_save_frequency' => null,
        ]);
    }

    /**
     * Get total contributions amount.
     */
    public function getTotalContributionsAttribute(): float
    {
        return $this->contributions()->sum('amount');
    }

    /**
     * Get contributions count.
     */
    public function getContributionsCountAttribute(): int
    {
        return $this->contributions()->count();
    }

    /**
     * Get average contribution amount.
     */
    public function getAverageContributionAttribute(): float
    {
        $count = $this->contributions_count;
        return $count > 0 ? $this->total_contributions / $count : 0;
    }

    /**
     * Get last contribution date.
     */
    public function getLastContributionDateAttribute(): ?\Carbon\Carbon
    {
        return $this->contributions()->latest('contributed_at')->value('contributed_at');
    }

    /**
     * Get estimated completion date based on current progress.
     */
    public function getEstimatedCompletionDateAttribute(): ?\Carbon\Carbon
    {
        if ($this->isCompleted() || $this->contributions_count < 2) {
            return null;
        }

        // Calculate average monthly contribution over last 3 months
        $recentContributions = $this->contributions()
            ->where('contributed_at', '>=', now()->subMonths(3))
            ->where('amount', '>', 0)
            ->get();

        if ($recentContributions->isEmpty()) {
            return null;
        }

        $monthlyAverage = $recentContributions->sum('amount') / 3;

        if ($monthlyAverage <= 0) {
            return null;
        }

        $monthsNeeded = $this->remaining_amount / $monthlyAverage;

        return now()->addMonths(ceil($monthsNeeded));
    }

    /**
     * Get progress status text.
     */
    public function getProgressStatusAttribute(): string
    {
        if ($this->isCompleted()) {
            return 'Objectif atteint !';
        }

        if ($this->isOverdue()) {
            return 'En retard';
        }

        if ($this->isOnTrack()) {
            return 'Sur la bonne voie';
        }

        return 'En retard sur l\'objectif';
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($goal) {
            if (!$goal->status) {
                $goal->status = self::STATUS_ACTIVE;
            }
            
            if (!$goal->priority) {
                $goal->priority = self::PRIORITY_MEDIUM;
            }
            
            if (!$goal->current_amount) {
                $goal->current_amount = 0;
            }
        });

        static::updated(function ($goal) {
            // Auto-complete goal if target is reached
            if ($goal->current_amount >= $goal->target_amount && $goal->status !== self::STATUS_COMPLETED) {
                $goal->complete();
            }
        });
    }

    /**
     * Get default goal templates.
     */
    public static function getDefaultTemplates(): array
    {
        return [
            [
                'name' => 'Fonds d\'urgence',
                'description' => 'Constituer un fonds d\'urgence pour 3-6 mois de dépenses',
                'target_amount' => 5000,
                'icon' => '🛡️',
                'color' => '#ef4444',
                'priority' => self::PRIORITY_HIGH,
            ],
            [
                'name' => 'Vacances',
                'description' => 'Économiser pour les prochaines vacances',
                'target_amount' => 2000,
                'icon' => '✈️',
                'color' => '#3b82f6',
                'priority' => self::PRIORITY_MEDIUM,
            ],
            [
                'name' => 'Apport immobilier',
                'description' => 'Constituer un apport pour l\'achat d\'un bien immobilier',
                'target_amount' => 50000,
                'icon' => '🏠',
                'color' => '#10b981',
                'priority' => self::PRIORITY_HIGH,
            ],
            [
                'name' => 'Nouvelle voiture',
                'description' => 'Économiser pour l\'achat d\'une nouvelle voiture',
                'target_amount' => 15000,
                'icon' => '🚗',
                'color' => '#f59e0b',
                'priority' => self::PRIORITY_MEDIUM,
            ],
            [
                'name' => 'Retraite',
                'description' => 'Constituer un complément retraite',
                'target_amount' => 100000,
                'icon' => '🏖️',
                'color' => '#8b5cf6',
                'priority' => self::PRIORITY_LOW,
            ],
        ];
    }
}

/**
 * Goal Contribution Model
 */
class GoalContribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'goal_id',
        'user_id',
        'amount',
        'description',
        'contributed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'contributed_at' => 'datetime',
    ];

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        $currency = $this->user->getPreferredCurrency();
        $symbol = match($currency) {
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            default => $currency
        };
        
        $prefix = $this->amount >= 0 ? '+' : '';
        return $prefix . $symbol . number_format($this->amount, 2);
    }

    public function isWithdrawal(): bool
    {
        return $this->amount < 0;
    }

    public function isContribution(): bool
    {
        return $this->amount > 0;
    }
}