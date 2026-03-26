<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->order->loadMissing(['items', 'dineInSlot']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmed: '.$this->order->reference,
        );
    }

    public function content(): Content
    {
        $fulfillmentLabel = Order::fulfillmentLabels()[$this->order->fulfillment_type] ?? ucfirst($this->order->fulfillment_type);
        $paymentMethod = Order::paymentMethodLabels()[$this->order->payment_method] ?? ucfirst((string) $this->order->payment_method);

        return new Content(
            view: 'emails.orders.placed',
            with: [
                'fulfillmentLabel' => $fulfillmentLabel,
                'paymentMethod' => $paymentMethod,
                'reservationDate' => $this->order->reservation_date?->format('F j, Y'),
                'reservationTime' => $this->order->reservation_time
                    ? Carbon::parse($this->order->reservation_time)->format('g:i A')
                    : null,
                'paymentReference' => $this->order->payment_reference,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
