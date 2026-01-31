<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('mobile', 10)->nullable();
            $table->string('role')->nullable();
            $table->unsignedBigInteger('supervising')->nullable();
            $table->string('avatar')->nullable();
            $table->string('status', 10)->default('true');
            $table->timestamp('last_active')->nullable()->useCurrent();
            $table->string('ip_address', 45)->nullable();
            $table->boolean('receiving_orders') ->default(0);
            $table->boolean('skipping') ->default(0);
            $table->string('password');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('supervising')->references('id')->on('users');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
