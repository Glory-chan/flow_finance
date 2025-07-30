<?php
// app/Models/SavingsGoal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'target_amount',
        'current_amount',
        'target_date',
        'icon',
        'color',
        'is_active',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'target_date' => 'date',
        'is_active' => 'boolean',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeInProgress($query)
    {
        return $query->where('is_active', true)
                    ->where('is_completed', false);
    }

    // Méthodes utilitaires
    public function addAmount($amount)
    {
        $newAmount = $this->current_amount + $amount;
        $this->update(['current_amount' => $newAmount]);

        // Vérifier si l'objectif est atteint
        if ($newAmount >= $this->target_amount && !$this->is_completed) {
            $this->markAsCompleted();
        }

        return $newAmount;
    }

    public function getRemainingAmount()
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    public function getProgressPercentage()
    {
        if ($this->target_amount == 0) return 0;
        return min(100, ($this->current_amount / $this->target_amount) * 100);
    }

    public function getDaysRemaining()
    {
        if (!$this->target_date) return null;
        return now()->diffInDays($this->target_date, false);
    }

    public function markAsCompleted()
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    public function isOverdue()
    {
        if (!$this->target_date) return false;
        return now()->isAfter($this->target_date) && !$this->is_completed;
    }

    public function getStatusColor()
    {
        if ($this->is_completed) return 'success';
        if ($this->isOverdue()) return 'danger';
        
        $percentage = $this->getProgressPercentage();
        if ($percentage >= 75) return 'success';
        if ($percentage >= 50) return 'warning';
        return 'primary';
    }
}