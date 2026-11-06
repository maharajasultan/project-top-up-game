<?php

namespace App\Mail;

use App\Models\OrderTopup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderTopupMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(OrderTopup $order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notifikasi Order Top Up - ' . $this->order->invoice_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.topup',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
