<?php

namespace App\Mr20\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Merchant extends Model
{
    protected $fillable = [
        'name',
        'sector',
        'default_commission_model',
        'default_commission_value',
        'default_payout_delay_days',
        'api_key',
    ];

    public function programs(): HasMany
    {
        return $this->hasMany(MerchantProgram::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}

