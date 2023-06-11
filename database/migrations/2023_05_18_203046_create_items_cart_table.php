<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('items_cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('amount');
            $table->enum('state', ['in_cart', 'selected', 'in_order']);
            $table->foreignId('order_id')->nullable()->constrained('orders');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items_cart');
    }
};