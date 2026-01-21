<?php

namespace App\Mr20\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MerchantProgram extends Model
{
    protected $fillable = [
        'merchant_id',
        'name',
        'commission_type',
        'commission_value',
        'lifetime_mode',
        'lifetime_count_limit',
        'lifetime_period_days',
        'attribution_model',
        'scope',
        'terms_summary',
        'status',
    ];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function tiers(): HasMany
    {
        return $this->hasMany(ProgramCommissionTier::class, 'program_id');
    }

    public function links(): HasMany
    {
        return $this->hasMany(PartnerCustomerProductLink::class, 'program_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'program_id');
    }
}

