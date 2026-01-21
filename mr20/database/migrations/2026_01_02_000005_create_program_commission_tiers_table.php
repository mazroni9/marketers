<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('program_commission_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('merchant_programs')->cascadeOnDelete();
            $table->unsignedInteger('from_count');
            $table->unsignedInteger('to_count')->nullable(); // null = "higher than"
            $table->enum('commission_type', ['percentage', 'flat'])->default('percentage');
            $table->decimal('commission_value', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_commission_tiers');
    }
};

