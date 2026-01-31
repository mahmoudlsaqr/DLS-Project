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
        Schema::create('order_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id")->nullable();
            $table->text("notes")->nullable();
            $table->unsignedBigInteger("created_by")->nullable();
            $table->string("file")->nullable();
            $table->timestamps();

            $table->foreign("order_id") ->references("id") ->on("orders") ->onDelete("cascade") ->onUpdate("cascade");
            $table->foreign("created_by") ->references("id") ->on("users") ->onDelete("cascade") ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_notes');
    }
};
