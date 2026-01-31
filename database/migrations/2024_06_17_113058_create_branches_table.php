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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar') ->unique();
            $table->string('name_en') ->unique();
            $table->string('code');
            $table->string('mobile', 10);
            $table->string('phone', 10);
            $table->string('address');
            $table->string('tin',);
            $table->string('com_reg');
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('city_id') ->nullable();
            $table->unsignedBigInteger('district_id') ->nullable();
            $table->string('longitude') ->nullable();
            $table->string('latitude') ->nullable();
            $table->unsignedBigInteger('created_by') ->nullable();
            $table->unsignedBigInteger('updated_by') ->nullable();
            $table->string('status', 6) ->default("true") ->nullable();
            $table->timestamps();

            $table ->foreign('region_id') ->references('id') ->on('regions');
            $table ->foreign('city_id') ->references('id') ->on('cities');
            $table ->foreign('district_id') ->references('id') ->on('districts');
            $table ->foreign('created_by') ->references('id') ->on('users');
            $table ->foreign('updated_by') ->references('id') ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
