<?php

namespace App\Mr20\Infrastructure\Http\Controllers\Api;

use App\Mr20\Application\Links\CreateLinkHandler;
use App\Mr20\Infrastructure\Http\Controllers\Controller;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\MerchantEloquentRepository;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function __construct(
        protected CreateLinkHandler $createLinkHandler,
        protected MerchantEloquentRepository $merchantRepository
    ) {
    }

    public function store(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey) {
            return $this->error('Missing X-API-KEY header', 401);
        }

        $merchant = $this->merchantRepository->findByApiKey($apiKey);

        if (!$merchant) {
            return $this->error('Invalid merchant API key', 401);
        }

        $validated = $request->validate([
            'partner_id' => ['required', 'exists:partners,id'],
            'program_id' => ['required', 'exists:merchant_programs,id'],
            'external_customer_id' => ['required', 'string', 'max:255'],
            'external_product_code' => ['required', 'string', 'max:255'],
        ]);

        try {
            $result = $this->createLinkHandler->handle($merchant, $validated);
            return $this->success($result, 201);
        } catch (\RuntimeException $e) {
            return $this->error($e->getMessage(), 403);
        }
    }
}
