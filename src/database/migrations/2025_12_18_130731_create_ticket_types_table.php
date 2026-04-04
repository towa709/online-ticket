<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();

            // 公演回との紐づけ
            $table->foreignId('event_schedule_id')
                  ->constrained('event_schedules')
                  ->cascadeOnDelete();

            // チケット種別
            $table->string('type'); // general / student

            // 価格
            $table->unsignedInteger('price');

            // 在庫数
            $table->unsignedInteger('stock');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
