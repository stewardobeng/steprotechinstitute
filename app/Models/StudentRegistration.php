<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudentRegistration extends Model
{
    protected $fillable = [
        'user_id',
        'student_id',
        'affiliate_agent_id',
        'invite_code_used',
        'registration_fee',
        'payment_status',
        'payment_reference',
        'payment_date',
        'added_to_whatsapp',
        'added_by',
        'added_at',
        'classroom_approved',
    ];

    protected function casts(): array
    {
        return [
            'registration_fee' => 'decimal:2',
            'payment_date' => 'datetime',
            'added_to_whatsapp' => 'boolean',
            'added_at' => 'datetime',
            'classroom_approved' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function affiliateAgent(): BelongsTo
    {
        return $this->belongsTo(AffiliateAgent::class);
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }
}
