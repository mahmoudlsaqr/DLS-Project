<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_ar');
            $table->string('name_en');
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('city_id');
            $table ->string('status', 6) ->default('true') ->nullable();
            $table->timestamps();

            $table ->foreign('region_id') ->references('id') ->on('regions');
            $table ->foreign('city_id') ->references('id') ->on('cities');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
