<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Models\LoanRepayment;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{


    public function index(Request $request)
    {
        $user = auth()->user();

        // Latest KYC
        $kyc = $user->kyc()->latest()->first();
        $kycStatus = $kyc?->status ?? 'pending';

        // ACTIVE loan: under_review, approved, disbursed ONLY
        $activeLoan = $user->loans()
            ->whereIn('status', ['under_review', 'approved', 'disbursed'])
            ->latest('updated_at')
            ->first();

        // Latest loan overall (backup)
        $latestLoan = $user->loans()->latest('created_at')->first();

        // Unified vars
        $loan = $activeLoan ?? $latestLoan;
        $currentStatus = $loan?->status ?? 'none';
        $hasActiveLoan = (bool) $activeLoan;

        // 3-step progress (matches exactly)
        $loanProgressSteps = [
            'under_review' => ['label' => 'Under Review', 'icon' => 'mdi mdi-magnify-scan', 'color' => 'warning'],
            'approved'     => ['label' => 'Approved',     'icon' => 'mdi mdi-shield-check', 'color' => 'success'],
            'disbursed'    => ['label' => 'Disbursed',    'icon' => 'mdi mdi-cash-check',   'color' => 'primary'],
        ];

        // Loan amount
        $activeLoanAmount = $activeLoan
            ? ($activeLoan->amount_approved ?? $activeLoan->amount_requested ?? 0)
            : 0;

        // Status label
        $loanStatusLabel = match ($currentStatus) {
            'under_review' => 'Under Review',
            'approved'     => 'Approved',
            'disbursed'    => 'Disbursed',
            default        => 'No Active Loan',
        };

        // Next repayment (disbursed only)
        $nextRepaymentAmount = $nextRepaymentDate = null;
        if ($activeLoan?->status === 'disbursed' && $activeLoan->first_due_date) {
            $nextRepaymentAmount = $activeLoan->total_payable && $activeLoan->tenure_months
                ? round($activeLoan->total_payable / $activeLoan->tenure_months)
                : null;
            $nextRepaymentDate = $activeLoan->first_due_date;
        }

        // Recent repayments
        $recentRepayments = LoanRepayment::whereHas('loan', fn($q) => $q->where('user_id', $user->id))
            ->latest('due_date')
            ->limit(3)
            ->get()
            ->map(fn($r) => [
                'date'   => $r->due_date ? \Carbon\Carbon::parse($r->due_date)->format('d M') : '—',
                'amount' => $r->amount_due,
                'status' => $r->status ?? 'pending',
            ]);

        // All loans requested so far (history)
        $loanHistory = $user->loans()
            ->latest('created_at')
            ->get()
            ->map(function ($l) {
                return [
                    'date' => $l->created_at ? $l->created_at->format('d M, Y') : '—',
                    'reference' => $l->reference ?? $l->id, // change if you have code/loan_no
                    'amount_requested' => (float) ($l->amount_requested ?? 0),
                    'amount_approved'  => (float) ($l->amount_approved ?? 0),
                    'status' => $l->status ?? 'pending',
                ];
            });

        /**
         * ✅ NEW: Loan Workflow Levels (7 items, only current is active)
         * - Safe: only loads if $loan exists
         * - Produces $workflowUI array for the view
         */
        $workflowUI = [];

        if ($loan) {
            $loan->load('workflowLevels');

            $levels = \App\Models\Loan::WORKFLOW_LEVELS; // key => label

            foreach ($levels as $key => $label) {
                $levelRow = $loan->workflowLevels->firstWhere('level_key', $key);

                $status = $levelRow?->status ?? 'under_review';
                $isActive = ($loan->current_level ?? null) === $key;

                // $workflowUI[] = [
                //     'key'       => $key,
                //     'label'     => $label,
                //     'status'    => $status,
                //     'is_active' => $isActive,
                //     'can_open'  => $isActive, // user can only open current level
                // ];

                $workflowUI = [];

                if ($loan) {
                    $loan->load('workflowLevels');

                    $levels = \App\Models\Loan::WORKFLOW_LEVELS; // key => label (7 items)

                    // % mapping by index (must match the order of WORKFLOW_LEVELS)
                    $percentSteps = [10, 20, 30, 40, 50, 80, 100];

                    $i = 0;
                    foreach ($levels as $key => $label) {
                        $levelRow = $loan->workflowLevels->firstWhere('level_key', $key);

                        $status = $levelRow?->status ?? 'under_review';
                        $isActive = ($loan->current_level ?? null) === $key;

                        $workflowUI[] = [
                            'key'       => $key,
                            'label'     => $label,
                            'percent'   => $percentSteps[$i] ?? null, // ✅ added
                            'status'    => $status,
                            'is_active' => $isActive,
                            'can_open'  => $isActive,
                        ];

                        $i++;
                    }
                }
            }
        }

        return view('user.dashboard', compact(
            'user',
            'kyc',
            'kycStatus',
            'activeLoan',
            'latestLoan',
            'loan',
            'currentStatus',
            'hasActiveLoan',
            'loanProgressSteps',
            'activeLoanAmount',
            'loanStatusLabel',
            'nextRepaymentAmount',
            'nextRepaymentDate',
            'recentRepayments',
            'loanHistory',
            'workflowUI'
        ) + [
            'kycApproved'    => $kycStatus === 'approved',
            'currencySymbol' => config('app.currency_symbol', '$'),
        ]);
    }





    public function show(Request $request)
    {
        $user = $request->user();
        $kyc  = $user->kyc; // assumes hasOne relationship: User -> kyc

        return view('user.profile.show', compact('user', 'kyc'));
    }
}
