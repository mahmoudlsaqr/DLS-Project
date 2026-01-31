<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('color_codes', function (Blueprint $table) {
            $table->id();
            $table->string("color_code")->unique();
            $table->string("status", 5)->default("true")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('color_codes');
    }
};
