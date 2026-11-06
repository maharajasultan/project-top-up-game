<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('order_type', ['topup', 'joki']);
            $table->unsignedBigInteger('order_id');
            $table->string('recipient_email');
            $table->enum('status', ['sent', 'failed']);
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index(['order_type', 'order_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('notification_logs'); }
};
