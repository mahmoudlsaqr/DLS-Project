<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors_requests_archives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->string('filename');
            $table->string('size');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('doctors_requests')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnUpdate();
            $table->foreign('updated_by')->references('id')->on('users')->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors_requests_archives');
    }
};
