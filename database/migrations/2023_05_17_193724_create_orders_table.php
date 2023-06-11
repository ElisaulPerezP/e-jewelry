<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('reference');
            $table->integer('total')->nullable();
            $table->string('currency');
            $table->enum('state', ['pending', 'rejected', 'approved']);
            $table->string('return_url');
            $table->string('request_id')->nullable();
            $table->string('process_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
