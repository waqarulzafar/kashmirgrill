<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $previousStatus,
    ) {}

    public function envelope(): Envelope
    {
        $statusLabel = Order::statusLabels()[$this->order->status] ?? ucfirst($this->order->status);

        return new Envelope(
            subject: 'Order '.$statusLabel.': '.$this->order->reference,
        );
    }

    public function content(): Content
    {
        $statusLabel = Order::statusLabels()[$this->order->status] ?? ucfirst($this->order->status);
        $fulfillmentLabel = Order::fulfillmentLabels()[$this->order->fulfillment_type] ?? ucfirst($this->order->fulfillment_type);
        $paymentStatusLabel = Order::paymentStatusLabels()[$this->order->payment_status] ?? ucfirst($this->order->payment_status);
        $headline = match ($this->order->status) {
            Order::STATUS_CONFIRMED => 'Your order has been accepted by the restaurant.',
            Order::STATUS_PREPARING => 'Your order is now being prepared.',
            Order::STATUS_READY => 'Your order is ready.',
            Order::STATUS_COMPLETED => 'Your order has been completed.',
            Order::STATUS_CANCELLED => 'Your order has been cancelled.',
            default => 'Your order status has changed.',
        };

        return new Content(
            view: 'emails.orders.status-updated',
            with: [
                'headline' => $headline,
                'statusLabel' => $statusLabel,
                'fulfillmentLabel' => $fulfillmentLabel,
                'paymentStatusLabel' => $paymentStatusLabel,
                'reservationDate' => $this->order->reservation_date?->format('F j, Y'),
                'reservationTime' => $this->order->reservation_time
                    ? Carbon::parse($this->order->reservation_time)->format('g:i A')
                    : null,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
