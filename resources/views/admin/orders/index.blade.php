@extends('admin.layout')

@section('admin_title', 'Order Management')
@section('admin_description', 'Monitor incoming orders, payment state, fulfillment type, and kitchen workflow progress from one unified queue.')

@section('admin_actions')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Back to Dashboard</a>
@endsection

@section('admin_content')
    <div class="card admin-panel mb-7">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">Filter Orders</h3>
                <p class="admin-panel-copy">Search by date, order type, workflow state, or payment status.</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-5 align-items-end">
                <div class="col-md-2">
                    <label for="date_from" class="form-label fw-semibold">Date From</label>
                    <input id="date_from" type="date" name="date_from" class="form-control form-control-solid" value="{{ $filters['date_from'] }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label fw-semibold">Date To</label>
                    <input id="date_to" type="date" name="date_to" class="form-control form-control-solid" value="{{ $filters['date_to'] }}">
                </div>
                <div class="col-md-2">
                    <label for="fulfillment_type" class="form-label fw-semibold">Order Type</label>
                    <select id="fulfillment_type" name="fulfillment_type" class="form-select form-select-solid">
                        <option value="all" @selected($filters['fulfillment_type'] === 'all')>All</option>
                        @foreach($fulfillmentLabels as $value => $label)
                            <option value="{{ $value }}" @selected($filters['fulfillment_type'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select id="status" name="status" class="form-select form-select-solid">
                        <option value="all" @selected($filters['status'] === 'all')>All</option>
                        @foreach($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="payment_status" class="form-label fw-semibold">Payment</label>
                    <select id="payment_status" name="payment_status" class="form-select form-select-solid">
                        <option value="all" @selected($filters['payment_status'] === 'all')>All</option>
                        @foreach($paymentStatusLabels as $value => $label)
                            <option value="{{ $value }}" @selected($filters['payment_status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="search" class="form-label fw-semibold">Search</label>
                    <input id="search" type="text" name="search" class="form-control form-control-solid" placeholder="Ref, name, email" value="{{ $filters['search'] }}">
                </div>
                <div class="col-12 d-flex gap-3">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-light">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-5 mb-7">
        <div class="col-sm-6 col-xl-2">
            <div class="admin-stat-card"><span class="text-gray-600 fw-semibold d-block mb-2">Total</span><span class="fs-2hx fw-bold text-gray-900">{{ number_format($stats['total']) }}</span></div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="admin-stat-card"><span class="text-gray-600 fw-semibold d-block mb-2">Pending</span><span class="fs-2hx fw-bold text-warning">{{ number_format($stats['pending']) }}</span></div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="admin-stat-card"><span class="text-gray-600 fw-semibold d-block mb-2">Confirmed</span><span class="fs-2hx fw-bold text-success">{{ number_format($stats['confirmed']) }}</span></div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="admin-stat-card"><span class="text-gray-600 fw-semibold d-block mb-2">Completed</span><span class="fs-2hx fw-bold text-primary">{{ number_format($stats['completed']) }}</span></div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="admin-stat-card"><span class="text-gray-600 fw-semibold d-block mb-2">Paid</span><span class="fs-2hx fw-bold text-info">{{ number_format($stats['paid']) }}</span></div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="admin-stat-card"><span class="text-gray-600 fw-semibold d-block mb-2">Revenue</span><span class="fs-2hx fw-bold text-gray-900">&euro;{{ number_format($stats['revenue'], 2) }}</span></div>
        </div>
    </div>

    <div class="card admin-panel">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">Orders</h3>
                <p class="admin-panel-copy">Open each order to inspect payment, fulfillment details, items, and workflow updates.</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-4 admin-table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th>Reference</th>
                            <th>Customer</th>
                            <th>Order Type</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-700">
                        @forelse($orders as $order)
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
                            <tr>
                                <td class="text-gray-900 fw-bold">{{ $order->reference }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900">{{ $order->customer_name }}</span>
                                        <span class="text-gray-500 fs-8">{{ $order->customer_email }}</span>
                                        <span class="text-gray-500 fs-8">{{ $order->items_count }} items</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $fulfillmentLabels[$order->fulfillment_type] ?? ucfirst($order->fulfillment_type) }}</span>
                                        <span class="text-gray-500 fs-8">{{ optional($order->created_at)->format('d M Y H:i') }}</span>
                                    </div>
                                </td>
                                <td class="text-gray-900 fw-bold">&euro;{{ number_format((float) $order->total, 2) }}</td>
                                <td><span class="badge {{ $statusBadge }}">{{ $statusLabels[$order->status] ?? ucfirst($order->status) }}</span></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge {{ $paymentBadge }}">{{ $paymentStatusLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}</span>
                                        <span class="text-gray-500 fs-8 mt-2">{{ $paymentMethodLabels[$order->payment_method] ?? 'Not set' }}</span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light-primary">Open</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="admin-empty">No orders found for the current filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-4">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
