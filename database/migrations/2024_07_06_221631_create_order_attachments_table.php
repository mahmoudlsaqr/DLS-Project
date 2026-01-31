<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id")->nullable();
            $table->tinyInteger("type");
            $table->text("file");
            $table->string("description", 255)->nullable();
            $table->unsignedBigInteger("filesize")->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id') ->on("orders") ->onDelete("cascade") ->onUpdate("cascade");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_attachments');
    }
};
