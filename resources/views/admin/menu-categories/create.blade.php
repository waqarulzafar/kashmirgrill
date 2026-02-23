@extends('admin.layout')

@section('admin_title', 'Create Menu Category')

@section('admin_content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-3">New Category</h2>
            <form method="POST" action="{{ route('admin.menu-categories.store') }}" class="row g-3">
                @csrf
                <div class="col-12 col-lg-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-12 col-lg-6">
                    <label for="slug" class="form-label">Slug (optional)</label>
                    <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="auto-generated if empty">
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-brand">Save Category</button>
                    <a href="{{ route('admin.menu-categories.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
