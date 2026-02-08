@extends('layouts.admin')

@section('content')
    @include('user.partials.alerts')

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5>{{ isset($slider) ? 'Edit' : 'Create' }} Slider</h5>
                </div>
                <div class="card-body">
                    <form
                        action="{{ isset($slider) ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($slider))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $slider->title ?? '') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subtitle</label>
                            <input type="text" name="subtitle"
                                class="form-control @error('subtitle') is-invalid @enderror"
                                value="{{ old('subtitle', $slider->subtitle ?? '') }}">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            @if (isset($slider) && $slider->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $slider->image) }}"
                                        style="width: 200px; height: 120px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                    value="1" {{ old('is_active', $slider->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control"
                                value="{{ old('sort_order', $slider->sort_order ?? 0) }}" min="0">
                        </div>

                        <button type="submit" class="btn btn-primary">Save Slider</button>
                        <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
