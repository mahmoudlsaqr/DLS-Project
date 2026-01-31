<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('many_work_orders_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("work_order_id")->nullable();
            $table->unsignedBigInteger("invoice_id")->nullable();

            $table->foreign("work_order_id")->references("id")->on("work_orders_management");
            $table->foreign("invoice_id")->references("id")->on("invoices_main");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('many_work_orders_invoices');
    }
};
