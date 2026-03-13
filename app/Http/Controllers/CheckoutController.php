<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\DineInSlot;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\Payments\Exceptions\PaymentException;
use App\Services\Payments\PaymentGatewayManager;
use App\Support\CartManager;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(Request $request, CartManager $cart): View|RedirectResponse
    {
        if ($cart->isEmpty()) {
            return redirect()
                ->route('menu')
                ->withErrors(['cart' => 'Your cart is empty. Add items before checkout.']);
        }

        $selectedDate = $request->old('reservation_date', now()->toDateString());
        $selectedGuests = (int) $request->old('guest_count', 2);
        $slotAvailability = $this->getSlotAvailability($selectedDate, max(1, $selectedGuests));

        return view('pages.checkout', [
            'cart' => $cart->summary(),
            'slotAvailability' => $slotAvailability,
            'selectedDate' => $selectedDate,
            'fulfillmentOptions' => [
                Order::FULFILLMENT_TAKEAWAY => 'Take Away',
                Order::FULFILLMENT_DELIVERY => 'Delivery',
                Order::FULFILLMENT_DINE_IN => 'Dine In',
            ],
            'paymentOptions' => [
                Order::PAYMENT_METHOD_STRIPE => 'Stripe (Card)',
                Order::PAYMENT_METHOD_PAYPAL => 'PayPal',
            ],
        ]);
    }

    public function store(Request $request, CartManager $cart, PaymentGatewayManager $paymentGateways): RedirectResponse
    {
        if ($cart->isEmpty()) {
            return redirect()
                ->route('menu')
                ->withErrors(['cart' => 'Your cart is empty.']);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:40'],
            'fulfillment_type' => ['required', Rule::in([
                Order::FULFILLMENT_TAKEAWAY,
                Order::FULFILLMENT_DELIVERY,
                Order::FULFILLMENT_DINE_IN,
            ])],
            'delivery_address' => ['nullable', 'string', 'max:1200'],
            'notes' => ['nullable', 'string', 'max:1200'],
            'reservation_date' => ['nullable', 'date', 'after_or_equal:today'],
            'reservation_slot_id' => ['nullable', 'integer', 'exists:dine_in_slots,id'],
            'guest_count' => ['nullable', 'integer', 'min:1', 'max:20'],
            'payment_method' => ['required', Rule::in([
                Order::PAYMENT_METHOD_STRIPE,
                Order::PAYMENT_METHOD_PAYPAL,
            ])],
            'create_account' => ['nullable', 'boolean'],
            'account_name' => ['nullable', 'string', 'max:120'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $validator->sometimes('delivery_address', ['required', 'string', 'max:1200'], function ($input) {
            return $input->fulfillment_type === Order::FULFILLMENT_DELIVERY;
        });

        $validator->sometimes('reservation_date', ['required', 'date', 'after_or_equal:today'], function ($input) {
            return $input->fulfillment_type === Order::FULFILLMENT_DINE_IN;
        });

        $validator->sometimes('reservation_slot_id', ['required', 'integer', 'exists:dine_in_slots,id'], function ($input) {
            return $input->fulfillment_type === Order::FULFILLMENT_DINE_IN;
        });

        $validator->sometimes('guest_count', ['required', 'integer', 'min:1', 'max:20'], function ($input) {
            return $input->fulfillment_type === Order::FULFILLMENT_DINE_IN;
        });

        $validator->sometimes('account_name', ['required', 'string', 'max:120'], function ($input) use ($request) {
            return ! $request->user() && (bool) $input->create_account;
        });

        $validator->sometimes('password', ['required', 'string', 'min:8', 'confirmed'], function ($input) use ($request) {
            return ! $request->user() && (bool) $input->create_account;
        });

        $validator->sometimes('email', ['unique:users,email'], function ($input) use ($request) {
            return ! $request->user() && (bool) $input->create_account;
        });

        $validator->after(function ($validator) use ($request) {
            if ($request->input('fulfillment_type') !== Order::FULFILLMENT_DINE_IN) {
                return;
            }

            $slotId = (int) $request->input('reservation_slot_id');
            $guestCount = (int) $request->input('guest_count', 1);
            $reservationDate = (string) $request->input('reservation_date');

            if (! $slotId || ! $reservationDate || $guestCount < 1) {
                return;
            }

            $remaining = $this->remainingGuestsForSlot($reservationDate, $slotId);
            if ($remaining < $guestCount) {
                $validator->errors()->add('reservation_slot_id', "Only {$remaining} seats are left for this slot.");
            }
        });

        $validated = $validator->validate();
        $cartSummary = $cart->summary();
        $subtotal = (float) $cartSummary['subtotal'];
        $deliveryFee = $validated['fulfillment_type'] === Order::FULFILLMENT_DELIVERY ? 3.50 : 0.00;
        $total = $subtotal + $deliveryFee;

        $user = $request->user();
        if (! $user && ! empty($validated['create_account'])) {
            $user = User::query()->create([
                'name' => (string) $validated['account_name'],
                'email' => (string) $validated['email'],
                'password' => (string) $validated['password'],
            ]);
            Auth::login($user);
        }

        $slot = null;
        if ($validated['fulfillment_type'] === Order::FULFILLMENT_DINE_IN && ! empty($validated['reservation_slot_id'])) {
            $slot = DineInSlot::query()
                ->where('is_active', true)
                ->find($validated['reservation_slot_id']);
        }

        $order = $this->createOrder(
            validated: $validated,
            cartSummary: $cartSummary,
            subtotal: $subtotal,
            deliveryFee: $deliveryFee,
            total: $total,
            slot: $slot,
            user: $user,
        );

        try {
            $gateway = $paymentGateways->for((string) $validated['payment_method']);

            $successUrl = $validated['payment_method'] === Order::PAYMENT_METHOD_STRIPE
                ? route('checkout.payment.stripe.success', $order).'?session_id={CHECKOUT_SESSION_ID}'
                : route('checkout.payment.paypal.success', $order);

            $cancelUrl = $validated['payment_method'] === Order::PAYMENT_METHOD_STRIPE
                ? route('checkout.payment.stripe.cancel', $order)
                : route('checkout.payment.paypal.cancel', $order);

            $checkoutRedirect = $gateway->createCheckout(
                order: $order,
                successUrl: $successUrl,
                cancelUrl: $cancelUrl,
            );
        } catch (PaymentException $exception) {
            $order->forceFill([
                'status' => Order::STATUS_PAYMENT_FAILED,
                'payment_status' => Order::PAYMENT_STATUS_FAILED,
                'payment_meta' => $this->mergePaymentMeta($order, [
                    'gateway_init_error' => $exception->getMessage(),
                ]),
            ])->save();

            return redirect()
                ->route('checkout.create')
                ->withInput()
                ->withErrors(['payment' => 'Unable to initialize payment right now. Please try again.']);
        }

        $order->forceFill([
            'payment_session_id' => $checkoutRedirect->sessionId,
            'payment_reference' => $checkoutRedirect->reference,
            'payment_meta' => $this->mergePaymentMeta($order, $checkoutRedirect->meta),
        ])->save();

        return redirect()->away($checkoutRedirect->url);
    }

    public function stripeSuccess(Request $request, Order $order, CartManager $cart, PaymentGatewayManager $paymentGateways): RedirectResponse
    {
        return $this->handlePaymentSuccess(
            method: Order::PAYMENT_METHOD_STRIPE,
            order: $order,
            request: $request,
            cart: $cart,
            paymentGateways: $paymentGateways,
        );
    }

    public function paypalSuccess(Request $request, Order $order, CartManager $cart, PaymentGatewayManager $paymentGateways): RedirectResponse
    {
        return $this->handlePaymentSuccess(
            method: Order::PAYMENT_METHOD_PAYPAL,
            order: $order,
            request: $request,
            cart: $cart,
            paymentGateways: $paymentGateways,
        );
    }

    public function stripeCancel(Order $order): RedirectResponse
    {
        return $this->handlePaymentCancel(Order::PAYMENT_METHOD_STRIPE, $order);
    }

    public function paypalCancel(Order $order): RedirectResponse
    {
        return $this->handlePaymentCancel(Order::PAYMENT_METHOD_PAYPAL, $order);
    }

    public function success(): View
    {
        return view('pages.checkout-success');
    }

    public function slots(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'guest_count' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $availability = $this->getSlotAvailability(
            (string) $validated['date'],
            (int) ($validated['guest_count'] ?? 2)
        );

        return response()->json([
            'ok' => true,
            'slots' => $availability->values()->all(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @param  array<string, mixed>  $cartSummary
     */
    private function createOrder(
        array $validated,
        array $cartSummary,
        float $subtotal,
        float $deliveryFee,
        float $total,
        ?DineInSlot $slot,
        ?User $user,
    ): Order {
        return DB::transaction(function () use ($validated, $cartSummary, $subtotal, $deliveryFee, $total, $slot, $user) {
            $order = Order::query()->create([
                'reference' => 'KGH-PENDING-'.Str::upper(Str::random(12)),
                'user_id' => $user?->id,
                'status' => Order::STATUS_PENDING_PAYMENT,
                'fulfillment_type' => $validated['fulfillment_type'],
                'customer_name' => (string) $validated['full_name'],
                'customer_email' => (string) $validated['email'],
                'customer_phone' => (string) $validated['phone'],
                'delivery_address' => ! empty($validated['delivery_address']) ? (string) $validated['delivery_address'] : null,
                'notes' => ! empty($validated['notes']) ? (string) $validated['notes'] : null,
                'dine_in_slot_id' => $slot?->id,
                'reservation_date' => ! empty($validated['reservation_date']) ? (string) $validated['reservation_date'] : null,
                'reservation_time' => $slot?->start_time,
                'guest_count' => ! empty($validated['guest_count']) ? (int) $validated['guest_count'] : null,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
                'payment_method' => (string) $validated['payment_method'],
                'payment_provider' => (string) $validated['payment_method'],
                'payment_status' => Order::PAYMENT_STATUS_PENDING,
                'placed_at' => now(),
            ]);

            $order->reference = sprintf('KGH-%s-%05d', now()->format('Ymd'), $order->id);
            $order->save();

            foreach ($cartSummary['items'] as $cartItem) {
                $unitPrice = (float) $cartItem['price'];
                $quantity = (int) $cartItem['quantity'];

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'menu_item_id' => (int) $cartItem['menu_item_id'],
                    'item_name' => (string) $cartItem['name'],
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'line_total' => $unitPrice * $quantity,
                ]);
            }

            return $order;
        });
    }

    private function handlePaymentSuccess(
        string $method,
        Order $order,
        Request $request,
        CartManager $cart,
        PaymentGatewayManager $paymentGateways
    ): RedirectResponse {
        if ($order->payment_status === Order::PAYMENT_STATUS_PAID) {
            return redirect()
                ->route('checkout.success')
                ->with('order_reference', $order->reference);
        }

        if ($order->payment_method !== $method) {
            return redirect()
                ->route('checkout.create')
                ->withErrors(['payment' => 'Invalid payment callback for this order.']);
        }

        try {
            $confirmation = $paymentGateways->for($method)->confirmCheckout($order, $request);
        } catch (PaymentException $exception) {
            $order->forceFill([
                'status' => Order::STATUS_PAYMENT_FAILED,
                'payment_status' => Order::PAYMENT_STATUS_FAILED,
                'payment_meta' => $this->mergePaymentMeta($order, [
                    'gateway_confirm_error' => $exception->getMessage(),
                ]),
            ])->save();

            return redirect()
                ->route('checkout.create')
                ->withErrors(['payment' => 'Payment verification failed. Please try again.']);
        }

        if (! $confirmation->successful) {
            $order->forceFill([
                'status' => Order::STATUS_PAYMENT_FAILED,
                'payment_status' => Order::PAYMENT_STATUS_FAILED,
                'payment_session_id' => $confirmation->sessionId ?? $order->payment_session_id,
                'payment_reference' => $confirmation->transactionId ?? $order->payment_reference,
                'payment_meta' => $this->mergePaymentMeta($order, $confirmation->payload),
            ])->save();

            return redirect()
                ->route('checkout.create')
                ->withErrors(['payment' => $confirmation->message ?? 'Payment could not be completed.']);
        }

        $order->forceFill([
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_STATUS_PAID,
            'payment_session_id' => $confirmation->sessionId ?? $order->payment_session_id,
            'payment_reference' => $confirmation->transactionId ?? $order->payment_reference,
            'payment_meta' => $this->mergePaymentMeta($order, $confirmation->payload),
            'paid_at' => now(),
        ])->save();

        $cart->clear();

        return redirect()
            ->route('checkout.success')
            ->with('order_reference', $order->reference);
    }

    private function handlePaymentCancel(string $method, Order $order): RedirectResponse
    {
        if ($order->payment_status === Order::PAYMENT_STATUS_PAID) {
            return redirect()
                ->route('checkout.success')
                ->with('order_reference', $order->reference);
        }

        if ($order->payment_method === $method && $order->payment_status === Order::PAYMENT_STATUS_PENDING) {
            $order->forceFill([
                'status' => Order::STATUS_CANCELLED,
                'payment_status' => Order::PAYMENT_STATUS_CANCELLED,
            ])->save();
        }

        return redirect()
            ->route('checkout.create')
            ->withErrors(['payment' => 'Payment was cancelled. You can checkout again when ready.']);
    }

    /**
     * @param  array<string, mixed>  $incoming
     * @return array<string, mixed>
     */
    private function mergePaymentMeta(Order $order, array $incoming): array
    {
        $current = is_array($order->payment_meta) ? $order->payment_meta : [];

        return array_merge($current, $incoming);
    }

    private function getSlotAvailability(string $reservationDate, int $guestCount): Collection
    {
        $date = Carbon::parse($reservationDate)->toDateString();
        $guestCount = max(1, $guestCount);
        $slots = DineInSlot::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('start_time')
            ->get();

        if ($slots->isEmpty()) {
            return collect();
        }

        $reservedGuestsBySlot = Order::query()
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

        return $slots->map(function (DineInSlot $slot) use ($guestCount, $reservedGuestsByBooking, $reservedGuestsBySlot) {
            $reserved = (int) ($reservedGuestsBySlot[$slot->id] ?? 0) + (int) ($reservedGuestsByBooking[$slot->id] ?? 0);
            $remaining = max(0, ((int) $slot->max_guests) - $reserved);

            return [
                'id' => $slot->id,
                'name' => $slot->name,
                'time_range' => Carbon::createFromFormat('H:i:s', $slot->start_time)->format('H:i')
                    .' - '.
                    Carbon::createFromFormat('H:i:s', $slot->end_time)->format('H:i'),
                'remaining' => $remaining,
                'max_guests' => (int) $slot->max_guests,
                'can_book' => $remaining >= $guestCount,
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

        $reserved = (int) Order::query()
            ->where('fulfillment_type', Order::FULFILLMENT_DINE_IN)
            ->whereDate('reservation_date', Carbon::parse($reservationDate)->toDateString())
            ->where('dine_in_slot_id', $slotId)
            ->whereNotIn('status', [Order::STATUS_CANCELLED, Order::STATUS_PAYMENT_FAILED])
            ->sum('guest_count');

        $reservedByBooking = (int) Booking::query()
            ->where('booking_type', Booking::TYPE_TABLE)
            ->whereDate('date', Carbon::parse($reservationDate)->toDateString())
            ->where('dine_in_slot_id', $slotId)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->sum('persons');

        return max(0, ((int) $slot->max_guests) - ($reserved + $reservedByBooking));
    }
}
