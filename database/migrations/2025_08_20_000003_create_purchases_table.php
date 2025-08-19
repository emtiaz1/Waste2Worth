<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('user_email');
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->integer('eco_coin_price');
            $table->integer('total_cost');
            $table->json('delivery_address')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index('user_email');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
