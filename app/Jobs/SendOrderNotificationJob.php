<?php

namespace App\Jobs;

use App\Models\OrderTopup;
use App\Models\OrderJoki;
use App\Models\NotificationLog;
use App\Mail\OrderTopupMail;
use App\Mail\OrderJokiMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $orderType;

    public function __construct($order, $orderType)
    {
        $this->order = $order;
        $this->orderType = $orderType;
    }

    public function handle(): void
    {
        try {
            $email = $this->orderType === 'topup' ? $this->order->email : $this->order->customer_email;
            
            if ($this->orderType === 'topup') {
                Mail::to($email)->send(new OrderTopupMail($this->order));
            } else {
                Mail::to($email)->send(new OrderJokiMail($this->order));
            }
            
            $this->order->update(['email_sent_status' => 'sent']);
            
            NotificationLog::create([
                'order_type' => $this->orderType,
                'order_id' => $this->order->id,
                'status' => 'sent',
                'error_message' => null
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send email notification: ' . $e->getMessage());
            $this->order->update(['email_sent_status' => 'failed']);
            NotificationLog::create([
                'order_type' => $this->orderType,
                'order_id' => $this->order->id,
                'status' => 'failed',
                'error_message' => substr($e->getMessage(), 0, 255)
            ]);
        }
    }
}
