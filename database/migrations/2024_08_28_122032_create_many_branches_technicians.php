<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('many_branches_technicians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("technician_id")->nullable();
            $table->unsignedBigInteger("branch_id")->nullable();

            $table->foreign("technician_id")->references("id")->on("technicians")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign("branch_id")->references("id")->on("branches");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('many_branches_technicians');
    }
};
