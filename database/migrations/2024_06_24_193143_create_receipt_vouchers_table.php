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
        Schema::create('receipt_vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("case_id")->nullable();
            $table->string("clinic_name")->nullable();
            $table->decimal("amount")->nullable();
            $table->text("description")->nullable();
            $table->string("file")->nullable();
            $table->tinyInteger("status")->default(1)->nullable();
            $table->unsignedBigInteger("created_by")->nullable();
            $table->unsignedBigInteger("updated_by")->nullable();
            $table->timestamps();

            $table->foreign("case_id") ->references("id") ->on("cases");
            $table->foreign("created_by") ->references("id") ->on("users");
            $table->foreign("updated_by") ->references("id") ->on("users");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_vouchers');
    }
};
