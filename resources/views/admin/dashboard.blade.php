@extends('layouts.mainadmin')

@section('title')
    <div id="kt_app_header_page_title_wrapper">
        <div
            class="page-title d-flex flex-column justify-content-center me-3 mb-0">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Bookings Dashboard</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Admin</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Bookings Overview</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <div class="card mb-7">
                        <div class="card-header border-0 pt-6">
                            <h3 class="card-title fw-bold">Filters</h3>
                        </div>
                        <div class="card-body pt-0">
                            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-5 align-items-end">
                                <div class="col-md-3">
                                    <label for="date_from" class="form-label fw-semibold">Date From</label>
                                    <input id="date_from" type="date" name="date_from" class="form-control form-control-solid" value="{{ $filters['date_from'] }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="date_to" class="form-label fw-semibold">Date To</label>
                                    <input id="date_to" type="date" name="date_to" class="form-control form-control-solid" value="{{ $filters['date_to'] }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="booking_type" class="form-label fw-semibold">Booking Type</label>
                                    <select id="booking_type" name="booking_type" class="form-select form-select-solid">
                                        <option value="all" @selected($filters['booking_type'] === 'all')>All</option>
                                        <option value="{{ \App\Models\Booking::TYPE_TABLE }}" @selected($filters['booking_type'] === \App\Models\Booking::TYPE_TABLE)>Table</option>
                                        <option value="{{ \App\Models\Booking::TYPE_EVENT }}" @selected($filters['booking_type'] === \App\Models\Booking::TYPE_EVENT)>Event</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="status" class="form-label fw-semibold">Status</label>
                                    <select id="status" name="status" class="form-select form-select-solid">
                                        <option value="all" @selected($filters['status'] === 'all')>All</option>
                                        <option value="{{ \App\Models\Booking::STATUS_PENDING }}" @selected($filters['status'] === \App\Models\Booking::STATUS_PENDING)>Pending</option>
                                        <option value="{{ \App\Models\Booking::STATUS_CONFIRMED }}" @selected($filters['status'] === \App\Models\Booking::STATUS_CONFIRMED)>Confirmed</option>
                                        <option value="{{ \App\Models\Booking::STATUS_CANCELLED }}" @selected($filters['status'] === \App\Models\Booking::STATUS_CANCELLED)>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="search" class="form-label fw-semibold">Search</label>
                                    <input id="search" type="text" name="search" class="form-control form-control-solid" placeholder="Name, email, phone" value="{{ $filters['search'] }}">
                                </div>
                                <div class="col-12 d-flex gap-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ki-duotone ki-magnifier fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                                        Apply Filters
                                    </button>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-flush h-xl-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-gray-600 fw-semibold">Total Bookings</span>
                                        <i class="ki-duotone ki-calendar-8 fs-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </div>
                                    <div class="mt-4">
                                        <span class="fs-2hx fw-bold text-gray-900">{{ number_format($stats['total_bookings']) }}</span>
                                    </div>
                                    <span class="text-gray-500 fs-7">Today: {{ number_format($stats['today_bookings']) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-flush h-xl-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-gray-600 fw-semibold">Pending</span>
                                        <span class="badge badge-light-warning">Awaiting Review</span>
                                    </div>
                                    <div class="mt-4">
                                        <span class="fs-2hx fw-bold text-warning">{{ number_format($stats['pending_bookings']) }}</span>
                                    </div>
                                    <span class="text-gray-500 fs-7">Requires follow-up by staff</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-flush h-xl-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-gray-600 fw-semibold">Confirmed</span>
                                        <span class="badge badge-light-success">Ready</span>
                                    </div>
                                    <div class="mt-4">
                                        <span class="fs-2hx fw-bold text-success">{{ number_format($stats['confirmed_bookings']) }}</span>
                                    </div>
                                    <span class="text-gray-500 fs-7">Cancelled: {{ number_format($stats['cancelled_bookings']) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-flush h-xl-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-gray-600 fw-semibold">Guests & Capacity</span>
                                        <i class="ki-duotone ki-people fs-2 text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </div>
                                    <div class="mt-4">
                                        <span class="fs-2hx fw-bold text-gray-900">{{ number_format($stats['total_guests']) }}</span>
                                    </div>
                                    <span class="text-gray-500 fs-7">Avg party: {{ number_format($stats['average_party_size'], 1) }} | Active slots: {{ number_format($stats['active_slots']) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                        <div class="col-xl-8">
                            <div class="card card-flush h-xl-100">
                                <div class="card-header pt-7">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-900">Booking Trend</span>
                                        <span class="text-gray-500 pt-2 fs-6">Daily reservations for selected filters</span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div id="kt_admin_booking_trend_chart" style="height: 360px;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card card-flush mb-5 mb-xl-10">
                                <div class="card-header pt-7">
                                    <h3 class="card-title fw-bold text-gray-900">Booking Type Split</h3>
                                </div>
                                <div class="card-body pt-0">
                                    <div id="kt_admin_booking_type_chart" style="height: 240px;"></div>
                                    <div class="d-flex flex-wrap pt-5 gap-4">
                                        <div class="d-flex align-items-center">
                                            <span class="bullet bullet-dot bg-primary me-2"></span>
                                            <span class="fs-7 text-gray-600">Table: {{ number_format($stats['table_bookings']) }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="bullet bullet-dot bg-danger me-2"></span>
                                            <span class="fs-7 text-gray-600">Event: {{ number_format($stats['event_bookings']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-flush h-xl-100">
                                <div class="card-header pt-7">
                                    <h3 class="card-title fw-bold text-gray-900">Status Distribution</h3>
                                </div>
                                <div class="card-body pt-0">
                                    <div id="kt_admin_booking_status_chart" style="height: 210px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 g-xl-10">
                        <div class="col-xl-8">
                            <div class="card card-flush h-xl-100">
                                <div class="card-header pt-7">
                                    <h3 class="card-title fw-bold text-gray-900">Latest Bookings</h3>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed fs-6 gy-4">
                                            <thead>
                                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                    <th>Reference</th>
                                                    <th>Guest</th>
                                                    <th>Date & Time</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
                                                    <th>Party</th>
                                                    <th>Slot</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-semibold text-gray-700">
                                                @forelse($latestBookings as $booking)
                                                    <tr>
                                                        <td class="text-gray-900 fw-bold">KGH-{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <span class="text-gray-900">{{ $booking->full_name }}</span>
                                                                <span class="text-gray-500 fs-8">{{ $booking->email }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <span>{{ optional($booking->date)->format('d M Y') }}</span>
                                                                <span class="text-gray-500 fs-8">{{ \Illuminate\Support\Carbon::parse($booking->time)->format('H:i') }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge @if($booking->booking_type === \App\Models\Booking::TYPE_EVENT) badge-light-danger @else badge-light-primary @endif">
                                                                {{ $booking->booking_type === \App\Models\Booking::TYPE_EVENT ? 'Event' : 'Table' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge @if($booking->status === \App\Models\Booking::STATUS_CONFIRMED) badge-light-success @elseif($booking->status === \App\Models\Booking::STATUS_CANCELLED) badge-light-danger @else badge-light-warning @endif">
                                                                {{ ucfirst($booking->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $booking->persons }}</td>
                                                        <td>{{ $booking->dineInSlot?->name ?: 'N/A' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center py-10 text-gray-500">No bookings match the current filters.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="pt-4">
                                        {{ $latestBookings->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card card-flush mb-5 mb-xl-10">
                                <div class="card-header pt-7">
                                    <h3 class="card-title fw-bold text-gray-900">Top Reserved Slots</h3>
                                </div>
                                <div class="card-body pt-0">
                                    @forelse($topSlots as $slotRow)
                                        <div class="d-flex align-items-center mb-5">
                                            <div class="symbol symbol-40px me-4">
                                                <div class="symbol-label fs-6 fw-bold bg-light-primary text-primary">
                                                    {{ $loop->iteration }}
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span class="text-gray-800 fw-bold">{{ $slotRow->dineInSlot?->name ?: 'Unknown Slot' }}</span>
                                                <span class="text-gray-500 fs-8">{{ $slotRow->total }} bookings</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-gray-500 mb-0">No slot bookings for the selected filters.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="card card-flush h-xl-100">
                                <div class="card-header pt-7">
                                    <h3 class="card-title fw-bold text-gray-900">Quick Actions</h3>
                                </div>
                                <div class="card-body d-flex flex-column gap-3">
                                    <a href="{{ route('admin.dine-in-slots.index') }}" class="btn btn-light-primary btn-flex justify-content-start">
                                        <i class="ki-duotone ki-time fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                                        Manage Dine-In Slots
                                    </a>
                                    <a href="{{ route('admin.menu-items.index') }}" class="btn btn-light-info btn-flex justify-content-start">
                                        <i class="ki-duotone ki-basket fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                                        Manage Menu Items
                                    </a>
                                    <a href="{{ route('book-now') }}" target="_blank" rel="noopener" class="btn btn-light-success btn-flex justify-content-start">
                                        <i class="ki-duotone ki-element-11 fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                                        Open Booking Page
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('partials.admin.footer')
    </div>
@endsection

@section('scripts')
    <script>
        (() => {
            const trendData = @json($charts['trend']);
            const typeData = @json($charts['booking_types']);
            const statusData = @json($charts['statuses']);

            if (typeof ApexCharts === 'undefined') {
                return;
            }

            const trendElement = document.getElementById('kt_admin_booking_trend_chart');
            if (trendElement) {
                new ApexCharts(trendElement, {
                    chart: {
                        type: 'area',
                        height: 360,
                        toolbar: { show: false },
                        fontFamily: 'inherit',
                    },
                    series: [{
                        name: 'Bookings',
                        data: trendData.series,
                    }],
                    xaxis: {
                        categories: trendData.labels,
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3,
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.35,
                            opacityTo: 0.02,
                            stops: [0, 95, 100],
                        },
                    },
                    colors: ['#009ef7'],
                    grid: {
                        strokeDashArray: 4,
                    },
                    tooltip: {
                        y: {
                            formatter: (value) => `${value} bookings`,
                        },
                    },
                }).render();
            }

            const typeElement = document.getElementById('kt_admin_booking_type_chart');
            if (typeElement) {
                new ApexCharts(typeElement, {
                    chart: {
                        type: 'donut',
                        height: 240,
                        fontFamily: 'inherit',
                    },
                    labels: typeData.labels,
                    series: typeData.series,
                    colors: ['#009ef7', '#f1416c'],
                    dataLabels: {
                        enabled: true,
                    },
                    legend: {
                        position: 'bottom',
                    },
                }).render();
            }

            const statusElement = document.getElementById('kt_admin_booking_status_chart');
            if (statusElement) {
                new ApexCharts(statusElement, {
                    chart: {
                        type: 'bar',
                        height: 210,
                        toolbar: { show: false },
                        fontFamily: 'inherit',
                    },
                    series: [{
                        name: 'Bookings',
                        data: statusData.series,
                    }],
                    xaxis: {
                        categories: statusData.labels,
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 6,
                            columnWidth: '48%',
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    colors: ['#ffc700', '#50cd89', '#f1416c'],
                    legend: {
                        show: false,
                    },
                }).render();
            }
        })();
    </script>
@endsection
