<?php

namespace App\Http\Controllers;

use App\Mail\BookingSubmittedMail;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(): View
    {
        return view('pages.book-now');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'website' => ['nullable', 'max:0'],
            'form_rendered_at' => ['required', 'integer'],
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:40'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'persons' => ['required', 'integer', 'min:1', 'max:40'],
            'table_preference' => ['nullable', 'string', 'max:120'],
            'selected_menu' => ['nullable', 'string', 'max:120'],
            'additional_notes' => ['nullable', 'string', 'max:1200'],
        ]);

        $renderedAt = (int) $request->input('form_rendered_at');
        if ($renderedAt <= 0 || now()->timestamp - $renderedAt < 3) {
            return back()
                ->withErrors(['full_name' => 'Submission blocked. Please try again.'])
                ->withInput();
        }

        $booking = Booking::create([
            'full_name' => (string) $request->input('full_name'),
            'email' => (string) $request->input('email'),
            'phone' => (string) $request->input('phone'),
            'date' => (string) $request->input('date'),
            'time' => (string) $request->input('time'),
            'persons' => (int) $request->input('persons'),
            'table_preference' => $request->filled('table_preference') ? (string) $request->input('table_preference') : null,
            'selected_menu' => $request->filled('selected_menu') ? (string) $request->input('selected_menu') : null,
            'additional_notes' => $request->filled('additional_notes') ? (string) $request->input('additional_notes') : null,
        ]);

        Mail::to(config('mail.restaurant_email'))->send(new BookingSubmittedMail($booking));

        return redirect()
            ->route('bookings.success')
            ->with('booking_reference', $booking->id);
    }

    public function success(): View
    {
        return view('pages.book-now-success');
    }
}
