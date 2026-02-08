<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Notifications\LoanSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kyc;

class LoanController extends Controller
{
    public function index()
    {

        $kyc = auth()->user()->kyc;

        $kycStatus = $kyc?->status ?? 'pending';

        $loans = Auth::user()
            ->loans()
            ->latest()
            ->paginate(10);

        return view('user.loans.index', compact('loans'));
    }

    public function create()
    {


        $user = auth()->user();

        // Get KYC (assuming hasOne relationship)
        $kyc = $user->kyc;

        // If no KYC record exists, redirect back to dashboard
        // if (!$kyc) {
        //     return redirect()
        //         ->route('user.dashboard')
        //         ->with('error', 'Please complete your KYC before applying for a loan.');
        // }

        $kycStatus = $kyc->status ?? 'pending';

        $loans = $user->loans()
            ->latest()
            ->paginate(10);

        return view('user.loans.create_loan_kyc', [
            'loans'       => $loans,
            'kyc'         => $kyc,
            'kycStatus'   => $kycStatus,
            'kycApproved' => $kycStatus === 'approved',
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Enforce KYC before allowing loan application
        if (!$user->kyc || $user->kyc->status !== 'approved') {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Your KYC is not approved. Please complete KYC verification before applying for a loan.');
        }

        // ✅ Validate request
        $validated = $request->validate([
            'amount_requested' => ['required', 'numeric', 'min:1000'],
            'tenure_months'    => ['required', 'integer', 'min:1', 'max:60'],
            'amount_requested'   => ['required', 'numeric', 'min:1000'],
            'tenure_months'      => ['required', 'integer', 'in:3,6,9,12,18,24'],
            'repayment_method'   => ['required', 'in:bank_transfer,direct_debit,wallet'],
            'employment_type'    => ['required', 'in:salary,business,freelance,agriculture,other'],
            'income_band'        => ['required', 'in:below_100k,100k_300k,300k_700k,above_700k'],
            'interest_rate'      => ['nullable', 'numeric', 'min:0', 'max:100'],
            'purpose'            => ['nullable', 'string', 'max:2000'],
        ]);

        // ✅ Create loan
        $loan = Loan::create([
            'user_id'          => $user->id,
            'amount_requested' => $validated['amount_requested'],
            'tenure_months'    => $validated['tenure_months'],
            'interest_rate'    => $validated['interest_rate'] ?? 0,
            'purpose'          => $validated['purpose'] ?? null,
            'amount_requested'  => $validated['amount_requested'],
            'tenure_months'     => $validated['tenure_months'],
            'repayment_method'  => $validated['repayment_method'],
            'employment_type'   => $validated['employment_type'],
            'income_band'       => $validated['income_band'],
            'status'           => 'under_review',
        ]);

        foreach (array_keys(\App\Models\Loan::WORKFLOW_LEVELS) as $key) {
            $loan->workflowLevels()->create([
                'level_key' => $key,
                'status' => 'under_review',
                'edited_at' => now(),
            ]);
        }

        // set current
        $loan->update([
            'current_level' => 'risk_questionnaire',
            'current_level_status' => 'under_review',
        ]);



        // 🔔 Notify user (email + in-app)
        $user->notify(new LoanSubmitted($loan));

        return redirect()
            ->route('user.loans.index')
            ->with('success', 'Loan application submitted successfully and is under review.');
    }

    public function show(Loan $loan)
    {
        abort_unless($loan->user_id === Auth::id(), 403);

        $loan->load('repayments');

        return view('user.loans.show', compact('loan'));
    }
}
