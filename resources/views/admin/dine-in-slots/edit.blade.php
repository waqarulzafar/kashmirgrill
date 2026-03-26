@extends('admin.layout')

@section('admin_title', 'Edit Dine-In Slot')
@section('admin_description', 'Adjust timing, capacity, and visibility for an existing reservation window without breaking the booking flow.')

@section('admin_actions')
    <a href="{{ route('admin.dine-in-slots.index') }}" class="btn btn-light">Back to Slots</a>
@endsection

@section('admin_content')
    <div class="card admin-panel">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">Edit Reservation Slot</h3>
                <p class="admin-panel-copy">Keep dining operations and customer-facing availability aligned from one place.</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <form method="POST" action="{{ route('admin.dine-in-slots.update', $slot) }}">
                @csrf
                @method('PUT')
                @include('admin.dine-in-slots._form', ['slot' => $slot, 'submitLabel' => 'Update Slot'])
            </form>
        </div>
    </div>
@endsection
