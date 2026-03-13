@php
    $slot = $slot ?? null;
    $submitLabel = $submitLabel ?? 'Save Slot';
@endphp

<div class="row g-3">
    <div class="col-12 col-md-6">
        <label class="form-label" for="name">Slot Name</label>
        <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $slot?->name) }}" placeholder="Dinner Prime Seating" required>
        <small class="text-secondary">Shown on frontend while selecting a reservation time.</small>
    </div>

    <div class="col-6 col-md-3">
        <label class="form-label" for="start_time">Start Time</label>
        <input id="start_time" name="start_time" type="time" class="form-control" value="{{ old('start_time', $slot?->start_time ? \Illuminate\Support\Carbon::createFromFormat('H:i:s', $slot->start_time)->format('H:i') : null) }}" required>
    </div>

    <div class="col-6 col-md-3">
        <label class="form-label" for="end_time">End Time</label>
        <input id="end_time" name="end_time" type="time" class="form-control" value="{{ old('end_time', $slot?->end_time ? \Illuminate\Support\Carbon::createFromFormat('H:i:s', $slot->end_time)->format('H:i') : null) }}" required>
    </div>

    <div class="col-6 col-md-4">
        <label class="form-label" for="max_guests">Max Guests</label>
        <input id="max_guests" name="max_guests" type="number" min="1" max="300" class="form-control" value="{{ old('max_guests', $slot?->max_guests ?? 20) }}" required>
    </div>

    <div class="col-6 col-md-4">
        <label class="form-label" for="sort_order">Sort Order</label>
        <input id="sort_order" name="sort_order" type="number" min="0" max="9999" class="form-control" value="{{ old('sort_order', $slot?->sort_order ?? 0) }}">
    </div>

    <div class="col-12 col-md-4 d-flex align-items-end">
        <div class="form-check mb-2">
            <input id="is_active" name="is_active" type="checkbox" value="1" class="form-check-input" @checked(old('is_active', $slot?->is_active ?? true))>
            <label class="form-check-label" for="is_active">Active Slot</label>
        </div>
    </div>

    <div class="col-12 d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-brand">{{ $submitLabel }}</button>
        <a href="{{ route('admin.dine-in-slots.index') }}" class="btn btn-outline-light">Cancel</a>
    </div>
</div>
