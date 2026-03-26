@extends('admin.layout')

@section('admin_title', 'Dine-In Slots')
@section('admin_description', 'Shape the reservation windows that control booking availability, dining capacity, and dine-in checkout timing.')

@section('admin_actions')
    <a href="{{ route('admin.dine-in-slots.create') }}" class="btn btn-primary">Add Slot</a>
@endsection

@section('admin_content')
    <div class="row g-4 mb-7">
        <div class="col-12 col-md-4">
            <div class="admin-stat-card h-100">
                <span class="admin-stat-label">Configured Slots</span>
                <span class="admin-stat-value">{{ $totalSlots }}</span>
                <span class="admin-stat-meta">All reservation windows currently configured in the system.</span>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="admin-stat-card h-100">
                <span class="admin-stat-label">Active Slots</span>
                <span class="admin-stat-value">{{ $activeSlots }}</span>
                <span class="admin-stat-meta">Visible and bookable windows currently exposed to customers.</span>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="admin-stat-card h-100">
                <span class="admin-stat-label">Quick Action</span>
                <span class="admin-stat-value" style="font-size: 1.3rem;">Create New Slot</span>
                <div class="mt-3">
                    <a href="{{ route('admin.dine-in-slots.create') }}" class="btn btn-light btn-sm">Add Slot</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card admin-panel admin-slot-card">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">Reservation Time Slots</h3>
                <p class="admin-panel-copy">Balance timing, maximum covers, and display order for the guest-facing reservation flow.</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <div class="table-responsive">
                <table class="table align-middle admin-slot-table admin-table">
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
                                    <a href="{{ route('admin.dine-in-slots.edit', $slot) }}" class="btn btn-light btn-sm">Edit</a>
                                    <form action="{{ route('admin.dine-in-slots.destroy', $slot) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this slot?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="admin-empty">No slots found.</td>
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
        .admin-slot-card {
            background: linear-gradient(180deg, rgba(255, 255, 255, .03), rgba(255, 255, 255, .01));
        }

        .admin-slot-table thead th {
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
    </style>
@endpush
