<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'total_balance',
        'preferences',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'preferences' => 'array',
        'total_balance' => 'decimal:2',
    ];

    // Relations
    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function savingsGoals()
    {
        return $this->hasMany(SavingsGoal::class);
    }

    // Méthodes utilitaires
    public function getPrimaryBankAccount()
    {
        return $this->bankAccounts()->where('is_primary', true)->first();
    }

    public function updateTotalBalance()
    {
        $totalBalance = $this->bankAccounts()->sum('balance');
        $this->update(['total_balance' => $totalBalance]);
        return $totalBalance;
    }

    public function getMonthlyExpenses($month = null, $year = null)
    {
        $month = $month ?? date('m');
        $year = $year ?? date('Y');

        return $this->transactions()
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->sum('amount');
    }

    public function getMonthlyIncome($month = null, $year = null)
    {
        $month = $month ?? date('m');
        $year = $year ?? date('Y');

        return $this->transactions()
            ->where('type', 'income')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->sum('amount');
    }
}