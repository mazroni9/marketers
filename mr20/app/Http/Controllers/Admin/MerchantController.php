<?php

namespace App\Mr20\Http\Controllers\Admin;

use App\Mr20\Http\Controllers\Controller;
use App\Mr20\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MerchantController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sector' => ['nullable', 'string', 'max:100'],
            'default_commission_model' => ['required', 'in:percentage,flat'],
            'default_commission_value' => ['required', 'numeric', 'min:0'],
            'default_payout_delay_days' => ['required', 'integer', 'min:0'],
        ]);

        $merchant = new Merchant($validated);
        $merchant->api_key = Str::uuid()->toString();
        $merchant->save();

        return $this->success([
            'id' => $merchant->id,
            'api_key' => $merchant->api_key,
        ]);
    }
}

