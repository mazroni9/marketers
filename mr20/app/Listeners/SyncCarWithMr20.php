<?php

namespace App\Mr20\Listeners;

use App\Mr20\Services\Mr20Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Listener for CarCreated (or similar) event in DASM.
 *
 * Expected Event contract (adjust to real project):
 *   - $event->car has:
 *       - id
 *       - code (unique car code in DASM)
 *       - title or name
 *       - category_name (optional)
 *       - base_price (optional)
 */
class SyncCarWithMr20 implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        protected Mr20Client $client
    ) {
    }

    public function handle(object $event): void
    {
        $car = $event->car ?? null;

        if (!$car) {
            return;
        }

        try {
            $response = $this->client->syncProduct(
                externalProductCode: (string) $car->code,
                name: $car->title ?? ('Car #' . $car->id),
                category: $car->category_name ?? null,
                basePrice: $car->base_price ?? null,
            );

            if (!$response->successful()) {
                Log::warning('MR20 syncProduct failed', [
                    'car_id' => $car->id,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('MR20 syncProduct exception', [
                'car_id' => $car->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

