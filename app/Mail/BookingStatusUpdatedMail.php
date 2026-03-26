<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class BookingStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public string $previousStatus,
        public string $previousPaymentStatus,
    ) {}

    public function envelope(): Envelope
    {
        $statusLabel = Booking::statusLabels()[$this->booking->status] ?? ucfirst($this->booking->status);

        return new Envelope(
            subject: 'Booking '.$statusLabel.': '.$this->booking->formattedReference(),
        );
    }

    public function content(): Content
    {
        $bookingType = Booking::typeLabels()[$this->booking->booking_type] ?? ucfirst($this->booking->booking_type);
        $paymentMethod = Booking::paymentMethodLabels()[$this->booking->payment_method] ?? ucfirst((string) $this->booking->payment_method);
        $statusLabel = Booking::statusLabels()[$this->booking->status] ?? ucfirst($this->booking->status);
        $paymentStatusLabel = Booking::paymentStatusLabels()[$this->booking->payment_status] ?? ucfirst((string) $this->booking->payment_status);
        $headline = match ($this->booking->status) {
            Booking::STATUS_CONFIRMED => 'Your booking has been confirmed.',
            Booking::STATUS_CANCELLED => 'Your booking has been cancelled.',
            default => 'Your booking status has been updated.',
        };

        return new Content(
            view: 'emails.bookings.status-updated',
            with: [
                'headline' => $headline,
                'referenceId' => $this->booking->formattedReference(),
                'bookingType' => $bookingType,
                'statusLabel' => $statusLabel,
                'paymentMethod' => $paymentMethod,
                'paymentStatusLabel' => $paymentStatusLabel,
                'bookingDate' => $this->booking->date?->format('F j, Y'),
                'bookingTime' => Carbon::parse($this->booking->time)->format('g:i A'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
