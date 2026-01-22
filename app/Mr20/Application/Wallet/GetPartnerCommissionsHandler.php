<?php

namespace App\Mr20\Application\Wallet;

use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\CommissionEloquentRepository;

class GetPartnerCommissionsHandler
{
    public function __construct(
        protected CommissionEloquentRepository $commissionRepository
    ) {
    }

    public function handle(int $partnerId, ?string $status = null, ?int $perPage = 15): array
    {
        // Get commissions for partner
        $commissions = $this->commissionRepository->findByPartnerId($partnerId);

        // Filter by status if provided
        if ($status !== null) {
            $commissions = $commissions->filter(function ($commission) use ($status) {
                return $commission->status === $status;
            });
        }

        // Convert to array format
        $commissionsArray = $commissions->map(function ($commission) {
            return [
                'id' => $commission->id,
                'transaction_id' => $commission->transaction_id,
                'program_id' => $commission->program_id,
                'commission_amount' => (float) $commission->commission_amount,
                'status' => $commission->status,
                'will_be_available_at' => $commission->will_be_available_at?->toIso8601String(),
                'created_at' => $commission->created_at->toIso8601String(),
            ];
        })->values()->toArray();

        // Simple pagination (can be improved with proper pagination)
        if ($perPage > 0) {
            $commissionsArray = array_slice($commissionsArray, 0, $perPage);
        }

        return [
            'commissions' => $commissionsArray,
            'total' => $commissions->count(),
        ];
    }
}
