<?php

namespace App\Mr20\Infrastructure\Persistence\Eloquent\Repositories;

use App\Mr20\Models\ProgramCommissionTier;

class ProgramTierEloquentRepository
{
    public function findById(int $id): ?ProgramCommissionTier
    {
        return ProgramCommissionTier::find($id);
    }

    public function findByProgramId(int $programId): \Illuminate\Database\Eloquent\Collection
    {
        return ProgramCommissionTier::where('program_id', $programId)->get();
    }

    public function save(ProgramCommissionTier $tier): bool
    {
        return $tier->save();
    }

    public function create(array $attributes): ProgramCommissionTier
    {
        return ProgramCommissionTier::create($attributes);
    }
}
