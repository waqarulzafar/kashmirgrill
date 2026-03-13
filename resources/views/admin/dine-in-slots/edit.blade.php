@extends('admin.layout')

@section('admin_title', 'Edit Dine-In Slot')

@section('admin_content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <h2 class="h5 mb-4">Edit Reservation Slot</h2>
            <p class="text-secondary mb-4">Adjust timing, capacity, and visibility for this slot across booking and checkout flows.</p>
            <form method="POST" action="{{ route('admin.dine-in-slots.update', $slot) }}">
                @csrf
                @method('PUT')
                @include('admin.dine-in-slots._form', ['slot' => $slot, 'submitLabel' => 'Update Slot'])
            </form>
        </div>
    </div>
@endsection
