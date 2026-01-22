<?php

namespace App\Mr20\Application\Merchants;

use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\ProgramTierEloquentRepository;
use App\Mr20\Models\MerchantProgram;

class AttachProgramTiersHandler
{
    public function __construct(
        protected ProgramTierEloquentRepository $tierRepository
    ) {
    }

    public function handle(MerchantProgram $program, array $data): array
    {
        $data['program_id'] = $program->id;
        $tier = $this->tierRepository->create($data);

        return $tier->fresh()->toArray();
    }
}
