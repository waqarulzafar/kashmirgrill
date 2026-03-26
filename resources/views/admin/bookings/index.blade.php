@extends('admin.layout')

@section('admin_title', 'Booking Management')
@section('admin_description', 'Review guest reservation requests, inspect payment intent, and move each booking cleanly through confirmation or cancellation.')

@section('admin_actions')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Back to Dashboard</a>
@endsection

@section('admin_content')
    <div class="card admin-panel mb-7">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">Filter Bookings</h3>
                <p class="admin-panel-copy">Refine by date range, booking type, operational status, or payment state.</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-5 align-items-end">
                <div class="col-md-2">
                    <label for="date_from" class="form-label fw-semibold">Date From</label>
                    <input id="date_from" type="date" name="date_from" class="form-control form-control-solid" value="{{ $filters['date_from'] }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label fw-semibold">Date To</label>
                    <input id="date_to" type="date" name="date_to" class="form-control form-control-solid" value="{{ $filters['date_to'] }}">
                </div>
                <div class="col-md-2">
                    <label for="booking_type" class="form-label fw-semibold">Booking Type</label>
                    <select id="booking_type" name="booking_type" class="form-select form-select-solid">
                        <option value="all" @selected($filters['booking_type'] === 'all')>All</option>
                        @foreach($bookingTypeLabels as $value => $label)
                            <option value="{{ $value }}" @selected($filters['booking_type'] === $value)>{{ $label }}</option>
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
                    <input id="search" type="text" name="search" class="form-control form-control-solid" placeholder="Name, email, phone" value="{{ $filters['search'] }}">
                </div>
                <div class="col-12 d-flex gap-3">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-light">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-5 mb-7">
        <div class="col-sm-6 col-xl-2">
            <div class="admin-stat-card">
                    <span class="text-gray-600 fw-semibold d-block mb-2">Total</span>
                    <span class="fs-2hx fw-bold text-gray-900">{{ number_format($stats['total']) }}</span>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="admin-stat-card">
                    <span class="text-gray-600 fw-semibold d-block mb-2">Pending</span>
                    <span class="fs-2hx fw-bold text-warning">{{ number_format($stats['pending']) }}</span>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="admin-stat-card">
                    <span class="text-gray-600 fw-semibold d-block mb-2">Confirmed</span>
                    <span class="fs-2hx fw-bold text-success">{{ number_format($stats['confirmed']) }}</span>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="admin-stat-card">
                    <span class="text-gray-600 fw-semibold d-block mb-2">Cancelled</span>
                    <span class="fs-2hx fw-bold text-danger">{{ number_format($stats['cancelled']) }}</span>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="admin-stat-card">
                    <span class="text-gray-600 fw-semibold d-block mb-2">Paid</span>
                    <span class="fs-2hx fw-bold text-info">{{ number_format($stats['paid']) }}</span>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="admin-stat-card">
                    <span class="text-gray-600 fw-semibold d-block mb-2">Upcoming</span>
                    <span class="fs-2hx fw-bold text-primary">{{ number_format($stats['upcoming']) }}</span>
            </div>
        </div>
    </div>

    <div class="card admin-panel">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">Bookings</h3>
                <p class="admin-panel-copy">Open individual reservations to confirm details, update status, and notify guests.</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-4 admin-table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th>Reference</th>
                            <th>Guest</th>
                            <th>Booking</th>
                            <th>Party</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-700">
                        @forelse($bookings as $booking)
                            @php
                                $statusBadge = match ($booking->status) {
                                    \App\Models\Booking::STATUS_CONFIRMED => 'badge-light-success',
                                    \App\Models\Booking::STATUS_CANCELLED => 'badge-light-danger',
                                    default => 'badge-light-warning',
                                };
                                $paymentBadge = match ($booking->payment_status) {
                                    \App\Models\Booking::PAYMENT_STATUS_PAID => 'badge-light-success',
                                    \App\Models\Booking::PAYMENT_STATUS_CANCELLED => 'badge-light-danger',
                                    default => 'badge-light-warning',
                                };
                            @endphp
                            <tr>
                                <td class="text-gray-900 fw-bold">{{ $booking->formattedReference() }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900">{{ $booking->full_name }}</span>
                                        <span class="text-gray-500 fs-8">{{ $booking->email }}</span>
                                        <span class="text-gray-500 fs-8">{{ $booking->phone }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ optional($booking->date)->format('d M Y') }}</span>
                                        <span class="text-gray-500 fs-8">{{ \Illuminate\Support\Carbon::parse($booking->time)->format('H:i') }}</span>
                                        <span class="text-gray-500 fs-8">{{ $bookingTypeLabels[$booking->booking_type] ?? ucfirst($booking->booking_type) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $booking->persons }} guests</span>
                                        <span class="text-gray-500 fs-8">{{ $booking->dineInSlot?->name ?: 'No slot' }}</span>
                                    </div>
                                </td>
                                <td><span class="badge {{ $statusBadge }}">{{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}</span></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge {{ $paymentBadge }}">{{ $paymentStatusLabels[$booking->payment_status] ?? ucfirst($booking->payment_status) }}</span>
                                        <span class="text-gray-500 fs-8 mt-2">{{ $paymentMethodLabels[$booking->payment_method] ?? 'Not set' }}</span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-light-primary">Open</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="admin-empty">No bookings found for the current filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-4">
                {{ $bookings->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
