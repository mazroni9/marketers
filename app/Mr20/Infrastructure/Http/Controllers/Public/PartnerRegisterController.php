<?php

namespace App\Mr20\Infrastructure\Http\Controllers\Public;

use App\Mr20\Application\Partners\RegisterPartnerHandler;
use App\Mr20\Infrastructure\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartnerRegisterController extends Controller
{
    public function __construct(
        protected RegisterPartnerHandler $registerPartnerHandler
    ) {
    }

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

        $result = $this->registerPartnerHandler->handle($validated);

        return $this->success($result, 201);
    }
}
