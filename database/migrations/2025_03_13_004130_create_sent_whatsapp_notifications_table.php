<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sent_whatsapp_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_order_id');
            $table->unsignedBigInteger('phase_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sent_whatsapp_notifications');
    }
};
