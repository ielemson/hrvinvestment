@extends('layouts.admin')

@section('content')
    @include('user.partials.alerts')

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Sliders</h5>
            <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">Add New</a>
        </div>
        <div class="card-body">
            @if ($sliders->count())
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Subtitle</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sliders as $slider)
                                <tr>
                                    <td>
                                        @if ($slider->image)
                                            <img src="{{ asset('storage/' . $slider->image) }}"
                                                style="width: 60px; height: 40px; object-fit: cover;">
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($slider->title, 30) }}</td>
                                    <td>{{ Str::limit($slider->subtitle, 30) }}</td>
                                    <td>
                                        <span class="badge {{ $slider->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $slider->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.sliders.edit', $slider) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No sliders found. <a href="{{ route('admin.sliders.create') }}">Create one</a></p>
            @endif
        </div>
    </div>
@endsection
