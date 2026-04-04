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
    Schema::table('event_schedules', function (Blueprint $table) {
      $table->integer('seat_limit')->after('venue');
    });
  }

  public function down(): void
  {
    Schema::table('event_schedules', function (Blueprint $table) {
      $table->dropColumn('seat_limit');
    });
  }
};
