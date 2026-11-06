<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('session_id')->unique();
            $table->enum('status', ['unread', 'replied', 'closed'])->default('unread');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('chat_conversations'); }
};
