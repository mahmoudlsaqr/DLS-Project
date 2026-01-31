<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('many_users_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger("branch_id")->nullable();

            $table->foreign("user_id")->references("id")->on("users")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign("branch_id")->references("id")->on("branches");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('many_users_branches');
    }
};
