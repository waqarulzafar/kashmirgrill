@extends('admin.layout')

@section('admin_title', 'Create Dine-In Slot')
@section('admin_description', 'Add a reservation window that customers can see during availability checks and dine-in checkout.')

@section('admin_actions')
    <a href="{{ route('admin.dine-in-slots.index') }}" class="btn btn-light">Back to Slots</a>
@endsection

@section('admin_content')
    <div class="card admin-panel">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">New Reservation Slot</h3>
                <p class="admin-panel-copy">Create a bookable seating window with the right capacity and display order.</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <form method="POST" action="{{ route('admin.dine-in-slots.store') }}">
                @csrf
                @include('admin.dine-in-slots._form', ['submitLabel' => 'Create Slot'])
            </form>
        </div>
    </div>
@endsection
