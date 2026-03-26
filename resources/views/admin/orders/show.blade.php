@extends('admin.layout')

@section('admin_title', 'Order Details')
@section('admin_description', 'See the full commercial and operational story of an order before moving it through the fulfillment workflow.')

@section('admin_actions')
    <a href="{{ route('admin.orders.index') }}" class="btn btn-light">Back to Orders</a>
@endsection

@section('admin_content')
    @php
        $statusBadge = match ($order->status) {
            \App\Models\Order::STATUS_CONFIRMED, \App\Models\Order::STATUS_COMPLETED => 'badge-light-success',
            \App\Models\Order::STATUS_READY => 'badge-light-primary',
            \App\Models\Order::STATUS_PREPARING => 'badge-light-info',
            \App\Models\Order::STATUS_CANCELLED, \App\Models\Order::STATUS_PAYMENT_FAILED => 'badge-light-danger',
            default => 'badge-light-warning',
        };
        $paymentBadge = match ($order->payment_status) {
            \App\Models\Order::PAYMENT_STATUS_PAID => 'badge-light-success',
            \App\Models\Order::PAYMENT_STATUS_FAILED, \App\Models\Order::PAYMENT_STATUS_CANCELLED => 'badge-light-danger',
            default => 'badge-light-warning',
        };
    @endphp

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-7">
        <div>
            <h2 class="fw-bold text-gray-900 mb-1">{{ $order->reference }}</h2>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge {{ $statusBadge }}">{{ $statusLabels[$order->status] ?? ucfirst($order->status) }}</span>
                <span class="badge {{ $paymentBadge }}">{{ $paymentStatusLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}</span>
            </div>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-light">Back to Orders</a>
    </div>

    <div class="row g-7">
        <div class="col-xl-8">
            <div class="card mb-7">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title fw-bold">Customer & Fulfillment</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-5">
                        <div class="col-md-6">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Customer</div>
                                <div class="fw-bold text-gray-900">{{ $order->customer_name }}</div>
                                <div class="text-gray-600 mt-2">{{ $order->customer_email }}</div>
                                <div class="text-gray-600">{{ $order->customer_phone }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Order Type</div>
                                <div class="fw-bold text-gray-900">{{ $fulfillmentLabels[$order->fulfillment_type] ?? ucfirst($order->fulfillment_type) }}</div>
                                <div class="text-gray-600 mt-2">Placed {{ optional($order->placed_at ?: $order->created_at)->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                        @if($order->fulfillment_type === \App\Models\Order::FULFILLMENT_DELIVERY)
                            <div class="col-12">
                                <div class="p-5 rounded bg-light">
                                    <div class="fw-semibold text-gray-600 mb-1">Delivery Address</div>
                                    <div class="fw-bold text-gray-900">{{ $order->delivery_address ?: 'No address provided.' }}</div>
                                </div>
                            </div>
                        @endif
                        @if($order->fulfillment_type === \App\Models\Order::FULFILLMENT_DINE_IN)
                            <div class="col-md-6">
                                <div class="p-5 rounded bg-light">
                                    <div class="fw-semibold text-gray-600 mb-1">Reservation</div>
                                    <div class="fw-bold text-gray-900">
                                        {{ optional($order->reservation_date)->format('d M Y') ?: 'N/A' }}
                                        @if($order->reservation_time)
                                            at {{ \Illuminate\Support\Carbon::parse($order->reservation_time)->format('H:i') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-5 rounded bg-light">
                                    <div class="fw-semibold text-gray-600 mb-1">Table Details</div>
                                    <div class="fw-bold text-gray-900">{{ $order->guest_count ?: '0' }} guests</div>
                                    <div class="text-gray-600 mt-2">{{ $order->dineInSlot?->name ?: 'No slot selected' }}</div>
                                </div>
                            </div>
                        @endif
                        <div class="col-12">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Order Notes</div>
                                <div class="fw-bold text-gray-900">{{ $order->notes ?: 'No notes submitted.' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title fw-bold">Items & Totals</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive mb-5">
                        <table class="table align-middle table-row-dashed fs-6 gy-4">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Line Total</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-700">
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>&euro;{{ number_format((float) $item->unit_price, 2) }}</td>
                                        <td>&euro;{{ number_format((float) $item->line_total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row g-5">
                        <div class="col-md-4">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Subtotal</div>
                                <div class="fw-bold text-gray-900">&euro;{{ number_format((float) $order->subtotal, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Delivery Fee</div>
                                <div class="fw-bold text-gray-900">&euro;{{ number_format((float) $order->delivery_fee, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Total</div>
                                <div class="fw-bold text-gray-900">&euro;{{ number_format((float) $order->total, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card mb-7">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title fw-bold">Payment Snapshot</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="p-5 rounded bg-light mb-4">
                        <div class="fw-semibold text-gray-600 mb-1">Method</div>
                        <div class="fw-bold text-gray-900">{{ $paymentMethodLabels[$order->payment_method] ?? 'Not set' }}</div>
                    </div>
                    <div class="p-5 rounded bg-light mb-4">
                        <div class="fw-semibold text-gray-600 mb-1">Status</div>
                        <div class="fw-bold text-gray-900">{{ $paymentStatusLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}</div>
                    </div>
                    <div class="p-5 rounded bg-light mb-4">
                        <div class="fw-semibold text-gray-600 mb-1">Paid At</div>
                        <div class="fw-bold text-gray-900">{{ optional($order->paid_at)->format('d M Y H:i') ?: 'Not paid yet' }}</div>
                    </div>
                    <div class="p-5 rounded bg-light">
                        <div class="fw-semibold text-gray-600 mb-1">Reference</div>
                        <div class="fw-bold text-gray-900">{{ $order->payment_reference ?: ($order->payment_session_id ?: 'Not available') }}</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title fw-bold">Update Order Status</h3>
                </div>
                <div class="card-body pt-0">
                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="row g-5">
                        @csrf
                        @method('PATCH')

                        <div class="col-12">
                            <label for="status" class="form-label fw-semibold">Workflow Status</label>
                            <select id="status" name="status" class="form-select form-select-solid">
                                @foreach($manageableStatusLabels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $order->status) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-4">
                                <div class="fw-semibold text-gray-700">
                                    Status updates notify the customer automatically. Unpaid orders can only be cancelled from here.
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Save Order Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
