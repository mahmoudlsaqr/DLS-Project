<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string("name_ar")->unique();
            $table->string("name_en")->unique();
            $table->string("service_code")->unique();
            $table->unsignedBigInteger("category_id")->nullable();
            $table->decimal("service_cost")->nullable();
            $table->decimal("service_price")->nullable();
            $table->decimal("tax_percent")->nullable();
            $table->decimal("tax_value")->nullable();
            $table->decimal("service_price_with_tax")->nullable();
            $table->string("branches")->nullable();
            $table->text("note")->nullable();
            $table->string("status", 5)->default("true")->nullable();

            $table->timestamps();
            $table->foreign("category_id")->references("id")->on("services_categories");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
