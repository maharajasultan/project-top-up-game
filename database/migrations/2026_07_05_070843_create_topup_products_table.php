<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('topup_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->string('name');
            $table->string('type')->default('diamond'); // diamond, uc, membership, pass
            $table->decimal('price', 12, 2);
            $table->decimal('modal_price', 12, 2)->nullable();
            $table->decimal('original_price', 12, 2)->nullable();
            $table->enum('discount_type', ['percent', 'nominal'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->date('discount_start')->nullable();
            $table->date('discount_end')->nullable();
            $table->boolean('is_discount_active')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('topup_products'); }
};
