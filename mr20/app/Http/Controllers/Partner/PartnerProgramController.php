<?php

namespace App\Mr20\Http\Controllers\Partner;

use App\Mr20\Http\Controllers\Controller;
use App\Mr20\Models\MerchantProgram;
use App\Mr20\Models\Partner;
use Illuminate\Http\Request;

class PartnerProgramController extends Controller
{
    public function available(Request $request)
    {
        // في تطبيق حقيقي، سيتم استنتاج الشريك من JWT
        /** @var Partner|null $partner */
        $partner = $request->user('partner'); // placeholder

        // مبدئياً نعيد جميع البرامج النشطة
        $programs = MerchantProgram::where('status', 'active')->get();

        return $this->success([
            'programs' => $programs,
        ]);
    }

    public function enroll(Request $request)
    {
        // في تطبيق حقيقي، سيتم استنتاج الشريك من JWT
        /** @var Partner|null $partner */
        $partner = $request->user('partner'); // placeholder

        $validated = $request->validate([
            'program_id' => ['required', 'exists:merchant_programs,id'],
        ]);

        // يمكن لاحقاً إضافة جدول pivot للبرامج المسجلة
        // حالياً نعيد فقط تأكيد التسجيل بشكل صوري

        $program = MerchantProgram::findOrFail($validated['program_id']);

        return $this->success([
            'program_id' => $program->id,
            'status' => 'enrolled',
        ]);
    }
}

