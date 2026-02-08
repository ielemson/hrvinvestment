@csrf

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Title *</label>
            <input name="title" class="form-control" value="{{ old('title', $service->title ?? '') }}" required>
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Slug (optional)</label>
            <input name="slug" class="form-control" value="{{ old('slug', $service->slug ?? '') }}">
            <small class="text-muted">Leave empty to auto-generate from title.</small>
            @error('slug')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Short Description</label>
            <input name="short_description" class="form-control"
                value="{{ old('short_description', $service->short_description ?? '') }}">
            @error('short_description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="5" class="form-control">{{ old('description', $service->description ?? '') }}</textarea>
            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Icon class</label>
            <input name="icon" class="form-control" placeholder="mdi mdi-briefcase"
                value="{{ old('icon', $service->icon ?? '') }}">
            <small class="text-muted">Use the icon pack your LibertyUI theme supports.</small>
            @error('icon')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Image (optional)</label>
            <input type="file" name="image" class="form-control">
            @error('image')
                <small class="text-danger">{{ $message }}</small>
            @enderror

            @if (!empty($service->image))
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $service->image) }}" alt="" style="max-height:80px;">
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>CTA Text</label>
            <input name="cta_text" class="form-control" value="{{ old('cta_text', $service->cta_text ?? '') }}">
        </div>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            <label>CTA URL</label>
            <input name="cta_url" class="form-control" value="{{ old('cta_url', $service->cta_url ?? '') }}">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" class="form-control"
                value="{{ old('sort_order', $service->sort_order ?? 0) }}" min="0">
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
