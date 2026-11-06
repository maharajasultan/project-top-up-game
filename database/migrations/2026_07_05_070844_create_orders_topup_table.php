<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders_topup', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('game_id')->constrained('games');
            $table->foreignId('product_id')->constrained('topup_products');
            $table->string('user_game_id');
            $table->string('server_id')->nullable();
            $table->string('username_result')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->decimal('original_price', 12, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->enum('discount_type', ['percent', 'nominal'])->nullable();
            $table->decimal('final_price', 12, 2);
            $table->enum('status', ['pending', 'success', 'expired', 'failed'])->default('pending');
            $table->enum('email_sent_status', ['pending', 'sent', 'failed'])->default('pending');
            $table->string('midtrans_order_id')->nullable();
            $table->string('payment_channel')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('orders_topup'); }
};
