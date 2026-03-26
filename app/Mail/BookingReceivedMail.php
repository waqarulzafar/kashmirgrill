<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class BookingReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
        $this->booking->loadMissing('dineInSlot');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Request Received: '.$this->booking->formattedReference(),
        );
    }

    public function content(): Content
    {
        $bookingType = Booking::typeLabels()[$this->booking->booking_type] ?? ucfirst($this->booking->booking_type);
        $paymentMethod = Booking::paymentMethodLabels()[$this->booking->payment_method] ?? ucfirst((string) $this->booking->payment_method);

        return new Content(
            view: 'emails.bookings.received',
            with: [
                'referenceId' => $this->booking->formattedReference(),
                'bookingType' => $bookingType,
                'paymentMethod' => $paymentMethod,
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
