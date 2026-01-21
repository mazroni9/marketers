<?php

namespace App\Mr20\Http\Controllers\Api;

use App\Mr20\Http\Controllers\Controller;
use App\Mr20\Models\Merchant;
use App\Mr20\Models\MerchantProgram;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function store(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey) {
            return $this->error('Missing X-API-KEY header', 401);
        }

        $merchant = Merchant::where('api_key', $apiKey)->first();

        if (!$merchant) {
            return $this->error('Invalid merchant API key', 401);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'commission_type' => ['required', 'in:percentage,flat'],
            'commission_value' => ['required', 'numeric', 'min:0'],
            'lifetime_mode' => ['required', 'in:lifetime,by_count,by_period'],
            'lifetime_count_limit' => ['nullable', 'integer', 'min:1'],
            'lifetime_period_days' => ['nullable', 'integer', 'min:1'],
            'attribution_model' => ['required', 'in:first_click,last_click'],
            'scope' => ['required', 'in:product,category'],
            'terms_summary' => ['nullable', 'string'],
        ]);

        $program = new MerchantProgram($validated);
        $program->merchant_id = $merchant->id;
        $program->status = 'active';
        $program->save();

        return $this->success($program->fresh()->toArray(), 201);
    }
}

