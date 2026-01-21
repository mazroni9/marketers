<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sector')->nullable(); // cars, pharmacy, general, …
            $table->enum('default_commission_model', ['percentage', 'flat'])->default('percentage');
            $table->decimal('default_commission_value', 10, 2)->default(0);
            $table->unsignedInteger('default_payout_delay_days')->default(7);
            $table->string('api_key')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};

