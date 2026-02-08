@extends('layouts.admin')
@section('title', 'Loan Application')

@section('content')
    <div class="content-wrapper">

        <div class="page-header">
            <h3 class="page-title">Loan #{{ $loan->id }}</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.loans.index') }}">Loans</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Loan #{{ $loan->id }}</li>
                </ol>
            </nav>
        </div>

        @php
            $badge = match ($loan->status) {
                'approved', 'completed' => 'badge-success',
                'under_review', 'submitted' => 'badge-warning',
                'rejected', 'defaulted', 'cancelled' => 'badge-danger',
                'disbursed', 'active' => 'badge-info',
                default => 'badge-secondary',
            };
        @endphp

        <div class="row">
            <div class="col-lg-5 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h4 class="card-title mb-1">Loan Summary</h4>
                                <p class="text-muted mb-0">Track status and key figures</p>
                            </div>
                            <span class="badge {{ $badge }}">
                                {{ strtoupper(str_replace('_', ' ', $loan->status)) }}
                            </span>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Amount Requested</span>
                                <strong>₦{{ number_format((float) $loan->amount_requested, 2) }}</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Amount Approved</span>
                                <strong>
                                    @if ($loan->amount_approved)
                                        ₦{{ number_format((float) $loan->amount_approved, 2) }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tenure</span>
                                <strong>{{ $loan->tenure_months }} months</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Interest Rate</span>
                                <strong>{{ number_format((float) $loan->interest_rate, 2) }}%</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Submitted</span>
                                <strong>{{ optional($loan->created_at)->format('d M, Y') }}</strong>
                            </div>

                            @if ($loan->disbursed_at)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Disbursed</span>
                                    <strong>{{ \Carbon\Carbon::parse($loan->disbursed_at)->format('d M, Y') }}</strong>
                                </div>
                            @endif

                            @if ($loan->rejection_reason)
                                <div class="alert alert-danger mt-3">
                                    <strong>Rejection Reason:</strong><br>
                                    {{ $loan->rejection_reason }}
                                </div>
                            @endif

                            @if ($loan->purpose)
                                <div class="mt-3">
                                    <p class="text-muted mb-1">Purpose</p>
                                    <div class="border rounded p-2">
                                        {{ $loan->purpose }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('user.loans.index') }}" class="btn btn-light">Back</a>
                            {{-- Future: show CTA only when active --}}
                            {{-- <a href="{{ route('repayments.pay') }}" class="btn btn-primary">Make a Payment</a> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Repayment Schedule</h4>
                        <p class="card-description text-muted">Installments linked to this loan</p>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Due Date</th>
                                        <th>Amount Due</th>
                                        <th>Paid</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($loan->repayments as $repayment)
                                        @php
                                            $rb = match ($repayment->status) {
                                                'paid' => 'badge-success',
                                                'overdue' => 'badge-danger',
                                                'partial' => 'badge-warning',
                                                default => 'badge-secondary',
                                            };
                                        @endphp
                                        <tr>
                                            <td>{{ $repayment->installment_no }}</td>
                                            <td>{{ optional($repayment->due_date)->format('d M, Y') }}</td>
                                            <td>₦{{ number_format((float) $repayment->amount_due, 2) }}</td>
                                            <td>₦{{ number_format((float) $repayment->amount_paid, 2) }}</td>
                                            <td><span
                                                    class="badge {{ $rb }}">{{ strtoupper($repayment->status) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="text-center py-4">
                                                    <p class="text-muted mb-0">
                                                        No repayment schedule yet. It will appear after
                                                        approval/disbursement.
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Optional summary cards --}}
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card card-statistics">
                                    <div class="card-body">
                                        <p class="text-muted mb-1">Total Installments</p>
                                        <h5 class="mb-0">{{ $loan->repayments->count() }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-statistics">
                                    <div class="card-body">
                                        <p class="text-muted mb-1">Paid Installments</p>
                                        <h5 class="mb-0">{{ $loan->repayments->where('status', 'paid')->count() }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-statistics">
                                    <div class="card-body">
                                        <p class="text-muted mb-1">Overdue</p>
                                        <h5 class="mb-0">{{ $loan->repayments->where('status', 'overdue')->count() }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
