<?php

namespace App\Mr20\Http\Controllers\Api;

use App\Mr20\Http\Controllers\Controller;
use App\Mr20\Models\Category;
use App\Mr20\Models\Commission;
use App\Mr20\Models\Customer;
use App\Mr20\Models\Merchant;
use App\Mr20\Models\MerchantProgram;
use App\Mr20\Models\PartnerCustomerProductLink;
use App\Mr20\Models\Product;
use App\Mr20\Models\Transaction;
use App\Mr20\Models\WalletEntry;
use App\Mr20\Services\CommissionCalculator;
use App\Mr20\Services\LifetimeRulesEngine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionReportController extends Controller
{
    public function __construct(
        protected LifetimeRulesEngine $lifetimeRulesEngine,
        protected CommissionCalculator $commissionCalculator
    ) {
    }
    public function store(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey) {
            return $this->error('Missing X-API-KEY header', 401);
        }

        /** @var Merchant|null $merchant */
        $merchant = Merchant::where('api_key', $apiKey)->first();

        if (!$merchant) {
            return $this->error('Invalid merchant API key', 401);
        }

        $validated = $request->validate([
            'external_transaction_id' => ['required', 'string', 'max:255'],
            'external_customer_id' => ['required', 'string', 'max:255'],
            'external_product_code' => ['required', 'string', 'max:255'],
            'external_category_code' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'occurred_at' => ['required', 'date'],
            'program_id' => ['nullable', 'integer', 'exists:merchant_programs,id'],
        ]);

        $occurredAt = Carbon::parse($validated['occurred_at']);

        return DB::transaction(function () use ($merchant, $validated, $occurredAt) {
            // 1) Ensure customer
            /** @var Customer $customer */
            $customer = Customer::updateOrCreate(
                [
                    'merchant_id' => $merchant->id,
                    'external_customer_id' => $validated['external_customer_id'],
                ],
                []
            );

            // 2) Determine / ensure product or category
            $product = null;
            $category = null;

            // Program resolution (may depend on scope)
            $program = null;
            if (!empty($validated['program_id'])) {
                $program = MerchantProgram::where('id', $validated['program_id'])
                    ->where('merchant_id', $merchant->id)
                    ->where('status', 'active')
                    ->first();
            }

            if (!$program) {
                $program = MerchantProgram::where('merchant_id', $merchant->id)
                    ->where('status', 'active')
                    ->orderBy('id')
                    ->first();
            }

            if (!$program) {
                // لا يوجد برنامج عمولات معرف لهذا التاجر
                $transaction = $this->createOrUpdateTransaction(
                    $merchant,
                    $customer,
                    null,
                    null,
                    null,
                    $validated['external_transaction_id'],
                    $validated['amount'],
                    $occurredAt
                );

                return $this->success([
                    'transaction_id' => $transaction->id,
                    'commission_created' => false,
                    'reason' => 'program_not_found',
                ]);
            }

            if ($program->scope === 'product') {
                $product = Product::firstOrCreate(
                    [
                        'merchant_id' => $merchant->id,
                        'external_product_code' => $validated['external_product_code'],
                    ],
                    [
                        'name' => $validated['external_product_code'],
                    ]
                );
            } else { // scope === category
                if (empty($validated['external_category_code'])) {
                    $transaction = $this->createOrUpdateTransaction(
                        $merchant,
                        $customer,
                        null,
                        null,
                        $program,
                        $validated['external_transaction_id'],
                        $validated['amount'],
                        $occurredAt
                    );

                    return $this->success([
                        'transaction_id' => $transaction->id,
                        'commission_created' => false,
                        'reason' => 'external_category_code_required',
                    ]);
                }

                $category = Category::firstOrCreate(
                    [
                        'merchant_id' => $merchant->id,
                        'external_category_code' => $validated['external_category_code'],
                    ],
                    [
                        'name' => $validated['external_category_code'],
                    ]
                );
            }

            // 3) Create or update transaction record
            $transaction = $this->createOrUpdateTransaction(
                $merchant,
                $customer,
                $product,
                $category,
                $program,
                $validated['external_transaction_id'],
                $validated['amount'],
                $occurredAt
            );

            // 4) Find appropriate Link according to attribution model
            $linkQuery = PartnerCustomerProductLink::query()
                ->where('customer_id', $customer->id)
                ->where('program_id', $program->id)
                ->where('status', 'active');

            if ($program->scope === 'product' && $product) {
                $linkQuery->where('product_id', $product->id);
            } elseif ($program->scope === 'category' && $category) {
                $linkQuery->where('category_id', $category->id);
            }

            if ($program->attribution_model === 'first_click') {
                $linkQuery->orderBy('created_at', 'asc');
            } else {
                $linkQuery->orderBy('created_at', 'desc');
            }

            /** @var PartnerCustomerProductLink|null $link */
            $link = $linkQuery->first();

            if (!$link) {
                return $this->success([
                    'transaction_id' => $transaction->id,
                    'commission_created' => false,
                    'reason' => 'no_link_found',
                ]);
            }

            // 5) Determine eligibility based on lifetime_mode via LifetimeRulesEngine
            $eligibility = $this->lifetimeRulesEngine->evaluateEligibility($program, $link, $occurredAt);
            if (!$eligibility['eligible']) {
                return $this->success([
                    'transaction_id' => $transaction->id,
                    'commission_created' => false,
                    'reason' => $eligibility['reason'] ?: 'not_eligible',
                ]);
            }

            // 6) Determine Tier and commission amount
            $nextTxNumber = $link->total_eligible_transactions + 1;
            $tier = $this->lifetimeRulesEngine->selectTier($program, $nextTxNumber);

            $commissionAmount = $this->commissionCalculator->calculate(
                $program,
                $tier,
                $transaction->amount
            );

            if ($commissionAmount <= 0) {
                return $this->success([
                    'transaction_id' => $transaction->id,
                    'commission_created' => false,
                    'reason' => 'commission_amount_zero',
                ]);
            }

            // 7) Create Commission + WalletEntry, update Link counters
            $willBeAvailableAt = $occurredAt->copy()->addDays($merchant->default_payout_delay_days);

            /** @var Commission $commission */
            $commission = Commission::create([
                'partner_id' => $link->partner_id,
                'transaction_id' => $transaction->id,
                'program_id' => $program->id,
                'commission_amount' => $commissionAmount,
                'status' => 'pending',
                'will_be_available_at' => $willBeAvailableAt,
            ]);

            WalletEntry::create([
                'partner_id' => $link->partner_id,
                'type' => 'commission_pending',
                'amount' => $commissionAmount,
                'related_commission_id' => $commission->id,
            ]);

            // Update link counters and first_eligible_at if needed
            if ($program->lifetime_mode === 'by_period' && $link->first_eligible_at === null && $eligibility['first_eligible_at']) {
                $link->first_eligible_at = $eligibility['first_eligible_at'];
            }

            $link->total_eligible_transactions += 1;
            $link->save();

            return $this->success([
                'transaction_id' => $transaction->id,
                'commission_created' => true,
                'partner_id' => $link->partner_id,
                'commission_amount' => $commissionAmount,
                'status' => $commission->status,
                'will_be_available_at' => $willBeAvailableAt,
            ]);
        });
    }

    private function createOrUpdateTransaction(
        Merchant $merchant,
        Customer $customer,
        ?Product $product,
        ?Category $category,
        ?MerchantProgram $program,
        string $externalTransactionId,
        float $amount,
        Carbon $occurredAt
    ): Transaction {
        /** @var Transaction $transaction */
        $transaction = Transaction::updateOrCreate(
            [
                'merchant_id' => $merchant->id,
                'external_transaction_id' => $externalTransactionId,
            ],
            [
                'customer_id' => $customer->id,
                'product_id' => $product?->id,
                'category_id' => $category?->id,
                'program_id' => $program?->id,
                'amount' => $amount,
                'occurred_at' => $occurredAt,
            ]
        );

        return $transaction;
    }

}

