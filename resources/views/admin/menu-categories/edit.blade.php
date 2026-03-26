@extends('admin.layout')

@section('admin_title', 'Edit Menu Category')
@section('admin_description', 'Refine naming and slug structure while keeping the category system readable and consistent.')

@section('admin_actions')
    <a href="{{ route('admin.menu-categories.index') }}" class="btn btn-light">Back to Categories</a>
@endsection

@section('admin_content')
    <div class="card admin-panel">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">Edit Category</h3>
                <p class="admin-panel-copy">Update labels and URLs without losing the category’s place in the broader menu structure.</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <form method="POST" action="{{ route('admin.menu-categories.update', $category) }}" class="row g-4">
                @csrf
                @method('PUT')
                <div class="col-12 col-lg-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                </div>
                <div class="col-12 col-lg-6">
                    <label for="slug" class="form-label">Slug (optional)</label>
                    <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $category->slug) }}">
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Category</button>
                    <a href="{{ route('admin.menu-categories.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
