<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('events', function (Blueprint $table) {
      $table->boolean('allow_cash')->default(true);
      $table->boolean('allow_transfer')->default(false);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('events', function (Blueprint $table) {
      $table->dropColumn(['allow_cash', 'allow_transfer']);
    });
  }
};
