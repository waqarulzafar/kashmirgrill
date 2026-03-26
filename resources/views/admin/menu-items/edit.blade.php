@extends('admin.layout')

@section('admin_title', 'Edit Menu Item')
@section('admin_description', 'Refine customer-facing dish details while keeping price, status, and imagery aligned with operations.')

@section('admin_actions')
    <a href="{{ route('admin.menu-items.index') }}" class="btn btn-light">Back to Menu Items</a>
@endsection

@section('admin_content')
    <div class="card admin-panel">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">Edit Menu Item</h3>
                <p class="admin-panel-copy">Update a live dish record without breaking consistency across the menu and checkout experience.</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <form method="POST" action="{{ route('admin.menu-items.update', $item) }}" enctype="multipart/form-data" class="row g-4">
                @csrf
                @method('PUT')

                <div class="col-12 col-lg-6">
                    <label for="menu_category_id" class="form-label">Category</label>
                    <select id="menu_category_id" name="menu_category_id" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('menu_category_id', $item->menu_category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-lg-6">
                    <label for="name" class="form-label">Name</label>
                    <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $item->name) }}" required>
                </div>
                <div class="col-12 col-lg-4">
                    <label for="price" class="form-label">Price</label>
                    <input id="price" name="price" type="number" min="0" step="0.01" class="form-control" value="{{ old('price', $item->price) }}" required>
                </div>
                <div class="col-12 col-lg-8">
                    <label for="tags" class="form-label">Tags (comma separated)</label>
                    <input id="tags" name="tags" type="text" class="form-control" value="{{ old('tags', $item->tags) }}" placeholder="Popular, Spicy, Vegan">
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $item->description) }}</textarea>
                </div>
                <div class="col-12 col-lg-6">
                    <label for="image" class="form-label">Replace Image</label>
                    <input id="image" name="image" type="file" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                    <div class="form-text">JPG, PNG, WEBP up to 2MB.</div>
                </div>
                <div class="col-12 col-lg-6 d-flex flex-column justify-content-end gap-2">
                    <div class="form-check">
                        <input id="is_available" name="is_available" type="checkbox" class="form-check-input" value="1" @checked(old('is_available', $item->is_available))>
                        <label for="is_available" class="form-check-label">Available</label>
                    </div>
                    @if($item->image_path)
                        <div class="form-check">
                            <input id="remove_image" name="remove_image" type="checkbox" class="form-check-input" value="1">
                            <label for="remove_image" class="form-check-label">Remove current image</label>
                        </div>
                    @endif
                </div>

                @if($item->image_path)
                    <div class="col-12">
                        <p class="mb-2 fw-semibold">Current Image</p>
                        <div class="admin-preview-card d-inline-flex">
                            <img src="{{ $item->imageUrl() }}" alt="{{ $item->name }}" class="img-fluid rounded" style="max-width: 220px;" loading="lazy">
                        </div>
                    </div>
                @endif

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Menu Item</button>
                    <a href="{{ route('admin.menu-items.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
