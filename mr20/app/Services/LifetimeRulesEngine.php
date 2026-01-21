<?php

namespace App\Mr20\Services;

use App\Mr20\Models\MerchantProgram;
use App\Mr20\Models\PartnerCustomerProductLink;
use App\Mr20\Models\ProgramCommissionTier;
use Carbon\Carbon;

class LifetimeRulesEngine
{
    /**
     * تقييم أهلية العملية حسب lifetime_mode.
     *
     * @return array{eligible: bool, reason: ?string, first_eligible_at: ?Carbon}
     */
    public function evaluateEligibility(
        MerchantProgram $program,
        PartnerCustomerProductLink $link,
        Carbon $occurredAt
    ): array {
        $eligible = false;
        $reason = null;
        $firstEligibleAt = $link->first_eligible_at instanceof Carbon
            ? $link->first_eligible_at->copy()
            : ($link->first_eligible_at ? Carbon::parse($link->first_eligible_at) : null);

        switch ($program->lifetime_mode) {
            case 'lifetime':
                $eligible = true;
                break;

            case 'by_count':
                if ($program->lifetime_count_limit === null || $program->lifetime_count_limit === 0) {
                    $eligible = true;
                } elseif ($link->total_eligible_transactions < $program->lifetime_count_limit) {
                    $eligible = true;
                } else {
                    $reason = 'count_limit_reached';
                }
                break;

            case 'by_period':
                if ($firstEligibleAt === null) {
                    $firstEligibleAt = $occurredAt->copy();
                }

                if ($program->lifetime_period_days === null || $program->lifetime_period_days === 0) {
                    $eligible = true;
                } else {
                    $daysDiff = $firstEligibleAt->diffInDays($occurredAt);
                    if ($daysDiff <= $program->lifetime_period_days) {
                        $eligible = true;
                    } else {
                        $reason = 'lifetime_period_expired';
                    }
                }
                break;
        }

        return [
            'eligible' => $eligible,
            'reason' => $reason,
            'first_eligible_at' => $firstEligibleAt,
        ];
    }

    /**
     * اختيار الـ Tier المناسب بناءً على رقم العملية التالية.
     */
    public function selectTier(MerchantProgram $program, int $nextTxNumber): ?ProgramCommissionTier
    {
        return ProgramCommissionTier::where('program_id', $program->id)
            ->where('from_count', '<=', $nextTxNumber)
            ->where(function ($query) use ($nextTxNumber) {
                $query->whereNull('to_count')
                    ->orWhere('to_count', '>=', $nextTxNumber);
            })
            ->orderBy('from_count', 'desc')
            ->first();
    }
}

