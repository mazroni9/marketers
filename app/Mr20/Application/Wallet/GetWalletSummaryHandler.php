<?php

namespace App\Mr20\Application\Wallet;

use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\CommissionEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\WalletEntryEloquentRepository;
use Carbon\Carbon;

class GetWalletSummaryHandler
{
    public function __construct(
        protected CommissionEloquentRepository $commissionRepository,
        protected WalletEntryEloquentRepository $walletEntryRepository
    ) {
    }

    public function handle(int $partnerId): array
    {
        $now = Carbon::now();

        // Get all commissions for this partner
        $commissions = $this->commissionRepository->findByPartnerId($partnerId);

        // Calculate totals based on commission status and will_be_available_at
        $totalPending = 0;
        $totalAvailable = 0;
        $totalPaidOut = 0;

        foreach ($commissions as $commission) {
            $amount = (float) $commission->commission_amount;

            if ($commission->status === 'paid_out') {
                $totalPaidOut += $amount;
            } elseif ($commission->status === 'available') {
                $totalAvailable += $amount;
            } elseif ($commission->status === 'pending') {
                // Check if it should be available based on will_be_available_at
                if ($commission->will_be_available_at && $commission->will_be_available_at <= $now) {
                    $totalAvailable += $amount;
                } else {
                    $totalPending += $amount;
                }
            }
        }

        return [
            'total_pending' => round($totalPending, 2),
            'total_available' => round($totalAvailable, 2),
            'total_paid_out' => round($totalPaidOut, 2),
        ];
    }
}
