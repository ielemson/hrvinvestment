@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Service</h4>
                <form method="POST" enctype="multipart/form-data" action="{{ route('admin.services.update', $service) }}">
                    @method('PUT')
                    @include('admin.services._form', ['service' => $service])
                </form>
            </div>
        </div>
    </div>
@endsection
