<?php

namespace App\Mr20\Application\Merchants;

use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\MerchantEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\ProgramEloquentRepository;
use App\Mr20\Models\Merchant;

class CreateProgramHandler
{
    public function __construct(
        protected MerchantEloquentRepository $merchantRepository,
        protected ProgramEloquentRepository $programRepository
    ) {
    }

    public function handle(Merchant $merchant, array $data): array
    {
        $data['merchant_id'] = $merchant->id;
        $data['status'] = 'active';
        $program = $this->programRepository->create($data);

        return $program->fresh()->toArray();
    }
}
