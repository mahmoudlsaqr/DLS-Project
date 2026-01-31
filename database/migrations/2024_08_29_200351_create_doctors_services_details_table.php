<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors_services_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("doctor_id")->nullable();
            $table->unsignedBigInteger("service_id")->nullable();
            $table->unsignedBigInteger("category_id")->nullable();
            $table->string("service_code");
            $table->decimal("service_price")->default(0);
            $table->decimal("service_discount")->default(0);
            $table->decimal("price")->default(0);
            $table->string("status", 5)->default("true")->nullable();

            $table->timestamps();
            $table->foreign("doctor_id")->references("id")->on("doctors")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("service_id")->references("id")->on("services");
            $table->foreign("category_id")->references("id")->on("services_categories");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors_services_details');
    }
};
