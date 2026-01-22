<?php

use Illuminate\Support\Facades\Route;
use App\Mr20\Infrastructure\Http\Controllers\Admin\MerchantController;
use App\Mr20\Infrastructure\Http\Controllers\Api\ProductController;
use App\Mr20\Infrastructure\Http\Controllers\Api\ProgramController;
use App\Mr20\Infrastructure\Http\Controllers\Api\ProgramTierController;
use App\Mr20\Infrastructure\Http\Controllers\Api\LinkController;
use App\Mr20\Infrastructure\Http\Controllers\Api\TransactionReportController;
use App\Mr20\Infrastructure\Http\Controllers\Public\PartnerRegisterController;
use App\Mr20\Infrastructure\Http\Controllers\Partner\PartnerProgramController;
use App\Mr20\Infrastructure\Http\Controllers\Api\WalletController;
use App\Mr20\Infrastructure\Http\Controllers\Api\CommissionsController;

Route::prefix('api')->group(function () {
    // Admin merchants
    Route::post('/admin/merchants', [MerchantController::class, 'store']);

    // Public partner registration
    Route::post('/public/partners/register', [PartnerRegisterController::class, 'store']);

    // Partner programs (for authenticated partners)
    Route::get('/partner/programs/available', [PartnerProgramController::class, 'available']);
    Route::post('/partner/programs/enroll', [PartnerProgramController::class, 'enroll']);

    // Partner wallet and commissions
    Route::get('/partner/wallet/summary', [WalletController::class, 'summary']);
    Route::get('/partner/commissions', [CommissionsController::class, 'index']);

    // v1 core APIs
    Route::prefix('v1')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::post('/programs', [ProgramController::class, 'store']);
        Route::post('/programs/{program}/tiers', [ProgramTierController::class, 'store']);
        Route::post('/links', [LinkController::class, 'store']);
        Route::post('/transactions/report', [TransactionReportController::class, 'store']);
    });
});

