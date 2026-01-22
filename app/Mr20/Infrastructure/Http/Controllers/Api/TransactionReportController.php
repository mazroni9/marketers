<?php

namespace App\Mr20\Infrastructure\Http\Controllers\Api;

use App\Mr20\Application\Transactions\ReportTransactionHandler;
use App\Mr20\Infrastructure\Http\Controllers\Controller;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\MerchantEloquentRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionReportController extends Controller
{
    public function __construct(
        protected ReportTransactionHandler $reportTransactionHandler,
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
            'external_transaction_id' => ['required', 'string', 'max:255'],
            'external_customer_id' => ['required', 'string', 'max:255'],
            'external_product_code' => ['required', 'string', 'max:255'],
            'external_category_code' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'occurred_at' => ['required', 'date'],
            'program_id' => ['nullable', 'integer', 'exists:merchant_programs,id'],
        ]);

        $occurredAt = Carbon::parse($validated['occurred_at']);

        $result = $this->reportTransactionHandler->handle($merchant, $validated, $occurredAt);

        return $this->success($result);
    }
}
