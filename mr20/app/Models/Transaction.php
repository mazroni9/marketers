<?php

namespace App\Mr20\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'merchant_id',
        'external_transaction_id',
        'customer_id',
        'product_id',
        'category_id',
        'program_id',
        'amount',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(MerchantProgram::class, 'program_id');
    }

    public function commission(): HasOne
    {
        return $this->hasOne(Commission::class);
    }

    public function walletEntries(): HasMany
    {
        return $this->hasMany(WalletEntry::class, 'related_commission_id');
    }
}

