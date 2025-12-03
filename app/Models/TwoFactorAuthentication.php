<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwoFactorAuthentication extends Model
{
    protected $fillable = [
        'user_id',
        'secret_key',
        'passkit_identifier',
        'enabled',
        'backup_codes',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'backup_codes' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
