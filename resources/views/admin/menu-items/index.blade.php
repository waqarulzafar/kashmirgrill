@extends('admin.layout')

@section('admin_title', 'Manage Menu Items')

@section('admin_content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">Menu Items</h2>
                <a href="{{ route('admin.menu-items.create') }}" class="btn btn-brand btn-sm">Add Item</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
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
                                        <img src="{{ $item->imageUrl() }}" alt="{{ $item->name }}" class="img-fluid rounded" loading="lazy">
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
                                <td>£{{ number_format((float) $item->price, 2) }}</td>
                                <td>
                                    @if($item->is_available)
                                        <span class="badge text-bg-success">Available</span>
                                    @else
                                        <span class="badge text-bg-secondary">Unavailable</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.menu-items.edit', $item) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-item-{{ $item->id }}">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-4">No menu items yet.</td>
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
