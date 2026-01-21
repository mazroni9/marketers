<?php

namespace App\Mr20\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerCustomerProductLink extends Model
{
    protected $fillable = [
        'partner_id',
        'customer_id',
        'product_id',
        'category_id',
        'program_id',
        'first_eligible_at',
        'total_eligible_transactions',
        'status',
    ];

    protected $casts = [
        'first_eligible_at' => 'datetime',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
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
}

