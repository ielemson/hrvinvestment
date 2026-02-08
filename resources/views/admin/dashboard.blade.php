@extends('layouts.admin')

@section('content')
    <div class="container">

        {{-- 1. KPI STATISTICS (First Template Data in Second Style) --}}
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card card-statistics">
                    <div class="row">

                        {{-- Total Users --}}
                        <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                    <i class="mdi mdi-account-group text-primary mr-0 mr-sm-4 icon-lg"></i>
                                    <div class="wrapper text-center text-sm-left">
                                        <p class="card-text mb-0">Total Users</p>
                                        <h3 class="card-title mb-0">{{ number_format($stats['totalUsers']) }}</h3>
                                        <small class="text-muted">All registered accounts</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Pending KYC --}}
                        <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                    <i class="mdi mdi-account-check text-warning mr-0 mr-sm-4 icon-lg"></i>
                                    <div class="wrapper text-center text-sm-left">
                                        <p class="card-text mb-0">Pending KYC</p>
                                        <h3 class="card-title mb-0">{{ number_format($stats['pendingKyc']) }}</h3>
                                        <small class="text-muted">Needs verification</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Pending Loans --}}
                        <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                    <i class="mdi mdi-cash-remove text-danger mr-0 mr-sm-4 icon-lg"></i>
                                    <div class="wrapper text-center text-sm-left">
                                        <p class="card-text mb-0">Pending Loans</p>
                                        <h3 class="card-title mb-0">{{ number_format($stats['pendingLoans']) }}</h3>
                                        <small class="text-muted">Awaiting review</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Active Loans --}}
                        <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                    <i class="mdi mdi-cash-check text-success mr-0 mr-sm-4 icon-lg"></i>
                                    <div class="wrapper text-center text-sm-left">
                                        <p class="card-text mb-0">Active Loans</p>
                                        <h3 class="card-title mb-0">{{ number_format($stats['activeLoans']) }}</h3>
                                        <small class="text-muted">Currently disbursed</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- quick actiona --}}
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                            <div class="mb-3 mb-md-0">
                                <h4 class="card-title mb-1">Quick Actions</h4>
                                <p class="text-muted mb-0">
                                    {{ number_format($stats['totalLoans']) }} total loans |
                                    {{ number_format($stats['pendingKyc']) }} KYC pending
                                </p>
                            </div>

                            <div class="d-flex flex-wrap">
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary mr-2 mb-2">
                                    <i class="mdi mdi-account-plus mr-1"></i>Add User
                                </a>

                                <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-primary mr-2 mb-2">
                                    <i class="mdi mdi-image-multiple mr-1"></i>Manage Sliders
                                </a>

                                <a href="{{ route('admin.kyc.index') }}" class="btn btn-outline-warning mr-2 mb-2">
                                    <i class="mdi mdi-account-check mr-1"></i>KYC Queue ({{ $stats['pendingKyc'] }})
                                </a>

                                <a href="{{ route('admin.loans.index') }}" class="btn btn-outline-danger mr-2 mb-2">
                                    <i class="mdi mdi-cash-remove mr-1"></i>Review Loans ({{ $stats['pendingLoans'] }})
                                </a>

                                <a href="{{ route('home') }}" class="btn btn-outline-info mb-2" target="_blank">
                                    <i class="mdi mdi-home mr-1"></i>Preview Site
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            // DB: site_settings fields
            $currencySymbol = $siteSettings->currency_symbol ?? "$";
            $sn = 0;
        @endphp
        <!-- RECENT ACTIVITY TABLE (Dummy) -->
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h5 class="card-title mb-0">Recent Users</h5>
                                <small class="text-muted">Latest sign-ups with KYC and loan snapshot</small>
                            </div>

                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="mdi mdi-account-group-outline mr-1"></i> View All Users
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table center-aligned-table">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="border-bottom-0">#</th>
                                        <th class="border-bottom-0">User</th>
                                        <th class="border-bottom-0">Phone</th>
                                        <th class="border-bottom-0">KYC</th>
                                        <th class="border-bottom-0">Loans</th>
                                        <th class="border-bottom-0">Latest Loan</th>
                                        <th class="border-bottom-0">Joined</th>
                                        <th class="border-bottom-0 text-right">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($recentUsers as $user)
                                        @php
                                            $kycStatus = $user->kyc_status ?? 'not_submitted';
                                            $loanStatus = $user->latest_loan_status ?? null;

                                            $kycBadge = match ($kycStatus) {
                                                'approved' => ['text' => 'Verified', 'class' => 'success'],
                                                'submitted' => ['text' => 'Submitted', 'class' => 'warning'],
                                                'under_review' => ['text' => 'Under Review', 'class' => 'info'],
                                                'rejected' => ['text' => 'Rejected', 'class' => 'danger'],
                                                default => ['text' => 'Not Submitted', 'class' => 'secondary'],
                                            };

                                            $loanBadge = match ($loanStatus) {
                                                'under_review' => ['text' => 'Under Review', 'class' => 'warning'],
                                                'approved' => ['text' => 'Approved', 'class' => 'success'],
                                                'disbursed' => ['text' => 'Disbursed', 'class' => 'primary'],
                                                'rejected' => ['text' => 'Rejected', 'class' => 'danger'],
                                                'closed' => ['text' => 'Closed', 'class' => 'dark'],
                                                default => null,
                                            };
                                        @endphp

                                        <tr>
                                            <td>{{ ++$sn }}</td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-2">
                                                        <span class="badge badge-light text-dark">U</span>
                                                    </div>
                                                    <div>
                                                        <div class="d-flex align-items-center">
                                                            <strong>{{ $user->name }}</strong>
                                                            @if ($user->is_admin)
                                                                <span class="badge badge-dark ml-2">Admin</span>
                                                            @endif
                                                        </div>
                                                        <small class="text-muted">{{ $user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="text-muted">{{ $user->phone ?? '—' }}</span>
                                            </td>

                                            <td>
                                                <span class="badge badge-{{ $kycBadge['class'] }}">
                                                    {{ $kycBadge['text'] }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge badge-info">{{ $user->loans_count ?? 0 }}</span>
                                            </td>

                                            <td>
                                                @if ($loanBadge)
                                                    <div class="d-flex flex-column">
                                                        <span class="badge badge-{{ $loanBadge['class'] }} w-fit">
                                                            {{ $loanBadge['text'] }}
                                                        </span>
                                                        <small class="text-muted mt-1">
                                                            {{ $currencySymbol }}{{ number_format($user->latest_loan_amount ?? 0) }}
                                                            @if ($user->latest_loan_date)
                                                                ·
                                                                {{ \Carbon\Carbon::parse($user->latest_loan_date)->format('d M') }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="text-muted">
                                                    {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d M, Y') : '—' }}
                                                </span>
                                            </td>

                                            <td class="text-right">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="" class="btn btn-outline-primary" title="View">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>

                                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                                        class="btn btn-outline-secondary" title="Edit">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>

                                                    <a href="{{ route('admin.loans.index', ['user_id' => $user->id]) }}"
                                                        class="btn btn-outline-info" title="Loans">
                                                        <i class="mdi mdi-cash-multiple"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                No users found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
