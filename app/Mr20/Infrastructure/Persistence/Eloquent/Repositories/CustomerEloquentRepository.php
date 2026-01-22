<?php

namespace App\Mr20\Infrastructure\Persistence\Eloquent\Repositories;

use App\Mr20\Models\Customer;

class CustomerEloquentRepository
{
    public function findById(int $id): ?Customer
    {
        return Customer::find($id);
    }

    public function findByMerchantAndExternalId(int $merchantId, string $externalCustomerId): ?Customer
    {
        return Customer::where('merchant_id', $merchantId)
            ->where('external_customer_id', $externalCustomerId)
            ->first();
    }

    public function save(Customer $customer): bool
    {
        return $customer->save();
    }

    public function create(array $attributes): Customer
    {
        return Customer::create($attributes);
    }

    public function updateOrCreate(array $criteria, array $attributes): Customer
    {
        return Customer::updateOrCreate($criteria, $attributes);
    }
}
