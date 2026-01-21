<?php

namespace Tests\Feature\Mr20;

use App\Mr20\Models\Commission;
use App\Mr20\Models\Customer;
use App\Mr20\Models\Merchant;
use App\Mr20\Models\MerchantProgram;
use App\Mr20\Models\Partner;
use App\Mr20\Models\PartnerCustomerProductLink;
use App\Mr20\Models\Product;
use App\Mr20\Models\ProgramCommissionTier;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // تأكد من تحميل Migrations و Service Provider الخاص بـ MR20 قبل تشغيل هذه الاختبارات في تطبيق Laravel حقيقي.
    }

    protected function createMerchantWithProgram(array $programOverrides = []): array
    {
        $merchant = Merchant::factory()->create([
            'default_commission_model' => 'percentage',
            'default_commission_value' => 20,
            'default_payout_delay_days' => 7,
        ]);

        $program = MerchantProgram::create(array_merge([
            'merchant_id' => $merchant->id,
            'name' => 'Test Program',
            'commission_type' => 'percentage',
            'commission_value' => 20,
            'lifetime_mode' => 'lifetime',
            'lifetime_count_limit' => null,
            'lifetime_period_days' => null,
            'attribution_model' => 'first_click',
            'scope' => 'product',
            'terms_summary' => null,
            'status' => 'active',
        ], $programOverrides));

        return [$merchant, $program];
    }

    public function test_lifetime_program_creates_commission_for_every_transaction()
    {
        [$merchant, $program] = $this->createMerchantWithProgram([
            'lifetime_mode' => 'lifetime',
        ]);

        $partner = Partner::factory()->create();
        $customer = Customer::create([
            'merchant_id' => $merchant->id,
            'external_customer_id' => 'CUST-1',
        ]);
        $product = Product::create([
            'merchant_id' => $merchant->id,
            'external_product_code' => 'PRD-1',
            'name' => 'Test Product',
        ]);

        $link = PartnerCustomerProductLink::create([
            'partner_id' => $partner->id,
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'category_id' => null,
            'program_id' => $program->id,
            'first_eligible_at' => null,
            'total_eligible_transactions' => 0,
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/v1/transactions/report', [
            'external_transaction_id' => 'TX-1',
            'external_customer_id' => 'CUST-1',
            'external_product_code' => 'PRD-1',
            'amount' => 1000,
            'occurred_at' => Carbon::now()->toISOString(),
            'program_id' => $program->id,
        ], [
            'X-API-KEY' => $merchant->api_key,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.commission_created', true);

        $this->assertDatabaseHas('commissions', [
            'partner_id' => $partner->id,
            'program_id' => $program->id,
        ]);

        $link->refresh();
        $this->assertEquals(1, $link->total_eligible_transactions);
    }

    public function test_by_count_program_stops_after_limit()
    {
        [$merchant, $program] = $this->createMerchantWithProgram([
            'lifetime_mode' => 'by_count',
            'lifetime_count_limit' => 1,
        ]);

        $partner = Partner::factory()->create();
        $customer = Customer::create([
            'merchant_id' => $merchant->id,
            'external_customer_id' => 'CUST-2',
        ]);
        $product = Product::create([
            'merchant_id' => $merchant->id,
            'external_product_code' => 'PRD-2',
            'name' => 'Test Product',
        ]);

        $link = PartnerCustomerProductLink::create([
            'partner_id' => $partner->id,
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'category_id' => null,
            'program_id' => $program->id,
            'first_eligible_at' => null,
            'total_eligible_transactions' => 1,
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/v1/transactions/report', [
            'external_transaction_id' => 'TX-2',
            'external_customer_id' => 'CUST-2',
            'external_product_code' => 'PRD-2',
            'amount' => 1000,
            'occurred_at' => Carbon::now()->toISOString(),
            'program_id' => $program->id,
        ], [
            'X-API-KEY' => $merchant->api_key,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.commission_created', false)
            ->assertJsonPath('data.reason', 'count_limit_reached');
    }

    public function test_by_period_program_expires_after_period()
    {
        [$merchant, $program] = $this->createMerchantWithProgram([
            'lifetime_mode' => 'by_period',
            'lifetime_period_days' => 365,
        ]);

        $partner = Partner::factory()->create();
        $customer = Customer::create([
            'merchant_id' => $merchant->id,
            'external_customer_id' => 'CUST-3',
        ]);
        $product = Product::create([
            'merchant_id' => $merchant->id,
            'external_product_code' => 'PRD-3',
            'name' => 'Test Product',
        ]);

        $firstEligible = Carbon::now()->subDays(400);

        $link = PartnerCustomerProductLink::create([
            'partner_id' => $partner->id,
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'category_id' => null,
            'program_id' => $program->id,
            'first_eligible_at' => $firstEligible,
            'total_eligible_transactions' => 10,
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/v1/transactions/report', [
            'external_transaction_id' => 'TX-3',
            'external_customer_id' => 'CUST-3',
            'external_product_code' => 'PRD-3',
            'amount' => 1000,
            'occurred_at' => Carbon::now()->toISOString(),
            'program_id' => $program->id,
        ], [
            'X-API-KEY' => $merchant->api_key,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.commission_created', false)
            ->assertJsonPath('data.reason', 'lifetime_period_expired');
    }

    public function test_tiered_program_uses_correct_commission_value()
    {
        [$merchant, $program] = $this->createMerchantWithProgram([
            'lifetime_mode' => 'by_count',
            'lifetime_count_limit' => 200,
        ]);

        $partner = Partner::factory()->create();
        $customer = Customer::create([
            'merchant_id' => $merchant->id,
            'external_customer_id' => 'CUST-4',
        ]);
        $product = Product::create([
            'merchant_id' => $merchant->id,
            'external_product_code' => 'PRD-4',
            'name' => 'Test Product',
        ]);

        // Define tiers: 1-10 => 20%, 11-50 => 15%, 51+ => 10%
        ProgramCommissionTier::create([
            'program_id' => $program->id,
            'from_count' => 1,
            'to_count' => 10,
            'commission_type' => 'percentage',
            'commission_value' => 20,
        ]);

        ProgramCommissionTier::create([
            'program_id' => $program->id,
            'from_count' => 11,
            'to_count' => 50,
            'commission_type' => 'percentage',
            'commission_value' => 15,
        ]);

        $link = PartnerCustomerProductLink::create([
            'partner_id' => $partner->id,
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'category_id' => null,
            'program_id' => $program->id,
            'first_eligible_at' => null,
            'total_eligible_transactions' => 10, // next_tx_number = 11 → tier 11-50
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/v1/transactions/report', [
            'external_transaction_id' => 'TX-4',
            'external_customer_id' => 'CUST-4',
            'external_product_code' => 'PRD-4',
            'amount' => 1000,
            'occurred_at' => Carbon::now()->toISOString(),
            'program_id' => $program->id,
        ], [
            'X-API-KEY' => $merchant->api_key,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.commission_created', true)
            ->assertJsonPath('data.commission_amount', 150.00); // 15% من 1000
    }
}

