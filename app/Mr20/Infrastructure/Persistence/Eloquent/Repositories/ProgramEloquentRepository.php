<?php

namespace App\Mr20\Infrastructure\Persistence\Eloquent\Repositories;

use App\Mr20\Models\MerchantProgram;

class ProgramEloquentRepository
{
    public function findById(int $id): ?MerchantProgram
    {
        return MerchantProgram::find($id);
    }

    public function findByMerchantId(int $merchantId): \Illuminate\Database\Eloquent\Collection
    {
        return MerchantProgram::where('merchant_id', $merchantId)->get();
    }

    public function findActiveByMerchantId(int $merchantId): \Illuminate\Database\Eloquent\Collection
    {
        return MerchantProgram::where('merchant_id', $merchantId)
            ->where('status', 'active')
            ->get();
    }

    public function findAllActive(): \Illuminate\Database\Eloquent\Collection
    {
        return MerchantProgram::where('status', 'active')->get();
    }

    public function save(MerchantProgram $program): bool
    {
        return $program->save();
    }

    public function create(array $attributes): MerchantProgram
    {
        return MerchantProgram::create($attributes);
    }
}
