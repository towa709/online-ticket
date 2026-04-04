<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('events', function (Blueprint $table) {
      $table->id();

      // 劇団との紐づけ
      $table->foreignId('organization_id')
        ->constrained('organizations')
        ->cascadeOnDelete();

      // 公演名
      $table->string('title');

      // 公演ごとの劇団名（任意）
      $table->string('display_organization_name')->nullable();

      // 公演説明（任意）
      $table->text('description')->nullable();

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('events');
  }
};
