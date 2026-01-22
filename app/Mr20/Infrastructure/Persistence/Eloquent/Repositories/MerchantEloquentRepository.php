<?php

namespace App\Mr20\Infrastructure\Persistence\Eloquent\Repositories;

use App\Mr20\Models\Merchant;

class MerchantEloquentRepository
{
    public function findById(int $id): ?Merchant
    {
        return Merchant::find($id);
    }

    public function findByApiKey(string $apiKey): ?Merchant
    {
        return Merchant::where('api_key', $apiKey)->first();
    }

    public function save(Merchant $merchant): bool
    {
        return $merchant->save();
    }

    public function create(array $attributes): Merchant
    {
        return Merchant::create($attributes);
    }
}
