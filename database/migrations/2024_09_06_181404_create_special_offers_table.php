<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('special_offers', function (Blueprint $table) {
            $table->id();
            $table->string("offer_name")->unique();
            $table->unsignedBigInteger("doctor_id")->unique();
            $table->date("start_date");
            $table->date("end_date");
            $table->decimal("discount");

            $table->timestamps();
            $table->foreign('doctor_id')->references('id')->on('doctors');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_offers');
    }
};
