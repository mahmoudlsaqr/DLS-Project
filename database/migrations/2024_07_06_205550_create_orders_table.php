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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("patient_name");
            $table->unsignedBigInteger("designer")->nullable();
            $table->integer("age")->nullable();
            $table->tinyInteger("gender")->nullable();
            $table->text("required_in_case")->nullable();
            $table->text("treatment_plan")->nullable();
            $table->text("patient_expectations")->nullable();
            $table->integer("status")->default(1)->nullable();
            $table->unsignedBigInteger("accepted_by")->nullable();
            $table->timestamp("accepted_at")->nullable();
            $table->unsignedBigInteger("rejected_by")->nullable();
            $table->timestamp("rejected_at")->nullable();
            $table->unsignedBigInteger("created_by")->nullable();
            $table->unsignedBigInteger("updated_by")->nullable();
            $table->timestamps();

            $table ->foreign("designer")->references("id")->on("users");
            $table ->foreign("accepted_by")->references("id")->on("users");
            $table ->foreign("rejected_by")->references("id")->on("users");
            $table ->foreign("created_by")->references("id")->on("users");
            $table ->foreign("updated_by")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
