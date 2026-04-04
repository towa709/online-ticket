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
      $table->dateTime('reservation_start_at')
        ->nullable()
        ->after('reservation_form_url');

      $table->dateTime('reservation_end_at')
        ->nullable()
        ->after('reservation_start_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('events', function (Blueprint $table) {
      $table->dropColumn([
        'reservation_start_at',
        'reservation_end_at',
      ]);
    });
  }
};
