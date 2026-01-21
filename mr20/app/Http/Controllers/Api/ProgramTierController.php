<?php

namespace App\Mr20\Http\Controllers\Api;

use App\Mr20\Http\Controllers\Controller;
use App\Mr20\Models\Merchant;
use App\Mr20\Models\MerchantProgram;
use App\Mr20\Models\ProgramCommissionTier;
use Illuminate\Http\Request;

class ProgramTierController extends Controller
{
    public function store(Request $request, MerchantProgram $program)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey) {
            return $this->error('Missing X-API-KEY header', 401);
        }

        $merchant = Merchant::where('api_key', $apiKey)->first();

        if (!$merchant || $program->merchant_id !== $merchant->id) {
            return $this->error('Unauthorized for this program', 403);
        }

        $validated = $request->validate([
            'from_count' => ['required', 'integer', 'min:1'],
            'to_count' => ['nullable', 'integer', 'min:1'],
            'commission_type' => ['required', 'in:percentage,flat'],
            'commission_value' => ['required', 'numeric', 'min:0'],
        ]);

        $tier = new ProgramCommissionTier($validated);
        $tier->program_id = $program->id;
        $tier->save();

        return $this->success($tier->fresh()->toArray(), 201);
    }
}

