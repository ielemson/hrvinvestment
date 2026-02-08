@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Create Role</h4>

                    <form class="forms-sample" method="POST" action="{{ route('admin.roles.store') }}">
                        @csrf

                        <div class="form-group">
                            <label>Role Name</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" placeholder="e.g admin">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
