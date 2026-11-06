<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_conversation_id')->constrained('chat_conversations')->onDelete('cascade');
            $table->enum('sender_type', ['customer', 'admin', 'bot'])->default('customer');
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('chat_messages'); }
};
