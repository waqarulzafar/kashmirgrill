<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DineInSlot;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'booking_type' => ['nullable', Rule::in(['all', Booking::TYPE_TABLE, Booking::TYPE_EVENT])],
            'status' => ['nullable', Rule::in(['all', Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED, Booking::STATUS_CANCELLED])],
            'search' => ['nullable', 'string', 'max:120'],
        ]);

        $dateFrom = (string) ($validated['date_from'] ?? now()->subDays(29)->toDateString());
        $dateTo = (string) ($validated['date_to'] ?? now()->toDateString());
        $bookingType = (string) ($validated['booking_type'] ?? 'all');
        $status = (string) ($validated['status'] ?? 'all');
        $search = trim((string) ($validated['search'] ?? ''));

        $startDate = Carbon::parse($dateFrom)->toDateString();
        $endDate = Carbon::parse($dateTo)->toDateString();

        $filteredQuery = Booking::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->when($bookingType !== 'all', function (Builder $query) use ($bookingType): void {
                $query->where('booking_type', $bookingType);
            })
            ->when($status !== 'all', function (Builder $query) use ($status): void {
                $query->where('status', $status);
            })
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $nameQuery) use ($search): void {
                    $nameQuery
                        ->where('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            });

        $stats = [
            'total_bookings' => (clone $filteredQuery)->count(),
            'pending_bookings' => (clone $filteredQuery)->where('status', Booking::STATUS_PENDING)->count(),
            'confirmed_bookings' => (clone $filteredQuery)->where('status', Booking::STATUS_CONFIRMED)->count(),
            'cancelled_bookings' => (clone $filteredQuery)->where('status', Booking::STATUS_CANCELLED)->count(),
            'table_bookings' => (clone $filteredQuery)->where('booking_type', Booking::TYPE_TABLE)->count(),
            'event_bookings' => (clone $filteredQuery)->where('booking_type', Booking::TYPE_EVENT)->count(),
            'total_guests' => (int) (clone $filteredQuery)->sum('persons'),
            'average_party_size' => round((float) (clone $filteredQuery)->avg('persons'), 1),
            'today_bookings' => Booking::query()->whereDate('date', now()->toDateString())->count(),
            'active_slots' => DineInSlot::query()->where('is_active', true)->count(),
        ];

        $dailyCounts = (clone $filteredQuery)
            ->selectRaw('date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $trendLabels = [];
        $trendSeries = [];
        foreach (CarbonPeriod::create($startDate, $endDate) as $day) {
            $dateKey = $day->toDateString();
            $trendLabels[] = $day->format('d M');
            $trendSeries[] = (int) ($dailyCounts[$dateKey] ?? 0);
        }

        $bookingTypeCounts = (clone $filteredQuery)
            ->selectRaw('booking_type, COUNT(*) as total')
            ->groupBy('booking_type')
            ->pluck('total', 'booking_type');

        $statusCounts = (clone $filteredQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $topSlots = (clone $filteredQuery)
            ->whereNotNull('dine_in_slot_id')
            ->selectRaw('dine_in_slot_id, COUNT(*) as total')
            ->groupBy('dine_in_slot_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('dineInSlot:id,name,start_time,end_time')
            ->get();

        $latestBookings = (clone $filteredQuery)
            ->with('dineInSlot:id,name,start_time,end_time')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.dashboard', [
            'filters' => [
                'date_from' => $startDate,
                'date_to' => $endDate,
                'booking_type' => $bookingType,
                'status' => $status,
                'search' => $search,
            ],
            'stats' => $stats,
            'charts' => [
                'trend' => [
                    'labels' => $trendLabels,
                    'series' => $trendSeries,
                ],
                'booking_types' => [
                    'labels' => ['Table', 'Event'],
                    'series' => [
                        (int) ($bookingTypeCounts[Booking::TYPE_TABLE] ?? 0),
                        (int) ($bookingTypeCounts[Booking::TYPE_EVENT] ?? 0),
                    ],
                ],
                'statuses' => [
                    'labels' => ['Pending', 'Confirmed', 'Cancelled'],
                    'series' => [
                        (int) ($statusCounts[Booking::STATUS_PENDING] ?? 0),
                        (int) ($statusCounts[Booking::STATUS_CONFIRMED] ?? 0),
                        (int) ($statusCounts[Booking::STATUS_CANCELLED] ?? 0),
                    ],
                ],
            ],
            'topSlots' => $topSlots,
            'latestBookings' => $latestBookings,
        ]);

    }
}
