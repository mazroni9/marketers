<?php

namespace App\Mr20\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    protected $fillable = [
        'partner_id',
        'transaction_id',
        'program_id',
        'commission_amount',
        'status',
        'will_be_available_at',
    ];

    protected $casts = [
        'will_be_available_at' => 'datetime',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(MerchantProgram::class, 'program_id');
    }
}

