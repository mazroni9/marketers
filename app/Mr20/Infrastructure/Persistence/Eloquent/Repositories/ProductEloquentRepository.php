<?php

namespace App\Mr20\Infrastructure\Persistence\Eloquent\Repositories;

use App\Mr20\Models\Product;

class ProductEloquentRepository
{
    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function findByMerchantAndExternalCode(int $merchantId, string $externalProductCode): ?Product
    {
        return Product::where('merchant_id', $merchantId)
            ->where('external_product_code', $externalProductCode)
            ->first();
    }

    public function save(Product $product): bool
    {
        return $product->save();
    }

    public function create(array $attributes): Product
    {
        return Product::create($attributes);
    }

    public function firstOrCreate(array $criteria, array $attributes): Product
    {
        return Product::firstOrCreate($criteria, $attributes);
    }
}
