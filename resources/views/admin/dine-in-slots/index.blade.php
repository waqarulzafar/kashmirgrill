@extends('admin.layout')

@section('admin_title', 'Dine-In Slots')

@section('admin_content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 rounded-4 h-100 admin-kpi admin-kpi--dark">
                <div class="card-body">
                    <p class="text-uppercase small mb-1 text-white-50">Configured Slots</p>
                    <h2 class="h3 mb-0 text-white">{{ $totalSlots }}</h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 rounded-4 h-100 admin-kpi admin-kpi--green">
                <div class="card-body">
                    <p class="text-uppercase small mb-1 text-white-50">Active Slots</p>
                    <h2 class="h3 mb-0 text-white">{{ $activeSlots }}</h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 rounded-4 h-100 admin-kpi admin-kpi--red">
                <div class="card-body d-flex align-items-center justify-content-between gap-2">
                    <div>
                        <p class="text-uppercase small mb-1 text-white-50">Quick Action</p>
                        <h2 class="h5 mb-0 text-white">Create New Slot</h2>
                    </div>
                    <a href="{{ route('admin.dine-in-slots.create') }}" class="btn btn-light btn-sm fw-semibold">Add Slot</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 admin-slot-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h2 class="h5 mb-0">Reservation Time Slots</h2>
                <a href="{{ route('admin.dine-in-slots.create') }}" class="btn btn-brand btn-sm">Add Slot</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle admin-slot-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Time Window</th>
                            <th>Max Guests</th>
                            <th>Status</th>
                            <th>Sort</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($slots as $slot)
                            <tr>
                                <td>
                                    <strong class="d-block">{{ $slot->name }}</strong>
                                    <small class="text-secondary">ID #{{ $slot->id }}</small>
                                </td>
                                <td>
                                    {{ \Illuminate\Support\Carbon::createFromFormat('H:i:s', $slot->start_time)->format('H:i') }}
                                    -
                                    {{ \Illuminate\Support\Carbon::createFromFormat('H:i:s', $slot->end_time)->format('H:i') }}
                                </td>
                                <td>{{ $slot->max_guests }} guests</td>
                                <td>
                                    @if($slot->is_active)
                                        <span class="badge text-bg-success">Active</span>
                                    @else
                                        <span class="badge text-bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $slot->sort_order }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.dine-in-slots.edit', $slot) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                    <form action="{{ route('admin.dine-in-slots.destroy', $slot) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this slot?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-4">No slots found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $slots->links() }}
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .admin-kpi {
            box-shadow: 0 16px 28px rgba(0, 0, 0, .22);
        }

        .admin-kpi--dark {
            background: linear-gradient(135deg, #171717, #252525);
        }

        .admin-kpi--green {
            background: linear-gradient(135deg, #0f4733, #1c6d4f);
        }

        .admin-kpi--red {
            background: linear-gradient(135deg, #531718, #8a2528);
        }

        .admin-slot-card {
            background: linear-gradient(180deg, rgba(255, 255, 255, .03), rgba(255, 255, 255, .01));
            border: 1px solid rgba(255, 255, 255, .08);
        }

        .admin-slot-table thead th {
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255, 255, 255, .62);
            border-bottom-color: rgba(255, 255, 255, .16);
        }

        .admin-slot-table tbody td {
            color: rgba(255, 255, 255, .86);
            border-bottom-color: rgba(255, 255, 255, .1);
        }
    </style>
@endpush
