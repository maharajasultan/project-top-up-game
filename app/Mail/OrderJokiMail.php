<?php

namespace App\Mail;

use App\Models\OrderJoki;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderJokiMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(OrderJoki $order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notifikasi Order Joki - ' . $this->order->invoice_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.joki',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
