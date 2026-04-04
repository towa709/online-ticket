<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // 注文との紐づけ（1対1）
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete();

            // 支払い方法
            $table->string('payment_method');
            // onsite / bank_transfer

            // 支払い状態
            $table->string('payment_status')->default('unpaid');
            // unpaid / paid

            // 支払日時
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
