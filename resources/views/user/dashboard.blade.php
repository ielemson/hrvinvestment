@extends('layouts.admin')

@section('content')
    <div class="container">

        @include('user.partials.kyc_notification')
        @include('user.partials.alerts')
        <!-- =====================
                                                                        SUMMARY CARDS
                                                                        ====================== -->
        @php
            // DB: site_settings fields

            $currencySymbol = $siteSettings->currency_symbol ?? "$";
        @endphp

        @php
            $statusMap = [
                'pending' => [
                    'label' => 'Pending Submission',
                    'badge' => 'badge-secondary',
                    'message' => 'Upload ID and selfie to verify your identity.',
                    'cta' => 'Start Verification',
                ],
                'submitted' => [
                    'label' => 'Documents Submitted',
                    'badge' => 'badge-info',
                    'message' => 'Your documents are queued for review.',
                    'cta' => 'View Documents',
                ],
                'under_review' => [
                    'label' => 'Under Review',
                    'badge' => 'badge-warning',
                    'message' => 'Verification in progress (usually 24-48 hours).',
                    'cta' => null,
                ],
                'approved' => [
                    'label' => 'Verified',
                    'badge' => 'badge-success',
                    'message' => 'Identity confirmed! Ready for higher loan limits.',
                    'cta' => null,
                ],
                'rejected' => [
                    'label' => 'Documents Rejected',
                    'badge' => 'badge-danger',
                    'message' => $kyc?->rejection_reason ?? 'Please upload clear, valid documents.',
                    'cta' => 'Re-upload Documents',
                ],
            ];

            $current = $statusMap[$kycStatus] ?? $statusMap['pending'];
        @endphp

        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card card-statistics">
                    <div class="row">

                        {{-- Active Loan --}}
                        <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-cash-multiple text-primary icon-lg mr-3"></i>
                                    <div>
                                        <p class="card-text mb-0">Active Loan</p>
                                        <h4 class="mb-0">
                                            {{ $currencySymbol }}{{ number_format($activeLoanAmount ?? 0) }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Loan Status --}}
                        <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-progress-check text-warning icon-lg mr-3"></i>
                                    <div>
                                        <p class="card-text mb-0">Loan Status</p>
                                        <h4 class="mb-0">
                                            {{ $loanStatusLabel ?? 'No Loan' }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Wallet Balance (hook up to your existing wallet logic later) --}}
                        <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-wallet text-success icon-lg mr-3"></i>
                                    <div>
                                        <p class="card-text mb-0">Wallet Balance</p>
                                        <h4 class="mb-0">
                                            {{ $currencySymbol }}{{ number_format($wallet->available_balance ?? 0) }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Next Repayment --}}
                        <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-calendar-clock text-danger icon-lg mr-3"></i>
                                    <div>
                                        <p class="card-text mb-0">
                                            Next Repayment
                                            @if ($nextRepaymentDate)
                                                <small class="d-block">
                                                    {{ \Carbon\Carbon::parse($nextRepaymentDate)->format('d M Y') }}
                                                </small>
                                            @endif
                                        </p>

                                        @if ($nextRepaymentAmount)
                                            <h4 class="mb-0">
                                                {{ $currencySymbol }}{{ number_format($nextRepaymentAmount) }}
                                            </h4>
                                        @else
                                            <h4 class="mb-0">—</h4>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- =====================
                                        LOAN PROGRESS + KYC
                                        ====================== -->
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        {{-- Header --}}
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="mdi mdi-progress-clock text-primary mr-2"></i>
                                Loan Progress
                            </h5>
                            @if ($hasActiveLoan)
                                <span
                                    class="badge badge-{{ $loanProgressSteps[$currentStatus]['color'] ?? 'secondary' }} px-3 py-2">
                                    <i
                                        class="mdi mdi-{{ $currentStatus === 'disbursed' ? 'cash-check' : ($currentStatus === 'approved' ? 'shield-check' : 'magnify-scan') }} mr-1"></i>
                                    {{ $loanStatusLabel }}
                                </span>
                            @else
                                <span class="badge badge-secondary px-3 py-2">
                                    <i class="mdi mdi-plus-circle-outline mr-1"></i>Apply Now
                                </span>
                            @endif
                        </div>

                        @if ($hasActiveLoan)
                            @if ($loan && !empty($workflowUI))
                                <div>
                                    <div class="card-body">

                                        <div class="loan-timeline">
                                            @foreach ($workflowUI as $item)
                                                @php
                                                    $status = $item['status'] ?? 'under_review';

                                                    $badge = match ($status) {
                                                        'approved' => 'success',
                                                        'reviewed' => 'primary',
                                                        default => 'warning',
                                                    };

                                                    $isCompleted = $status === 'approved';
                                                    $isActive = (bool) ($item['is_active'] ?? false);
                                                    $isLocked = !$isActive;

                                                    // 🔹 Get actioned date safely
                                                    $levelRow = $loan->workflowLevels->firstWhere(
                                                        'level_key',
                                                        $item['key'],
                                                    );
                                                    $actionedAt = $levelRow?->edited_at ?? $levelRow?->updated_at;

                                                    $date = $actionedAt
                                                        ? \Carbon\Carbon::parse($actionedAt)->format('d-m-Y')
                                                        : '—';
                                                    $time = $actionedAt
                                                        ? \Carbon\Carbon::parse($actionedAt)->format('H:i')
                                                        : '';
                                                @endphp

                                                <div class="loan-timeline__item {{ $isLocked ? 'is-locked' : '' }}">
                                                    {{-- LEFT: DATE / TIME --}}
                                                    <div class="loan-timeline__time">
                                                        <div class="loan-timeline__date">{{ $date }}</div>
                                                        <div class="loan-timeline__clock">{{ $time }}</div>
                                                    </div>

                                                    {{-- CENTER: DOT + LINE --}}
                                                    <div class="loan-timeline__rail">
                                                        <span
                                                            class="loan-timeline__dot
                    {{ $isCompleted ? 'is-completed' : ($isActive ? 'is-active' : 'is-pending') }}">
                                                        </span>

                                                        @if (!$loop->last)
                                                            <span class="loan-timeline__line"></span>
                                                        @endif
                                                    </div>

                                                    {{-- RIGHT: CONTENT --}}
                                                    <div class="loan-timeline__content">
                                                        <div class="loan-timeline__top">
                                                            <div class="loan-timeline__title">
                                                                {{ $item['label'] }}
                                                            </div>

                                                            <span class="badge badge-{{ $badge }} badge-pill">
                                                                {{ ucwords(str_replace('_', ' ', $status)) }}
                                                            </span>
                                                        </div>

                                                        <div class="loan-timeline__meta text-muted">
                                                            @if ($isCompleted)
                                                                <i class="mdi mdi-check-circle-outline mr-1"></i>Completed
                                                            @elseif($isActive)
                                                                <i class="mdi mdi-progress-clock mr-1"></i>In progress
                                                            @else
                                                                <i class="mdi mdi-lock-outline mr-1"></i>Pending
                                                            @endif
                                                        </div>

                                                        @if ($item['can_open'])
                                                            <div class="mt-2">
                                                                <a href="{{ route('user.loans.show', $loan->id) }}"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <i class="mdi mdi-eye-outline mr-1"></i>View
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>


                                    </div>
                                </div>
                            @endif
                        @else
                            {{-- Empty --}}
                            <div class="text-center py-5">
                                <i class="mdi mdi-bank-outline mdi-4x text-muted mb-4"></i>
                                <h6 class="mb-3 text-muted">No Active Loans</h6>
                                <p class="text-muted mb-4">Start your loan journey today</p>
                                <a href="{{ route('user.loans.create') }}" class="btn btn-primary btn-lg">
                                    <i class="mdi mdi-plus-circle-outline mr-2"></i>New Application
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6 grid-margin stretch-card">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        {{-- Header --}}
                        <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                            <div class="status-icon mr-3">
                                <i
                                    class="mdi mdi-account-check-outline mdi-3x {{ $kycApproved ? 'text-success' : 'text-warning' }}"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">KYC Verification</h5>
                                <small class="text-muted">Identity confirmation status</small>
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        <div class="status-badge mb-4">
                            <span class="badge badge-{{ $current['badge'] }} px-4 py-2 font-weight-normal">
                                <i class="mdi mdi-{{ $kycApproved ? 'check-circle' : 'clock-outline' }} mr-2"></i>
                                {{ $current['label'] }}
                            </span>
                        </div>

                        {{-- Status Message --}}
                        <div class="status-message mb-4">
                            <p class="text-muted mb-3">{{ $current['message'] }}</p>

                            @if ($kyc && in_array($kycStatus, ['under_review', 'rejected']))
                                <small class="text-muted">
                                    <i class="mdi mdi-calendar-clock mr-1"></i>
                                    Updated: {{ $kyc->updated_at?->format('M d, Y') ?? 'Never' }}
                                </small>
                            @endif
                        </div>

                        {{-- Recommended: Visual Progress (No Text Problems) --}}
                        <div class="progress-container mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">
                                    <i class="mdi mdi-account-check-outline mr-1"></i>Verification Progress
                                </small>
                                <span class="badge badge-sucess px-2">
                                    {{ $kycApproved ? '100%' : '65%' }}
                                    <i class="mdi mdi-{{ $kycApproved ? 'check' : 'clock' }} ml-1"></i>
                                </span>
                            </div>
                            <div class="progress rounded-pill" style="height: 10px;">
                                <div class="progress-bar {{ $kycApproved ? 'bg-success' : 'bg-warning' }} progress-bar-animated rounded-pill"
                                    role="progressbar" style="width: {{ $kycApproved ? '100%' : '65%' }}">
                                </div>
                            </div>
                            <div class="progress-steps mt-3">
                                <div class="step small {{ !$kycApproved ? 'active' : 'completed' }}">
                                    <i class="mdi mdi-file-upload-outline"></i> Documents
                                </div>
                                <div class="step small {{ $kycApproved ? 'completed' : 'active' }}">
                                    <i class="mdi mdi-account-check"></i> Review
                                </div>
                                <div class="step small {{ $kycApproved ? 'completed' : '' }}">
                                    <i class="mdi mdi-check-circle"></i> Verified
                                </div>
                            </div>
                        </div>


                        {{-- Action --}}
                        @if ($current['cta'])
                            <a href="{{ route('user.kyc.edit') }}"
                                class="btn btn-{{ $kycStatus === 'rejected' ? 'danger' : 'primary' }} btn-block btn-lg">
                                <i class="mdi mdi-{{ $kycStatus === 'rejected' ? 'redo' : 'upload' }} mr-2"></i>
                                {{ $current['cta'] }}
                            </a>
                        @elseif(!$kycApproved)
                            <div class="alert alert-info border-0">
                                <i class="mdi mdi-lightbulb-outline mr-2"></i>
                                Complete KYC to unlock higher loan limits
                            </div>
                        @else
                            <div class="text-center py-3 bg-success text-white rounded p-3">
                                <i class="mdi mdi-check-circle mdi-2x mb-2 d-block"></i>
                                <small>Ready for loans!</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>


        </div>

        <!-- =====================
                                                           LOAN HISTORY
                                               ====================== -->
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">My Loan Requests</h5>

                        <div class="table-responsive">
                            <table class="table center-aligned-table">
                                <thead>
                                    <tr class="bg-light">
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Requested</th>
                                        <th>Approved</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($loanHistory as $row)
                                        @php $status = $row['status']; @endphp

                                        <tr>
                                            <td>{{ $row['date'] }}</td>
                                            <td>{{ $row['reference'] }}</td>

                                            <td>{{ $currencySymbol }}{{ number_format($row['amount_requested']) }}</td>
                                            <td>
                                                @if (($row['amount_approved'] ?? 0) > 0)
                                                    {{ $currencySymbol }}{{ number_format($row['amount_approved']) }}
                                                @else
                                                    —
                                                @endif
                                            </td>

                                            <td>
                                                @switch($status)
                                                    @case('under_review')
                                                        <span class="badge badge-warning">Under Review</span>
                                                    @break

                                                    @case('approved')
                                                        <span class="badge badge-success">Approved</span>
                                                    @break

                                                    @case('disbursed')
                                                        <span class="badge badge-primary">Disbursed</span>
                                                    @break

                                                    @case('rejected')
                                                        <span class="badge badge-danger">Rejected</span>
                                                    @break

                                                    @case('cancelled')
                                                        <span class="badge badge-secondary">Cancelled</span>
                                                    @break

                                                    @case('closed')
                                                        <span class="badge badge-dark">Closed</span>
                                                    @break

                                                    @default
                                                        <span class="badge badge-light text-dark">Pending</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">
                                                    You haven’t requested any loans yet.
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
            <!-- =====================
                                                                                                            REPAYMENT HISTORY
                                                                                   ====================== -->
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Recent Repayments</h5>
                            <div class="table-responsive">
                                <table class="table center-aligned-table">
                                    <thead>
                                        <tr class="bg-light">
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Method</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentRepayments as $repayment)
                                            <tr>
                                                <td>{{ $repayment['date'] }}</td>
                                                <td>{{ $currencySymbol }}{{ number_format($repayment['amount']) }}</td>
                                                <td>{{ $repayment['method'] }}</td>
                                                <td>
                                                    @php $status = $repayment['status']; @endphp
                                                    @if ($status === 'paid')
                                                        <span class="badge badge-success">Paid</span>
                                                    @elseif($status === 'overdue' || $status === 'missed')
                                                        <span class="badge badge-danger">Missed</span>
                                                    @else
                                                        <span class="badge badge-warning">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    No repayment history yet.
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


            <!-- =====================
                                                                                                                    NOTIFICATIONS
                                                                                                                    ====================== -->
            {{-- <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Recent Notifications</h5>
                            <ul class="bullet-line-list">
                                <li>Loan application received</li>
                                <li>Repayment due in 3 days</li>
                                <li>KYC document pending</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Quick Actions</h5>
                            <div class="d-flex flex-column">
                                <a href="{{ route('user.loans.create') }}" class="btn btn-primary mb-2">Apply for Loan</a>
                                <button class="btn btn-outline-success mb-2">Make Repayment</button>
                                <button class="btn btn-outline-info">Contact Support</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>
    @endsection

    @push('styles')
        <style>
            .progress-steps {
                display: flex;
                gap: 8px;
                margin-bottom: 24px;
            }

            .step-item {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 12px 8px;
                border-radius: 8px;
                background: #f8f9fa;
                transition: all 0.3s;
            }

            .step-item.active {
                background: linear-gradient(135deg, var(--primary) 0%, #007bffcc 100%);
                color: white;
                transform: translateY(-4px);
            }

            .step-item.completed {
                background: #d4edda;
            }

            .step-number {
                font-size: 12px;
                font-weight: 600;
                margin-bottom: 4px;
                opacity: 0.7;
            }

            .step-icon {
                font-size: 20px;
                margin-bottom: 6px;
            }

            .step-label {
                font-size: 12px;
                font-weight: 500;
                text-align: center;
            }

            .summary-box {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .progress-container .progress-steps {
                display: flex;
                justify-content: space-between;
                margin-top: 12px;
            }

            .progress-container .step {
                text-align: center;
                flex: 1;
                padding: 8px 4px;
                font-size: 11px;
                opacity: 0.6;
                transition: all 0.3s;
            }

            .progress-container .step.active,
            .progress-container .step.completed {
                opacity: 1;
                font-weight: 600;
            }

            .progress-container .step.completed i {
                color: #28a745;
            }

            .progress-container .step.active i {
                color: #ffc107;
            }

            .progress-label {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 11px;
                font-weight: 600;
                white-space: nowrap;
            }

            .loan-timeline {
                position: relative;
            }

            .loan-timeline__item {
                display: grid;
                grid-template-columns: 90px 28px 1fr;
                gap: 12px;
                padding: 14px 0;
            }

            .loan-timeline__time {
                text-align: right;
                line-height: 1.1;
                padding-top: 2px;
            }

            .loan-timeline__date {
                font-weight: 700;
                font-size: 12px;
                color: #6c757d;
            }

            .loan-timeline__clock {
                font-size: 12px;
                color: #adb5bd;
                margin-top: 6px;
            }

            .loan-timeline__rail {
                position: relative;
                display: flex;
                justify-content: center;
            }

            .loan-timeline__dot {
                width: 14px;
                height: 14px;
                border-radius: 50%;
                border: 2px solid #ced4da;
                background: #fff;
                position: relative;
                z-index: 2;
                margin-top: 4px;
            }

            .loan-timeline__line {
                position: absolute;
                top: 22px;
                width: 2px;
                height: calc(100% + 14px);
                background: #e9ecef;
                z-index: 1;
            }

            .loan-timeline__content {
                padding-bottom: 2px;
            }

            .loan-timeline__top {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 10px;
            }

            .loan-timeline__title {
                font-weight: 700;
                color: #343a40;
            }

            .loan-timeline__badge {
                font-weight: 600;
                white-space: nowrap;
            }

            .loan-timeline__meta {
                font-size: 12px;
                margin-top: 6px;
            }

            /* States */
            .loan-timeline__dot.is-active {
                border-color: #007bff;
                box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.10);
            }

            .loan-timeline__dot.is-completed {
                border-color: #28a745;
                background: #28a745;
            }

            .loan-timeline__dot.is-pending {
                border-color: #ced4da;
                background: #fff;
            }

            /* Locked (greyed out) */
            .loan-timeline__item.is-locked {
                opacity: 0.55;
            }

            .loan-timeline__item.is-locked .loan-timeline__title {
                color: #6c757d;
            }
        </style>
    @endpush
