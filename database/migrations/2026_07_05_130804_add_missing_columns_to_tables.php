<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix chat_conversations: tambah ticket_id (kalau belum ada)
        if (!Schema::hasColumn('chat_conversations', 'ticket_id')) {
            Schema::table('chat_conversations', function (Blueprint $table) {
                $table->string('ticket_id')->unique()->nullable()->after('id');
            });
        }

        // Ubah status enum chat agar pakai open/closed
        //\DB::statement("ALTER TABLE chat_conversations MODIFY COLUMN status ENUM('open','closed') NOT NULL DEFAULT 'open'");

        // Tambah snap_token ke orders_topup (kalau belum ada)
        if (!Schema::hasColumn('orders_topup', 'snap_token')) {
            Schema::table('orders_topup', function (Blueprint $table) {
                $table->string('snap_token')->nullable()->after('payment_channel');
            });
        }

        // Tambah snap_token ke orders_joki (kalau belum ada)
        if (!Schema::hasColumn('orders_joki', 'snap_token')) {
            Schema::table('orders_joki', function (Blueprint $table) {
                $table->string('snap_token')->nullable()->after('payment_channel');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('chat_conversations', 'ticket_id')) {
            Schema::table('chat_conversations', function (Blueprint $table) {
                $table->dropColumn('ticket_id');
            });
        }
        
    //\DB::statement("ALTER TABLE chat_conversations MODIFY COLUMN status ENUM('unread','replied','closed') NOT NULL DEFAULT 'unread'");

        if (Schema::hasColumn('orders_topup', 'snap_token')) {
            Schema::table('orders_topup', function (Blueprint $table) {
                $table->dropColumn('snap_token');
            });
        }

        if (Schema::hasColumn('orders_joki', 'snap_token')) {
            Schema::table('orders_joki', function (Blueprint $table) {
                $table->dropColumn('snap_token');
            });
        }
    }
};