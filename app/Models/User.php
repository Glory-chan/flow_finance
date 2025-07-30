<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'address',
        'city',
        'postal_code',
        'country',
        'avatar',
        'timezone',
        'currency',
        'language',
        'preferences',
        'notification_preferences',
        'two_factor_enabled',
        'two_factor_enabled_at',
        'last_activity_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'preferences' => 'array',
        'notification_preferences' => 'array',
        'two_factor_enabled' => 'boolean',
        'two_factor_enabled_at' => 'datetime',
        'last_activity_at' => 'datetime',
    ];

    /**
     * Get the user's transactions.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the user's cards.
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    /**
     * Get the user's budgets.
     */
    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    /**
     * Get the user's savings goals.
     */
    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    /**
     * Get the user's bank accounts.
     */
    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }

    /**
     * Get the user's categories.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the user's notifications.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the user's reports.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get recent transactions for the user.
     */
    public function recentTransactions(int $limit = 10)
    {
        return $this->transactions()
            ->with(['category', 'card'])
            ->orderBy('created_at', 'desc')
            ->limit($limit);
    }

    /**
     * Get total balance across all accounts.
     */
    public function getTotalBalanceAttribute(): float
    {
        // Dans un vrai système, cela viendrait des comptes bancaires
        return $this->bankAccounts()->sum('balance') ?? 0;
    }

    /**
     * Get monthly income for current month.
     */
    public function getMonthlyIncomeAttribute(): float
    {
        return $this->transactions()
            ->where('type', 'income')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    /**
     * Get monthly expenses for current month.
     */
    public function getMonthlyExpensesAttribute(): float
    {
        return $this->transactions()
            ->where('type', 'expense')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    /**
     * Get monthly savings for current month.
     */
    public function getMonthlySavingsAttribute(): float
    {
        return $this->monthly_income - $this->monthly_expenses;
    }

    /**
     * Get active savings goals.
     */
    public function activeGoals()
    {
        return $this->goals()->where('status', 'active');
    }

    /**
     * Get current month budgets.
     */
    public function currentBudgets()
    {
        return $this->budgets()
            ->whereMonth('period_start', now()->month)
            ->whereYear('period_start', now()->year)
            ->with('category');
    }

    /**
     * Check if user has premium subscription.
     */
    public function isPremium(): bool
    {
        // Logique pour vérifier l'abonnement premium
        return $this->subscription_type === 'premium';
    }

    /**
     * Get user's preferred currency.
     */
    public function getPreferredCurrency(): string
    {
        return $this->currency ?? 'EUR';
    }

    /**
     * Get user's preferred timezone.
     */
    public function getPreferredTimezone(): string
    {
        return $this->timezone ?? 'Europe/Paris';
    }

    /**
     * Check if two-factor authentication is enabled.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_enabled === true;
    }

    /**
     * Get user's full address.
     */
    public function getFullAddressAttribute(): string
    {
        return trim(implode(', ', array_filter([
            $this->address,
            $this->city,
            $this->postal_code,
            $this->country
        ])));
    }

    /**
     * Get user's initials for avatar.
     */
    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        
        return substr($initials, 0, 2);
    }

    /**
     * Scope for active users.
     */
    public function scopeActive($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope for premium users.
     */
    public function scopePremium($query)
    {
        return $query->where('subscription_type', 'premium');
    }
}