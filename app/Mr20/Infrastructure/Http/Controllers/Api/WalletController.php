<?php

namespace App\Mr20\Infrastructure\Http\Controllers\Api;

use App\Mr20\Application\Wallet\GetWalletSummaryHandler;
use App\Mr20\Infrastructure\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(
        protected GetWalletSummaryHandler $getWalletSummaryHandler
    ) {
    }

    public function summary(Request $request)
    {
        // Note: In a real application, partner would be extracted from JWT
        // For now, this is a placeholder - you might need to pass partner_id in request
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

        $result = $this->getWalletSummaryHandler->handle((int) $partnerId);

        return $this->success($result);
    }
}
