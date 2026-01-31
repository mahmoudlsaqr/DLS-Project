<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technicians', function (Blueprint $table) {
            $table->id();
            $table->string("name_ar")->unique();
            $table->string("name_en")->unique();
            $table->unsignedBigInteger("section_id")->nullable();
            $table->unsignedBigInteger("nationality_id")->nullable();
            $table->string('email')->unique();
            $table->string('country_code', 5)->nullable();
            $table->string('mobile', 10);
            $table->bigInteger('monthly_target')->nullable();
            $table->text("branches")->nullable();
            $table->text("phases")->nullable();
            $table->text("note")->nullable();
            $table->unsignedBigInteger('user_id') ->nullable();
            $table->unsignedBigInteger('created_by') ->nullable();
            $table->unsignedBigInteger('updated_by') ->nullable();
            $table->string("status", 5)->default("true")->nullable();
            $table->timestamps();
            $table->foreign("section_id")->references("id")->on("sections");
            $table->foreign("nationality_id")->references("id")->on("nationalities");
            $table->foreign('created_by') ->references('id') ->on('users');
            $table->foreign('updated_by') ->references('id') ->on('users');
            $table->foreign('user_id') ->references('id') ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technicians');
    }
};
