<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndexBookingsRequest;
use App\Http\Requests\Admin\UpdateBookingRequest;
use App\Mail\BookingPaymentRequestedMail;
use App\Mail\BookingStatusUpdatedMail;
use App\Models\Booking;
use App\Models\DineInSlot;
use App\Support\LocalizationManager;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
            'dineInSlots' => DineInSlot::query()
                ->orderBy('sort_order')
                ->orderBy('start_time')
                ->get(['id', 'name', 'start_time', 'end_time', 'is_active']),
            'statusLabels' => Booking::statusLabels(),
            'bookingTypeLabels' => Booking::typeLabels(),
            'paymentMethodLabels' => Booking::paymentMethodLabels(),
            'paymentStatusLabels' => Booking::paymentStatusLabels(),
        ]);
    }

    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validated();
        $previousStatus = (string) $booking->status;
        $previousPaymentStatus = (string) $booking->payment_status;
        $previousPaymentMethod = (string) $booking->payment_method;
        $previousPaymentAmount = (float) ($booking->payment_amount ?? 0);
        $previousEmail = (string) $booking->email;

        $nextStatus = (string) $validated['status'];
        $nextPaymentStatus = (string) $validated['payment_status'];
        $nextPaymentMethod = $validated['payment_method'] ?: null;
        $nextPaymentAmount = $nextPaymentMethod === Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION
            ? (float) ($validated['payment_amount'] ?? 0)
            : null;

        $paymentToken = $booking->payment_token;
        if ($nextPaymentMethod === Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION && ! is_string($paymentToken)) {
            $paymentToken = null;
        }

        if ($nextPaymentMethod === Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION && $paymentToken === null) {
            $paymentToken = Str::random(48);
        }

        $booking->forceFill([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'booking_type' => $validated['booking_type'],
            'status' => $nextStatus,
            'date' => Carbon::parse($validated['date'])->toDateString(),
            'time' => Carbon::createFromFormat('H:i', $validated['time'])->format('H:i:s'),
            'dine_in_slot_id' => $validated['dine_in_slot_id'] ?? null,
            'persons' => (int) $validated['persons'],
            'table_preference' => $validated['table_preference'] ?: null,
            'selected_menu' => $validated['selected_menu'] ?: null,
            'special_occasion' => $validated['special_occasion'] ?: null,
            'payment_method' => $nextPaymentMethod,
            'payment_status' => $nextPaymentStatus,
            'payment_amount' => $nextPaymentAmount,
            'payment_token' => $paymentToken,
            'paid_at' => $nextPaymentStatus === Booking::PAYMENT_STATUS_PAID
                ? ($booking->paid_at ?? now())
                : null,
            'marketing_opt_in' => $request->boolean('marketing_opt_in'),
            'additional_notes' => $validated['additional_notes'] ?: null,
        ])->save();

        if ($booking->payment_method !== Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION && $booking->payment_status !== Booking::PAYMENT_STATUS_PAID) {
            $booking->forceFill([
                'payment_session_id' => null,
                'payment_reference' => null,
                'payment_meta' => null,
                'payment_token' => null,
                'payment_amount' => null,
                'paid_at' => null,
            ])->save();
        }

        $shouldSendPaymentRequest = $booking->canCollectCardPayment()
            && (
                $previousStatus !== Booking::STATUS_CONFIRMED
                || $previousPaymentStatus !== Booking::PAYMENT_STATUS_PENDING
                || $previousPaymentMethod !== Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION
                || abs($previousPaymentAmount - (float) ($booking->payment_amount ?? 0)) > 0.0001
                || $previousEmail !== $booking->email
            );

        if ($shouldSendPaymentRequest) {
            Mail::to($booking->email)->send(
                new BookingPaymentRequestedMail(
                    booking: $booking,
                    summaryUrl: route('bookings.payment.show', [
                        'locale' => app(LocalizationManager::class)->defaultLocale(),
                        'booking' => $booking,
                        'token' => $booking->payment_token,
                    ])
                )
            );
        } elseif ($previousStatus !== $booking->status || $previousPaymentStatus !== $booking->payment_status) {
            Mail::to($booking->email)->send(
                new BookingStatusUpdatedMail($booking, $previousStatus, $previousPaymentStatus)
            );
        }

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', $shouldSendPaymentRequest
                ? 'Booking confirmed and payment link emailed to the guest.'
                : 'Booking details updated successfully.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $bookingReference = $booking->formattedReference();

        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', "Booking {$bookingReference} deleted successfully.");
    }
}
