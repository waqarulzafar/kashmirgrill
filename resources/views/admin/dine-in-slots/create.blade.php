@extends('admin.layout')

@section('admin_title', 'Create Dine-In Slot')

@section('admin_content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <h2 class="h5 mb-4">New Reservation Slot</h2>
            <p class="text-secondary mb-4">Create a new slot that appears on the customer booking page when availability is checked.</p>
            <form method="POST" action="{{ route('admin.dine-in-slots.store') }}">
                @csrf
                @include('admin.dine-in-slots._form', ['submitLabel' => 'Create Slot'])
            </form>
        </div>
    </div>
@endsection
