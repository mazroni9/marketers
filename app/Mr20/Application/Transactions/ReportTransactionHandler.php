<?php

namespace App\Mr20\Application\Transactions;

use App\Mr20\Application\Services\CommissionCalculator;
use App\Mr20\Application\Services\LifetimeRulesEngine;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\CategoryEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\CommissionEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\CustomerEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\LinkEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\MerchantEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\ProductEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\ProgramEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\TransactionEloquentRepository;
use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\WalletEntryEloquentRepository;
use App\Mr20\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportTransactionHandler
{
    public function __construct(
        protected MerchantEloquentRepository $merchantRepository,
        protected CustomerEloquentRepository $customerRepository,
        protected ProductEloquentRepository $productRepository,
        protected CategoryEloquentRepository $categoryRepository,
        protected ProgramEloquentRepository $programRepository,
        protected TransactionEloquentRepository $transactionRepository,
        protected LinkEloquentRepository $linkRepository,
        protected CommissionEloquentRepository $commissionRepository,
        protected WalletEntryEloquentRepository $walletEntryRepository,
        protected LifetimeRulesEngine $lifetimeRulesEngine,
        protected CommissionCalculator $commissionCalculator
    ) {
    }

    public function handle(Merchant $merchant, array $data, Carbon $occurredAt): array
    {
        return DB::transaction(function () use ($merchant, $data, $occurredAt) {
            // 1) Ensure customer
            $customer = $this->customerRepository->updateOrCreate(
                [
                    'merchant_id' => $merchant->id,
                    'external_customer_id' => $data['external_customer_id'],
                ],
                []
            );

            // 2) Determine / ensure product or category
            $product = null;
            $category = null;

            // Program resolution (may depend on scope)
            $program = null;
            if (!empty($data['program_id'])) {
                $program = $this->programRepository->findById($data['program_id']);
                if ($program && ($program->merchant_id !== $merchant->id || $program->status !== 'active')) {
                    $program = null;
                }
            }

            if (!$program) {
                $programs = $this->programRepository->findActiveByMerchantId($merchant->id);
                $program = $programs->first();
            }

            if (!$program) {
                // No commission program found for this merchant
                $transaction = $this->createOrUpdateTransaction(
                    $merchant,
                    $customer,
                    null,
                    null,
                    null,
                    $data['external_transaction_id'],
                    $data['amount'],
                    $occurredAt
                );

                return [
                    'transaction_id' => $transaction->id,
                    'commission_created' => false,
                    'reason' => 'program_not_found',
                ];
            }

            if ($program->scope === 'product') {
                $product = $this->productRepository->firstOrCreate(
                    [
                        'merchant_id' => $merchant->id,
                        'external_product_code' => $data['external_product_code'],
                    ],
                    [
                        'name' => $data['external_product_code'],
                    ]
                );
            } else { // scope === category
                if (empty($data['external_category_code'])) {
                    $transaction = $this->createOrUpdateTransaction(
                        $merchant,
                        $customer,
                        null,
                        null,
                        $program,
                        $data['external_transaction_id'],
                        $data['amount'],
                        $occurredAt
                    );

                    return [
                        'transaction_id' => $transaction->id,
                        'commission_created' => false,
                        'reason' => 'external_category_code_required',
                    ];
                }

                $category = $this->categoryRepository->firstOrCreate(
                    [
                        'merchant_id' => $merchant->id,
                        'external_category_code' => $data['external_category_code'],
                    ],
                    [
                        'name' => $data['external_category_code'],
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
                $data['external_transaction_id'],
                $data['amount'],
                $occurredAt
            );

            // 4) Find appropriate Link according to attribution model
            $link = $this->findLink($customer, $program, $product, $category);

            if (!$link) {
                return [
                    'transaction_id' => $transaction->id,
                    'commission_created' => false,
                    'reason' => 'no_link_found',
                ];
            }

            // 5) Determine eligibility based on lifetime_mode via LifetimeRulesEngine
            $eligibility = $this->lifetimeRulesEngine->evaluateEligibility($program, $link, $occurredAt);
            if (!$eligibility['eligible']) {
                return [
                    'transaction_id' => $transaction->id,
                    'commission_created' => false,
                    'reason' => $eligibility['reason'] ?: 'not_eligible',
                ];
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
                return [
                    'transaction_id' => $transaction->id,
                    'commission_created' => false,
                    'reason' => 'commission_amount_zero',
                ];
            }

            // 7) Create Commission + WalletEntry, update Link counters
            $willBeAvailableAt = $occurredAt->copy()->addDays($merchant->default_payout_delay_days);

            $commission = $this->commissionRepository->create([
                'partner_id' => $link->partner_id,
                'transaction_id' => $transaction->id,
                'program_id' => $program->id,
                'commission_amount' => $commissionAmount,
                'status' => 'pending',
                'will_be_available_at' => $willBeAvailableAt,
            ]);

            $this->walletEntryRepository->create([
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
            $this->linkRepository->save($link);

            return [
                'transaction_id' => $transaction->id,
                'commission_created' => true,
                'partner_id' => $link->partner_id,
                'commission_amount' => $commissionAmount,
                'status' => $commission->status,
                'will_be_available_at' => $willBeAvailableAt,
            ];
        });
    }

    private function findLink($customer, $program, $product, $category)
    {
        // Build query using repository methods
        // Since we need complex querying, we'll use the model through repository pattern
        // In a more pure DDD approach, we'd add a method to LinkRepository for this
        
        $linkQuery = \App\Mr20\Models\PartnerCustomerProductLink::query()
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

        return $linkQuery->first();
    }

    private function createOrUpdateTransaction(
        Merchant $merchant,
        $customer,
        $product,
        $category,
        $program,
        string $externalTransactionId,
        float $amount,
        Carbon $occurredAt
    ) {
        // Use repository updateOrCreate pattern
        // Since TransactionRepository doesn't have updateOrCreate, we'll add it or use model directly
        // For now, using model directly to maintain functionality
        $transaction = \App\Mr20\Models\Transaction::updateOrCreate(
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
