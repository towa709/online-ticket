<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_schedules', function (Blueprint $table) {
            $table->id();

            // 公演（events）との紐づけ
            $table->foreignId('event_id')
                  ->constrained('events')
                  ->cascadeOnDelete();

            // 会場名
            $table->string('venue');

            // 開演日時
            $table->dateTime('start_time');

            // 販売開始・終了
            $table->dateTime('sale_start');
            $table->dateTime('sale_end');

            // 座席数
            $table->unsignedInteger('seat_capacity');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_schedules');
    }
};
