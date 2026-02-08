@extends('layouts.admin')
@section('title', 'Loan Application')

@section('content')
    <div class="content-wrapper">

        <div class="page-header">
            {{-- <h3 class="page-title">My Loans</h3> --}}
            @php $currencySymbol = $siteSettings->currency_symbol ?? '$'; @endphp
            <div class="d-flex align-items-center">
                <form method="GET" action="{{ route('user.loans.index') }}" class="mr-2">
                    <div class="input-group">
                        <select class="form-control" name="status" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            @php
                                $statuses = [
                                    'draft',
                                    'submitted',
                                    'under_review',
                                    'approved',
                                    'rejected',
                                    'disbursed',
                                    'active',
                                    'completed',
                                    'defaulted',
                                    'cancelled',
                                ];
                            @endphp
                            @foreach ($statuses as $s)
                                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                    {{ strtoupper(str_replace('_', ' ', $s)) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <a href="{{ route('user.loans.index') }}" class="btn btn-light">Reset</a>
                        </div>
                    </div>
                </form>

                <a href="{{ route('user.loans.create') }}" class="btn btn-primary btn-icon-text">
                    <i class="mdi mdi-plus btn-icon-prepend"></i> Apply for Loan
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">Loan Applications</h4>
                            <small class="text-muted">Track your loan lifecycle here.</small>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Loan ID</th>
                                        <th>Requested</th>
                                        <th>Approved</th>
                                        <th>Tenure</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($loans as $loan)
                                        <tr>
                                            <td>#{{ $loan->id }}</td>
                                            <td>{{ $currencySymbol }}{{ number_format((float) $loan->amount_requested, 2) }}
                                            </td>
                                            <td>
                                                @if ($loan->amount_approved)
                                                    {{ $currencySymbol }}{{ number_format((float) $loan->amount_approved, 2) }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ $loan->tenure_months }} mo</td>
                                            <td>
                                                @php
                                                    $badge = match ($loan->status) {
                                                        'approved', 'completed' => 'badge-success',
                                                        'under_review', 'submitted' => 'badge-warning',
                                                        'rejected', 'defaulted', 'cancelled' => 'badge-danger',
                                                        'disbursed', 'active' => 'badge-info',
                                                        default => 'badge-secondary',
                                                    };
                                                @endphp
                                                <span class="badge {{ $badge }}">
                                                    {{ strtoupper(str_replace('_', ' ', $loan->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ optional($loan->created_at)->format('d M, Y') }}</td>
                                            <td class="text-right">
                                                <a href="{{ route('user.loans.show', $loan) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <div class="text-center py-4">
                                                    <p class="text-muted mb-2">No loans found.</p>
                                                    <a href="{{ route('user.loans.create') }}"
                                                        class="btn btn-primary btn-sm">
                                                        Apply for your first loan
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $loans->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
