<?php

namespace App\Mr20\Infrastructure\Persistence\Eloquent\Repositories;

use App\Mr20\Models\Commission;

class CommissionEloquentRepository
{
    public function findById(int $id): ?Commission
    {
        return Commission::find($id);
    }

    public function findByPartnerId(int $partnerId, ?int $limit = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Commission::where('partner_id', $partnerId)
            ->orderBy('created_at', 'desc');

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function save(Commission $commission): bool
    {
        return $commission->save();
    }

    public function create(array $attributes): Commission
    {
        return Commission::create($attributes);
    }
}
