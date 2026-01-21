<?php

namespace App\Mr20\Services;

use DateTimeInterface;

class TransactionReportService
{
    public function __construct(
        protected Mr20Client $client
    ) {
    }

    public function reportCarSale(
        string $externalTransactionId,
        string $externalCustomerId,
        string $externalProductCode,
        float $amount,
        DateTimeInterface $occurredAt,
        ?int $programId = null
    ): void {
        $this->client->reportTransaction(
            externalTransactionId: $externalTransactionId,
            externalCustomerId: $externalCustomerId,
            externalProductCode: $externalProductCode,
            amount: $amount,
            occurredAt: $occurredAt,
            programId: $programId
        );
    }
}

