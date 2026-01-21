<?php

namespace App\Mr20\Listeners;

use App\Mr20\Services\Mr20Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Listener for CarAssignedToPartner (or similar) event in DASM.
 *
 * Expected Event contract (adjust to real project):
 *   - $event->car with:
 *       - code
 *       - owner_external_id (ID of car owner in merchant system)
 *   - $event->partner with:
 *       - mr20_partner_id (ID in MR20 partners table)
 *   - $event->program_id (optional MR20 program id)
 */
class LinkCarPartnerWithMr20 implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        protected Mr20Client $client
    ) {
    }

    public function handle(object $event): void
    {
        $car = $event->car ?? null;
        $partner = $event->partner ?? null;

        if (!$car || !$partner) {
            return;
        }

        $programId = $event->program_id ?? null;
        if ($programId === null) {
            // يمكن ضبط برنامج افتراضي من Config أو من car->program_id حسب تصميم DASM
            Log::notice('MR20 link skipped: program_id is missing', [
                'car_code' => $car->code ?? null,
                'partner_id' => $partner->id ?? null,
            ]);
            return;
        }

        try {
            $response = $this->client->linkPartnerToCar(
                partnerId: (int) ($partner->mr20_partner_id ?? $partner->id),
                programId: (int) $programId,
                externalCustomerId: (string) ($car->owner_external_id ?? $car->owner_id ?? $car->code),
                externalProductCode: (string) $car->code,
            );

            if (!$response->successful()) {
                Log::warning('MR20 linkPartnerToCar failed', [
                    'car_code' => $car->code,
                    'partner_id' => $partner->id ?? null,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('MR20 linkPartnerToCar exception', [
                'car_code' => $car->code ?? null,
                'partner_id' => $partner->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

