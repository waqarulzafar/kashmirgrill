<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingAvailabilityRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Mail\BookingSubmittedMail;
use App\Models\Booking;
use App\Models\DineInSlot;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(): View
    {
        $selectedDate = old('date', now()->toDateString());
        $selectedGuests = max(1, (int) old('persons', 2));
        $selectedTimeFilter = (string) old('time_filter', 'all');
        $selectedBookingType = (string) old('booking_type', Booking::TYPE_TABLE);

        return view('pages.book-now', [
            'selectedDate' => $selectedDate,
            'selectedGuests' => $selectedGuests,
            'selectedTimeFilter' => $selectedTimeFilter,
            'selectedBookingType' => $selectedBookingType,
            'slotAvailability' => $selectedBookingType === Booking::TYPE_TABLE
                ? $this->getSlotAvailability($selectedDate, $selectedGuests, $selectedTimeFilter)
                : collect(),
            'bookingTypeOptions' => [
                Booking::TYPE_TABLE => 'Table Reservation',
                Booking::TYPE_EVENT => 'Whole Restaurant Event',
            ],
            'paymentMethodOptions' => [
                Booking::PAYMENT_METHOD_PAY_ON_ARRIVAL => 'Pay at Restaurant',
                Booking::PAYMENT_METHOD_CARD_ON_CONFIRMATION => 'Card Checkout After Confirmation',
            ],
            'occasionOptions' => [
                'Birthday',
                'Anniversary',
                'Graduation',
                'Retirement',
                'Celebration',
                'Business Dinner',
            ],
        ]);
    }

    public function availability(BookingAvailabilityRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $bookingType = (string) ($validated['booking_type'] ?? Booking::TYPE_TABLE);

        if ($bookingType === Booking::TYPE_EVENT) {
            return response()->json([
                'ok' => true,
                'slots' => [],
            ]);
        }

        $availability = $this->getSlotAvailability(
            (string) $validated['date'],
            (int) ($validated['guest_count'] ?? 2),
            (string) ($validated['time_filter'] ?? 'all'),
        );

        return response()->json([
            'ok' => true,
            'slots' => $availability->values()->all(),
        ]);
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $renderedAt = (int) $validated['form_rendered_at'];
        if ($renderedAt <= 0 || now()->timestamp - $renderedAt < 3) {
            return back()
                ->withErrors(['full_name' => 'Submission blocked. Please try again.'])
                ->withInput();
        }

        $idempotencyHash = sha1((string) $validated['idempotency_key']);
        $idempotencyResultKey = "bookings:idempotency:result:{$idempotencyHash}";
        $idempotencyLockKey = "bookings:idempotency:lock:{$idempotencyHash}";

        $existingBookingId = Cache::get($idempotencyResultKey);
        if ($existingBookingId) {
            $existingBooking = Booking::query()->find($existingBookingId);
            if ($existingBooking) {
                return redirect()
                    ->route('bookings.success')
                    ->with('booking_reference', $this->formatReference($existingBooking));
            }

            Cache::forget($idempotencyResultKey);
        }

        if (! Cache::add($idempotencyLockKey, now()->timestamp, now()->addSeconds(30))) {
            $existingBookingId = Cache::get($idempotencyResultKey);
            if ($existingBookingId) {
                $existingBooking = Booking::query()->find($existingBookingId);
                if ($existingBooking) {
                    return redirect()
                        ->route('bookings.success')
                        ->with('booking_reference', $this->formatReference($existingBooking));
                }
            }

            return back()
                ->withErrors(['full_name' => 'Your reservation is already being processed. Please wait a moment and try again.'])
                ->withInput();
        }

        try {
            $existingBookingId = Cache::get($idempotencyResultKey);
            if ($existingBookingId) {
                $existingBooking = Booking::query()->find($existingBookingId);
                if ($existingBooking) {
                    return redirect()
                        ->route('bookings.success')
                        ->with('booking_reference', $this->formatReference($existingBooking));
                }
            }

            $bookingType = (string) $validated['booking_type'];
            $bookingDate = (string) $validated['date'];
            $guestCount = (int) $validated['persons'];

            $slot = null;
            if ($bookingType === Booking::TYPE_TABLE) {
                $slot = DineInSlot::query()
                    ->where('is_active', true)
                    ->find((int) $validated['selected_slot_id']);

                if (! $slot) {
                    return back()
                        ->withErrors(['selected_slot_id' => 'Selected slot is no longer available.'])
                        ->withInput();
                }

                $remainingSeats = $this->remainingGuestsForSlot($bookingDate, $slot->id);
                if ($remainingSeats < $guestCount) {
                    return back()
                        ->withErrors(['selected_slot_id' => "Only {$remainingSeats} seats are left for this slot."])
                        ->withInput();
                }
            }

            $bookingTime = $bookingType === Booking::TYPE_TABLE
                ? (string) $slot?->start_time
                : Carbon::createFromFormat('H:i', (string) $validated['time'])->format('H:i:s');

            $booking = Booking::create([
                'full_name' => (string) $validated['full_name'],
                'email' => (string) $validated['email'],
                'phone' => (string) $validated['phone'],
                'phone_country_iso2' => $validated['phone_country_iso2'] ?? null,
                'booking_type' => $bookingType,
                'status' => Booking::STATUS_PENDING,
                'date' => $bookingDate,
                'time' => $bookingTime,
                'dine_in_slot_id' => $slot?->id,
                'persons' => $guestCount,
                'table_preference' => $validated['table_preference'] ?? null,
                'selected_menu' => $validated['selected_menu'] ?? null,
                'special_occasion' => $validated['special_occasion'] ?? null,
                'payment_method' => (string) $validated['payment_method'],
                'payment_status' => Booking::PAYMENT_STATUS_PENDING,
                'marketing_opt_in' => (bool) ($validated['marketing_opt_in'] ?? false),
                'additional_notes' => $validated['additional_notes'] ?? null,
            ]);

            Cache::put($idempotencyResultKey, $booking->id, now()->addHours(6));

            Mail::to(config('mail.restaurant_email'))->send(new BookingSubmittedMail($booking));

            return redirect()
                ->route('bookings.success')
                ->with('booking_reference', $this->formatReference($booking));
        } finally {
            Cache::forget($idempotencyLockKey);
        }
    }

    public function success(): View
    {
        return view('pages.book-now-success');
    }

    private function getSlotAvailability(string $reservationDate, int $guestCount, string $timeFilter = 'all'): Collection
    {
        $date = Carbon::parse($reservationDate)->toDateString();
        $guestCount = max(1, $guestCount);

        $slotsQuery = DineInSlot::query()
            ->where('is_active', true);

        if ($timeFilter === 'lunch') {
            $slotsQuery->where('start_time', '<', '17:00:00');
        }

        if ($timeFilter === 'dinner') {
            $slotsQuery->where('start_time', '>=', '17:00:00');
        }

        $slots = $slotsQuery
            ->orderBy('sort_order')
            ->orderBy('start_time')
            ->get();

        if ($slots->isEmpty()) {
            return collect();
        }

        $reservedGuestsByOrder = Order::query()
            ->where('fulfillment_type', Order::FULFILLMENT_DINE_IN)
            ->whereDate('reservation_date', $date)
            ->whereNotNull('dine_in_slot_id')
            ->whereNotIn('status', [Order::STATUS_CANCELLED, Order::STATUS_PAYMENT_FAILED])
            ->selectRaw('dine_in_slot_id, COALESCE(SUM(guest_count), 0) as reserved_guests')
            ->groupBy('dine_in_slot_id')
            ->pluck('reserved_guests', 'dine_in_slot_id');

        $reservedGuestsByBooking = Booking::query()
            ->where('booking_type', Booking::TYPE_TABLE)
            ->whereDate('date', $date)
            ->whereNotNull('dine_in_slot_id')
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->selectRaw('dine_in_slot_id, COALESCE(SUM(persons), 0) as reserved_guests')
            ->groupBy('dine_in_slot_id')
            ->pluck('reserved_guests', 'dine_in_slot_id');

        return $slots->map(function (DineInSlot $slot) use ($date, $guestCount, $reservedGuestsByBooking, $reservedGuestsByOrder) {
            $reservedFromOrders = (int) ($reservedGuestsByOrder[$slot->id] ?? 0);
            $reservedFromBookings = (int) ($reservedGuestsByBooking[$slot->id] ?? 0);
            $reserved = $reservedFromOrders + $reservedFromBookings;
            $remaining = max(0, ((int) $slot->max_guests) - $reserved);
            $slotStartsAt = Carbon::createFromFormat('Y-m-d H:i:s', $date.' '.$slot->start_time);
            $isPastSlot = $slotStartsAt->lessThanOrEqualTo(now());

            return [
                'id' => $slot->id,
                'name' => $slot->name,
                'start_time' => Carbon::createFromFormat('H:i:s', $slot->start_time)->format('H:i'),
                'time_range' => Carbon::createFromFormat('H:i:s', $slot->start_time)->format('H:i')
                    .' - '.
                    Carbon::createFromFormat('H:i:s', $slot->end_time)->format('H:i'),
                'remaining' => $remaining,
                'max_guests' => (int) $slot->max_guests,
                'can_book' => $remaining >= $guestCount && ! $isPastSlot,
                'is_past' => $isPastSlot,
            ];
        });
    }

    private function remainingGuestsForSlot(string $reservationDate, int $slotId): int
    {
        $slot = DineInSlot::query()
            ->where('is_active', true)
            ->find($slotId);

        if (! $slot) {
            return 0;
        }

        $date = Carbon::parse($reservationDate)->toDateString();

        $reservedFromOrders = (int) Order::query()
            ->where('fulfillment_type', Order::FULFILLMENT_DINE_IN)
            ->whereDate('reservation_date', $date)
            ->where('dine_in_slot_id', $slotId)
            ->whereNotIn('status', [Order::STATUS_CANCELLED, Order::STATUS_PAYMENT_FAILED])
            ->sum('guest_count');

        $reservedFromBookings = (int) Booking::query()
            ->where('booking_type', Booking::TYPE_TABLE)
            ->whereDate('date', $date)
            ->where('dine_in_slot_id', $slotId)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->sum('persons');

        return max(0, ((int) $slot->max_guests) - ($reservedFromOrders + $reservedFromBookings));
    }

    private function formatReference(Booking $booking): string
    {
        return 'KGH-'.str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT);
    }
}
