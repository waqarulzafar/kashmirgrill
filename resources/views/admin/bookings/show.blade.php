@extends('admin.layout')

@section('admin_title', 'Booking Details')
@section('admin_description', 'Edit the reservation record, adjust payment and status, or remove the booking entirely if it was created in error.')

@section('admin_actions')
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-light-primary">Back to Bookings</a>
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
        $guestInitial = strtoupper(mb_substr(trim($booking->full_name), 0, 1));
        $selectedSlotId = old('dine_in_slot_id', $booking->dine_in_slot_id);
        $summaryCards = [
            [
                'label' => 'Visit Window',
                'value' => optional($booking->date)->format('D, d M Y'),
                'meta' => \Illuminate\Support\Carbon::parse($booking->time)->format('H:i'),
                'tone' => 'primary',
                'icon' => 'ki-calendar-8',
            ],
            [
                'label' => 'Party Size',
                'value' => $booking->persons.' guests',
                'meta' => $booking->dineInSlot?->name ?: 'Slot not assigned',
                'tone' => 'info',
                'icon' => 'ki-profile-user',
            ],
            [
                'label' => 'Booking Type',
                'value' => $bookingTypeLabels[$booking->booking_type] ?? ucfirst($booking->booking_type),
                'meta' => $booking->special_occasion ?: 'No special occasion set',
                'tone' => 'warning',
                'icon' => 'ki-book-open',
            ],
            [
                'label' => 'Payment Method',
                'value' => $paymentMethodLabels[$booking->payment_method] ?? 'Not set',
                'meta' => $paymentStatusLabels[$booking->payment_status] ?? ucfirst($booking->payment_status),
                'tone' => 'success',
                'icon' => 'ki-wallet',
            ],
        ];
    @endphp

    <div class="card card-flush mb-7">
        <div class="card-body pt-8 pb-7">
            <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-7 mb-8">
                <div class="d-flex align-items-center gap-5">
                    <div class="symbol symbol-70px">
                        <span class="symbol-label bg-light-primary text-primary fs-2 fw-bold">{{ $guestInitial }}</span>
                    </div>
                    <div class="d-flex flex-column">
                        <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                            <h2 class="text-gray-900 fw-bold mb-0">{{ $booking->formattedReference() }}</h2>
                            <span class="badge {{ $statusBadge }}">{{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}</span>
                            <span class="badge {{ $paymentBadge }}">{{ $paymentStatusLabels[$booking->payment_status] ?? ucfirst($booking->payment_status) }}</span>
                        </div>
                        <span class="text-gray-900 fw-semibold fs-5">{{ $booking->full_name }}</span>
                        <div class="d-flex flex-wrap align-items-center gap-4 mt-2 text-gray-600 fs-7">
                            <span>{{ $booking->email }}</span>
                            <span class="bullet bullet-dot bg-gray-400"></span>
                            <span>{{ $booking->phone }}</span>
                            <span class="bullet bullet-dot bg-gray-400"></span>
                            <span>Submitted {{ optional($booking->created_at)->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <a href="mailto:{{ old('email', $booking->email) }}" class="btn btn-light">Email Guest</a>
                    <a href="tel:{{ old('phone', $booking->phone) }}" class="btn btn-light">Call Guest</a>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-light-primary">Back to Queue</a>
                </div>
            </div>

            <div class="row g-5">
                @foreach($summaryCards as $summaryCard)
                    <div class="col-md-6 col-xl-3">
                        <div class="border border-gray-200 rounded-3 px-5 py-4 h-100">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="symbol symbol-35px">
                                    <span class="symbol-label bg-light-{{ $summaryCard['tone'] }}">
                                        <i class="ki-duotone {{ $summaryCard['icon'] }} fs-3 text-{{ $summaryCard['tone'] }}"></i>
                                    </span>
                                </div>
                                <span class="text-uppercase fs-8 fw-bold text-muted">{{ $summaryCard['label'] }}</span>
                            </div>
                            <div class="text-gray-900 fw-bold fs-5">{{ $summaryCard['value'] }}</div>
                            <div class="text-gray-600 fs-7 mt-2">{{ $summaryCard['meta'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
        @csrf
        @method('PATCH')

        <div class="row g-7">
            <div class="col-xl-8">
                <div class="card card-flush mb-7">
                    <div class="card-header pt-7">
                        <div class="card-title">
                            <h3 class="fw-bold text-gray-900 m-0">Guest Profile</h3>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row g-5">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label fw-semibold fs-7 text-uppercase text-muted">Full Name</label>
                                <input id="full_name" name="full_name" type="text" class="form-control form-control-solid" value="{{ old('full_name', $booking->full_name) }}">
                                @error('full_name')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold fs-7 text-uppercase text-muted">Phone</label>
                                <input id="phone" name="phone" type="text" class="form-control form-control-solid" value="{{ old('phone', $booking->phone) }}">
                                @error('phone')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold fs-7 text-uppercase text-muted">Email</label>
                                <input id="email" name="email" type="email" class="form-control form-control-solid" value="{{ old('email', $booking->email) }}">
                                @error('email')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-7 text-uppercase text-muted d-block">Marketing Preference</label>
                                <label class="form-check form-switch form-check-custom form-check-solid mt-2">
                                    <input type="hidden" name="marketing_opt_in" value="0">
                                    <input class="form-check-input" type="checkbox" name="marketing_opt_in" value="1" @checked(old('marketing_opt_in', $booking->marketing_opt_in))>
                                    <span class="form-check-label fw-semibold text-gray-700">Subscribed to updates</span>
                                </label>
                                @error('marketing_opt_in')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-flush mb-7">
                    <div class="card-header pt-7">
                        <div class="card-title">
                            <h3 class="fw-bold text-gray-900 m-0">Reservation Details</h3>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row g-5">
                            <div class="col-md-6">
                                <label for="booking_type" class="form-label fw-semibold fs-7 text-uppercase text-muted">Booking Type</label>
                                <select id="booking_type" name="booking_type" class="form-select form-select-solid">
                                    @foreach($bookingTypeLabels as $value => $label)
                                        <option value="{{ $value }}" @selected(old('booking_type', $booking->booking_type) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('booking_type')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="date" class="form-label fw-semibold fs-7 text-uppercase text-muted">Date</label>
                                <input id="date" name="date" type="date" class="form-control form-control-solid" value="{{ old('date', optional($booking->date)->format('Y-m-d')) }}">
                                @error('date')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="time" class="form-label fw-semibold fs-7 text-uppercase text-muted">Time</label>
                                <input id="time" name="time" type="time" class="form-control form-control-solid" value="{{ old('time', \Illuminate\Support\Carbon::parse($booking->time)->format('H:i')) }}">
                                @error('time')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="persons" class="form-label fw-semibold fs-7 text-uppercase text-muted">Guest Count</label>
                                <input id="persons" name="persons" type="number" min="1" class="form-control form-control-solid" value="{{ old('persons', $booking->persons) }}">
                                @error('persons')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label for="dine_in_slot_id" class="form-label fw-semibold fs-7 text-uppercase text-muted">Dine-In Slot</label>
                                <select id="dine_in_slot_id" name="dine_in_slot_id" class="form-select form-select-solid">
                                    <option value="">No slot assigned</option>
                                    @foreach($dineInSlots as $dineInSlot)
                                        <option value="{{ $dineInSlot->id }}" @selected((string) $selectedSlotId === (string) $dineInSlot->id)>
                                            {{ $dineInSlot->name }} ({{ \Illuminate\Support\Carbon::createFromFormat('H:i:s', $dineInSlot->start_time)->format('H:i') }} - {{ \Illuminate\Support\Carbon::createFromFormat('H:i:s', $dineInSlot->end_time)->format('H:i') }}){{ $dineInSlot->is_active ? '' : ' [Inactive]' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dine_in_slot_id')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="table_preference" class="form-label fw-semibold fs-7 text-uppercase text-muted">Table Preference</label>
                                <input id="table_preference" name="table_preference" type="text" class="form-control form-control-solid" value="{{ old('table_preference', $booking->table_preference) }}">
                                @error('table_preference')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="selected_menu" class="form-label fw-semibold fs-7 text-uppercase text-muted">Selected Menu</label>
                                <input id="selected_menu" name="selected_menu" type="text" class="form-control form-control-solid" value="{{ old('selected_menu', $booking->selected_menu) }}">
                                @error('selected_menu')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="special_occasion" class="form-label fw-semibold fs-7 text-uppercase text-muted">Special Occasion</label>
                                <input id="special_occasion" name="special_occasion" type="text" class="form-control form-control-solid" value="{{ old('special_occasion', $booking->special_occasion) }}">
                                @error('special_occasion')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-flush">
                    <div class="card-header pt-7">
                        <div class="card-title">
                            <h3 class="fw-bold text-gray-900 m-0">Guest Notes</h3>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <label for="additional_notes" class="form-label fw-semibold fs-7 text-uppercase text-muted">Additional Notes</label>
                        <textarea id="additional_notes" name="additional_notes" rows="6" class="form-control form-control-solid">{{ old('additional_notes', $booking->additional_notes) }}</textarea>
                        @error('additional_notes')
                            <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card card-flush mb-7">
                    <div class="card-header pt-7">
                        <div class="card-title">
                            <h3 class="fw-bold text-gray-900 m-0">Status and Payment</h3>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row g-5">
                            <div class="col-12">
                                <label for="status" class="form-label fw-semibold fs-7 text-uppercase text-muted">Booking Status</label>
                                <select id="status" name="status" class="form-select form-select-solid">
                                    @foreach($statusLabels as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', $booking->status) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="payment_method" class="form-label fw-semibold fs-7 text-uppercase text-muted">Payment Method</label>
                                <select id="payment_method" name="payment_method" class="form-select form-select-solid">
                                    <option value="">Not set</option>
                                    @foreach($paymentMethodLabels as $value => $label)
                                        <option value="{{ $value }}" @selected(old('payment_method', $booking->payment_method) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('payment_method')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="payment_amount" class="form-label fw-semibold fs-7 text-uppercase text-muted">Stripe Payment Amount</label>
                                <div class="input-group input-group-solid">
                                    <span class="input-group-text">&euro;</span>
                                    <input id="payment_amount" name="payment_amount" type="number" min="0" step="0.01" class="form-control form-control-solid" value="{{ old('payment_amount', $booking->payment_amount) }}" placeholder="0.00">
                                </div>
                                <div class="text-muted fs-8 mt-2">Required when the booking uses card checkout after confirmation.</div>
                                @error('payment_amount')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="payment_status" class="form-label fw-semibold fs-7 text-uppercase text-muted">Payment Status</label>
                                <select id="payment_status" name="payment_status" class="form-select form-select-solid">
                                    @foreach($paymentStatusLabels as $value => $label)
                                        <option value="{{ $value }}" @selected(old('payment_status', $booking->payment_status) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('payment_status')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="notice d-flex bg-light-success rounded border-success border border-dashed p-5">
                                    <div class="d-flex flex-column">
                                        <span class="fs-6 fw-bold text-gray-900 mb-1">Guest notification</span>
                                        <span class="text-gray-700 fs-7">Confirmed card-checkout bookings send a payment email with a Stripe summary link. Other status changes still send the standard booking update email.</span>
                                    </div>
                                </div>
                            </div>
                            @if($booking->payment_reference || $booking->paid_at)
                                <div class="col-12">
                                    <div class="border border-dashed border-gray-300 rounded-3 px-5 py-4">
                                        <div class="fs-8 fw-bold text-uppercase text-muted mb-2">Payment Record</div>
                                        <div class="text-gray-900 fw-semibold">{{ $booking->payment_reference ?: 'No payment reference yet' }}</div>
                                        <div class="text-gray-600 fs-8 mt-2">{{ $booking->paid_at?->format('d M Y H:i') ?: 'Not paid yet' }}</div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">Save Booking Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row g-7 mt-0">
        <div class="col-xl-8"></div>
        <div class="col-xl-4">
                <div class="card card-flush">
                    <div class="card-header pt-7">
                        <div class="card-title">
                            <h3 class="fw-bold text-gray-900 m-0">Delete Booking</h3>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="notice d-flex bg-light-danger rounded border-danger border border-dashed p-5 mb-5">
                            <div class="d-flex flex-column">
                                <span class="fs-6 fw-bold text-gray-900 mb-1">Permanent action</span>
                                <span class="text-gray-700 fs-7">Use this only when the booking is invalid, duplicated, or should be removed from the system entirely.</span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Delete this booking permanently?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">Delete Booking</button>
                        </form>
                    </div>
                </div>
        </div>
    </div>
@endsection
