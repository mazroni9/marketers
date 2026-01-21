<?php

namespace App\Mr20\Services;

class ProductSyncService
{
    public function __construct(
        protected Mr20Client $client
    ) {
    }

    public function syncCarAsProduct(
        string $carCode,
        string $name,
        ?string $category = null,
        ?float $basePrice = null
    ): void {
        $this->client->syncProduct(
            externalProductCode: $carCode,
            name: $name,
            category: $category,
            basePrice: $basePrice
        );
    }
}

