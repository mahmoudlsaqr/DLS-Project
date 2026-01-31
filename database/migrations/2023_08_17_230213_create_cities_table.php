<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table ->bigIncrements('id');
            $table ->string('name_ar');
            $table ->string('name_en');
            $table ->unsignedBigInteger('region_id');
            $table ->string('status', 6) ->default('true') ->nullable();
            $table->timestamps();

            $table ->foreign('region_id') ->references('id') ->on('regions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
