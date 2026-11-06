<?php

namespace App\Jobs;

use App\Models\OrderTopup;
use App\Models\OrderJoki;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExpireOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = now();

        // Expire Order Topup
        $expiredTopups = OrderTopup::where('status', 'pending')
            ->where('expired_at', '<=', $now)
            ->update(['status' => 'expired']);

        if ($expiredTopups > 0) {
            Log::info("Expired {$expiredTopups} Top Up orders.");
        }

        // Expire Order Joki
        $expiredJokis = OrderJoki::where('status', 'pending_payment')
            ->where('expired_at', '<=', $now)
            ->update(['status' => 'expired']);

        if ($expiredJokis > 0) {
            Log::info("Expired {$expiredJokis} Joki orders.");
        }
    }
}
