@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Services</h4>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">Add Service</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Icon</th>
                                <th>CTA</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                                <tr>
                                    <td>{{ $service->sort_order }}</td>
                                    <td>
                                        <div class="font-weight-bold">{{ $service->title }}</div>
                                        <small class="text-muted">{{ $service->slug }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $service->is_active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $service->is_active ? 'Active' : 'Hidden' }}
                                        </span>
                                    </td>
                                    <td><small class="text-muted">{{ $service->icon ?? '—' }}</small></td>
                                    <td>
                                        <small class="text-muted">{{ $service->cta_text ?? '—' }}</small>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.services.edit', $service) }}"
                                            class="btn btn-outline-primary btn-sm">Edit</a>
                                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this service?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-muted">No services yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $services->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
