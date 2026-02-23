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

    public function __construct(public Booking $booking)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Booking Request: ' . $this->booking->full_name,
        );
    }

    public function content(): Content
    {
        $submittedAt = $this->booking->created_at ?: now();

        $bookingTime = Carbon::parse($this->booking->time)->format('g:i A');

        return new Content(
            view: 'emails.bookings.submitted',
            with: [
                'referenceId' => 'KGH-' . str_pad((string) $this->booking->id, 6, '0', STR_PAD_LEFT),
                'submittedAt' => $submittedAt->format('F j, Y g:i A'),
                'bookingDate' => $this->booking->date->format('F j, Y'),
                'bookingTime' => $bookingTime,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
