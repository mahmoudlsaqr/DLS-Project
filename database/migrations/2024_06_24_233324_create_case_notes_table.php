<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("case_id")->nullable();
            $table->text("notes")->nullable();
            $table->unsignedBigInteger("created_by")->nullable();
            $table->string("file")->nullable();
            $table->timestamps();

            $table->foreign("case_id") ->references("id") ->on("cases") ->onDelete("cascade") ->onUpdate("cascade");
            $table->foreign("created_by") ->references("id") ->on("users") ->onDelete("cascade") ->onUpdate("cascade");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_notes');
    }
};
