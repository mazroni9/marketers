<?php

namespace App\Mr20\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * HTTP client for communicating with MR 20% engine.
 *
 * Expects configuration:
 *   services.mr20.base_url
 *   services.mr20.api_key
 */
class Mr20Client
{
    public function __construct(
        protected ?string $baseUrl,
        protected ?string $merchantApiKey
    ) {
    }

    protected function baseRequest(): \Illuminate\Http\Client\PendingRequest
    {
        if (!$this->baseUrl || !$this->merchantApiKey) {
            throw new RuntimeException('MR20 client is not configured (services.mr20.base_url / api_key).');
        }

        return Http::baseUrl(rtrim($this->baseUrl, '/'))
            ->acceptJson()
            ->withHeaders([
                'X-API-KEY' => $this->merchantApiKey,
            ]);
    }

    /**
     * Sync or create a product in MR20 when a new car is created in DASM.
     */
    public function syncProduct(string $externalProductCode, string $name, ?string $category = null, ?float $basePrice = null): Response
    {
        return $this->baseRequest()->post('/api/v1/products', array_filter([
            'external_product_code' => $externalProductCode,
            'name' => $name,
            'category' => $category,
            'base_price' => $basePrice,
        ], static fn($v) => $v !== null));
    }

    /**
     * Link a car with a partner in MR20 when DASM assigns a car to a collaborator.
     */
    public function linkPartnerToCar(
        int $partnerId,
        int $programId,
        string $externalCustomerId,
        string $externalProductCode
    ): Response {
        return $this->baseRequest()->post('/api/v1/links', [
            'partner_id' => $partnerId,
            'program_id' => $programId,
            'external_customer_id' => $externalCustomerId,
            'external_product_code' => $externalProductCode,
        ]);
    }

    /**
     * Report a successful sale to MR20 to generate commission.
     */
    public function reportTransaction(
        string $externalTransactionId,
        string $externalCustomerId,
        string $externalProductCode,
        float $amount,
        \DateTimeInterface $occurredAt,
        ?int $programId = null
    ): Response {
        $payload = [
            'external_transaction_id' => $externalTransactionId,
            'external_customer_id' => $externalCustomerId,
            'external_product_code' => $externalProductCode,
            'amount' => $amount,
            'occurred_at' => $occurredAt->format(DATE_ATOM),
        ];

        if ($programId !== null) {
            $payload['program_id'] = $programId;
        }

        return $this->baseRequest()->post('/api/v1/transactions/report', $payload);
    }
}

