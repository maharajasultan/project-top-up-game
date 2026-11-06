<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->enum('order_type', ['topup', 'joki']);
            $table->unsignedBigInteger('order_id');
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('payment_channel')->nullable();
            $table->decimal('gross_amount', 12, 2);
            $table->string('transaction_status');
            $table->timestamp('transaction_time')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();
            
            $table->index(['order_type', 'order_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('payments'); }
};
