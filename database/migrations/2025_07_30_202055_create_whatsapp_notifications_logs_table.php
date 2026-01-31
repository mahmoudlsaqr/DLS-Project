<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_notifications_logs', function (Blueprint $table) {
            $table->id();
            $table->string('mobile');
            $table->text('message');
            $table->boolean('success')->default(false);
            $table->json('response')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('work_order_id')->nullable();
            $table->unsignedBigInteger('phase_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('work_order_id')->references('id')->on('work_orders_management')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('phase_id')->references('id')->on('working_phases')->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['type', 'user_id', 'work_order_id', 'phase_id'], 'unique_whatsapp_log');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_notifications_logs');
    }
};
