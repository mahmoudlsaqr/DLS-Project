<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_phases', function (Blueprint $table) {
            $table->id();
            $table->string("name_ar")->unique();
            $table->string("name_en")->unique();
            $table->decimal("completion_percent");
            $table->bigInteger("days_number")->nullable();
            $table->string("status", 5)->default("true")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('working_phases');
    }
};
