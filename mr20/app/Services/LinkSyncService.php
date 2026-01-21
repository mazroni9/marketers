<?php

namespace App\Mr20\Services;

class LinkSyncService
{
    public function __construct(
        protected Mr20Client $client
    ) {
    }

    public function createOrUpdateLink(
        int $partnerId,
        int $programId,
        string $externalCustomerId,
        string $externalProductCode
    ): void {
        $this->client->linkPartnerToCar(
            partnerId: $partnerId,
            programId: $programId,
            externalCustomerId: $externalCustomerId,
            externalProductCode: $externalProductCode
        );
    }
}

