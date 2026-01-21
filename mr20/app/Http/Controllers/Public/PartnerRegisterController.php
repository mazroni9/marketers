<?php

namespace App\Mr20\Http\Controllers\Public;

use App\Mr20\Http\Controllers\Controller;
use App\Mr20\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PartnerRegisterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50', 'unique:partners,phone'],
            'email' => ['nullable', 'email', 'max:255', 'unique:partners,email'],
            'city' => ['nullable', 'string', 'max:100'],
            'expertise_tags' => ['nullable', 'array'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $partner = new Partner($validated);

        if (!empty($validated['password'])) {
            $partner->password = Hash::make($validated['password']);
        }

        $partner->save();

        // ملاحظة: إصدار JWT أو Token فعلي سيتم ربطه بنظام Auth الرئيسي
        return $this->success([
            'id' => $partner->id,
            'full_name' => $partner->full_name,
            'phone' => $partner->phone,
            'email' => $partner->email,
            'city' => $partner->city,
            'expertise_tags' => $partner->expertise_tags,
        ], 201);
    }
}

