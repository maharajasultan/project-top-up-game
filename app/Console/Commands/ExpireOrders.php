<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderTopup;
use App\Models\OrderJoki;

class ExpireOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically expire pending orders that have passed their expired_at time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        $expiredTopupCount = OrderTopup::where('status', 'pending')
            ->where('expired_at', '<', $now)
            ->update(['status' => 'expired']);

        $expiredJokiCount = OrderJoki::where('status', 'pending_payment')
            ->where('expired_at', '<', $now)
            ->update(['status' => 'expired']);

        $total = $expiredTopupCount + $expiredJokiCount;
        
        if ($total > 0) {
            $this->info("Successfully expired {$expiredTopupCount} Top Up orders and {$expiredJokiCount} Joki orders.");
        } else {
            $this->info("No pending orders to expire.");
        }
    }
}
