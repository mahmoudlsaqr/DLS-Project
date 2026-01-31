<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_orders_management', function (Blueprint $table) {
            $table->id();
            $table->string("work_order_number")->unique();
            $table->date("work_order_date");
            $table->unsignedBigInteger("branch_id");
            $table->unsignedBigInteger("doctor_id");
            $table->unsignedBigInteger("client_id")->nullable();
            $table->unsignedBigInteger("color_id");
            $table->unsignedBigInteger("service_id");
            $table->integer("service_count");
            $table->decimal("service_price");
            $table->decimal("services_price");
            $table->decimal("service_discount")->default(0);
            $table->decimal("service_discount_value");
            $table->decimal("services_discount_value");
            $table->decimal("service_total_net");
            $table->decimal("service_tax")->default(0);
            $table->decimal("service_tax_value");
            $table->decimal("service_total");
            $table->string("patient_name");
            $table->string("statement");
            $table->string("file_number");
            $table->unsignedBigInteger("phase_id")->default(1);
            $table->text("note")->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string("status", 5)->default("true")->nullable();
            $table->timestamps();

            $table->foreign("branch_id")->references("id")->on("branches");
            $table->foreign("doctor_id")->references("id")->on("doctors");
            $table->foreign("client_id")->references("id")->on("clients");
            $table->foreign("color_id")->references("id")->on("color_codes");
            $table->foreign("service_id")->references("id")->on("services");
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign("phase_id")->references("id")->on("working_phases");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders_management');
    }
};
