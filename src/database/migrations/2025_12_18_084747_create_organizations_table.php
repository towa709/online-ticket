<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('organizations', function (Blueprint $table) {
      $table->id();

      // 管理者との紐づけ
      $table->foreignId('admin_id')
        ->constrained('admins')
        ->cascadeOnDelete();

      // 基本の劇団名
      $table->string('name');

      // 予約関連
      $table->string('reservation_email')->nullable();
      $table->string('reservation_url')->nullable();

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('organizations');
  }
};
