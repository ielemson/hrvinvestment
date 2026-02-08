@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Service</h4>
                <form method="POST" enctype="multipart/form-data" action="{{ route('admin.services.store') }}">
                    @include('admin.services._form')
                </form>
            </div>
        </div>
    </div>
@endsection
