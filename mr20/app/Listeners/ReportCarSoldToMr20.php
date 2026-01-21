<?php

namespace App\Mr20\Listeners;

use App\Mr20\Services\Mr20Client;
use DateTimeInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Listener for CarSold (or similar) event in DASM.
 *
 * Expected Event contract (adjust to real project):
 *   - $event->car with:
 *       - code (external_product_code)
 *       - owner_external_id (external_customer_id)
 *   - $event->sale with:
 *       - id (external_transaction_id) أو رقم الصفقة
 *       - commission_amount (قيمة العمولة التي تُرسل إلى MR20 لحساب نسبة الوسيط)
 *       - occurred_at (DateTimeInterface)
 */
class ReportCarSoldToMr20 implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        protected Mr20Client $client
    ) {
    }

    public function handle(object $event): void
    {
        $car = $event->car ?? null;
        $sale = $event->sale ?? null;

        if (!$car || !$sale) {
            return;
        }

        $externalTransactionId = (string) ($sale->id ?? ($car->code . '-' . ($sale->reference ?? uniqid())));
        $externalCustomerId = (string) ($car->owner_external_id ?? $car->owner_id ?? $car->code);
        $externalProductCode = (string) $car->code;
        $amount = (float) ($sale->commission_amount ?? $sale->amount ?? 0);
        $occurredAt = $sale->occurred_at instanceof DateTimeInterface
            ? $sale->occurred_at
            : now();

        if ($amount <= 0) {
            Log::notice('MR20 reportTransaction skipped: amount <= 0', [
                'transaction_id' => $externalTransactionId,
                'car_code' => $externalProductCode,
            ]);
            return;
        }

        try {
            $response = $this->client->reportTransaction(
                externalTransactionId: $externalTransactionId,
                externalCustomerId: $externalCustomerId,
                externalProductCode: $externalProductCode,
                amount: $amount,
                occurredAt: $occurredAt,
                programId: $event->program_id ?? null,
            );

            if (!$response->successful()) {
                Log::warning('MR20 reportTransaction failed', [
                    'transaction_id' => $externalTransactionId,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('MR20 reportTransaction exception', [
                'transaction_id' => $externalTransactionId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

