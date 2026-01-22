<?php

namespace App\Mr20\Infrastructure\Persistence\Eloquent\Repositories;

use App\Mr20\Models\Transaction;

class TransactionEloquentRepository
{
    public function findById(int $id): ?Transaction
    {
        return Transaction::find($id);
    }

    public function findByExternalId(string $externalTransactionId, int $merchantId): ?Transaction
    {
        return Transaction::where('external_transaction_id', $externalTransactionId)
            ->where('merchant_id', $merchantId)
            ->first();
    }

    public function save(Transaction $transaction): bool
    {
        return $transaction->save();
    }

    public function create(array $attributes): Transaction
    {
        return Transaction::create($attributes);
    }
}
