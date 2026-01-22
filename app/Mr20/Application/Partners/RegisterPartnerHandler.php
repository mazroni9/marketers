<?php

namespace App\Mr20\Application\Partners;

use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\PartnerEloquentRepository;
use Illuminate\Support\Facades\Hash;

class RegisterPartnerHandler
{
    public function __construct(
        protected PartnerEloquentRepository $partnerRepository
    ) {
    }

    public function handle(array $data): array
    {
        // Hash password if provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $partner = $this->partnerRepository->create($data);

        // Return partner data without password
        return [
            'id' => $partner->id,
            'full_name' => $partner->full_name,
            'phone' => $partner->phone,
            'email' => $partner->email,
            'city' => $partner->city,
            'expertise_tags' => $partner->expertise_tags,
        ];
    }
}
