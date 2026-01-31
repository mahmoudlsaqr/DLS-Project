<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors_requests', function (Blueprint $table) {
            $table->id();
            $table->string('main_code');
            $table->string('doctor_name');
            $table->string('patient_name');
            $table->string('file_number');
            $table->string('type_case')->nullable();
            $table->string('number_teeth');
            $table->unsignedBigInteger('color_code');
            $table->string('duration_delivery');
            $table->boolean('crowns')->default(false);
            $table->boolean('bridges')->default(false);
            $table->boolean('rpd')->default(false);
            $table->boolean('rcd')->default(false);
            $table->tinyInteger('request_status')->default(1);
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('confirmed_by')->nullable();
            $table->timestamp('confirmed_at')->nullable();

            $table->timestamps();

            $table->foreign('color_code')->references('id')->on('color_codes');
            $table->foreign('confirmed_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors_requests');
    }
};
