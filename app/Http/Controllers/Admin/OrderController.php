<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndexOrdersRequest;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(IndexOrdersRequest $request): View
    {
        $validated = $request->validated();
        $dateFrom = (string) ($validated['date_from'] ?? now()->subDays(7)->toDateString());
        $dateTo = (string) ($validated['date_to'] ?? now()->toDateString());
        $fulfillmentType = (string) ($validated['fulfillment_type'] ?? 'all');
        $status = (string) ($validated['status'] ?? 'all');
        $paymentStatus = (string) ($validated['payment_status'] ?? 'all');
        $search = trim((string) ($validated['search'] ?? ''));

        $query = Order::query()
            ->with(['dineInSlot:id,name,start_time,end_time', 'user:id,name,email'])
            ->withCount('items')
            ->whereBetween('created_at', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay(),
            ])
            ->when($fulfillmentType !== 'all', function (Builder $builder) use ($fulfillmentType): void {
                $builder->where('fulfillment_type', $fulfillmentType);
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
                        ->where('reference', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            });

        $orders = (clone $query)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->whereIn('status', [Order::STATUS_PENDING_PAYMENT, Order::STATUS_PENDING])->count(),
            'confirmed' => (clone $query)->where('status', Order::STATUS_CONFIRMED)->count(),
            'completed' => (clone $query)->where('status', Order::STATUS_COMPLETED)->count(),
            'paid' => (clone $query)->where('payment_status', Order::PAYMENT_STATUS_PAID)->count(),
            'revenue' => (float) (clone $query)->where('payment_status', Order::PAYMENT_STATUS_PAID)->sum('total'),
        ];

        return view('admin.orders.index', [
            'orders' => $orders,
            'stats' => $stats,
            'filters' => [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'fulfillment_type' => $fulfillmentType,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'search' => $search,
            ],
            'statusLabels' => Order::statusLabels(),
            'manageableStatusLabels' => Order::manageableStatusLabels(),
            'fulfillmentLabels' => Order::fulfillmentLabels(),
            'paymentMethodLabels' => Order::paymentMethodLabels(),
            'paymentStatusLabels' => Order::paymentStatusLabels(),
        ]);
    }

    public function show(Order $order): View
    {
        $order->loadMissing([
            'items.menuItem:id,name,slug',
            'dineInSlot:id,name,start_time,end_time',
            'user:id,name,email',
        ]);

        return view('admin.orders.show', [
            'order' => $order,
            'statusLabels' => Order::statusLabels(),
            'manageableStatusLabels' => Order::manageableStatusLabels(),
            'fulfillmentLabels' => Order::fulfillmentLabels(),
            'paymentMethodLabels' => Order::paymentMethodLabels(),
            'paymentStatusLabels' => Order::paymentStatusLabels(),
        ]);
    }

    public function update(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $validated = $request->validated();
        $nextStatus = (string) $validated['status'];

        if ($order->payment_status !== Order::PAYMENT_STATUS_PAID && $nextStatus !== Order::STATUS_CANCELLED) {
            return back()->withErrors([
                'status' => 'Only paid orders can move into the preparation workflow.',
            ]);
        }

        $previousStatus = (string) $order->status;
        if ($previousStatus === $nextStatus) {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Order status already matches the selected value.');
        }

        $order->status = $nextStatus;

        if ($nextStatus === Order::STATUS_CANCELLED && $order->payment_status === Order::PAYMENT_STATUS_PENDING) {
            $order->payment_status = Order::PAYMENT_STATUS_CANCELLED;
        }

        $order->save();

        Mail::to($order->customer_email)->send(new OrderStatusUpdatedMail($order, $previousStatus));

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }
}
