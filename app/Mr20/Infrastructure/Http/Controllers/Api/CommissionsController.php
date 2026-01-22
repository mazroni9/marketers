<?php

namespace App\Mr20\Infrastructure\Http\Controllers\Api;

use App\Mr20\Application\Wallet\GetPartnerCommissionsHandler;
use App\Mr20\Infrastructure\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommissionsController extends Controller
{
    public function __construct(
        protected GetPartnerCommissionsHandler $getPartnerCommissionsHandler
    ) {
    }

    public function index(Request $request)
    {
        // Note: In a real application, partner would be extracted from JWT
        // For now, this is a placeholder
        /** @var \App\Mr20\Models\Partner|null $partner */
        $partner = $request->user('partner'); // placeholder

        // For now, accept partner_id from request if not authenticated
        $partnerId = $request->input('partner_id');
        if (!$partnerId && $partner) {
            $partnerId = $partner->id;
        }

        if (!$partnerId) {
            return $this->error('Partner ID is required', 400);
        }

        $validated = $request->validate([
            'status' => ['nullable', 'in:pending,available,paid_out,cancelled'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $status = $validated['status'] ?? null;
        $perPage = $validated['per_page'] ?? 15;

        $result = $this->getPartnerCommissionsHandler->handle((int) $partnerId, $status, $perPage);

        return $this->success($result);
    }
}
