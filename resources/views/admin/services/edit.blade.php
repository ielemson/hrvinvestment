@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Service</h4>
                <form method="POST" enctype="multipart/form-data" action="{{ route('admin.services.update', $service) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Title <span class="text-danger">*</span></label>
                                <input name="title" class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title', $service->title ?? '') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Slug (optional)</label>
                                <input name="slug" class="form-control @error('slug') is-invalid @enderror"
                                    value="{{ old('slug', $service->slug ?? '') }}">
                                <small class="text-muted">Leave empty to auto-generate from title.</small>
                                @error('slug')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Short Description</label>
                                <input name="short_description"
                                    class="form-control @error('short_description') is-invalid @enderror"
                                    value="{{ old('short_description', $service->short_description ?? '') }}">
                                @error('short_description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $service->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Icon class</label>
                                <input name="icon" class="form-control @error('icon') is-invalid @enderror"
                                    placeholder="mdi mdi-briefcase" value="{{ old('icon', $service->icon ?? '') }}">
                                <small class="text-muted">Use the icon pack your LibertyUI theme supports.</small>
                                @error('icon')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Image (optional)</label>
                                <input type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.webp">
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                @if (!empty($service->image))
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $service->image) }}" alt="Service image"
                                            style="max-height:80px;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>CTA Text</label>
                                <input name="cta_text" class="form-control @error('cta_text') is-invalid @enderror"
                                    value="{{ old('cta_text', $service->cta_text ?? '') }}">
                                @error('cta_text')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>CTA URL</label>
                                <input name="cta_url" class="form-control @error('cta_url') is-invalid @enderror"
                                    value="{{ old('cta_url', $service->cta_url ?? '') }}">
                                @error('cta_url')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sort Order</label>
                                <input type="number" name="sort_order"
                                    class="form-control @error('sort_order') is-invalid @enderror"
                                    value="{{ old('sort_order', $service->sort_order ?? 0) }}" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label">Active (show on website)</label>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary mt-3">Save</button>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-light mt-3">Cancel</a>

                </form>
            </div>
        </div>
    </div>
@endsection
