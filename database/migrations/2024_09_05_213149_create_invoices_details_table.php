<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string("file_number");
            $table->string("patient_name");
            $table->unsignedBigInteger('work_order_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('color_id');
            $table->integer('service_count');
            $table->decimal('service_price');
            $table->decimal('service_discount');
            $table->decimal('service_discount_value');
            $table->decimal('service_total_net');
            $table->decimal('service_tax');
            $table->decimal('service_tax_value');
            $table->decimal('service_total');

            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('invoices_main');
            $table->foreign('work_order_id')->references('id')->on('work_orders_management');
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('color_id')->references('id')->on('color_codes');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices_details');
    }
};
