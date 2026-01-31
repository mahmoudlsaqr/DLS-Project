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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string("case_number")->unique();
            $table->string("patient_name");
            $table->unsignedBigInteger("physician");
            $table->unsignedBigInteger("supervising")->nullable();
            $table->integer("age")->nullable();
            $table->tinyInteger("gender")->nullable();
            $table->tinyInteger("status")->default(1)->nullable();
            $table->text("note");
            $table->string("created_by")->nullable();
            $table->string("updated_by")->nullable();
            $table->timestamps();

            $table ->foreign('physician') ->references('id') ->on('users');
            $table ->foreign('supervising') ->references('id') ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
