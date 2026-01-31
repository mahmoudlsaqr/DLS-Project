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
        Schema::create('case_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("case_id")->nullable();
            $table->tinyInteger("type");
            $table->text("file");
            $table->string("description", 255)->nullable();
             $table->unsignedBigInteger("filesize")->nullable();
            $table->timestamps();

            $table->foreign('case_id')->references('id') ->on("cases") ->onDelete("cascade") ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_attachments');
    }
};
