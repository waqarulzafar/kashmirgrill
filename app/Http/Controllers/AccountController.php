<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\UpdateAccountPasswordRequest;
use App\Http\Requests\UpdateAccountProfileRequest;
use App\Models\Booking;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();

        abort_unless($user, 403);

        $this->syncHistoricalRecords($user);

        $stats = [
            'orders_total' => $user->orders()->count(),
            'spent_total' => (float) $user->orders()->where('payment_status', Order::PAYMENT_STATUS_PAID)->sum('total'),
            'bookings_total' => $user->bookings()->count(),
            'bookings_upcoming' => $user->bookings()->whereDate('date', '>=', now()->toDateString())->count(),
        ];

        return view('account.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'latestOrder' => $this->ordersQuery($user)->first(),
            'latestBooking' => $this->bookingsQuery($user)->first(),
            'orderStatusLabels' => Order::statusLabels(),
            'bookingStatusLabels' => Booking::statusLabels(),
            'orderFulfillmentLabels' => Order::fulfillmentLabels(),
            'bookingTypeLabels' => Booking::typeLabels(),
            'activeAccountPage' => 'dashboard',
        ]);
    }

    public function orders(Request $request): View
    {
        $user = $request->user();

        abort_unless($user, 403);

        $this->syncHistoricalRecords($user);

        return view('account.orders', [
            'user' => $user,
            'orders' => $this->ordersQuery($user)
                ->paginate(8)
                ->withQueryString(),
            'orderStatusLabels' => Order::statusLabels(),
            'orderFulfillmentLabels' => Order::fulfillmentLabels(),
            'activeAccountPage' => 'orders',
        ]);
    }

    public function showOrder(Request $request, Order $order): View
    {
        $user = $request->user();

        abort_unless($user, 403);

        $this->syncHistoricalRecords($user);

        $order->refresh();

        abort_unless((int) $order->user_id === (int) $user->id, 404);

        $order->loadMissing([
            'items.menuItem:id,name,slug',
            'dineInSlot:id,name,start_time,end_time',
        ]);

        return view('account.order-show', [
            'user' => $user,
            'order' => $order,
            'statusLabels' => Order::statusLabels(),
            'fulfillmentLabels' => Order::fulfillmentLabels(),
            'paymentMethodLabels' => Order::paymentMethodLabels(),
            'paymentStatusLabels' => Order::paymentStatusLabels(),
            'activeAccountPage' => 'orders',
        ]);
    }

    public function bookings(Request $request): View
    {
        $user = $request->user();

        abort_unless($user, 403);

        $this->syncHistoricalRecords($user);

        return view('account.bookings', [
            'user' => $user,
            'bookings' => $this->bookingsQuery($user)
                ->paginate(8)
                ->withQueryString(),
            'bookingStatusLabels' => Booking::statusLabels(),
            'bookingTypeLabels' => Booking::typeLabels(),
            'bookingPaymentStatusLabels' => Booking::paymentStatusLabels(),
            'activeAccountPage' => 'bookings',
        ]);
    }

    public function showBooking(Request $request, Booking $booking): View
    {
        $user = $request->user();

        abort_unless($user, 403);

        $this->syncHistoricalRecords($user);

        $booking->refresh();

        abort_unless((int) $booking->user_id === (int) $user->id, 404);

        $booking->loadMissing('dineInSlot:id,name,start_time,end_time');

        return view('account.booking-show', [
            'user' => $user,
            'booking' => $booking,
            'statusLabels' => Booking::statusLabels(),
            'bookingTypeLabels' => Booking::typeLabels(),
            'paymentMethodLabels' => Booking::paymentMethodLabels(),
            'paymentStatusLabels' => Booking::paymentStatusLabels(),
            'activeAccountPage' => 'bookings',
        ]);
    }

    public function profile(Request $request): View
    {
        $user = $request->user();

        abort_unless($user, 403);

        $this->syncHistoricalRecords($user);

        return view('account.profile', [
            'user' => $user,
            'activeAccountPage' => 'profile',
        ]);
    }

    public function updateProfile(UpdateAccountProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user, 403);

        $previousEmail = (string) $user->email;
        $validated = $request->validated();

        $this->syncHistoricalRecords($user, $previousEmail);

        $user->forceFill([
            'name' => (string) $validated['name'],
            'email' => (string) $validated['email'],
        ])->save();

        $this->syncHistoricalRecords($user);

        return redirect()
            ->route('account.profile')
            ->with('success', __('Your profile details were updated successfully.'));
    }

    public function updatePassword(UpdateAccountPasswordRequest $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user, 403);

        $validated = $request->validated();

        $user->forceFill([
            'password' => (string) $validated['password'],
        ])->save();

        return redirect()
            ->route('account.profile')
            ->with('success', __('Your password was updated successfully.'));
    }

    public function destroy(DeleteAccountRequest $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user, 403);

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', __('Your account has been deleted successfully.'));
    }

    private function syncHistoricalRecords(User $user, ?string $email = null): void
    {
        $matchEmail = trim((string) ($email ?? $user->email));

        if ($matchEmail === '') {
            return;
        }

        Order::query()
            ->whereNull('user_id')
            ->where('customer_email', $matchEmail)
            ->update(['user_id' => $user->id]);

        Booking::query()
            ->whereNull('user_id')
            ->where('email', $matchEmail)
            ->update(['user_id' => $user->id]);
    }

    private function ordersQuery(User $user)
    {
        return $user->orders()
            ->with('dineInSlot')
            ->withCount('items')
            ->latest('placed_at')
            ->latest('id');
    }

    private function bookingsQuery(User $user)
    {
        return $user->bookings()
            ->with('dineInSlot')
            ->orderByDesc('date')
            ->orderByDesc('time');
    }
}
