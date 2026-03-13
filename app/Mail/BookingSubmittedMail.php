<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class BookingSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Booking Request: '.$this->booking->full_name,
        );
    }

    public function content(): Content
    {
        $submittedAt = $this->booking->created_at ?: now();
        $bookingTime = Carbon::parse($this->booking->time)->format('g:i A');
        $bookingType = $this->booking->booking_type === Booking::TYPE_EVENT
            ? 'Whole Restaurant Event'
            : 'Table Reservation';
        $paymentMethod = $this->booking->payment_method === Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION
            ? 'Card Checkout After Confirmation'
            : 'Pay at Restaurant';

        return new Content(
            view: 'emails.bookings.submitted',
            with: [
                'referenceId' => 'KGH-'.str_pad((string) $this->booking->id, 6, '0', STR_PAD_LEFT),
                'submittedAt' => $submittedAt->format('F j, Y g:i A'),
                'bookingDate' => $this->booking->date->format('F j, Y'),
                'bookingTime' => $bookingTime,
                'bookingType' => $bookingType,
                'paymentMethod' => $paymentMethod,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
