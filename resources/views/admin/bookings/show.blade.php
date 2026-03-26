@extends('admin.layout')

@section('admin_title', 'Booking Details')
@section('admin_description', 'Inspect the full reservation record, operational notes, and customer payment intent before changing status.')

@section('admin_actions')
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-light">Back to Bookings</a>
@endsection

@section('admin_content')
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

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-7">
        <div>
            <h2 class="fw-bold text-gray-900 mb-1">{{ $booking->formattedReference() }}</h2>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge {{ $statusBadge }}">{{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}</span>
                <span class="badge {{ $paymentBadge }}">{{ $paymentStatusLabels[$booking->payment_status] ?? ucfirst($booking->payment_status) }}</span>
            </div>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-light">Back to Bookings</a>
    </div>

    <div class="row g-7">
        <div class="col-xl-8">
            <div class="card mb-7">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title fw-bold">Guest Information</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-5">
                        <div class="col-md-6">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Full Name</div>
                                <div class="fw-bold text-gray-900">{{ $booking->full_name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Phone</div>
                                <div class="fw-bold text-gray-900">{{ $booking->phone }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Email</div>
                                <div class="fw-bold text-gray-900">{{ $booking->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Submitted</div>
                                <div class="fw-bold text-gray-900">{{ optional($booking->created_at)->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title fw-bold">Booking Summary</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-5">
                        <div class="col-md-6">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Booking Type</div>
                                <div class="fw-bold text-gray-900">{{ $bookingTypeLabels[$booking->booking_type] ?? ucfirst($booking->booking_type) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Party Size</div>
                                <div class="fw-bold text-gray-900">{{ $booking->persons }} guests</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Date & Time</div>
                                <div class="fw-bold text-gray-900">
                                    {{ optional($booking->date)->format('d M Y') }} at {{ \Illuminate\Support\Carbon::parse($booking->time)->format('H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Slot</div>
                                <div class="fw-bold text-gray-900">{{ $booking->dineInSlot?->name ?: 'No slot selected' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Payment Method</div>
                                <div class="fw-bold text-gray-900">{{ $paymentMethodLabels[$booking->payment_method] ?? 'Not set' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Special Occasion</div>
                                <div class="fw-bold text-gray-900">{{ $booking->special_occasion ?: 'Not specified' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Table Preference</div>
                                <div class="fw-bold text-gray-900">{{ $booking->table_preference ?: 'Not specified' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Selected Menu</div>
                                <div class="fw-bold text-gray-900">{{ $booking->selected_menu ?: 'Not specified' }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-5 rounded bg-light">
                                <div class="fw-semibold text-gray-600 mb-1">Guest Notes</div>
                                <div class="fw-bold text-gray-900">{{ $booking->additional_notes ?: 'No notes submitted.' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title fw-bold">Update Status</h3>
                </div>
                <div class="card-body pt-0">
                    <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="row g-5">
                        @csrf
                        @method('PATCH')

                        <div class="col-12">
                            <label for="status" class="form-label fw-semibold">Booking Status</label>
                            <select id="status" name="status" class="form-select form-select-solid">
                                @foreach($statusLabels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $booking->status) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="payment_status" class="form-label fw-semibold">Payment Status</label>
                            <select id="payment_status" name="payment_status" class="form-select form-select-solid">
                                @foreach($paymentStatusLabels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('payment_status', $booking->payment_status) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-4">
                                <div class="d-flex flex-stack flex-grow-1">
                                    <div class="fw-semibold">
                                        <div class="fs-6 text-gray-700">
                                            Saving this form updates the booking and emails the guest with the new confirmation state.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Save Booking Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
