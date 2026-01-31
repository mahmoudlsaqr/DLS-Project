<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nationalities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Numeric_3', 10);
            $table->string('Alpha_2', 10);
            $table->string('Alpha_3', 10);
            $table->string('Nat_Ar', 64);
            $table->string('Nat_En', 64);
            $table->string('Country_Ar', 64);
            $table->string('Country_EN', 64);
            $table ->string('status', 6) ->default('true') ->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nationalities');
    }
};
