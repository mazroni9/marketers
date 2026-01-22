<?php

namespace App\Mr20\Infrastructure\Persistence\Eloquent\Repositories;

use App\Mr20\Models\PartnerCustomerProductLink;

class LinkEloquentRepository
{
    public function findById(int $id): ?PartnerCustomerProductLink
    {
        return PartnerCustomerProductLink::find($id);
    }

    public function findByPartnerAndProduct(int $partnerId, int $productId, ?int $programId = null): ?PartnerCustomerProductLink
    {
        $query = PartnerCustomerProductLink::where('partner_id', $partnerId)
            ->where('product_id', $productId);

        if ($programId !== null) {
            $query->where('program_id', $programId);
        }

        return $query->first();
    }

    public function findByPartnerAndCategory(int $partnerId, int $categoryId, ?int $programId = null): ?PartnerCustomerProductLink
    {
        $query = PartnerCustomerProductLink::where('partner_id', $partnerId)
            ->where('category_id', $categoryId);

        if ($programId !== null) {
            $query->where('program_id', $programId);
        }

        return $query->first();
    }

    public function save(PartnerCustomerProductLink $link): bool
    {
        return $link->save();
    }

    public function create(array $attributes): PartnerCustomerProductLink
    {
        return PartnerCustomerProductLink::create($attributes);
    }

    public function updateOrCreate(array $criteria, array $attributes): PartnerCustomerProductLink
    {
        return PartnerCustomerProductLink::updateOrCreate($criteria, $attributes);
    }
}
