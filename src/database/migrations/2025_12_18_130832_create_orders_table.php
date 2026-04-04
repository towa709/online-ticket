<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // 公演回との紐づけ
            $table->foreignId('event_schedule_id')
                  ->constrained('event_schedules')
                  ->cascadeOnDelete();

            // 購入者情報
            $table->string('name');
            $table->string('email');

            // 購入枚数
            $table->unsignedInteger('ticket_quantity');

            // 備考欄
            $table->text('notes')->nullable();

            // 状態（購入・キャンセル）
            $table->string('status')->default('purchased');
            // purchased / canceled

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
