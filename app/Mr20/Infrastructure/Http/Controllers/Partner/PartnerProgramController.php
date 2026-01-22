<?php

namespace App\Mr20\Infrastructure\Http\Controllers\Partner;

use App\Mr20\Application\Partners\EnrollPartnerInProgramHandler;
use App\Mr20\Application\Partners\GetAvailableProgramsHandler;
use App\Mr20\Infrastructure\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartnerProgramController extends Controller
{
    public function __construct(
        protected GetAvailableProgramsHandler $getAvailableProgramsHandler,
        protected EnrollPartnerInProgramHandler $enrollPartnerInProgramHandler
    ) {
    }

    public function available(Request $request)
    {
        // Note: In a real application, partner would be extracted from JWT
        // For now, this is a placeholder
        /** @var \App\Mr20\Models\Partner|null $partner */
        $partner = $request->user('partner'); // placeholder

        $result = $this->getAvailableProgramsHandler->handle();

        return $this->success($result);
    }

    public function enroll(Request $request)
    {
        // Note: In a real application, partner would be extracted from JWT
        // For now, this is a placeholder
        /** @var \App\Mr20\Models\Partner|null $partner */
        $partner = $request->user('partner'); // placeholder

        $validated = $request->validate([
            'program_id' => ['required', 'exists:merchant_programs,id'],
        ]);

        try {
            $result = $this->enrollPartnerInProgramHandler->handle($validated['program_id']);
            return $this->success($result);
        } catch (\RuntimeException $e) {
            return $this->error($e->getMessage(), 404);
        }
    }
}
