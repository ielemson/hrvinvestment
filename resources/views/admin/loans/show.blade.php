@extends('layouts.admin')

@section('content')
    @php
        // DB: site_settings fields
        $currencySymbol = $siteSettings->currency_symbol ?? "$";
    @endphp
    <div class="content-wrapper">
        <div class="content">
            <div class="row">
                {{-- Main Loan Info --}}
                <div class="col-xl-12 grid-margin stretch-card">
                    <div class="card shadow-sm">
                        <div class="card-body">

                            @php
                                // Applicant avatar
                                $avatarUrl = $loan->user->avatar
                                    ? asset('storage/' . $loan->user->avatar)
                                    : asset('admin/images/faces/user.png');

                                // Status badge config
                                $statusConfig = [
                                    'submitted' => [
                                        'label' => 'Submitted',
                                        'class' => 'warning',
                                        'icon' => 'clock-outline',
                                    ],
                                    'under_review' => [
                                        'label' => 'Under Review',
                                        'class' => 'info',
                                        'icon' => 'account-search',
                                    ],
                                    'approved' => [
                                        'label' => 'Approved',
                                        'class' => 'success',
                                        'icon' => 'check-circle',
                                    ],
                                    'disbursed' => [
                                        'label' => 'Disbursed',
                                        'class' => 'primary',
                                        'icon' => 'cash-check',
                                    ],
                                    'rejected' => [
                                        'label' => 'Rejected',
                                        'class' => 'danger',
                                        'icon' => 'close-circle',
                                    ],
                                    'cancelled' => ['label' => 'Cancelled', 'class' => 'secondary', 'icon' => 'cancel'],
                                    'closed' => ['label' => 'Closed', 'class' => 'dark', 'icon' => 'lock-check'],
                                ];
                                $config = $statusConfig[$loan->status] ?? [
                                    'label' => ucfirst($loan->status),
                                    'class' => 'secondary',
                                    'icon' => 'help-circle',
                                ];

                                // Map new fields to nice labels
                                $repaymentMap = [
                                    'bank_transfer' => 'Bank Transfer',
                                    'direct_debit' => 'Direct Debit',
                                    'wallet' => 'Wallet Balance',
                                ];
                                $employmentMap = [
                                    'salary' => 'Salaried Employment',
                                    'business' => 'Business / Self-Employed',
                                    'freelance' => 'Freelance / Contract',
                                    'agriculture' => 'Agriculture',
                                    'other' => 'Other',
                                ];
                                $incomeMap = [
                                    'below_100k' => "Below {$currencySymbol}100,000",
                                    '100k_300k' => "{$currencySymbol}100,000 – {$currencySymbol}300,000",
                                    '300k_700k' => "{$currencySymbol}300,000 – {$currencySymbol}700,000",
                                    'above_700k' => "Above {$currencySymbol}700,000",
                                ];

                                // KYC snapshot (if relationship exists)
                                // KYC snapshot (if relationship exists)
                                $kyc = $loan->user->kyc; // no need for latest() if hasOne

                                $kycE164 = $kyc->phone_e164 ?? ($kyc->phone ?? null);

                                $kycAddress = $kyc
                                    ? collect([
                                        $kyc->address,
                                        $kyc->city,
                                        $kyc->state,
                                        $kyc->country, // ✅ added
                                    ])
                                        ->filter()
                                        ->implode(', ')
                                    : null;

                                // Simple affordability hint (optional): if income band known, compare with estimated monthly (very rough)
                                // You can replace this later with a proper score engine.
                                $tenure = (int) ($loan->tenure_months ?? 0);
                                $rate = (float) ($loan->interest_rate ?? 0);
                                $requested = (float) ($loan->amount_requested ?? 0);
                                $approved = (float) ($loan->amount_approved ?? 0);
                                $principalForEst = $approved > 0 ? $approved : $requested;
                                $totalEst = $principalForEst + $principalForEst * ($rate / 100);
                                $monthlyEst = $tenure > 0 ? round($totalEst / $tenure) : null;

                                // Income upper bounds for quick hint
                                $incomeUpper = [
                                    'below_100k' => 100000,
                                    '100k_300k' => 300000,
                                    '300k_700k' => 700000,
                                    'above_700k' => null, // unknown upper
                                ];
                                $incomeCap = $incomeUpper[$loan->income_band ?? ''] ?? null;
                                $affordFlag = $monthlyEst && $incomeCap ? $monthlyEst > $incomeCap * 0.4 : null; // >40% of band cap
                            @endphp

                            <div class="row">
                                <div class="col-md-8">
                                    {{-- Header --}}
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div>
                                            <h4 class="card-title mb-1">
                                                Loan Application #{{ $loan->id }}
                                            </h4>
                                            <small class="text-muted d-block">
                                                Reference: <span class="fw-semibold">{{ $loan->reference ?? '—' }}</span>
                                            </small>
                                            <small class="text-muted d-block">
                                                Project: <span class="fw-semibold">{{ $loan->project_name ?? '—' }}</span>
                                            </small>
                                        </div>

                                        <div class="text-right">
                                            <span class="badge badge-{{ $config['class'] }} px-3 py-2">
                                                <i class="mdi mdi-{{ $config['icon'] }} mr-1"></i>{{ $config['label'] }}
                                            </span>

                                            <div class="mt-2">
                                                <small class="text-muted d-block">
                                                    Level: <span
                                                        class="fw-semibold">{{ ucwords(str_replace('_', ' ', $loan->current_level ?? '—')) }}</span>
                                                </small>
                                                <small class="text-muted d-block">
                                                    Level Status: <span
                                                        class="fw-semibold">{{ ucwords(str_replace('_', ' ', $loan->current_level_status ?? '—')) }}</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Applicant / Timeline / KYC --}}
                                    <div class="card mb-3">
                                        <div class="card-body py-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless mb-0">
                                                        <tr>
                                                            <td class="font-weight-bold text-muted">Applicant:</td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <img src="{{ $avatarUrl }}"
                                                                        class="rounded-circle mr-2" width="40"
                                                                        alt="{{ $loan->user->name }}">
                                                                    <div>
                                                                        <div class="fw-semibold">{{ $loan->user->name }}
                                                                        </div>
                                                                        <small
                                                                            class="text-muted">{{ $loan->user->email }}</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        @if ($kycE164)
                                                            <tr>
                                                                <td class="font-weight-bold text-muted">Phone:</td>
                                                                <td><i class="mdi mdi-phone mr-1"></i>{{ $kycE164 }}
                                                                </td>
                                                            </tr>
                                                        @endif

                                                        @if ($kyc && $kyc->status)
                                                            @php
                                                                $kbadge = match ($kyc->status) {
                                                                    'approved' => 'success',
                                                                    'rejected' => 'danger',
                                                                    'under_review' => 'info',
                                                                    'submitted' => 'warning',
                                                                    default => 'secondary',
                                                                };
                                                            @endphp
                                                            <tr>
                                                                <td class="font-weight-bold text-muted">KYC:</td>
                                                                <td>
                                                                    <span class="badge badge-{{ $kbadge }}">
                                                                        {{ strtoupper(str_replace('_', ' ', $kyc->status)) }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </table>
                                                </div>

                                                <div class="col-md-6">
                                                    <table class="table table-borderless mb-0">
                                                        <tr>
                                                            <td class="font-weight-bold text-muted">Applied:</td>
                                                            <td><i
                                                                    class="mdi mdi-calendar mr-1"></i>{{ $loan->created_at->format('M d, Y H:i') }}
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="font-weight-bold text-muted">Last Updated:</td>
                                                            <td><i
                                                                    class="mdi mdi-update mr-1"></i>{{ $loan->updated_at->format('M d, Y H:i') }}
                                                            </td>
                                                        </tr>

                                                        @if ($loan->reviewed_at)
                                                            <tr>
                                                                <td class="font-weight-bold text-muted">Reviewed:</td>
                                                                <td>
                                                                    <i
                                                                        class="mdi mdi-account-check mr-1"></i>{{ $loan->reviewed_at->format('M d, Y H:i') }}
                                                                    @if ($loan->reviewed_by)
                                                                        <div class="small text-muted mt-1">
                                                                            Reviewer ID: <span
                                                                                class="fw-semibold">{{ $loan->reviewed_by }}</span>
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endif

                                                        @if (!empty($loan->rejection_reason))
                                                            <tr>
                                                                <td class="font-weight-bold text-muted">Rejection Reason:
                                                                </td>
                                                                <td class="text-danger small">{{ $loan->rejection_reason }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>

                                            @if ($kycAddress)
                                                <div class="mt-2">
                                                    <div class="text-muted font-weight-bold">Residential Address (KYC)</div>
                                                    <div class="small">{{ $kycAddress }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Loan Figures / Personal Loan Inputs --}}
                                    <div class="card mb-3">
                                        <div class="card-body py-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless mb-0">
                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Requested:</td>
                                                            <td class="text-success fw-bold h5 mb-0">
                                                                {{ $currencySymbol }}{{ number_format($loan->amount_requested, 0) }}
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Approved:</td>
                                                            <td class="text-primary fw-bold h5 mb-0">
                                                                {{ $currencySymbol }}{{ number_format($loan->amount_approved ?? 0, 0) }}
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Tenure:</td>
                                                            <td class="fw-bold">{{ $loan->tenure_months ?? '—' }} months
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Interest Rate:</td>
                                                            <td>
                                                                <span
                                                                    class="badge badge-info">{{ $loan->interest_rate ?? 0 }}%</span>
                                                                <span class="text-muted small">per annum</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Repayment Method:
                                                            </td>
                                                            <td class="fw-semibold">
                                                                {{ $repaymentMap[$loan->repayment_method] ?? ($loan->repayment_method ? ucwords(str_replace('_', ' ', $loan->repayment_method)) : '—') }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>

                                                <div class="col-md-6">
                                                    <table class="table table-borderless mb-0">
                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Income Source:</td>
                                                            <td class="fw-semibold">
                                                                {{ $employmentMap[$loan->employment_type] ?? ($loan->employment_type ? ucwords(str_replace('_', ' ', $loan->employment_type)) : '—') }}
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Income Range:</td>
                                                            <td class="fw-semibold">
                                                                {{ $incomeMap[$loan->income_band] ?? ($loan->income_band ? ucwords(str_replace('_', ' ', $loan->income_band)) : '—') }}
                                                            </td>
                                                        </tr>

                                                        @if ($monthlyEst)
                                                            <tr>
                                                                <td class="font-weight-bold text-muted w-50">Est. Monthly:
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="fw-semibold">{{ $currencySymbol }}{{ number_format($monthlyEst) }}</span>
                                                                    @if ($affordFlag === true)
                                                                        <span class="badge badge-danger ml-2">High vs
                                                                            Income</span>
                                                                    @elseif($affordFlag === false)
                                                                        <span class="badge badge-success ml-2">OK</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endif

                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Total Payable:</td>
                                                            <td class="fw-semibold">
                                                                {{ $loan->total_payable ? $currencySymbol . number_format($loan->total_payable, 0) : '—' }}
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Outstanding:</td>
                                                            <td class="fw-semibold">
                                                                {{ $loan->outstanding_balance ? $currencySymbol . number_format($loan->outstanding_balance, 0) : '—' }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>

                                            @if (!empty($loan->purpose))
                                                <hr class="my-3">
                                                <div>
                                                    <div class="text-muted font-weight-bold">Purpose</div>
                                                    <div class="small">{{ $loan->purpose }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Project / Company / Documents --}}
                                    <div class="card">
                                        <div class="card-body py-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="text-muted font-weight-bold mb-2">Project Details</div>
                                                    <table class="table table-borderless mb-0">
                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Location:</td>
                                                            <td>{{ $loan->project_location ?? '—' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Urgency:</td>
                                                            <td>{{ $loan->project_urgency ? ucwords(str_replace('_', ' ', $loan->project_urgency)) : '—' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Project Type:</td>
                                                            <td>{{ $loan->project_type ? ucwords(str_replace('_', ' ', $loan->project_type)) : '—' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Financing Type:
                                                            </td>
                                                            <td>{{ $loan->loan_type ? ucwords(str_replace('_', ' ', $loan->loan_type)) : '—' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Duration:</td>
                                                            <td>{{ $loan->expected_duration_years ? $loan->expected_duration_years . ' yrs' : '—' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Previously Funded:
                                                            </td>
                                                            <td>{{ $loan->previous_investor_funding ? strtoupper($loan->previous_investor_funding) : '—' }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="text-muted font-weight-bold mb-2">Company Details</div>
                                                    <table class="table table-borderless mb-0">
                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Company:</td>
                                                            <td>{{ $loan->company_name ?? '—' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Address:</td>
                                                            <td>{{ $loan->company_address ?? '—' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">CEO:</td>
                                                            <td>{{ $loan->ceo_name ?? '—' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">CEO Email:</td>
                                                            <td>{{ $loan->ceo_email ?? '—' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold text-muted w-50">Bank Statement:
                                                            </td>
                                                            <td>
                                                                @if (!empty($loan->bank_account_statement_path))
                                                                    <a href="{{ Storage::url($loan->bank_account_statement_path) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-primary">
                                                                        <i class="mdi mdi-file-pdf mr-1"></i>View
                                                                    </a>
                                                                @else
                                                                    —
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>

                                            @if (!empty($loan->project_summary))
                                                <hr class="my-3">
                                                <div>
                                                    <div class="text-muted font-weight-bold">Project Summary</div>
                                                    <div class="small">{{ $loan->project_summary }}</div>
                                                </div>
                                            @endif

                                            <hr class="my-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="text-muted font-weight-bold">Disbursement</div>
                                                    <div class="small">
                                                        Disbursed At: <span
                                                            class="fw-semibold">{{ $loan->disbursed_at ? $loan->disbursed_at->format('M d, Y H:i') : '—' }}</span><br>
                                                        First Due Date: <span
                                                            class="fw-semibold">{{ $loan->first_due_date ? \Carbon\Carbon::parse($loan->first_due_date)->format('M d, Y') : '—' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{-- Right Summary --}}
                                <div class="col-md-4 text-center border-left">
                                    <div class="p-4">
                                        <i
                                            class="mdi mdi-{{ $loan->status == 'disbursed' ? 'cash-plus' : ($loan->status == 'approved' ? 'thumb-up' : 'clock-check') }} display-3 text-muted mb-3"></i>

                                        <h5 class="mb-1">
                                            {{ $loan->status == 'disbursed' ? 'Funds Released' : ucwords(str_replace('_', ' ', $loan->status)) }}
                                        </h5>

                                        @if ($loan->status == 'rejected' && $loan->rejection_reason)
                                            <p class="text-danger small mb-0">
                                                {{ \Illuminate\Support\Str::limit($loan->rejection_reason, 120) }}</p>
                                        @endif

                                        {{-- Quick callouts --}}
                                        <div class="mt-3">
                                            @if ($loan->repayment_method)
                                                <div class="small text-muted">Repayment: <span
                                                        class="fw-semibold text-dark">
                                                        {{ $repaymentMap[$loan->repayment_method] ?? ucwords(str_replace('_', ' ', $loan->repayment_method)) }}
                                                    </span></div>
                                            @endif

                                            @if ($loan->income_band)
                                                <div class="small text-muted">Income: <span class="fw-semibold text-dark">
                                                        {{ $incomeMap[$loan->income_band] ?? ucwords(str_replace('_', ' ', $loan->income_band)) }}
                                                    </span></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{--    WORKFLOW / DOCUMENT STAGES --}}
                <div class="col-md-6  grid-margin stretch-card mx-auto">

                    @php
                        $workflowLevels = \App\Models\Loan::WORKFLOW_LEVELS;

                        // helper to fetch row quickly
                        $findLevelRow = fn($key) => $loan->workflowLevels->firstWhere('level_key', $key);

                        // status label + badge map
                        $wfBadge = fn($status) => match ($status) {
                            'approved' => 'success',
                            'reviewed' => 'primary',
                            default => 'warning',
                        };

                        $wfLabel = fn($status) => ucwords(str_replace('_', ' ', $status ?? 'under_review'));
                    @endphp

                    <div class="card shadow-sm mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="mdi mdi-file-document-multiple-outline mr-2"></i>Workflow Stages
                            </h5>

                            <span class="badge badge-info px-3 py-2">
                                <i class="mdi mdi-sitemap mr-1"></i>
                                Active:
                                {{ $workflowLevels[$loan->current_level] ?? ucwords(str_replace('_', ' ', $loan->current_level)) }}
                            </span>
                        </div>

                        <div class="card-body">

                            {{-- Admin Update Form --}}
                            <form action="{{ route('admin.loans.workflow.update', $loan) }}" method="POST"
                                class="mb-4">
                                @csrf

                                <div class="form-group">
                                    <label class="font-weight-bold">Set Current Level</label>
                                    <select name="level_key" class="form-control" required>
                                        @foreach ($workflowLevels as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ $loan->current_level === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">This controls what is active for the user (others will be
                                        greyed out).</small>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Level Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="under_review"
                                            {{ $loan->current_level_status === 'under_review' ? 'selected' : '' }}>Under
                                            Review</option>
                                        <option value="reviewed"
                                            {{ $loan->current_level_status === 'reviewed' ? 'selected' : '' }}>Reviewed
                                        </option>
                                        <option value="approved"
                                            {{ $loan->current_level_status === 'approved' ? 'selected' : '' }}>Approved
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Notes (optional)</label>
                                    <textarea name="notes" rows="2" class="form-control" placeholder="Internal notes for this stage..."></textarea>
                                </div>

                                {{-- this tells controller to also set as current --}}
                                <input type="hidden" name="set_as_current" value="1">

                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="mdi mdi-content-save-outline mr-1"></i>Update Workflow
                                </button>
                            </form>

                            {{-- Timeline (DTDC-style): includes actioned date/time --}}
                            <div class="loan-timeline">
                                @foreach ($workflowLevels as $key => $label)
                                    @php
                                        $row = $findLevelRow($key);
                                        $status = $row?->status ?? 'under_review';

                                        // actioned date: edited_at first, else updated_at, else null
                                        $actionedAt = $row?->edited_at ?? $row?->updated_at;

                                        $date = $actionedAt ? \Carbon\Carbon::parse($actionedAt)->format('d-m-Y') : '—';
                                        $time = $actionedAt ? \Carbon\Carbon::parse($actionedAt)->format('H:i') : '';

                                        $isActive = $loan->current_level === $key;
                                        $isCompleted = $status === 'approved';
                                        $dotClass = $isCompleted
                                            ? 'is-completed'
                                            : ($isActive
                                                ? 'is-active'
                                                : 'is-pending');

                                        $badgeClass = $wfBadge($status);
                                    @endphp

                                    <div class="loan-timeline__item {{ $isActive ? 'is-current' : '' }}">
                                        <div class="loan-timeline__time">
                                            <div class="loan-timeline__date">{{ $date }}</div>
                                            <div class="loan-timeline__clock">{{ $time }}</div>
                                        </div>

                                        <div class="loan-timeline__rail">
                                            <span class="loan-timeline__dot {{ $dotClass }}"></span>
                                            @if (!$loop->last)
                                                <span class="loan-timeline__line"></span>
                                            @endif
                                        </div>

                                        <div class="loan-timeline__content">
                                            <div class="loan-timeline__top">
                                                <div class="loan-timeline__title">
                                                    {{ $label }}
                                                    @if ($isActive)
                                                        <span class="badge badge-info ml-2">CURRENT</span>
                                                    @endif
                                                </div>

                                                <span class="badge badge-{{ $badgeClass }} badge-pill">
                                                    {{ $wfLabel($status) }}
                                                </span>
                                            </div>

                                            <div class="loan-timeline__meta text-muted">
                                                @if ($isCompleted)
                                                    <i class="mdi mdi-check-circle-outline mr-1"></i>Completed
                                                @elseif($isActive)
                                                    <i class="mdi mdi-progress-clock mr-1"></i>In progress
                                                @else
                                                    <i class="mdi mdi-clock-outline mr-1"></i>Pending
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>

                </div>
                {{-- Approve Modal --}}
                <div class="modal fade" id="approveLoanModal" tabindex="-1" role="dialog"
                    aria-labelledby="approveLoanModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form id="approveLoanForm">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="approveLoanModalLabel">Approve Loan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    {{-- Modal alert --}}
                                    <div id="approveLoanAlert" class="alert d-none" role="alert"></div>

                                    <div class="form-group">
                                        <label for="amount_approved">Amount Approved</label>
                                        <input type="number" step="0.01" min="1" class="form-control"
                                            id="amount_approved" name="amount_approved" required>
                                        <small class="text-danger d-block" id="err_amount_approved"></small>
                                    </div>

                                    <div class="form-group">
                                        <label for="interest_rate">Interest Rate (%)</label>
                                        <input type="number" step="0.01" min="0" max="100"
                                            class="form-control" id="interest_rate" name="interest_rate" required>
                                        <small class="text-danger d-block" id="err_interest_rate"></small>
                                    </div>

                                    <div class="form-group">
                                        <label for="tenure_months">Tenure (Months)</label>
                                        <input type="number" min="1" max="60" class="form-control"
                                            id="tenure_months" name="tenure_months" required>
                                        <small class="text-danger d-block" id="err_tenure_months"></small>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success" id="approveLoanSubmitBtn">
                                        <span class="btn-text">Approve</span>
                                        <span class="d-none" id="approveLoanSpinner">Processing...</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Actions Sidebar --}}
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>

                        <div class="card-body">
                            {{-- Page-level Alert for loan actions --}}
                            <div id="loanActionAlert" class="alert d-none" role="alert"></div>

                            <div id="loanActionButtons">
                                @if (in_array($loan->status, ['submitted', 'under_review']))
                                    {{-- Approve (AJAX + modal) --}}
                                    <button type="button" class="btn btn-success btn-block mb-2" data-toggle="modal"
                                        data-target="#approveLoanModal"
                                        data-approve-url="{{ route('admin.loans.approve', $loan) }}"
                                        data-amount-requested="{{ $loan->amount_requested }}"
                                        data-amount-approved="{{ $loan->amount_approved ?? 0 }}"
                                        data-tenure-months="{{ $loan->tenure_months }}"
                                        data-interest-rate="{{ $loan->interest_rate }}">
                                        <i class="mdi mdi-check mr-1"></i> Approve Loan
                                    </button>

                                    {{-- Reject stays as-is for now --}}
                                    <a href="#rejectModal" class="btn btn-outline-danger btn-block mb-3"
                                        data-toggle="modal">
                                        <i class="mdi mdi-close mr-1"></i> Reject Loan
                                    </a>
                                @endif

                                @if ($loan->status == 'approved')
                                    <a href="{{ route('admin.loans.disburse', $loan) }}"
                                        class="btn btn-info btn-block mb-3">
                                        <i class="mdi mdi-cash mr-1"></i> Disburse Funds
                                    </a>
                                @endif

                                <a href="" class="btn btn-outline-primary btn-block mb-3">
                                    <i class="mdi mdi-pencil mr-1"></i> Edit Details
                                </a>

                                <button class="btn btn-outline-danger btn-block"
                                    onclick="confirmDelete({{ $loan->id }})">
                                    <i class="mdi mdi-delete mr-1"></i> Delete Loan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Loan Purpose & Repayments --}}
            {{-- <div class="row mt-4">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="mdi mdi-lightbulb mr-2"></i>Loan Purpose</h5>
                        </div>
                        <div class="card-body">
                            <p class="lead">{{ $loan->purpose }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="mdi mdi-receipt mr-2"></i>Repayments
                                ({{ $loan->repayments->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @if ($loan->repayments->count())
                                <ul class="list-unstyled">
                                    @foreach ($loan->repayments->take(3) as $repayment)
                                        <li class="d-flex justify-content-between small mb-2 pb-1 border-bottom">
                                            <span>{{ $repayment->due_date->format('M d') }}</span>
                                            <span
                                                class="fw-bold text-success">{{ $currencySymbol }}{{ number_format($repayment->amount, 0) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                @if ($loan->repayments->count() > 3)
                                    <small class="text-muted">...and {{ $loan->repayments->count() - 3 }} more</small>
                                @endif
                            @else
                                <p class="text-muted text-center py-3">No repayments yet</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Loan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('admin.loans.reject', $loan) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Rejection Reason</label>
                            <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Loan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm('Delete this loan permanently?')) {
                window.location = ``.replace(':id', id);
            }
        }
    </script>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('approveLoanForm');

            const submitBtn = document.getElementById('approveLoanSubmitBtn');
            const spinner = document.getElementById('approveLoanSpinner');

            const modalAlert = document.getElementById('approveLoanAlert');
            const pageAlert = document.getElementById('loanActionAlert');
            const actionButtonsWrap = document.getElementById('loanActionButtons');

            let approveUrl = null;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            function setLoading(isLoading) {
                submitBtn.disabled = isLoading;
                spinner.classList.toggle('d-none', !isLoading);
            }

            function clearErrors() {
                ['amount_approved', 'interest_rate', 'tenure_months'].forEach((k) => {
                    const el = document.getElementById('err_' + k);
                    if (el) el.textContent = '';
                });
            }

            function showModalAlert(message, type = 'danger') {
                modalAlert.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning',
                    'alert-info');
                modalAlert.classList.add(type === 'success' ? 'alert-success' : 'alert-danger');
                modalAlert.textContent = message;
            }

            function hideModalAlert() {
                modalAlert.classList.add('d-none');
                modalAlert.textContent = '';
            }

            function showPageAlert(message, type = 'success') {
                pageAlert.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning',
                    'alert-info');
                pageAlert.classList.add(type === 'success' ? 'alert-success' : 'alert-danger');
                pageAlert.textContent = message;
            }

            // Helper: attempt JSON parse safely
            async function safeJson(res) {
                const ct = res.headers.get('content-type') || '';
                if (ct.includes('application/json')) return await res.json();
                return {};
            }

            // Prefill inputs on modal open
            $('#approveLoanModal').on('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                approveUrl = button?.getAttribute('data-approve-url') || null;

                form.reset();
                clearErrors();
                hideModalAlert();
                setLoading(false);

                const amountRequested = Number(button.getAttribute('data-amount-requested') || 0);
                const amountApproved = Number(button.getAttribute('data-amount-approved') || 0);
                const tenureMonths = button.getAttribute('data-tenure-months') || '';
                const interestRate = button.getAttribute('data-interest-rate') || '';

                // Prefill approval fields
                const amountApprovedInput = document.getElementById('amount_approved');
                const rateInput = document.getElementById('interest_rate');
                const tenureInput = document.getElementById('tenure_months');

                if (amountApprovedInput) amountApprovedInput.value = amountApproved > 0 ? amountApproved :
                    amountRequested;
                if (rateInput) rateInput.value = interestRate;
                if (tenureInput) tenureInput.value = tenureMonths;
            });

            // Submit approval via AJAX
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (!approveUrl) {
                    showModalAlert('Approval URL not found. Please refresh the page.');
                    return;
                }
                if (!csrfToken) {
                    showModalAlert(
                        'CSRF token missing. Add <meta name="csrf-token" content="{{ csrf_token() }}"> in layout.'
                    );
                    return;
                }

                clearErrors();
                hideModalAlert();
                setLoading(true);

                const formData = new FormData(form);

                try {
                    const res = await fetch(approveUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    const data = await safeJson(res);

                    // Validation errors
                    if (res.status === 422) {
                        const errors = data.errors || {};
                        Object.keys(errors).forEach((field) => {
                            const el = document.getElementById('err_' + field);
                            if (el) el.textContent = errors[field][0] || 'Invalid value';
                        });
                        showModalAlert('Please correct the highlighted fields and try again.');
                        setLoading(false);
                        return;
                    }

                    // CSRF expired/session issues
                    if (res.status === 419) {
                        showModalAlert('Your session expired. Please refresh the page and try again.');
                        setLoading(false);
                        return;
                    }

                    // Other errors
                    if (!res.ok) {
                        showModalAlert(data.message || 'Something went wrong. Try again.');
                        setLoading(false);
                        return;
                    }

                    // Success
                    $('#approveLoanModal').modal('hide');
                    showPageAlert(data.message || 'Loan approved successfully.', 'success');

                    // Remove approve + reject buttons (status has changed)
                    if (actionButtonsWrap) {
                        actionButtonsWrap
                            .querySelectorAll(
                                'button[data-target="#approveLoanModal"], a[href="#rejectModal"]')
                            .forEach(el => el.remove());
                    }

                    // Optional UI hook: update a status badge if you add it on the page
                    // Example: <span id="loanStatusBadge" class="badge ...">Submitted</span>
                    const statusBadge = document.getElementById('loanStatusBadge');
                    if (statusBadge) {
                        statusBadge.textContent = 'Approved';
                        statusBadge.className = 'badge badge-success px-3 py-2';
                    }

                    // ✅ NO reload here (prevents Route [admin.loans.disburse] not defined)

                    setLoading(false);

                } catch (err) {
                    showModalAlert('Network error. Please check your connection and retry.');
                    setLoading(false);
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .loan-timeline__item {
            display: grid;
            grid-template-columns: 90px 28px 1fr;
            gap: 12px;
            padding: 12px 0;
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
            height: calc(100% + 12px);
            background: #e9ecef;
            z-index: 1;
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

        .loan-timeline__meta {
            font-size: 12px;
            margin-top: 6px;
        }

        /* dot states */
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

        /* current row emphasis */
        .loan-timeline__item.is-current .loan-timeline__title {
            color: #007bff;
        }
    </style>
@endpush
