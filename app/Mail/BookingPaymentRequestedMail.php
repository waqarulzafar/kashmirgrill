<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class BookingPaymentRequestedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public string $summaryUrl,
    ) {
        $this->booking->loadMissing('dineInSlot');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Complete Booking Payment: '.$this->booking->formattedReference(),
        );
    }

    public function content(): Content
    {
        $bookingType = Booking::typeLabels()[$this->booking->booking_type] ?? ucfirst($this->booking->booking_type);

        return new Content(
            view: 'emails.bookings.payment-requested',
            with: [
                'referenceId' => $this->booking->formattedReference(),
                'bookingType' => $bookingType,
                'bookingDate' => $this->booking->date?->format('F j, Y'),
                'bookingTime' => Carbon::createFromFormat('H:i:s', $this->booking->time)->format('g:i A'),
                'paymentAmount' => number_format((float) ($this->booking->payment_amount ?? 0), 2),
                'summaryUrl' => $this->summaryUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
