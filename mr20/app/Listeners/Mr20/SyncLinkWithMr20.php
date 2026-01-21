<?php

namespace App\Listeners\Mr20;

use App\Mr20\Services\LinkSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Listener يربط حدث ربط سيارة بشريك مع MR 20%.
 *
 * ملاحظة: يجب ضبط event الفعلي في DASM-Platform (مثلاً App\Events\CarPartnerAssigned)
 * بحيث يحتوي على:
 *   - $event->car
 *   - $event->partner
 *   - $event->program_id (اختياري)
 */
class SyncLinkWithMr20 implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        protected LinkSyncService $linkSyncService
    ) {
    }

    public function handle(object $event): void
    {
        $car = $event->car ?? null;
        $partner = $event->partner ?? null;
        $programId = $event->program_id ?? null;

        if (!$car || !$partner || !$programId) {
            return;
        }

        try {
            $this->linkSyncService->createOrUpdateLink(
                partnerId: (int) ($partner->mr20_partner_id ?? $partner->id),
                programId: (int) $programId,
                externalCustomerId: (string) ($car->owner_external_id ?? $car->owner_id ?? $car->code),
                externalProductCode: (string) $car->code,
            );
        } catch (\Throwable $e) {
            Log::error('MR20 SyncLinkWithMr20 failed', [
                'car_code' => $car->code ?? null,
                'partner_id' => $partner->id ?? null,
                'program_id' => $programId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

