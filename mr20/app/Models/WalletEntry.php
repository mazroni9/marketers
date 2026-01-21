<?php

namespace App\Mr20\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletEntry extends Model
{
    protected $fillable = [
        'partner_id',
        'type',
        'amount',
        'related_commission_id',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function commission(): BelongsTo
    {
        return $this->belongsTo(Commission::class, 'related_commission_id');
    }
}

