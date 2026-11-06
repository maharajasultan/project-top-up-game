<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('joki_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('joki_type_id')->constrained('joki_types')->onDelete('cascade');
            $table->string('tier_name');
            $table->integer('tier_order')->default(0);
            $table->decimal('price_per_unit', 12, 2);
            $table->integer('min_order')->default(3);
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percent', 'nominal'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->date('discount_start')->nullable();
            $table->date('discount_end')->nullable();
            $table->boolean('is_discount_active')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('joki_tiers'); }
};
