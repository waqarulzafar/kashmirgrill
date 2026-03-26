@extends('admin.layout')

@section('admin_title', 'Create Menu Category')
@section('admin_description', 'Create a new menu section so dishes stay grouped clearly across the public site and admin catalog.')

@section('admin_actions')
    <a href="{{ route('admin.menu-categories.index') }}" class="btn btn-light">Back to Categories</a>
@endsection

@section('admin_content')
    <div class="card admin-panel">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">New Category</h3>
                <p class="admin-panel-copy">Use short, descriptive names that make the menu easier to browse and maintain.</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <form method="POST" action="{{ route('admin.menu-categories.store') }}" class="row g-4">
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
                    <button type="submit" class="btn btn-primary">Save Category</button>
                    <a href="{{ route('admin.menu-categories.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
