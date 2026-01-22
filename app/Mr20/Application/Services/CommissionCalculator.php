<?php

namespace App\Mr20\Application\Services;

use App\Mr20\Models\MerchantProgram;
use App\Mr20\Models\ProgramCommissionTier;

class CommissionCalculator
{
    /**
     * احسب مبلغ العمولة النهائي بناءً على نوع العمولة (برنامج أو Tier) وقيمة العملية.
     */
    public function calculate(
        MerchantProgram $program,
        ?ProgramCommissionTier $tier,
        float $transactionAmount
    ): float {
        $type = $tier?->commission_type ?? $program->commission_type;
        $value = (float) ($tier?->commission_value ?? $program->commission_value);

        if ($type === 'percentage') {
            return round(($transactionAmount * $value) / 100, 2);
        }

        return round($value, 2);
    }
}
