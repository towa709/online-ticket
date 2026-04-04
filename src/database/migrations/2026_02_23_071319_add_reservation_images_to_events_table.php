<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up()
  {
    Schema::table('events', function (Blueprint $table) {
      $table->string('reservation_image_1')->nullable()->after('reservation_end_at');
      $table->string('reservation_image_2')->nullable()->after('reservation_image_1');
    });
  }

  public function down()
  {
    Schema::table('events', function (Blueprint $table) {
      $table->dropColumn(['reservation_image_1', 'reservation_image_2']);
    });
  }
};
