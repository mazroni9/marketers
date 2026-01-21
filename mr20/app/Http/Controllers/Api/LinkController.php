<?php

namespace App\Mr20\Http\Controllers\Api;

use App\Mr20\Http\Controllers\Controller;
use App\Mr20\Models\Customer;
use App\Mr20\Models\Merchant;
use App\Mr20\Models\MerchantProgram;
use App\Mr20\Models\Partner;
use App\Mr20\Models\PartnerCustomerProductLink;
use App\Mr20\Models\Product;
use Illuminate\Http\Request;

class LinkController extends Controller
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
            'partner_id' => ['required', 'exists:partners,id'],
            'program_id' => ['required', 'exists:merchant_programs,id'],
            'external_customer_id' => ['required', 'string', 'max:255'],
            'external_product_code' => ['required', 'string', 'max:255'],
        ]);

        /** @var MerchantProgram $program */
        $program = MerchantProgram::where('id', $validated['program_id'])
            ->where('merchant_id', $merchant->id)
            ->first();

        if (!$program) {
            return $this->error('Program does not belong to this merchant', 403);
        }

        /** @var Partner $partner */
        $partner = Partner::findOrFail($validated['partner_id']);

        // Ensure customer exists
        $customer = Customer::updateOrCreate(
            [
                'merchant_id' => $merchant->id,
                'external_customer_id' => $validated['external_customer_id'],
            ],
            []
        );

        // Ensure product exists (basic placeholder; in إنتاجي يجب ضمان تعريفه مسبقاً)
        $product = Product::firstOrCreate(
            [
                'merchant_id' => $merchant->id,
                'external_product_code' => $validated['external_product_code'],
            ],
            [
                'name' => $validated['external_product_code'],
            ]
        );

        // بسيط: ننشئ/نحدّث link واحد لهذا الزوج (partner, customer, product, program)
        $link = PartnerCustomerProductLink::updateOrCreate(
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

        return $this->success([
            'link_id' => $link->id,
            'linked_at' => $link->created_at,
        ], 201);
    }
}

