<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained('merchants')->cascadeOnDelete();
            $table->string('external_transaction_id');
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->foreignId('program_id')->nullable()->constrained('merchant_programs')->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->dateTime('occurred_at');
            $table->timestamps();

            $table->unique(['merchant_id', 'external_transaction_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

