<?php

namespace App\Mr20\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'city',
        'expertise_tags',
        'password',
    ];

    protected $casts = [
        'expertise_tags' => 'array',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function links(): HasMany
    {
        return $this->hasMany(PartnerCustomerProductLink::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    public function walletEntries(): HasMany
    {
        return $this->hasMany(WalletEntry::class);
    }
}

