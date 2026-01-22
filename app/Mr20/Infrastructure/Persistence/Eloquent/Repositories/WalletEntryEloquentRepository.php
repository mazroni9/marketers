<?php

namespace App\Mr20\Infrastructure\Persistence\Eloquent\Repositories;

use App\Mr20\Models\WalletEntry;

class WalletEntryEloquentRepository
{
    public function findById(int $id): ?WalletEntry
    {
        return WalletEntry::find($id);
    }

    public function findByPartnerId(int $partnerId): \Illuminate\Database\Eloquent\Collection
    {
        return WalletEntry::where('partner_id', $partnerId)->get();
    }

    public function save(WalletEntry $walletEntry): bool
    {
        return $walletEntry->save();
    }

    public function create(array $attributes): WalletEntry
    {
        return WalletEntry::create($attributes);
    }
}
