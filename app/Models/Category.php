<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'parent_id',
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'type',
        'is_system',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Category types.
     */
    const TYPE_EXPENSE = 'expense';
    const TYPE_INCOME = 'income';
    const TYPE_BOTH = 'both';

    /**
     * Get the user that owns the category.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get all descendants recursively.
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get the transactions for this category.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the budgets for this category.
     */
    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    /**
     * Scope for active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for system categories.
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Scope for user categories.
     */
    public function scopeUser($query)
    {
        return $query->where('is_system', false);
    }

    /**
     * Scope for parent categories.
     */
    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for child categories.
     */
    public function scopeChild($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Scope for expense categories.
     */
    public function scopeExpense($query)
    {
        return $query->whereIn('type', [self::TYPE_EXPENSE, self::TYPE_BOTH]);
    }

    /**
     * Scope for income categories.
     */
    public function scopeIncome($query)
    {
        return $query->whereIn('type', [self::TYPE_INCOME, self::TYPE_BOTH]);
    }

    /**
     * Scope for specific type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get full category path.
     */
    public function getFullNameAttribute(): string
    {
        if ($this->parent) {
            return $this->parent->full_name . ' > ' . $this->name;
        }
        
        return $this->name;
    }

    /**
     * Get category depth level.
     */
    public function getDepthAttribute(): int
    {
        $depth = 0;
        $parent = $this->parent;
        
        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }
        
        return $depth;
    }

    /**
     * Check if category is parent.
     */
    public function isParent(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Check if category is child.
     */
    public function isChild(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * Check if category has children.
     */
    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * Check if category is system category.
     */
    public function isSystem(): bool
    {
        return $this->is_system === true;
    }

    /**
     * Check if category can be deleted.
     */
    public function canBeDeleted(): bool
    {
        // System categories cannot be deleted
        if ($this->is_system) {
            return false;
        }
        
        // Categories with transactions cannot be deleted
        if ($this->transactions()->count() > 0) {
            return false;
        }
        
        // Categories with budgets cannot be deleted
        if ($this->budgets()->count() > 0) {
            return false;
        }
        
        return true;
    }

    /**
     * Get total spending for this category in current month.
     */
    public function getCurrentMonthSpending(): float
    {
        return $this->transactions()
            ->expense()
            ->currentMonth()
            ->sum('amount');
    }

    /**
     * Get total income for this category in current month.
     */
    public function getCurrentMonthIncome(): float
    {
        return $this->transactions()
            ->income()
            ->currentMonth()
            ->sum('amount');
    }

    /**
     * Get transactions count for this category.
     */
    public function getTransactionsCountAttribute(): int
    {
        return $this->transactions()->count();
    }

    /**
     * Get category icon with fallback.
     */
    public function getDisplayIconAttribute(): string
    {
        return $this->icon ?: match($this->type) {
            self::TYPE_EXPENSE => '💸',
            self::TYPE_INCOME => '💰',
            default => '📊'
        };
    }

    /**
     * Get category color with fallback.
     */
    public function getDisplayColorAttribute(): string
    {
        return $this->color ?: match($this->type) {
            self::TYPE_EXPENSE => '#ef4444',
            self::TYPE_INCOME => '#10b981',
            default => '#6b7280'
        };
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (!$category->slug) {
                $category->slug = \Str::slug($category->name);
            }
            
            if (!$category->sort_order) {
                $maxOrder = static::where('user_id', $category->user_id)
                    ->where('parent_id', $category->parent_id)
                    ->max('sort_order');
                $category->sort_order = ($maxOrder ?? 0) + 1;
            }
        });

        static::deleting(function ($category) {
            // Move child categories to parent or make them root categories
            $category->children()->update(['parent_id' => $category->parent_id]);
            
            // Soft delete all transactions (they will be unassigned)
            $category->transactions()->update(['category_id' => null]);
        });
    }

    /**
     * Get default system categories.
     */
    public static function getSystemCategories(): array
    {
        return [
            // Income categories
            [
                'name' => 'Salaire',
                'icon' => '💼',
                'color' => '#10b981',
                'type' => self::TYPE_INCOME,
                'is_system' => true,
            ],
            [
                'name' => 'Freelance',
                'icon' => '💻',
                'color' => '#3b82f6',
                'type' => self::TYPE_INCOME,
                'is_system' => true,
            ],
            [
                'name' => 'Investissements',
                'icon' => '📈',
                'color' => '#8b5cf6',
                'type' => self::TYPE_INCOME,
                'is_system' => true,
            ],
            
            // Expense categories
            [
                'name' => 'Alimentation',
                'icon' => '🛒',
                'color' => '#f59e0b',
                'type' => self::TYPE_EXPENSE,
                'is_system' => true,
            ],
            [
                'name' => 'Transport',
                'icon' => '🚗',
                'color' => '#ef4444',
                'type' => self::TYPE_EXPENSE,
                'is_system' => true,
            ],
            [
                'name' => 'Logement',
                'icon' => '🏠',
                'color' => '#6366f1',
                'type' => self::TYPE_EXPENSE,
                'is_system' => true,
            ],
            [
                'name' => 'Divertissement',
                'icon' => '🎬',
                'color' => '#ec4899',
                'type' => self::TYPE_EXPENSE,
                'is_system' => true,
            ],
            [
                'name' => 'Santé',
                'icon' => '🏥',
                'color' => '#14b8a6',
                'type' => self::TYPE_EXPENSE,
                'is_system' => true,
            ],
            [
                'name' => 'Éducation',
                'icon' => '📚',
                'color' => '#f97316',
                'type' => self::TYPE_EXPENSE,
                'is_system' => true,
            ],
        ];
    }
}