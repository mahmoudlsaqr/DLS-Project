<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->tinyInteger('payment_type')->nullable();
            $table->decimal('amount')->nullable();
            $table->string('reference')->nullable();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('invoices_main');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices_payments');
    }
};
