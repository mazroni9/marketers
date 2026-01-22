<?php

namespace App\Mr20\Infrastructure\Http\Controllers\Api;

use App\Mr20\Infrastructure\Http\Controllers\Controller;
use App\Mr20\Models\Merchant;
use App\Mr20\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
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
            'external_product_code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'base_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $product = Product::updateOrCreate(
            [
                'merchant_id' => $merchant->id,
                'external_product_code' => $validated['external_product_code'],
            ],
            [
                'name' => $validated['name'],
                'category' => $validated['category'] ?? null,
                'base_price' => $validated['base_price'] ?? null,
            ]
        );

        return $this->success([
            'id' => $product->id,
            'external_product_code' => $product->external_product_code,
            'name' => $product->name,
            'category' => $product->category,
            'base_price' => $product->base_price,
        ], 201);
    }
}
