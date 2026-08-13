<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlacedAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🛍  Nova narudžba — ' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.order-placed-admin',
            with: ['order' => $this->order->fresh('items')],
        );
    }
}
