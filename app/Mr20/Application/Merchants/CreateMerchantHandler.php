<?php

namespace App\Mr20\Application\Merchants;

use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\MerchantEloquentRepository;
use Illuminate\Support\Str;

class CreateMerchantHandler
{
    public function __construct(
        protected MerchantEloquentRepository $merchantRepository
    ) {
    }

    public function handle(array $data): array
    {
        $data['api_key'] = Str::uuid()->toString();
        $merchant = $this->merchantRepository->create($data);

        return [
            'id' => $merchant->id,
            'api_key' => $merchant->api_key,
        ];
    }
}
