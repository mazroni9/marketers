<?php

namespace App\Mr20\Infrastructure\Http\Controllers\Api;

use App\Mr20\Application\Merchants\AttachProgramTiersHandler;
use App\Mr20\Infrastructure\Http\Controllers\Controller;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\MerchantEloquentRepository;
use App\Mr20\Models\MerchantProgram;
use Illuminate\Http\Request;

class ProgramTierController extends Controller
{
    public function __construct(
        protected AttachProgramTiersHandler $attachTiersHandler,
        protected MerchantEloquentRepository $merchantRepository
    ) {
    }

    public function store(Request $request, MerchantProgram $program)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey) {
            return $this->error('Missing X-API-KEY header', 401);
        }

        $merchant = $this->merchantRepository->findByApiKey($apiKey);

        if (!$merchant || $program->merchant_id !== $merchant->id) {
            return $this->error('Unauthorized for this program', 403);
        }

        $validated = $request->validate([
            'from_count' => ['required', 'integer', 'min:1'],
            'to_count' => ['nullable', 'integer', 'min:1'],
            'commission_type' => ['required', 'in:percentage,flat'],
            'commission_value' => ['required', 'numeric', 'min:0'],
        ]);

        $result = $this->attachTiersHandler->handle($program, $validated);

        return $this->success($result, 201);
    }
}
