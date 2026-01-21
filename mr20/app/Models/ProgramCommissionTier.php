<?php

namespace App\Mr20\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramCommissionTier extends Model
{
    protected $fillable = [
        'program_id',
        'from_count',
        'to_count',
        'commission_type',
        'commission_value',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(MerchantProgram::class, 'program_id');
    }
}

