@extends('layouts.admin')
@section('title', 'KYC Verification')

@section('content')
    <div class="container-fluid">
        <div class="row">

            {{-- Left: Profile summary --}}
            <div class="col-lg-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img class="img-lg rounded-circle"
                                src="{{ $user->profile_photo_url ?? asset('assets/images/faces/face1.jpg') }}" alt="profile">
                        </div>

                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-2">{{ $user->email }}</p>

                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <span class="badge {{ $user->hasVerifiedEmail() ? 'badge-success' : 'badge-warning' }}">
                                Email {{ $user->hasVerifiedEmail() ? 'Verified' : 'Unverified' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: KYC Status --}}
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">KYC Status</h5>
                            <a href="" class="btn btn-sm btn-primary">
                                {{ $kyc ? 'Update Documents' : 'Upload Documents' }}
                            </a>
                        </div>

                        @php
                            $status = $kyc->status ?? 'not_submitted';

                            $badge = match ($status) {
                                'approved' => 'badge-success',
                                'rejected' => 'badge-danger',
                                'submitted' => 'badge-info',
                                'pending' => 'badge-warning',
                                default => 'badge-secondary',
                            };

                            $label = ucwords(str_replace('_', ' ', $status));
                        @endphp

                        <p class="mb-2">
                            Status:
                            <span class="badge {{ $badge }}">{{ $label }}</span>
                        </p>

                        {{-- Helpful message per status --}}
                        @if ($status === 'not_submitted')
                            <p class="text-muted">
                                You haven’t submitted your KYC yet. Upload a valid ID and proof of income to complete
                                verification.
                            </p>
                        @elseif($status === 'pending')
                            <p class="text-muted">
                                Your KYC is pending. Please upload the required documents to proceed.
                            </p>
                        @elseif($status === 'submitted')
                            <p class="text-muted">
                                Your documents have been submitted and are currently under review.
                            </p>
                        @elseif($status === 'approved')
                            <p class="text-muted">
                                Your KYC has been approved. You’re fully verified.
                            </p>
                        @elseif($status === 'rejected')
                            <p class="text-muted">
                                Your KYC was rejected. Please review the reason below and resubmit.
                            </p>

                            @if (!empty($kyc->rejection_reason))
                                <div class="alert alert-danger py-2 mb-0">
                                    <strong>Rejection reason:</strong> {{ $kyc->rejection_reason }}
                                </div>
                            @endif
                        @endif

                        {{-- Optional: show when submitted/reviewed --}}
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Submitted at</small>
                                <div>{{ optional($kyc?->created_at)->format('M d, Y h:i A') ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Reviewed at</small>
                                <div>{{ optional($kyc?->reviewed_at)->format('M d, Y h:i A') ?? '—' }}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
