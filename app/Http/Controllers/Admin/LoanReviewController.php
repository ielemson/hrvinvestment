<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Notifications\LoanStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with(['user', 'reviewedBy'])
            ->whereNotIn('status', ['draft']) // Exclude user drafts
            ->latest('created_at');

        // Status filter (focus on actionable states)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by user name/email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Amount range
        if ($request->filled('min_amount') || $request->filled('max_amount')) {
            $query->whereBetween('amount_requested', [
                $request->min_amount ?? 0,
                $request->max_amount ?? 999999999
            ]);
        }

        $loans = $query->paginate(25);

        // Quick stats
        $stats = [
            'pending' => Loan::whereIn('status', ['submitted', 'under_review'])->count(),
            'approved' => Loan::where('status', 'approved')->count(),
            'rejected' => Loan::where('status', 'rejected')->count(),
            'active' => Loan::whereIn('status', ['disbursed', 'active'])->count(),
            'total' => Loan::whereNotIn('status', ['draft'])->count(),
        ];

        $statusCounts = collect([
            'submitted' => Loan::where('status', 'submitted')->count(),
            'under_review' => Loan::where('status', 'under_review')->count(),
            'approved' => Loan::where('status', 'approved')->count(),
            'rejected' => Loan::where('status', 'rejected')->count(),
            'disbursed' => Loan::where('status', 'disbursed')->count(),
            'active' => Loan::where('status', 'active')->count(),
        ]);
        return view('admin.loans.index', compact('loans', 'statusCounts'));
    }


    public function show(Loan $loan)
    {
        $loan->load(['user', 'repayments', 'workflowLevels']); // ✅ add workflowLevels

        return view('admin.loans.show', compact('loan'));
    }


    public function approve(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'amount_approved' => ['required', 'numeric', 'min:1'],
            'interest_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'tenure_months' => ['required', 'integer', 'min:1', 'max:60'],
        ]);

        $old = $loan->status;

        $loan->update([
            'amount_approved' => $validated['amount_approved'],
            'interest_rate' => $validated['interest_rate'],
            'tenure_months' => $validated['tenure_months'],
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        $loan->user->notify(new LoanStatusChanged($loan, $old, 'approved'));

        return back()->with('success', 'Loan approved successfully.');
    }

    public function reject(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:2000'],
        ]);

        $old = $loan->status;

        $loan->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $loan->user->notify(new LoanStatusChanged($loan, $old, 'rejected'));

        return back()->with('success', 'Loan rejected.');
    }

    public function setStatus(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:under_review,disbursed,active,completed,defaulted,cancelled'],
        ]);

        $old = $loan->status;
        $new = $validated['status'];

        $loan->update([
            'status' => $new,
        ]);

        // Optional: set disbursed_at date when disbursed
        if ($new === 'disbursed' && !$loan->disbursed_at) {
            $loan->update(['disbursed_at' => now()->toDateString()]);
        }

        $loan->user->notify(new LoanStatusChanged($loan, $old, $new));

        return back()->with('success', 'Loan status updated.');
    }


    public function disburse(Request $request, Loan $loan)
    {
        // 🔒 Safety checks
        if ($loan->status === 'disbursed') {
            return $this->ajaxOrRedirect(
                $request,
                ['message' => 'This loan has already been disbursed.'],
                'warning'
            );
        }

        if ($loan->status !== 'approved') {
            return $this->ajaxOrRedirect(
                $request,
                ['message' => 'Only approved loans can be disbursed.'],
                'danger'
            );
        }

        // ✅ Perform disbursement
        $loan->update([
            'status'        => 'disbursed',
            'disbursed_at'  => now(),
            'disbursed_by'  => Auth::id(),
        ]);

        // 🔔 Optional: notify borrower
        // $loan->user->notify(new LoanStatusChanged($loan, 'approved', 'disbursed'));

        // ✅ Respond (AJAX or normal)
        return $this->ajaxOrRedirect(
            $request,
            [
                'message' => 'Loan disbursed successfully.',
                'loan' => [
                    'id' => $loan->id,
                    'status' => $loan->status,
                    'disbursed_at' => $loan->disbursed_at,
                ],
            ],
            'success'
        );
    }

    public function updateWorkflow(Request $request, Loan $loan)
    {

        $data = $request->validate([
            'level_key' => 'required|in:' . implode(',', array_keys(\App\Models\Loan::WORKFLOW_LEVELS)),
            'status' => 'required|in:under_review,reviewed,approved',
            'set_as_current' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $level = $loan->workflowLevels()->where('level_key', $data['level_key'])->firstOrFail();

        $level->update([
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
            'edited_by' => auth()->id(),
            'edited_at' => now(),
        ]);

        if (!empty($data['set_as_current'])) {
            $loan->update([
                'current_level' => $data['level_key'],
                'current_level_status' => $data['status'],
            ]);
        }

        return back()->with('success', 'Workflow updated.');
    }
}
