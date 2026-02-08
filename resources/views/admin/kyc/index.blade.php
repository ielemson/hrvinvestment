@extends('layouts.admin')
@section('title', 'KYC Requests')
@section('content')
    <div class="container">

        <div class="card">
            <div class="card-body">
                <h4 class="card-title">KYC Requests</h4>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="bg-light">
                                <th>User</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kycs as $kyc)
                                @php
                                    $badge = match ($kyc->status) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'under_review' => 'info',
                                        'submitted' => 'warning',
                                        default => 'secondary',
                                    };
                                @endphp
                                <tr>
                                    <td>{{ $kyc->full_name ?? $kyc->user->name }}</td>
                                    <td>{{ $kyc->user->email }}</td>
                                    <td><span class="badge badge-{{ $badge }}">{{ strtoupper($kyc->status) }}</span>
                                    </td>
                                    <td>{{ $kyc->updated_at->format('d M, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.kyc.show', $kyc) }}" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">{{ $kycs->links() }}</div>
            </div>
        </div>

    </div>
@endsection
