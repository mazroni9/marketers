<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('merchant_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained('merchants')->cascadeOnDelete();
            $table->string('name');
            $table->enum('commission_type', ['percentage', 'flat'])->default('percentage');
            $table->decimal('commission_value', 10, 2);
            $table->enum('lifetime_mode', ['lifetime', 'by_count', 'by_period'])->default('lifetime');
            $table->unsignedInteger('lifetime_count_limit')->nullable();
            $table->unsignedInteger('lifetime_period_days')->nullable();
            $table->enum('attribution_model', ['first_click', 'last_click'])->default('first_click');
            $table->enum('scope', ['product', 'category'])->default('product');
            $table->text('terms_summary')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('merchant_programs');
    }
};

