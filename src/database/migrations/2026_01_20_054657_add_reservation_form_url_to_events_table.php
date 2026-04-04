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
      $table->string('reservation_form_url')->unique()->after('display_organization_name');
    });
  }

  public function down()
  {
    Schema::table('events', function (Blueprint $table) {
      $table->dropColumn('reservation_form_url');
    });
  }
};
