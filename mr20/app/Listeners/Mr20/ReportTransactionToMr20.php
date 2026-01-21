<?php

namespace App\Listeners\Mr20;

use App\Mr20\Services\TransactionReportService;
use DateTimeInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Listener يربط حدث بيع سيارة في المزاد مع MR 20%.
 *
 * ملاحظة: يجب ضبط event الفعلي في DASM-Platform (مثلاً App\Events\CarSold)
 * بحيث يحتوي على:
 *   - $event->car
 *   - $event->sale
 *   - $event->program_id (اختياري)
 */
class ReportTransactionToMr20 implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        protected TransactionReportService $transactionReportService
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
        $programId = $event->program_id ?? null;

        if ($amount <= 0) {
            Log::notice('MR20 ReportTransactionToMr20 skipped: amount <= 0', [
                'transaction_id' => $externalTransactionId,
                'car_code' => $externalProductCode,
            ]);
            return;
        }

        try {
            $this->transactionReportService->reportCarSale(
                externalTransactionId: $externalTransactionId,
                externalCustomerId: $externalCustomerId,
                externalProductCode: $externalProductCode,
                amount: $amount,
                occurredAt: $occurredAt,
                programId: $programId,
            );
        } catch (\Throwable $e) {
            Log::error('MR20 ReportTransactionToMr20 failed', [
                'transaction_id' => $externalTransactionId,
                'car_code' => $externalProductCode,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

