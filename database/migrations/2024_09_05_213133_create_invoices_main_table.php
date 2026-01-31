<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices_main', function (Blueprint $table) {
            $table->id();
            $table->text("qr_code");
            $table->date("invoice_date");
            $table->time("invoice_time");
            $table->unsignedBigInteger("branch_id");
            $table->unsignedBigInteger("client_id");
            $table->unsignedBigInteger("doctor_id");
            $table->integer("total_count");
            $table->decimal("total_price");
            $table->decimal("total_discount_value");
            $table->decimal("additional_discount");
            $table->decimal("total_total_net");
            $table->decimal("total_tax_value");
            $table->decimal("total_total");
            $table->decimal("total_paid");
            $table->decimal("total_residual");
            $table->date("start_date_issue")->nullable();
            $table->date("end_date_issue")->nullable();
            $table->unsignedBigInteger("created_by");

            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices_main');
    }
};
