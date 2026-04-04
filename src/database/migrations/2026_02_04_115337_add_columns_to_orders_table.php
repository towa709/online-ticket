<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('orders', function (Blueprint $table) {
      // ふりがな
      $table->string('kana')
        ->nullable()
        ->after('name');

      // 支払方法（例: cash）
      $table->string('payment_method')
        ->after('email');
    });
  }

  public function down(): void
  {
    Schema::table('orders', function (Blueprint $table) {
      $table->dropColumn(['kana', 'payment_method']);
    });
  }
};
