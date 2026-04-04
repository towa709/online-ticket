<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('organization_names', function (Blueprint $table) {
            $table->id();

            // 紐づく劇団
            $table->foreignId('organization_id')
                  ->constrained('organizations')
                  ->cascadeOnDelete();

            // 劇団名
            $table->string('name');

            // 現在使用中かどうか
            $table->boolean('is_current')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_names');
    }
};
