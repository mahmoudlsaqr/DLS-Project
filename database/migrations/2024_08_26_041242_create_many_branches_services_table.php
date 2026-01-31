<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('many_branches_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("service_id")->nullable();
            $table->unsignedBigInteger("branch_id")->nullable();

            $table->foreign("service_id")->references("id")->on("services")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign("branch_id")->references("id")->on("branches");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('many_branches_services');
    }
};
