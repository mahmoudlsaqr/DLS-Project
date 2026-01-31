<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar') ->unique();
            $table->string('name_en') ->unique();
            $table->bigInteger('tax_number');
            $table->string('commercial_register') ->nullable();
            $table->string('mobile', 10);
            $table->string('phone', 10) ->nullable();
            $table->string('address') ->nullable();
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('city_id') ->nullable();
            $table->unsignedBigInteger('district_id') ->nullable();
            $table->unsignedBigInteger('branch_id') ->nullable();
            $table->decimal('discount') ->nullable();
            $table->string('longitude') ->nullable();
            $table->string('latitude') ->nullable();
            $table->string('commissioner') ->nullable();
            $table->integer('commissioner_number') ->nullable();
            $table->unsignedBigInteger('created_by') ->nullable();
            $table->unsignedBigInteger('updated_by') ->nullable();
            $table->string('status', 6) ->default("true") ->nullable();
            $table->timestamps();

            $table ->foreign('region_id') ->references('id') ->on('regions');
            $table ->foreign('city_id') ->references('id') ->on('cities');
            $table ->foreign('district_id') ->references('id') ->on('districts');
            $table ->foreign('branch_id') ->references('id') ->on('branches');
            $table ->foreign('created_by') ->references('id') ->on('users');
            $table ->foreign('updated_by') ->references('id') ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
