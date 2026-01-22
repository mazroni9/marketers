<?php

namespace App\Mr20\Infrastructure\Http\Controllers\Admin;

use App\Mr20\Application\Merchants\CreateMerchantHandler;
use App\Mr20\Infrastructure\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function __construct(
        protected CreateMerchantHandler $createMerchantHandler
    ) {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sector' => ['nullable', 'string', 'max:100'],
            'default_commission_model' => ['required', 'in:percentage,flat'],
            'default_commission_value' => ['required', 'numeric', 'min:0'],
            'default_payout_delay_days' => ['required', 'integer', 'min:0'],
        ]);

        $result = $this->createMerchantHandler->handle($validated);

        return $this->success($result);
    }
}
