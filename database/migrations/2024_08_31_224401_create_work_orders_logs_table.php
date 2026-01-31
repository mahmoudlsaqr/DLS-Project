<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_orders_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_order_id');
            $table->date('log_date');
            $table->time('log_time');
            $table->decimal('number_of_times_of_delay')->default(0);
            $table->unsignedBigInteger('phase_id')->default(1);
            $table->unsignedBigInteger('user_id');

            $table->timestamps();
            $table->foreign('work_order_id')->references('id')->on('work_orders_management');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign("phase_id")->references("id")->on("working_phases");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders_logs');
    }
};
