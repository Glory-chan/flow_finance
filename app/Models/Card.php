<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'bank_account_id',
        'card_number',
        'card_number_hash',
        'card_holder',
        'expiry_date',
        'card_type',
        'brand',
        'alias',
        'color',
        'is_active',
        'is_primary',
        'credit_limit',
        'current_balance',
        'available_credit',
        'last_four',
        'blocked_at',
        'expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'card_number',
        'card_number_hash',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_primary' => 'boolean',
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'available_credit' => 'decimal:2',
        'blocked_at' => 'datetime',
        'expires_at' => 'datetime',
        'expiry_date' => 'date',
    ];

    /**
     * Card types.
     */
    const TYPE_DEBIT = 'debit';
    const TYPE_CREDIT = 'credit';
    const TYPE_PREPAID = 'prepaid';

    /**
     * Card brands.
     */
    const BRAND_VISA = 'visa';
    const BRAND_MASTERCARD = 'mastercard';
    const BRAND_AMEX = 'amex';
    const BRAND_DISCOVER = 'discover';
    const BRAND_OTHER = 'other';

    /**
     * Get the user that owns the card.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bank account associated with the card.
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * Get the transactions for this card.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope for active cards.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for blocked cards.
     */
    public function scopeBlocked($query)
    {
        return $query->whereNotNull('blocked_at');
    }

    /**
     * Scope for expired cards.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    /**
     * Scope for expiring soon (next 30 days).
     */
    public function scopeExpiringSoon($query)
    {
        return $query->whereBetween('expires_at', [now(), now()->addDays(30)]);
    }

    /**
     * Scope for primary card.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope for specific brand.
     */
    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    /**
     * Scope for specific type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('card_type', $type);
    }

    /**
     * Get masked card number for display.
     */
    public function getMaskedNumberAttribute(): string
    {
        return $this->card_number ?: '**** **** **** ' . $this->last_four;
    }

    /**
     * Get card display name.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->alias) {
            return $this->alias;
        }
        
        return ucfirst($this->brand) . ' •••• ' . $this->last_four;
    }

    /**
     * Get card brand icon.
     */
    public function getBrandIconAttribute(): string
    {
        return match($this->brand) {
            self::BRAND_VISA => '💳',
            self::BRAND_MASTERCARD => '💳',
            self::BRAND_AMEX => '💎',
            self::BRAND_DISCOVER => '🔍',
            default => '💳'
        };
    }

    /**
     * Get card brand color.
     */
    public function getBrandColorAttribute(): string
    {
        if ($this->color) {
            return $this->color;
        }
        
        return match($this->brand) {
            self::BRAND_VISA => '#1a1f71',
            self::BRAND_MASTERCARD => '#eb001b',
            self::BRAND_AMEX => '#006fcf',
            self::BRAND_DISCOVER => '#ff6000',
            default => '#6b7280'
        };
    }

    /**
     * Get card status.
     */
    public function getStatusAttribute(): string
    {
        if ($this->blocked_at) {
            return 'blocked';
        }
        
        if ($this->expires_at && $this->expires_at < now()) {
            return 'expired';
        }
        
        if (!$this->is_active) {
            return 'inactive';
        }
        
        return 'active';
    }

    /**
     * Get card status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => '#10b981',
            'blocked' => '#ef4444',
            'expired' => '#f59e0b',
            'inactive' => '#6b7280',
            default => '#6b7280'
        };
    }

    /**
     * Check if card is active.
     */
    public function isActive(): bool
    {
        return $this->is_active && !$this->blocked_at && $this->expires_at > now();
    }

    /**
     * Check if card is blocked.
     */
    public function isBlocked(): bool
    {
        return $this->blocked_at !== null;
    }

    /**
     * Check if card is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at < now();
    }

    /**
     * Check if card is expiring soon.
     */
    public function isExpiringSoon(): bool
    {
        return $this->expires_at && $this->expires_at <= now()->addDays(30);
    }

    /**
     * Check if card is primary.
     */
    public function isPrimary(): bool
    {
        return $this->is_primary === true;
    }

    /**
     * Block the card.
     */
    public function block(): void
    {
        $this->update([
            'is_active' => false,
            'blocked_at' => now(),
        ]);
    }

    /**
     * Unblock the card.
     */
    public function unblock(): void
    {
        $this->update([
            'is_active' => true,
            'blocked_at' => null,
        ]);
    }

    /**
     * Set as primary card.
     */
    public function setPrimary(): void
    {
        // Remove primary flag from other cards
        $this->user->cards()->update(['is_primary' => false]);
        
        // Set this card as primary
        $this->update(['is_primary' => true]);
    }

    /**
     * Get monthly spending for this card.
     */
    public function getMonthlySpending(): float
    {
        return $this->transactions()
            ->expense()
            ->currentMonth()
            ->sum('amount');
    }

    /**
     * Get yearly spending for this card.
     */
    public function getYearlySpending(): float
    {
        return $this->transactions()
            ->expense()
            ->currentYear()
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
     * Get available credit amount.
     */
    public function getAvailableCreditAttribute(): float
    {
        if ($this->card_type !== self::TYPE_CREDIT || !$this->credit_limit) {
            return 0;
        }
        
        return max(0, $this->credit_limit - ($this->current_balance ?? 0));
    }

    /**
     * Get credit utilization percentage.
     */
    public function getCreditUtilizationAttribute(): float
    {
        if ($this->card_type !== self::TYPE_CREDIT || !$this->credit_limit) {
            return 0;
        }
        
        return round((($this->current_balance ?? 0) / $this->credit_limit) * 100, 2);
    }

    /**
     * Get formatted expiry date.
     */
    public function getFormattedExpiryAttribute(): string
    {
        return $this->expiry_date ? $this->expiry_date->format('m/y') : '';
    }

    /**
     * Get days until expiry.
     */
    public function getDaysUntilExpiryAttribute(): int
    {
        if (!$this->expires_at) {
            return 0;
        }
        
        return max(0, now()->diffInDays($this->expires_at, false));
    }

    /**
     * Get card gradient for UI.
     */
    public function getGradientAttribute(): string
    {
        $color = $this->brand_color;
        
        return match($this->brand) {
            self::BRAND_VISA => "linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)",
            self::BRAND_MASTERCARD => "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)",
            self::BRAND_AMEX => "linear-gradient(135deg, #4b6cb7 0%, #182848 100%)",
            default => "linear-gradient(135deg, {$color} 0%, " . $this->darkenColor($color, 20) . " 100%)"
        };
    }

    /**
     * Darken a hex color.
     */
    private function darkenColor(string $color, int $percent): string
    {
        $color = ltrim($color, '#');
        $rgb = array_map('hexdec', str_split($color, 2));
        
        foreach ($rgb as &$component) {
            $component = max(0, $component - ($component * $percent / 100));
        }
        
        return '#' . implode('', array_map(fn($c) => str_pad(dechex($c), 2, '0', STR_PAD_LEFT), $rgb));
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($card) {
            // Generate hash for card number
            if ($card->card_number) {
                $card->card_number_hash = hash('sha256', $card->card_number);
                $card->last_four = substr($card->card_number, -4);
            }
            
            // Set expires_at from expiry_date
            if ($card->expiry_date) {
                $card->expires_at = $card->expiry_date->endOfMonth();
            }
            
            // Set as primary if it's the first card
            if (!$card->user->cards()->count()) {
                $card->is_primary = true;
            }
        });

        static::updating(function ($card) {
            // Update expires_at if expiry_date changed
            if ($card->isDirty('expiry_date') && $card->expiry_date) {
                $card->expires_at = $card->expiry_date->endOfMonth();
            }
        });

        static::deleting(function ($card) {
            // If deleting primary card, set another card as primary
            if ($card->is_primary) {
                $nextCard = $card->user->cards()
                    ->where('id', '!=', $card->id)
                    ->active()
                    ->first();
                    
                if ($nextCard) {
                    $nextCard->setPrimary();
                }
            }
        });
    }

    /**
     * Determine card brand from number.
     */
    public static function determineBrand(string $cardNumber): string
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        
        if (preg_match('/^4/', $cardNumber)) {
            return self::BRAND_VISA;
        }
        
        if (preg_match('/^5[1-5]/', $cardNumber) || preg_match('/^2[2-7]/', $cardNumber)) {
            return self::BRAND_MASTERCARD;
        }
        
        if (preg_match('/^3[47]/', $cardNumber)) {
            return self::BRAND_AMEX;
        }
        
        if (preg_match('/^6(?:011|5)/', $cardNumber)) {
            return self::BRAND_DISCOVER;
        }
        
        return self::BRAND_OTHER;
    }

    /**
     * Validate card number using Luhn algorithm.
     */
    public static function validateCardNumber(string $cardNumber): bool
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        $sum = 0;
        $alternate = false;

        for ($i = strlen($cardNumber) - 1; $i >= 0; $i--) {
            $digit = (int) $cardNumber[$i];

            if ($alternate) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit = ($digit % 10) + 1;
                }
            }

            $sum += $digit;
            $alternate = !$alternate;
        }

        return ($sum % 10) === 0;
    }
}