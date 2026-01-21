<?php

namespace App\Listeners\Mr20;

use App\Mr20\Services\ProductSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Listener يربط حدث إنشاء سيارة جديدة مع MR 20%.
 *
 * ملاحظة: يجب ضبط event الفعلي في DASM-Platform (مثلاً App\Events\CarCreated)
 * بحيث يحتوي على كائن السيارة في $event->car.
 */
class SyncProductWithMr20 implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        protected ProductSyncService $productSyncService
    ) {
    }

    public function handle(object $event): void
    {
        $car = $event->car ?? null;

        if (!$car || empty($car->code)) {
            return;
        }

        try {
            $this->productSyncService->syncCarAsProduct(
                carCode: (string) $car->code,
                name: $car->title ?? $car->name ?? ('Car #' . ($car->id ?? '')),
                category: $car->category_name ?? null,
                basePrice: $car->base_price ?? null,
            );
        } catch (\Throwable $e) {
            Log::error('MR20 SyncProductWithMr20 failed', [
                'car_code' => $car->code,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

