<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('order_tickets', function (Blueprint $table) {
      $table->id();

      // 注文（予約）
      $table->foreignId('order_id')
        ->constrained()
        ->cascadeOnDelete();

      // 券種
      $table->foreignId('ticket_type_id')
        ->constrained()
        ->cascadeOnDelete();

      // 枚数
      $table->unsignedInteger('quantity');

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('order_tickets');
  }
};
