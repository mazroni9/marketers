<?php

namespace App\Mr20\Infrastructure\Persistence\Eloquent\Repositories;

use App\Mr20\Models\Category;

class CategoryEloquentRepository
{
    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function findByMerchantAndExternalCode(int $merchantId, string $externalCategoryCode): ?Category
    {
        return Category::where('merchant_id', $merchantId)
            ->where('external_category_code', $externalCategoryCode)
            ->first();
    }

    public function save(Category $category): bool
    {
        return $category->save();
    }

    public function create(array $attributes): Category
    {
        return Category::create($attributes);
    }

    public function firstOrCreate(array $criteria, array $attributes): Category
    {
        return Category::firstOrCreate($criteria, $attributes);
    }
}
