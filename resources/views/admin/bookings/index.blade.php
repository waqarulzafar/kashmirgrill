@extends('admin.layout')

@section('admin_title', 'Booking Management')
@section('admin_description', 'Review incoming reservations, keep the queue organized, and move each guest cleanly from pending to confirmed.')

@section('admin_actions')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-light-primary">Back to Dashboard</a>
@endsection

@section('admin_content')
    @php
        $activeFilterCount = collect($filters)->filter(fn ($value) => $value !== '' && $value !== 'all')->count();
        $activeFilterChips = array_filter([
            $filters['date_from'] && $filters['date_to']
                ? 'Window: '.\Illuminate\Support\Carbon::parse($filters['date_from'])->format('d M').' - '.\Illuminate\Support\Carbon::parse($filters['date_to'])->format('d M')
                : null,
            $filters['booking_type'] !== 'all'
                ? 'Type: '.($bookingTypeLabels[$filters['booking_type']] ?? ucfirst($filters['booking_type']))
                : null,
            $filters['status'] !== 'all'
                ? 'Status: '.($statusLabels[$filters['status']] ?? ucfirst($filters['status']))
                : null,
            $filters['payment_status'] !== 'all'
                ? 'Payment: '.($paymentStatusLabels[$filters['payment_status']] ?? ucfirst($filters['payment_status']))
                : null,
            $filters['search'] !== ''
                ? 'Search: '.$filters['search']
                : null,
        ]);
        $statCards = [
            ['label' => 'Total Bookings', 'value' => number_format($stats['total']), 'tone' => 'primary', 'icon' => 'ki-calendar-8'],
            ['label' => 'Pending Review', 'value' => number_format($stats['pending']), 'tone' => 'warning', 'icon' => 'ki-time'],
            ['label' => 'Confirmed', 'value' => number_format($stats['confirmed']), 'tone' => 'success', 'icon' => 'ki-check-circle'],
            ['label' => 'Cancelled', 'value' => number_format($stats['cancelled']), 'tone' => 'danger', 'icon' => 'ki-cross-circle'],
            ['label' => 'Paid', 'value' => number_format($stats['paid']), 'tone' => 'info', 'icon' => 'ki-wallet'],
            ['label' => 'Upcoming', 'value' => number_format($stats['upcoming']), 'tone' => 'dark', 'icon' => 'ki-calendar-tick'],
        ];
    @endphp

    <div class="card card-flush mb-7">
        <div class="card-header align-items-center py-7 gap-4">
            <div class="card-title">
                <div class="d-flex flex-column">
                    <span class="fs-2 fw-bold text-gray-900">Filter Reservation Queue</span>
                    <span class="fs-6 text-gray-600">Search by guest, date range, booking type, reservation status, or payment state.</span>
                </div>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-light">Reset Filters</a>
            </div>
        </div>
        <div class="card-body pt-0">
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-5 align-items-end">
                <div class="col-lg-2 col-md-4">
                    <label for="date_from" class="form-label fw-semibold fs-7 text-uppercase text-muted">Date From</label>
                    <input id="date_from" type="date" name="date_from" class="form-control form-control-solid" value="{{ $filters['date_from'] }}">
                </div>
                <div class="col-lg-2 col-md-4">
                    <label for="date_to" class="form-label fw-semibold fs-7 text-uppercase text-muted">Date To</label>
                    <input id="date_to" type="date" name="date_to" class="form-control form-control-solid" value="{{ $filters['date_to'] }}">
                </div>
                <div class="col-lg-2 col-md-4">
                    <label for="booking_type" class="form-label fw-semibold fs-7 text-uppercase text-muted">Booking Type</label>
                    <select id="booking_type" name="booking_type" class="form-select form-select-solid">
                        <option value="all" @selected($filters['booking_type'] === 'all')>All types</option>
                        @foreach($bookingTypeLabels as $value => $label)
                            <option value="{{ $value }}" @selected($filters['booking_type'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4">
                    <label for="status" class="form-label fw-semibold fs-7 text-uppercase text-muted">Status</label>
                    <select id="status" name="status" class="form-select form-select-solid">
                        <option value="all" @selected($filters['status'] === 'all')>All statuses</option>
                        @foreach($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4">
                    <label for="payment_status" class="form-label fw-semibold fs-7 text-uppercase text-muted">Payment</label>
                    <select id="payment_status" name="payment_status" class="form-select form-select-solid">
                        <option value="all" @selected($filters['payment_status'] === 'all')>All payment states</option>
                        @foreach($paymentStatusLabels as $value => $label)
                            <option value="{{ $value }}" @selected($filters['payment_status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-8">
                    <label for="search" class="form-label fw-semibold fs-7 text-uppercase text-muted">Guest Search</label>
                    <div class="input-group input-group-solid">
                        <span class="input-group-text">
                            <i class="ki-duotone ki-magnifier fs-4"></i>
                        </span>
                        <input id="search" type="text" name="search" class="form-control form-control-solid ps-0" placeholder="Name, email, phone" value="{{ $filters['search'] }}">
                    </div>
                </div>
                <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-3 pt-2">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-setting-3 fs-4 me-1"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-light">Clear</a>
                    </div>
                    <div class="text-muted fs-7 fw-semibold">
                        {{ $activeFilterCount }} active filter{{ $activeFilterCount === 1 ? '' : 's' }} across {{ number_format($bookings->total()) }} booking{{ $bookings->total() === 1 ? '' : 's' }}.
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-5 mb-7">
        @foreach($statCards as $statCard)
            <div class="col-sm-6 col-xl-2">
                <div class="card card-flush h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="symbol symbol-40px mb-5">
                            <span class="symbol-label bg-light-{{ $statCard['tone'] }} text-{{ $statCard['tone'] }}">
                                <i class="ki-duotone {{ $statCard['icon'] }} fs-2 text-{{ $statCard['tone'] }}"></i>
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600 fw-semibold d-block fs-7 text-uppercase mb-2">{{ $statCard['label'] }}</span>
                            <span class="fs-2hx fw-bold text-gray-900 lh-1">{{ $statCard['value'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card card-flush">
        <div class="card-header align-items-center py-7 gap-4">
            <div class="card-title">
                <div class="d-flex flex-column">
                    <span class="fs-2 fw-bold text-gray-900">Live Booking Queue</span>
                    <span class="fs-6 text-gray-600">Open any reservation to confirm details, update status, or review payment intent.</span>
                </div>
            </div>
            <div class="card-toolbar flex-wrap gap-2">
                @forelse($activeFilterChips as $chip)
                    <span class="badge badge-light-primary px-4 py-3 fs-8">{{ $chip }}</span>
                @empty
                    <span class="badge badge-light px-4 py-3 fs-8">Showing default date window</span>
                @endforelse
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-150px">Guest</th>
                            <th class="min-w-140px">Reference</th>
                            <th class="min-w-190px">Visit Window</th>
                            <th class="min-w-150px">Party</th>
                            <th class="min-w-140px">Status</th>
                            <th class="min-w-170px">Payment</th>
                            <th class="min-w-125px text-end">Action</th>
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
                                $guestInitial = strtoupper(mb_substr(trim($booking->full_name), 0, 1));
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-45px me-4">
                                            <span class="symbol-label bg-light-primary text-primary fs-6 fw-bold">{{ $guestInitial }}</span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-900 fw-bold fs-6">{{ $booking->full_name }}</span>
                                            <a href="mailto:{{ $booking->email }}" class="text-gray-600 text-hover-primary fs-7">{{ $booking->email }}</a>
                                            <a href="tel:{{ $booking->phone }}" class="text-gray-500 text-hover-primary fs-8">{{ $booking->phone }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 fw-bold">{{ $booking->formattedReference() }}</span>
                                        <span class="text-gray-500 fs-8">Created {{ optional($booking->created_at)->format('d M Y H:i') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 fw-bold">{{ optional($booking->date)->format('D, d M Y') }}</span>
                                        <span class="text-gray-600 fs-7">{{ \Illuminate\Support\Carbon::parse($booking->time)->format('H:i') }}</span>
                                        <span class="text-gray-500 fs-8">{{ $bookingTypeLabels[$booking->booking_type] ?? ucfirst($booking->booking_type) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 fw-bold">{{ $booking->persons }} guests</span>
                                        <span class="text-gray-500 fs-8">{{ $booking->dineInSlot?->name ?: 'Slot not assigned' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        <span class="badge {{ $statusBadge }} align-self-start">{{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}</span>
                                        <span class="text-gray-500 fs-8">
                                            <span class="bullet bullet-dot bg-gray-400 me-2"></span>
                                            {{ $booking->booking_type === \App\Models\Booking::TYPE_EVENT ? 'Event booking' : 'Table booking' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        <span class="badge {{ $paymentBadge }} align-self-start">{{ $paymentStatusLabels[$booking->payment_status] ?? ucfirst($booking->payment_status) }}</span>
                                        <span class="text-gray-500 fs-8">{{ $paymentMethodLabels[$booking->payment_method] ?? 'Not set' }}</span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-light-primary">
                                        Open
                                        <i class="ki-duotone ki-arrow-right fs-5 ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="d-flex flex-column flex-center py-15">
                                        <div class="symbol symbol-80px mb-5">
                                            <span class="symbol-label bg-light-warning">
                                                <i class="ki-duotone ki-information-5 fs-1 text-warning">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </span>
                                        </div>
                                        <div class="text-center">
                                            <div class="fs-3 fw-bold text-gray-900 mb-2">No bookings matched the current view</div>
                                            <div class="text-gray-600 fs-6">Adjust the date window or clear one of the active filters to widen the queue.</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-4 pt-7">
                <div class="text-muted fs-7 fw-semibold">
                    Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ number_format($bookings->total()) }} bookings
                </div>
                {{ $bookings->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
