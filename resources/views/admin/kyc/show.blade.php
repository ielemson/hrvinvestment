@extends('layouts.admin')
@section('title', 'KYC Requests')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">KYC Detail</h4>

                        <p class="mb-1"><strong>User:</strong> {{ $kyc->full_name ?? $kyc->user->name }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $kyc->user->email }}</p>
                        <p class="mb-1"><strong>Phone:</strong> {{ $kyc->phone_e164 }}</p>
                        <p class="mb-1"><strong>Address:</strong> {{ $kyc->address }}
                            {{ $kyc->city ? ', ' . $kyc->city : '' }} {{ $kyc->state ? ', ' . $kyc->state : '' }}</p>

                        <hr>

                        <h6 class="mb-2">Documents</h6>
                        <ul class="list-unstyled mb-0">
                            @foreach ($kyc->documents as $doc)
                                <li class="mb-2">
                                    <strong>{{ strtoupper(str_replace('_', ' ', $doc->type)) }}</strong>
                                    @if ($doc->label)
                                        <span class="text-muted">({{ $doc->label }})</span>
                                    @endif
                                    - <a target="_blank" href="{{ asset('storage/' . $doc->file_path) }}">Open</a>
                                </li>
                            @endforeach
                        </ul>

                        @if ($kyc->status === 'rejected' && $kyc->rejection_reason)
                            <div class="alert alert-danger mt-3">
                                <strong>Rejection reason:</strong> {{ $kyc->rejection_reason }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Review Action</h4>

                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('admin.kyc.approve', $kyc) }}" class="mb-3">
                            @csrf
                            <button class="btn btn-success w-100" type="submit">Approve KYC</button>
                        </form>

                        <form method="POST" action="{{ route('admin.kyc.reject', $kyc) }}">
                            @csrf
                            <div class="form-group">
                                <label>Rejection Reason</label>
                                <textarea class="form-control @error('rejection_reason') is-invalid @enderror" name="rejection_reason" rows="4"
                                    placeholder="Explain what user should fix...">{{ old('rejection_reason') }}</textarea>
                                @error('rejection_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-danger w-100" type="submit">Reject KYC</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
