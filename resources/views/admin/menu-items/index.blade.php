@extends('admin.layout')

@section('admin_title', 'Manage Menu Items')
@section('admin_description', 'Control dish presentation, pricing, imagery, and live availability from one consistent catalog view.')

@section('admin_actions')
    <a href="{{ route('admin.menu-items.create') }}" class="btn btn-primary">Add Menu Item</a>
@endsection

@section('admin_content')
    <div class="card admin-panel">
        <div class="admin-panel-head">
            <div>
                <h3 class="admin-panel-title">Menu Catalog</h3>
                <p class="admin-panel-copy">Review how each item appears operationally before it reaches the public menu and checkout flow.</p>
            </div>
        </div>
        <div class="admin-panel-body pt-4">
            <div class="table-responsive">
                <table class="table align-middle admin-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td style="width: 90px;">
                                    @if($item->image_path)
                                        <img src="{{ $item->imageUrl() }}" alt="{{ $item->name }}" class="admin-media-thumb" loading="lazy">
                                    @else
                                        <span class="badge text-bg-light">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $item->name }}</div>
                                    @if($item->tags)
                                        <small class="text-secondary">{{ $item->tags }}</small>
                                    @endif
                                </td>
                                <td>{{ $item->category?->name ?? '-' }}</td>
                                <td>EUR {{ number_format((float) $item->price, 2) }}</td>
                                <td>
                                    @if($item->is_available)
                                        <span class="badge text-bg-success">Available</span>
                                    @else
                                        <span class="badge text-bg-secondary">Unavailable</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.menu-items.edit', $item) }}" class="btn btn-light btn-sm">Edit</a>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-item-{{ $item->id }}">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="admin-empty">No menu items yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $items->links() }}
            </div>
        </div>
    </div>

    @foreach($items as $item)
        <div class="modal fade" id="delete-item-{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="h5 modal-title">Delete Item</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Delete <strong>{{ $item->name }}</strong> permanently?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <form method="POST" action="{{ route('admin.menu-items.destroy', $item) }}">
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
