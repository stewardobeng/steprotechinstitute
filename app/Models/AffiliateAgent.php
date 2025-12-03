<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AffiliateAgent extends Model
{
    protected $fillable = [
        'user_id',
        'referral_link',
        'total_earnings',
        'total_withdrawn',
        'wallet_balance',
        'registration_approved',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'total_earnings' => 'decimal:2',
            'total_withdrawn' => 'decimal:2',
            'wallet_balance' => 'decimal:2',
            'registration_approved' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function studentRegistrations(): HasMany
    {
        return $this->hasMany(StudentRegistration::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function canWithdraw(float $amount): bool
    {
        return $this->registration_approved 
            && $this->wallet_balance >= $amount 
            && $amount >= 200.00;
    }
}
