<?php

namespace App\Mr20\Infrastructure\Persistence\Eloquent\Repositories;

use App\Mr20\Models\Partner;

class PartnerEloquentRepository
{
    public function findById(int $id): ?Partner
    {
        return Partner::find($id);
    }

    public function findByEmail(string $email): ?Partner
    {
        return Partner::where('email', $email)->first();
    }

    public function save(Partner $partner): bool
    {
        return $partner->save();
    }

    public function create(array $attributes): Partner
    {
        return Partner::create($attributes);
    }
}
