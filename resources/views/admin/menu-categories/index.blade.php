@extends('admin.layout')

@section('admin_title', 'Manage Menu Categories')
@section('admin_description', 'Organize the catalog structure that powers menu grouping, customer browsing, and back-office maintenance.')

@section('admin_actions')
    <a href="{{ route('admin.menu-categories.create') }}" class="btn btn-primary">Add Category</a>
@endsection

@section('admin_content')
    <div class="card admin-panel">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">Category Directory</h3>
                <p class="admin-panel-copy">Manage the top-level dish groups that hold the rest of the menu system together.</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <div class="table-responsive">
                <table class="table align-middle admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Items</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="fw-semibold">{{ $category->name }}</td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>{{ $category->menu_items_count }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.menu-categories.edit', $category) }}" class="btn btn-light btn-sm">Edit</a>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-category-{{ $category->id }}">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="admin-empty">No categories yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    @foreach($categories as $category)
        <div class="modal fade" id="delete-category-{{ $category->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="h5 modal-title">Delete Category</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Delete <strong>{{ $category->name }}</strong>? Related menu items will also be removed.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <form method="POST" action="{{ route('admin.menu-categories.destroy', $category) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
