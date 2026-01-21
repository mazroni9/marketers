<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('partner_customer_product_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->foreignId('program_id')->constrained('merchant_programs')->cascadeOnDelete();
            $table->dateTime('first_eligible_at')->nullable();
            $table->unsignedInteger('total_eligible_transactions')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['partner_id', 'customer_id', 'product_id', 'category_id', 'program_id'], 'links_search_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_customer_product_links');
    }
};

