<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndexBookingsRequest;
use App\Http\Requests\Admin\UpdateBookingStatusRequest;
use App\Mail\BookingStatusUpdatedMail;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index(IndexBookingsRequest $request): View
    {
        $validated = $request->validated();
        $dateFrom = (string) ($validated['date_from'] ?? now()->toDateString());
        $dateTo = (string) ($validated['date_to'] ?? now()->addDays(30)->toDateString());
        $bookingType = (string) ($validated['booking_type'] ?? 'all');
        $status = (string) ($validated['status'] ?? 'all');
        $paymentStatus = (string) ($validated['payment_status'] ?? 'all');
        $search = trim((string) ($validated['search'] ?? ''));

        $query = Booking::query()
            ->with('dineInSlot:id,name,start_time,end_time')
            ->whereBetween('date', [
                Carbon::parse($dateFrom)->toDateString(),
                Carbon::parse($dateTo)->toDateString(),
            ])
            ->when($bookingType !== 'all', function (Builder $builder) use ($bookingType): void {
                $builder->where('booking_type', $bookingType);
            })
            ->when($status !== 'all', function (Builder $builder) use ($status): void {
                $builder->where('status', $status);
            })
            ->when($paymentStatus !== 'all', function (Builder $builder) use ($paymentStatus): void {
                $builder->where('payment_status', $paymentStatus);
            })
            ->when($search !== '', function (Builder $builder) use ($search): void {
                $builder->where(function (Builder $searchQuery) use ($search): void {
                    $searchQuery
                        ->where('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            });

        $bookings = (clone $query)
            ->orderBy('date')
            ->orderBy('time')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', Booking::STATUS_PENDING)->count(),
            'confirmed' => (clone $query)->where('status', Booking::STATUS_CONFIRMED)->count(),
            'cancelled' => (clone $query)->where('status', Booking::STATUS_CANCELLED)->count(),
            'paid' => (clone $query)->where('payment_status', Booking::PAYMENT_STATUS_PAID)->count(),
            'upcoming' => (clone $query)->whereDate('date', '>=', now()->toDateString())->count(),
        ];

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'stats' => $stats,
            'filters' => [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'booking_type' => $bookingType,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'search' => $search,
            ],
            'bookingTypeLabels' => Booking::typeLabels(),
            'statusLabels' => Booking::statusLabels(),
            'paymentMethodLabels' => Booking::paymentMethodLabels(),
            'paymentStatusLabels' => Booking::paymentStatusLabels(),
        ]);
    }

    public function show(Booking $booking): View
    {
        $booking->loadMissing('dineInSlot:id,name,start_time,end_time');

        return view('admin.bookings.show', [
            'booking' => $booking,
            'statusLabels' => Booking::statusLabels(),
            'bookingTypeLabels' => Booking::typeLabels(),
            'paymentMethodLabels' => Booking::paymentMethodLabels(),
            'paymentStatusLabels' => Booking::paymentStatusLabels(),
        ]);
    }

    public function update(UpdateBookingStatusRequest $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validated();
        $previousStatus = (string) $booking->status;
        $previousPaymentStatus = (string) $booking->payment_status;

        $nextStatus = (string) $validated['status'];
        $nextPaymentStatus = (string) ($validated['payment_status'] ?? $booking->payment_status);

        if (
            $nextStatus === Booking::STATUS_CANCELLED
            && $booking->payment_status !== Booking::PAYMENT_STATUS_PAID
            && ! array_key_exists('payment_status', $validated)
        ) {
            $nextPaymentStatus = Booking::PAYMENT_STATUS_CANCELLED;
        }

        if (
            $nextStatus !== Booking::STATUS_CANCELLED
            && $booking->payment_status === Booking::PAYMENT_STATUS_CANCELLED
            && ! array_key_exists('payment_status', $validated)
        ) {
            $nextPaymentStatus = Booking::PAYMENT_STATUS_PENDING;
        }

        $booking->forceFill([
            'status' => $nextStatus,
            'payment_status' => $nextPaymentStatus,
        ])->save();

        if ($previousStatus !== $booking->status || $previousPaymentStatus !== $booking->payment_status) {
            Mail::to($booking->email)->send(
                new BookingStatusUpdatedMail($booking, $previousStatus, $previousPaymentStatus)
            );
        }

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Booking updated successfully.');
    }
}
