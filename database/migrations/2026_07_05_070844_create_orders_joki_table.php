<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders_joki', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('game_id')->constrained('games');
            $table->foreignId('joki_type_id')->constrained('joki_types');
            $table->foreignId('joki_tier_id')->nullable()->constrained('joki_tiers');
            $table->foreignId('login_method_id')->constrained('joki_login_methods');
            $table->string('account_email');
            $table->text('account_password'); // encrypted
            $table->boolean('v2l_status')->default(true);
            $table->integer('qty')->default(1);
            $table->decimal('original_price_per_unit', 12, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->enum('discount_type', ['percent', 'nominal'])->nullable();
            $table->decimal('final_total_price', 12, 2);
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->enum('status', ['pending_payment', 'waiting_process', 'on_process', 'completed', 'expired', 'failed'])->default('pending_payment');
            $table->enum('email_sent_status', ['pending', 'sent', 'failed'])->default('pending');
            $table->foreignId('assigned_admin_id')->nullable()->constrained('admins');
            $table->text('internal_notes')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->string('payment_channel')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('orders_joki'); }
};
