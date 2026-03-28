@extends('admin.layout')

@section('admin_title', 'Operations Dashboard')
@section('admin_description', 'Track reservation demand, monitor slot usage, and move quickly between the most important restaurant workflows from one clear control surface.')

@section('admin_actions')
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary">Review Bookings</a>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-light">Review Orders</a>
@endsection

@php
    $confirmationRate = $stats['total_bookings'] > 0
        ? round(($stats['confirmed_bookings'] / $stats['total_bookings']) * 100)
        : 0;
    $pendingRate = $stats['total_bookings'] > 0
        ? round(($stats['pending_bookings'] / $stats['total_bookings']) * 100)
        : 0;
    $eventShare = $stats['total_bookings'] > 0
        ? round(($stats['event_bookings'] / $stats['total_bookings']) * 100)
        : 0;
    $rangeLabel = \Illuminate\Support\Carbon::parse($filters['date_from'])->format('d M Y')
        .' - '.
        \Illuminate\Support\Carbon::parse($filters['date_to'])->format('d M Y');
    $primaryInsight = match (true) {
        $stats['pending_bookings'] > 0 => __(':count bookings still need staff follow-up.', ['count' => $stats['pending_bookings']]),
        $stats['confirmed_bookings'] > 0 => __('Confirmation flow is healthy across the selected range.'),
        default => __('No booking pressure detected for the current dashboard filters.'),
    };
    $secondaryInsight = $topSlots->first()?->dineInSlot?->name
        ? __('Most reserved slot: :slot', ['slot' => $topSlots->first()->dineInSlot->name])
        : __('No slot demand pattern available yet.');
@endphp

@section('admin_content')
    <section class="dashboard-orbit mb-7">
        <div class="dashboard-orbit__glow dashboard-orbit__glow--red" aria-hidden="true"></div>
        <div class="dashboard-orbit__glow dashboard-orbit__glow--amber" aria-hidden="true"></div>

        <div class="row g-5 align-items-stretch">
            <div class="col-xl-8">
                <article class="dashboard-orbit__hero h-100">
                    <div class="dashboard-orbit__eyebrow">{{ __('Live Booking Command') }}</div>
                    <h2 class="dashboard-orbit__title">{{ __('A sharper control surface for reservations, slot demand, and guest flow.') }}</h2>
                    <p class="dashboard-orbit__copy">{{ $primaryInsight }} {{ $secondaryInsight }}</p>

                    <div class="dashboard-orbit__chips">
                        <span>{{ __('Range: :range', ['range' => $rangeLabel]) }}</span>
                        <span>{{ __('Today: :count bookings', ['count' => number_format($stats['today_bookings'])]) }}</span>
                        <span>{{ __('Event share: :percent%', ['percent' => $eventShare]) }}</span>
                    </div>

                    <div class="dashboard-orbit__actions">
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary">{{ __('Open Reservation Queue') }}</a>
                        <a href="{{ route('book-now') }}" target="_blank" rel="noopener" class="btn btn-light">{{ __('Preview Frontend Booking Flow') }}</a>
                    </div>
                </article>
            </div>

            <div class="col-xl-4">
                <article class="dashboard-orbit__signal h-100">
                    <div class="dashboard-orbit__signal-label">{{ __('Service Signal') }}</div>
                    <div class="dashboard-orbit__signal-value">{{ $confirmationRate }}%</div>
                    <p class="dashboard-orbit__signal-copy">{{ __('Confirmed share across selected bookings') }}</p>

                    <div class="dashboard-orbit__signal-grid">
                        <div>
                            <span>{{ __('Pending load') }}</span>
                            <strong>{{ $pendingRate }}%</strong>
                        </div>
                        <div>
                            <span>{{ __('Average party') }}</span>
                            <strong>{{ number_format($stats['average_party_size'], 1) }}</strong>
                        </div>
                        <div>
                            <span>{{ __('Active slots') }}</span>
                            <strong>{{ number_format($stats['active_slots']) }}</strong>
                        </div>
                        <div>
                            <span>{{ __('Guests total') }}</span>
                            <strong>{{ number_format($stats['total_guests']) }}</strong>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <div class="card admin-panel dashboard-filter-shell mb-7">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">{{ __('Filter Activity') }}</h3>
                <p class="admin-panel-copy">{{ __('Refine this dashboard by date, booking type, reservation state, or guest information.') }}</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-4 align-items-end">
                <div class="col-md-3">
                    <label for="date_from" class="form-label fw-semibold">{{ __('Date From') }}</label>
                    <input id="date_from" type="date" name="date_from" class="form-control form-control-solid" value="{{ $filters['date_from'] }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label fw-semibold">{{ __('Date To') }}</label>
                    <input id="date_to" type="date" name="date_to" class="form-control form-control-solid" value="{{ $filters['date_to'] }}">
                </div>
                <div class="col-md-2">
                    <label for="booking_type" class="form-label fw-semibold">{{ __('Booking Type') }}</label>
                    <select id="booking_type" name="booking_type" class="form-select form-select-solid">
                        <option value="all" @selected($filters['booking_type'] === 'all')>{{ __('All') }}</option>
                        <option value="{{ \App\Models\Booking::TYPE_TABLE }}" @selected($filters['booking_type'] === \App\Models\Booking::TYPE_TABLE)>{{ __('Table') }}</option>
                        <option value="{{ \App\Models\Booking::TYPE_EVENT }}" @selected($filters['booking_type'] === \App\Models\Booking::TYPE_EVENT)>{{ __('Event') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label fw-semibold">{{ __('Status') }}</label>
                    <select id="status" name="status" class="form-select form-select-solid">
                        <option value="all" @selected($filters['status'] === 'all')>{{ __('All') }}</option>
                        <option value="{{ \App\Models\Booking::STATUS_PENDING }}" @selected($filters['status'] === \App\Models\Booking::STATUS_PENDING)>{{ __('Pending') }}</option>
                        <option value="{{ \App\Models\Booking::STATUS_CONFIRMED }}" @selected($filters['status'] === \App\Models\Booking::STATUS_CONFIRMED)>{{ __('Confirmed') }}</option>
                        <option value="{{ \App\Models\Booking::STATUS_CANCELLED }}" @selected($filters['status'] === \App\Models\Booking::STATUS_CANCELLED)>{{ __('Cancelled') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="search" class="form-label fw-semibold">{{ __('Search') }}</label>
                    <input id="search" type="text" name="search" class="form-control form-control-solid" placeholder="{{ __('Name, email, phone') }}" value="{{ $filters['search'] }}">
                </div>
                <div class="col-12 d-flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-duotone ki-magnifier fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                        {{ __('Apply Filters') }}
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light">{{ __('Reset') }}</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-5 mb-7">
        <div class="col-sm-6 col-xl-3">
            <article class="dashboard-kpi-card dashboard-kpi-card--ruby">
                <div class="dashboard-kpi-card__top">
                    <span class="dashboard-kpi-card__label">{{ __('Total Bookings') }}</span>
                    <span class="dashboard-kpi-card__icon"><i class="ki-duotone ki-calendar-8 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                </div>
                <div class="dashboard-kpi-card__value">{{ number_format($stats['total_bookings']) }}</div>
                <div class="dashboard-kpi-card__meta">{{ __('Today: :count', ['count' => number_format($stats['today_bookings'])]) }}</div>
            </article>
        </div>

        <div class="col-sm-6 col-xl-3">
            <article class="dashboard-kpi-card dashboard-kpi-card--amber">
                <div class="dashboard-kpi-card__top">
                    <span class="dashboard-kpi-card__label">{{ __('Pending Review') }}</span>
                    <span class="dashboard-kpi-card__pill">{{ __('Needs action') }}</span>
                </div>
                <div class="dashboard-kpi-card__value">{{ number_format($stats['pending_bookings']) }}</div>
                <div class="dashboard-kpi-card__meta">{{ __(':percent% of selected bookings', ['percent' => $pendingRate]) }}</div>
            </article>
        </div>

        <div class="col-sm-6 col-xl-3">
            <article class="dashboard-kpi-card dashboard-kpi-card--emerald">
                <div class="dashboard-kpi-card__top">
                    <span class="dashboard-kpi-card__label">{{ __('Confirmed') }}</span>
                    <span class="dashboard-kpi-card__pill dashboard-kpi-card__pill--success">{{ __('Stable') }}</span>
                </div>
                <div class="dashboard-kpi-card__value">{{ number_format($stats['confirmed_bookings']) }}</div>
                <div class="dashboard-kpi-card__meta">{{ __('Cancelled: :count', ['count' => number_format($stats['cancelled_bookings'])]) }}</div>
            </article>
        </div>

        <div class="col-sm-6 col-xl-3">
            <article class="dashboard-kpi-card dashboard-kpi-card--coal">
                <div class="dashboard-kpi-card__top">
                    <span class="dashboard-kpi-card__label">{{ __('Guest Volume') }}</span>
                    <span class="dashboard-kpi-card__icon"><i class="ki-duotone ki-people fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                </div>
                <div class="dashboard-kpi-card__value">{{ number_format($stats['total_guests']) }}</div>
                <div class="dashboard-kpi-card__meta">{{ __('Avg party: :value | Slots: :count', ['value' => number_format($stats['average_party_size'], 1), 'count' => number_format($stats['active_slots'])]) }}</div>
            </article>
        </div>
    </div>

    <div class="row g-5 mb-7">
        <div class="col-xl-8">
            <div class="card admin-panel dashboard-chart-card h-100">
                <div class="card-header border-0 pt-7">
                    <div>
                        <h3 class="card-title fw-bold text-gray-900 mb-1">{{ __('Booking Trend') }}</h3>
                        <p class="dashboard-card-copy mb-0">{{ __('Daily reservation volume across the selected date range.') }}</p>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <div id="kt_admin_booking_trend_chart" style="height: 360px;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card admin-panel dashboard-chart-card mb-5">
                <div class="card-header border-0 pt-7">
                    <div>
                        <h3 class="card-title fw-bold text-gray-900 mb-1">{{ __('Booking Mix') }}</h3>
                        <p class="dashboard-card-copy mb-0">{{ __('Table versus event reservation balance.') }}</p>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div id="kt_admin_booking_type_chart" style="height: 240px;"></div>
                    <div class="dashboard-legend">
                        <span><i class="dashboard-legend__dot dashboard-legend__dot--primary"></i>{{ __('Table: :count', ['count' => number_format($stats['table_bookings'])]) }}</span>
                        <span><i class="dashboard-legend__dot dashboard-legend__dot--accent"></i>{{ __('Event: :count', ['count' => number_format($stats['event_bookings'])]) }}</span>
                    </div>
                </div>
            </div>

            <div class="card admin-panel dashboard-chart-card h-100">
                <div class="card-header border-0 pt-7">
                    <div>
                        <h3 class="card-title fw-bold text-gray-900 mb-1">{{ __('Status Distribution') }}</h3>
                        <p class="dashboard-card-copy mb-0">{{ __('Pending, confirmed, and cancelled split.') }}</p>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div id="kt_admin_booking_status_chart" style="height: 210px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5">
        <div class="col-xl-8">
            <div class="card admin-panel dashboard-table-card h-100">
                <div class="card-header border-0 pt-7">
                    <div>
                        <h3 class="card-title fw-bold text-gray-900 mb-1">{{ __('Latest Bookings') }}</h3>
                        <p class="dashboard-card-copy mb-0">{{ __('Most recent reservations inside the current filter window.') }}</p>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-4 admin-table dashboard-booking-table">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th>{{ __('Reference') }}</th>
                                    <th>{{ __('Guest') }}</th>
                                    <th>{{ __('Visit Window') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Party') }}</th>
                                    <th>{{ __('Slot') }}</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-700">
                                @forelse($latestBookings as $booking)
                                    @php
                                        $typeLabel = $booking->booking_type === \App\Models\Booking::TYPE_EVENT ? __('Event') : __('Table');
                                        $typeClass = $booking->booking_type === \App\Models\Booking::TYPE_EVENT ? 'is-event' : 'is-table';
                                        $statusClass = match ($booking->status) {
                                            \App\Models\Booking::STATUS_CONFIRMED => 'is-confirmed',
                                            \App\Models\Booking::STATUS_CANCELLED => 'is-cancelled',
                                            default => 'is-pending',
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="dashboard-booking-ref">
                                                <strong>KGH-{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dashboard-booking-guest">
                                                <strong>{{ $booking->full_name }}</strong>
                                                <span>{{ $booking->email }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dashboard-booking-visit">
                                                <strong>{{ optional($booking->date)->format('d M Y') }}</strong>
                                                <span>{{ \Illuminate\Support\Carbon::parse($booking->time)->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="dashboard-tag {{ $typeClass }}">{{ $typeLabel }}</span>
                                        </td>
                                        <td>
                                            <span class="dashboard-tag {{ $statusClass }}">{{ ucfirst($booking->status) }}</span>
                                        </td>
                                        <td>{{ $booking->persons }}</td>
                                        <td>{{ $booking->dineInSlot?->name ?: __('N/A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="admin-empty">{{ __('No bookings match the current filters.') }}</td>
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
            <div class="card admin-panel dashboard-side-card mb-5">
                <div class="card-header border-0 pt-7">
                    <div>
                        <h3 class="card-title fw-bold text-gray-900 mb-1">{{ __('Top Reserved Slots') }}</h3>
                        <p class="dashboard-card-copy mb-0">{{ __('The reservation windows guests are selecting most often.') }}</p>
                    </div>
                </div>
                <div class="card-body pt-3">
                    @forelse($topSlots as $slotRow)
                        <div class="dashboard-slot-row">
                            <div class="dashboard-slot-rank">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                            <div class="dashboard-slot-copy">
                                <strong>{{ $slotRow->dineInSlot?->name ?: __('Unknown Slot') }}</strong>
                                <span>{{ __(':count bookings', ['count' => $slotRow->total]) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="admin-empty mb-0">{{ __('No slot bookings for the selected filters.') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="card admin-panel dashboard-side-card h-100">
                <div class="card-header border-0 pt-7">
                    <div>
                        <h3 class="card-title fw-bold text-gray-900 mb-1">{{ __('Quick Actions') }}</h3>
                        <p class="dashboard-card-copy mb-0">{{ __('Jump directly into the core operating surfaces.') }}</p>
                    </div>
                </div>
                <div class="card-body d-flex flex-column gap-3 pt-3">
                    <a href="{{ route('admin.dine-in-slots.index') }}" class="dashboard-action-card">
                        <span class="dashboard-action-card__icon"><i class="ki-duotone ki-time fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="dashboard-action-card__copy">
                            <strong>{{ __('Manage Dine-In Slots') }}</strong>
                            <small>{{ __('Adjust bookable windows and dining capacity.') }}</small>
                        </span>
                    </a>
                    <a href="{{ route('admin.menu-items.index') }}" class="dashboard-action-card">
                        <span class="dashboard-action-card__icon"><i class="ki-duotone ki-basket fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="dashboard-action-card__copy">
                            <strong>{{ __('Manage Menu Items') }}</strong>
                            <small>{{ __('Keep live dishes, prices, and availability aligned.') }}</small>
                        </span>
                    </a>
                    <a href="{{ route('book-now') }}" target="_blank" rel="noopener" class="dashboard-action-card">
                        <span class="dashboard-action-card__icon"><i class="ki-duotone ki-element-11 fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="dashboard-action-card__copy">
                            <strong>{{ __('Open Booking Page') }}</strong>
                            <small>{{ __('Inspect the guest-facing reservation flow in a new tab.') }}</small>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .dashboard-orbit {
            position: relative;
            overflow: hidden;
        }

        .dashboard-orbit__glow {
            position: absolute;
            inset: auto auto 0 0;
            width: 18rem;
            height: 18rem;
            border-radius: 999px;
            filter: blur(70px);
            pointer-events: none;
            opacity: .24;
        }

        .dashboard-orbit__glow--red {
            top: -2rem;
            left: -2rem;
            background: rgba(219, 29, 48, 0.85);
        }

        .dashboard-orbit__glow--amber {
            top: 1.5rem;
            right: -1rem;
            left: auto;
            background: rgba(255, 149, 44, 0.72);
        }

        .dashboard-orbit__hero,
        .dashboard-orbit__signal,
        .dashboard-kpi-card,
        .dashboard-chart-card,
        .dashboard-table-card,
        .dashboard-side-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.4rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0)),
                linear-gradient(135deg, rgba(7, 7, 7, 0.96), rgba(17, 17, 17, 0.94));
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.16);
        }

        .dashboard-orbit__hero,
        .dashboard-orbit__signal {
            min-height: 100%;
            padding: 2rem 2.1rem;
        }

        .dashboard-orbit__eyebrow,
        .dashboard-orbit__signal-label,
        .dashboard-kpi-card__label {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: rgba(255, 255, 255, 0.62);
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        .dashboard-orbit__title {
            margin: .9rem 0 .8rem;
            color: #fff;
            font-size: clamp(1.8rem, 3vw, 2.55rem);
            line-height: 1;
            font-weight: 800;
        }

        .dashboard-orbit__copy,
        .dashboard-card-copy {
            color: rgba(255, 255, 255, 0.7);
            font-size: .96rem;
            line-height: 1.65;
        }

        .dashboard-orbit__chips {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            margin-top: 1.1rem;
        }

        .dashboard-orbit__chips span,
        .dashboard-kpi-card__pill,
        .dashboard-tag {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 2rem;
            padding: .38rem .78rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.86);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .dashboard-orbit__actions {
            display: flex;
            flex-wrap: wrap;
            gap: .85rem;
            margin-top: 1.35rem;
        }

        .dashboard-orbit__signal-value,
        .dashboard-kpi-card__value {
            margin-top: .9rem;
            color: #fff;
            font-size: clamp(2rem, 3vw, 3rem);
            line-height: 1;
            font-weight: 800;
        }

        .dashboard-orbit__signal-copy,
        .dashboard-kpi-card__meta {
            margin-top: .7rem;
            color: rgba(255, 255, 255, 0.62);
            font-size: .9rem;
            line-height: 1.55;
        }

        .dashboard-orbit__signal-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .85rem;
            margin-top: 1.4rem;
        }

        .dashboard-orbit__signal-grid div {
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.04);
            padding: .9rem 1rem;
        }

        .dashboard-orbit__signal-grid span,
        .dashboard-slot-copy span,
        .dashboard-booking-guest span,
        .dashboard-booking-visit span {
            display: block;
            color: rgba(255, 255, 255, 0.58);
            font-size: .8rem;
        }

        .dashboard-orbit__signal-grid strong,
        .dashboard-slot-copy strong,
        .dashboard-booking-guest strong,
        .dashboard-booking-visit strong {
            display: block;
            color: #fff;
            font-weight: 700;
        }

        .dashboard-filter-shell .form-control,
        .dashboard-filter-shell .form-select {
            min-height: 3.1rem;
            border-radius: .95rem;
        }

        .dashboard-filter-shell .admin-panel-head {
            padding: 1.65rem 1.85rem 0;
        }

        .dashboard-filter-shell .admin-panel-body {
            padding: 1.55rem 1.85rem 1.8rem;
        }

        .dashboard-kpi-card {
            min-height: 100%;
            padding: 1.45rem 1.45rem 1.35rem;
        }

        .dashboard-kpi-card__top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .75rem;
        }

        .dashboard-kpi-card__icon {
            color: rgba(255, 255, 255, 0.76);
        }

        .dashboard-kpi-card--ruby {
            background:
                radial-gradient(circle at top right, rgba(219, 29, 48, 0.18), transparent 40%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0)),
                linear-gradient(135deg, rgba(9, 9, 9, 0.98), rgba(19, 9, 10, 0.95));
        }

        .dashboard-kpi-card--amber {
            background:
                radial-gradient(circle at top right, rgba(255, 149, 44, 0.18), transparent 42%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0)),
                linear-gradient(135deg, rgba(9, 9, 9, 0.98), rgba(22, 15, 9, 0.95));
        }

        .dashboard-kpi-card--emerald {
            background:
                radial-gradient(circle at top right, rgba(77, 201, 145, 0.18), transparent 42%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0)),
                linear-gradient(135deg, rgba(9, 9, 9, 0.98), rgba(9, 18, 14, 0.95));
        }

        .dashboard-kpi-card--coal {
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.08), transparent 38%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0)),
                linear-gradient(135deg, rgba(9, 9, 9, 0.98), rgba(14, 14, 14, 0.95));
        }

        .dashboard-kpi-card__pill--success {
            border-color: rgba(80, 205, 137, 0.2);
            background: rgba(80, 205, 137, 0.12);
            color: #bff2d4;
        }

        .dashboard-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 0 1.5rem 1.25rem;
        }

        .dashboard-chart-card .card-header,
        .dashboard-table-card .card-header,
        .dashboard-side-card .card-header {
            padding: 1.7rem 1.85rem 0;
        }

        .dashboard-chart-card .card-body,
        .dashboard-table-card .card-body,
        .dashboard-side-card .card-body {
            padding-left: 1.85rem;
            padding-right: 1.85rem;
            padding-bottom: 1.85rem;
        }

        .dashboard-table-card .table-responsive {
            margin-top: .35rem;
        }

        .dashboard-legend span {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: rgba(255, 255, 255, 0.72);
            font-size: .82rem;
            font-weight: 600;
        }

        .dashboard-legend__dot {
            width: .7rem;
            height: .7rem;
            border-radius: 999px;
        }

        .dashboard-legend__dot--primary {
            background: #db1d30;
        }

        .dashboard-legend__dot--accent {
            background: #ff952c;
        }

        .dashboard-booking-table tbody tr {
            border-bottom-color: rgba(255, 255, 255, 0.06);
        }

        .dashboard-booking-table tbody td,
        .dashboard-booking-table thead th {
            white-space: nowrap;
        }

        .dashboard-booking-ref strong {
            color: #fff;
            letter-spacing: .04em;
        }

        .dashboard-tag.is-table {
            border-color: rgba(255, 149, 44, 0.2);
            background: rgba(255, 149, 44, 0.12);
            color: #ffd3a1;
        }

        .dashboard-tag.is-event {
            border-color: rgba(219, 29, 48, 0.24);
            background: rgba(219, 29, 48, 0.14);
            color: #ffb1b9;
        }

        .dashboard-tag.is-confirmed {
            border-color: rgba(80, 205, 137, 0.22);
            background: rgba(80, 205, 137, 0.12);
            color: #bff2d4;
        }

        .dashboard-tag.is-cancelled {
            border-color: rgba(241, 65, 108, 0.22);
            background: rgba(241, 65, 108, 0.12);
            color: #ffc1d0;
        }

        .dashboard-tag.is-pending,
        .dashboard-kpi-card__pill {
            border-color: rgba(255, 199, 0, 0.18);
            background: rgba(255, 199, 0, 0.12);
            color: #ffe59b;
        }

        .dashboard-slot-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: .95rem 0;
        }

        .dashboard-slot-row + .dashboard-slot-row {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .dashboard-slot-rank {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.7rem;
            height: 2.7rem;
            border-radius: 999px;
            background: rgba(219, 29, 48, 0.18);
            color: #fff;
            font-size: .9rem;
            font-weight: 700;
            letter-spacing: .08em;
        }

        .dashboard-action-card {
            display: flex;
            align-items: flex-start;
            gap: .95rem;
            padding: 1rem 1.05rem;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.04);
            color: inherit;
            transition: transform .2s ease, border-color .2s ease, background-color .2s ease;
        }

        .dashboard-action-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 149, 44, 0.24);
            background: rgba(255, 255, 255, 0.06);
        }

        .dashboard-action-card__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.8rem;
            height: 2.8rem;
            border-radius: .95rem;
            background: rgba(219, 29, 48, 0.18);
            color: #fff;
            flex-shrink: 0;
        }

        .dashboard-action-card__copy {
            display: flex;
            flex-direction: column;
            gap: .2rem;
        }

        .dashboard-action-card__copy strong {
            color: #fff;
            font-size: .95rem;
            font-weight: 700;
        }

        .dashboard-action-card__copy small {
            color: rgba(255, 255, 255, 0.62);
            font-size: .82rem;
            line-height: 1.45;
        }

        html[data-bs-theme="light"] .dashboard-orbit__hero,
        html[data-bs-theme="light"] .dashboard-orbit__signal,
        html[data-bs-theme="light"] .dashboard-kpi-card,
        html[data-bs-theme="light"] .dashboard-chart-card,
        html[data-bs-theme="light"] .dashboard-table-card,
        html[data-bs-theme="light"] .dashboard-side-card {
            border-color: rgba(15, 23, 42, 0.08);
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        html[data-bs-theme="light"] .dashboard-kpi-card--ruby {
            background: #ffffff;
            border-color: rgba(219, 29, 48, 0.14);
        }

        html[data-bs-theme="light"] .dashboard-kpi-card--amber {
            background: #ffffff;
            border-color: rgba(255, 149, 44, 0.16);
        }

        html[data-bs-theme="light"] .dashboard-kpi-card--emerald {
            background: #ffffff;
            border-color: rgba(80, 205, 137, 0.16);
        }

        html[data-bs-theme="light"] .dashboard-kpi-card--coal {
            background: #ffffff;
            border-color: rgba(15, 23, 42, 0.1);
        }

        html[data-bs-theme="light"] .dashboard-orbit__glow {
            display: none;
        }

        html[data-bs-theme="light"] .dashboard-orbit__title,
        html[data-bs-theme="light"] .dashboard-orbit__signal-value,
        html[data-bs-theme="light"] .dashboard-kpi-card__value,
        html[data-bs-theme="light"] .dashboard-orbit__signal-grid strong,
        html[data-bs-theme="light"] .dashboard-slot-copy strong,
        html[data-bs-theme="light"] .dashboard-booking-ref strong,
        html[data-bs-theme="light"] .dashboard-booking-guest strong,
        html[data-bs-theme="light"] .dashboard-booking-visit strong,
        html[data-bs-theme="light"] .dashboard-action-card__copy strong {
            color: #171717;
        }

        html[data-bs-theme="light"] .dashboard-orbit__copy,
        html[data-bs-theme="light"] .dashboard-card-copy,
        html[data-bs-theme="light"] .dashboard-orbit__signal-copy,
        html[data-bs-theme="light"] .dashboard-kpi-card__meta,
        html[data-bs-theme="light"] .dashboard-orbit__eyebrow,
        html[data-bs-theme="light"] .dashboard-orbit__signal-label,
        html[data-bs-theme="light"] .dashboard-kpi-card__label,
        html[data-bs-theme="light"] .dashboard-orbit__signal-grid span,
        html[data-bs-theme="light"] .dashboard-slot-copy span,
        html[data-bs-theme="light"] .dashboard-booking-guest span,
        html[data-bs-theme="light"] .dashboard-booking-visit span,
        html[data-bs-theme="light"] .dashboard-action-card__copy small,
        html[data-bs-theme="light"] .dashboard-legend span {
            color: rgba(23, 23, 23, 0.66);
        }

        html[data-bs-theme="light"] .dashboard-orbit__chips span,
        html[data-bs-theme="light"] .dashboard-kpi-card__pill,
        html[data-bs-theme="light"] .dashboard-tag,
        html[data-bs-theme="light"] .dashboard-action-card {
            border-color: rgba(15, 23, 42, 0.08);
            background: rgba(248, 250, 252, 0.88);
            color: #1f2937;
        }

        html[data-bs-theme="light"] .dashboard-orbit__signal-grid div,
        html[data-bs-theme="light"] .dashboard-action-card {
            background: #ffffff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
        }

        html[data-bs-theme="light"] .dashboard-slot-rank {
            background: rgba(219, 29, 48, 0.1);
            color: #b42318;
        }

        html[data-bs-theme="light"] .dashboard-action-card__icon {
            background: rgba(219, 29, 48, 0.1);
            color: #b42318;
        }

        html[data-bs-theme="light"] .dashboard-booking-table tbody tr {
            border-bottom-color: rgba(15, 23, 42, 0.08);
        }

        html[data-bs-theme="light"] .dashboard-slot-row + .dashboard-slot-row {
            border-top-color: rgba(15, 23, 42, 0.08);
        }

        html[data-bs-theme="light"] .dashboard-chart-card .card-header,
        html[data-bs-theme="light"] .dashboard-table-card .card-header,
        html[data-bs-theme="light"] .dashboard-side-card .card-header {
            border-bottom: 1px solid rgba(15, 23, 42, 0.04);
            padding-bottom: 1rem;
        }

        @media (max-width: 1199.98px) {
            .dashboard-orbit__hero,
            .dashboard-orbit__signal {
                padding: 1.65rem 1.75rem;
            }

            .dashboard-filter-shell .admin-panel-head,
            .dashboard-filter-shell .admin-panel-body,
            .dashboard-chart-card .card-header,
            .dashboard-table-card .card-header,
            .dashboard-side-card .card-header,
            .dashboard-chart-card .card-body,
            .dashboard-table-card .card-body,
            .dashboard-side-card .card-body {
                padding-left: 1.45rem;
                padding-right: 1.45rem;
            }
        }

        @media (max-width: 991.98px) {
            .dashboard-orbit__hero,
            .dashboard-orbit__signal {
                padding: 1.35rem 1.4rem;
            }
        }
    </style>
@endpush

@section('scripts')
    <script>
        (() => {
            const trendData = @json($charts['trend']);
            const typeData = @json($charts['booking_types']);
            const statusData = @json($charts['statuses']);
            const rootStyles = getComputedStyle(document.documentElement);
            const surfaceBorder = 'rgba(255, 255, 255, 0.08)';
            const labelColor = 'rgba(255, 255, 255, 0.62)';

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
                        foreColor: labelColor,
                    },
                    series: [{
                        name: 'Bookings',
                        data: trendData.series,
                    }],
                    xaxis: {
                        categories: trendData.labels,
                        axisBorder: { color: surfaceBorder },
                        axisTicks: { color: surfaceBorder },
                    },
                    yaxis: {
                        labels: {
                            formatter: (value) => Math.round(value),
                        },
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
                            opacityFrom: 0.42,
                            opacityTo: 0.03,
                            stops: [0, 92, 100],
                        },
                    },
                    colors: ['#db1d30'],
                    grid: {
                        borderColor: surfaceBorder,
                        strokeDashArray: 4,
                    },
                    tooltip: {
                        theme: 'dark',
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
                        foreColor: labelColor,
                    },
                    labels: typeData.labels,
                    series: typeData.series,
                    colors: ['#db1d30', '#ff952c'],
                    dataLabels: {
                        enabled: true,
                    },
                    legend: {
                        show: false,
                    },
                    stroke: {
                        colors: ['transparent'],
                    },
                    tooltip: {
                        theme: 'dark',
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
                        foreColor: labelColor,
                    },
                    series: [{
                        name: 'Bookings',
                        data: statusData.series,
                    }],
                    xaxis: {
                        categories: statusData.labels,
                        axisBorder: { color: surfaceBorder },
                        axisTicks: { color: surfaceBorder },
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 8,
                            columnWidth: '48%',
                        },
                    },
                    grid: {
                        borderColor: surfaceBorder,
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    colors: ['#ffc700', '#50cd89', '#f1416c'],
                    legend: {
                        show: false,
                    },
                    tooltip: {
                        theme: 'dark',
                    },
                }).render();
            }
        })();
    </script>
@endsection
