<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('payment_reference')->nullable();
            $table->string('description')->nullable();
            $table->integer('total');
            $table->string('currency');
            $table->enum('order_state', ['processing', 'reject', 'approved']);
            $table->date('expiration')->nullable();
            $table->string('return_url');
            $table->string('request_id')->nullable();
            $table->string('process_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
