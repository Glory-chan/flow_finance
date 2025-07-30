<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'bank_name',
        'account_name',
        'account_type',
        'account_number',
        'routing_number',
        'iban',
        'bic',
        'balance',
        'currency',
        'is_active',
        'is_primary',
        'last_synced_at',
        'sync_status',
        'sync_error',
        'external_id',
        'connection_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'account_number',
        'routing_number',
        'iban',
        'external_id',
        'connection_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
        'is_primary' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Account types.
     */
    const TYPE_CHECKING = 'checking';
    const TYPE_SAVINGS = 'savings';
    const TYPE_CREDIT = 'credit';
    const TYPE_INVESTMENT = 'investment';
    const TYPE_LOAN = 'loan';

    /**
     * Sync statuses.
     */
    const SYNC_SUCCESS = 'success';
    const SYNC_PENDING = 'pending';
    const SYNC_ERROR = 'error';
    const SYNC_DISCONNECTED = 'disconnected';

    /**
     * Get the user that owns the bank account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transactions for this bank account.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the cards for this bank account.
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    /**
     * Scope for active accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for primary account.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope for specific account type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('account_type', $type);
    }

    /**
     * Scope for successfully synced accounts.
     */
    public function scopeSynced($query)
    {
        return $query->where('sync_status', self::SYNC_SUCCESS);
    }

    /**
     * Scope for accounts with sync errors.
     */
    public function scopeWithSyncErrors($query)
    {
        return $query->where('sync_status', self::SYNC_ERROR);
    }

    /**
     * Get masked account number for display.
     */
    public function getMaskedAccountNumberAttribute(): string
    {
        if (!$this->account_number) {
            return '****';
        }
        
        $decrypted = decrypt($this->account_number);
        return '****' . substr($decrypted, -4);
    }

    /**
     * Get account display name.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->account_name) {
            return $this->account_name;
        }
        
        return $this->bank_name . ' - ' . ucfirst($this->account_type);
    }

    /**
     * Get formatted balance with currency.
     */
    public function getFormattedBalanceAttribute(): string
    {
        $symbol = match($this->currency) {
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            default => $this->currency
        };
        
        return $symbol . number_format($this->balance, 2);
    }

    /**
     * Get account type icon.
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->account_type) {
            self::TYPE_CHECKING => '🏦',
            self::TYPE_SAVINGS => '💰',
            self::TYPE_CREDIT => '💳',
            self::TYPE_INVESTMENT => '📈',
            self::TYPE_LOAN => '🏠',
            default => '🏦'
        };
    }

    /**
     * Get sync status color.
     */
    public function getSyncStatusColorAttribute(): string
    {
        return match($this->sync_status) {
            self::SYNC_SUCCESS => '#10b981',
            self::SYNC_PENDING => '#f59e0b',
            self::SYNC_ERROR => '#ef4444',
            self::SYNC_DISCONNECTED => '#6b7280',
            default => '#6b7280'
        };
    }

    /**
     * Check if account is active.
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Check if account is primary.
     */
    public function isPrimary(): bool
    {
        return $this->is_primary === true;
    }

    /**
     * Check if account sync is working.
     */
    public function isSyncWorking(): bool
    {
        return $this->sync_status === self::SYNC_SUCCESS;
    }

    /**
     * Check if account needs reconnection.
     */
    public function needsReconnection(): bool
    {
        return in_array($this->sync_status, [self::SYNC_ERROR, self::SYNC_DISCONNECTED]);
    }

    /**
     * Set as primary account.
     */
    public function setPrimary(): void
    {
        // Remove primary flag from other accounts
        $this->user->bankAccounts()->update(['is_primary' => false]);
        
        // Set this account as primary
        $this->update(['is_primary' => true]);
    }

    /**
     * Update sync status.
     */
    public function updateSyncStatus(string $status, string $error = null): void
    {
        $this->update([
            'sync_status' => $status,
            'sync_error' => $error,
            'last_synced_at' => $status === self::SYNC_SUCCESS ? now() : $this->last_synced_at,
        ]);
    }

    /**
     * Get monthly income for this account.
     */
    public function getMonthlyIncome(): float
    {
        return $this->transactions()
            ->income()
            ->currentMonth()
            ->sum('amount');
    }

    /**
     * Get monthly expenses for this account.
     */
    public function getMonthlyExpenses(): float
    {
        return $this->transactions()
            ->expense()
            ->currentMonth()
            ->sum('amount');
    }

    /**
     * Get total transactions count.
     */
    public function getTransactionsCountAttribute(): int
    {
        return $this->transactions()->count();
    }

    /**
     * Get recent transactions.
     */
    public function getRecentTransactions(int $limit = 10)
    {
        return $this->transactions()
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get last sync time in human format.
     */
    public function getLastSyncHumanAttribute(): string
    {
        if (!$this->last_synced_at) {
            return 'Jamais synchronisé';
        }
        
        return $this->last_synced_at->diffForHumans();
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($account) {
            // Encrypt sensitive data
            if ($account->account_number) {
                $account->account_number = encrypt($account->account_number);
            }
            
            if ($account->routing_number) {
                $account->routing_number = encrypt($account->routing_number);
            }
            
            // Set as primary if it's the first account
            if (!$account->user->bankAccounts()->count()) {
                $account->is_primary = true;
            }
            
            // Set default currency
            if (!$account->currency) {
                $account->currency = $account->user->getPreferredCurrency();
            }
        });

        static::updating(function ($account) {
            // Encrypt sensitive data if changed
            if ($account->isDirty('account_number') && $account->account_number) {
                $account->account_number = encrypt($account->account_number);
            }
            
            if ($account->isDirty('routing_number') && $account->routing_number) {
                $account->routing_number = encrypt($account->routing_number);
            }
        });

        static::deleting(function ($account) {
            // If deleting primary account, set another account as primary
            if ($account->is_primary) {
                $nextAccount = $account->user->bankAccounts()
                    ->where('id', '!=', $account->id)
                    ->active()
                    ->first();
                    
                if ($nextAccount) {
                    $nextAccount->setPrimary();
                }
            }
        });
    }

    /**
     * Get account type display name.
     */
    public static function getTypeDisplayName(string $type): string
    {
        return match($type) {
            self::TYPE_CHECKING => 'Compte courant',
            self::TYPE_SAVINGS => 'Livret d\'épargne',
            self::TYPE_CREDIT => 'Compte crédit',
            self::TYPE_INVESTMENT => 'Compte investissement',
            self::TYPE_LOAN => 'Compte prêt',
            default => ucfirst($type)
        };
    }

    /**
     * Get all available account types.
     */
    public static function getAccountTypes(): array
    {
        return [
            self::TYPE_CHECKING => self::getTypeDisplayName(self::TYPE_CHECKING),
            self::TYPE_SAVINGS => self::getTypeDisplayName(self::TYPE_SAVINGS),
            self::TYPE_CREDIT => self::getTypeDisplayName(self::TYPE_CREDIT),
            self::TYPE_INVESTMENT => self::getTypeDisplayName(self::TYPE_INVESTMENT),
            self::TYPE_LOAN => self::getTypeDisplayName(self::TYPE_LOAN),
        ];
    }

    /**
     * Validate IBAN format.
     */
    public static function validateIban(string $iban): bool
    {
        // Remove spaces and convert to uppercase
        $iban = strtoupper(str_replace(' ', '', $iban));
        
        // Check length (between 15 and 34 characters)
        if (strlen($iban) < 15 || strlen($iban) > 34) {
            return false;
        }
        
        // Check if it starts with 2 letters followed by 2 digits
        if (!preg_match('/^[A-Z]{2}[0-9]{2}/', $iban)) {
            return false;
        }
        
        // Move first 4 characters to the end
        $rearranged = substr($iban, 4) . substr($iban, 0, 4);
        
        // Replace letters with numbers (A=10, B=11, ..., Z=35)
        $numeric = '';
        for ($i = 0; $i < strlen($rearranged); $i++) {
            $char = $rearranged[$i];
            if (ctype_alpha($char)) {
                $numeric .= ord($char) - ord('A') + 10;
            } else {
                $numeric .= $char;
            }
        }
        
        // Check if the result mod 97 equals 1
        return bcmod($numeric, '97') === '1';
    }
}