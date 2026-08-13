<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlacedCustomer extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function envelope(): Envelope
    {
        $brand = Setting::get('brand_name', 'SinonimDesign');

        return new Envelope(
            subject: $brand . ' — potvrda narudžbe ' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.order-placed-customer',
            with: [
                'order' => $this->order->fresh('items'),
                'brand' => Setting::get('brand_name', 'SinonimDesign'),
                'contactEmail' => Setting::get('contact_email'),
                'whatsapp' => Setting::get('whatsapp_number'),
            ],
        );
    }
}
