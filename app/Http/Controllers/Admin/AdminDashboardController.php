<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{


    public function index()
    {
        $recentUsers = User::query()
            ->withCount('loans')
            ->with([
                // ✅ hasOne kyc (no latest needed)
                'kyc',

                // ✅ latest loan snapshot
                'loans' => fn($q) => $q->latest('created_at')->limit(1),
            ])
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($u) {
                $kyc = $u->kyc;              // hasOne => model or null
                $loan = $u->loans->first();  // limited to 1

                $u->setAttribute('kyc_status', $kyc?->status ?? 'not_submitted');

                // Prefer KYC phone (best formatted) then fallback to User.phone
                $u->setAttribute(
                    'phone_display',
                    $kyc?->phone_e164
                        ?? $kyc?->phone
                        ?? $u->phone
                        ?? null
                );

                // Latest loan snapshot
                $u->setAttribute('latest_loan_status', $loan?->status);
                $u->setAttribute('latest_loan_amount', $loan?->amount_approved ?? $loan?->amount_requested);
                $u->setAttribute('latest_loan_date', $loan?->created_at);

                // Optional: show the new loan fields too
                $u->setAttribute('latest_repayment_method', $loan?->repayment_method);
                $u->setAttribute('latest_employment_type', $loan?->employment_type);
                $u->setAttribute('latest_income_band', $loan?->income_band);

                return $u;
            });

        $stats = [
            'totalUsers'   => User::count(),
            'pendingKyc'   => Kyc::where('status', 'submitted')->count(),
            'pendingLoans' => Loan::whereIn('status', ['submitted', 'under_review'])->count(),
            'activeLoans'  => Loan::where('status', 'disbursed')->count(),
            'totalLoans'   => Loan::count(),
        ];

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }
}
