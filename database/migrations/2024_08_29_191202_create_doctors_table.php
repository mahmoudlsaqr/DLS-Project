<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string("name_ar")->unique();
            $table->string("name_en")->unique();
            $table->unsignedBigInteger("client_id")->nullable();
            $table->unsignedBigInteger("nationality_id")->nullable();
            $table->string('email')->unique();
            $table->string('mobile', 10);
            $table->unsignedBigInteger("branch_id")->nullable();
            $table->text("note")->nullable();
            $table->unsignedBigInteger('created_by') ->nullable();
            $table->unsignedBigInteger('updated_by') ->nullable();

            $table->string("status", 5)->default("true")->nullable();
            $table->timestamps();

            $table->foreign("branch_id")->references("id")->on("branches");
            $table->foreign("client_id")->references("id")->on("clients");
            $table->foreign("nationality_id")->references("id")->on("nationalities");
            $table->foreign('created_by') ->references('id') ->on('users');
            $table->foreign('updated_by') ->references('id') ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
