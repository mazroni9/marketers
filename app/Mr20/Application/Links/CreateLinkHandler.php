<?php

namespace App\Mr20\Application\Links;

use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\CustomerEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\LinkEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\MerchantEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\PartnerEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\ProductEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\ProgramEloquentRepository;
use App\Mr20\Models\Merchant;

class CreateLinkHandler
{
    public function __construct(
        protected MerchantEloquentRepository $merchantRepository,
        protected ProgramEloquentRepository $programRepository,
        protected PartnerEloquentRepository $partnerRepository,
        protected CustomerEloquentRepository $customerRepository,
        protected ProductEloquentRepository $productRepository,
        protected LinkEloquentRepository $linkRepository
    ) {
    }

    public function handle(Merchant $merchant, array $data): array
    {
        // Validate program belongs to merchant
        $program = $this->programRepository->findById($data['program_id']);
        if (!$program || $program->merchant_id !== $merchant->id) {
            throw new \RuntimeException('Program does not belong to this merchant');
        }

        // Get partner
        $partner = $this->partnerRepository->findById($data['partner_id']);
        if (!$partner) {
            throw new \RuntimeException('Partner not found');
        }

        // Ensure customer exists
        $customer = $this->customerRepository->updateOrCreate(
            [
                'merchant_id' => $merchant->id,
                'external_customer_id' => $data['external_customer_id'],
            ],
            []
        );

        // Ensure product exists
        $product = $this->productRepository->firstOrCreate(
            [
                'merchant_id' => $merchant->id,
                'external_product_code' => $data['external_product_code'],
            ],
            [
                'name' => $data['external_product_code'],
            ]
        );

        // Create or update link
        $link = $this->linkRepository->updateOrCreate(
            [
                'partner_id' => $partner->id,
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'program_id' => $program->id,
            ],
            [
                'category_id' => null,
                'status' => 'active',
            ]
        );

        return [
            'link_id' => $link->id,
            'linked_at' => $link->created_at,
        ];
    }
}
