<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    public const STATUSES = [
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

    protected $fillable = [
        'user_id',

        'amount_requested',
        'amount_approved',
        'tenure_months',
        'repayment_method',
        'employment_type',
        'income_band',
        'interest_rate',
        'purpose',

        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'disbursed_at',
        'first_due_date',
        'total_payable',
        'outstanding_balance',
        'current_level',
        'current_level_status',

        // Project
        'project_name',
        'project_type',
        'project_location',
        'project_summary',
        'project_urgency',

        // Company
        'company_name',
        'company_address',
        'ceo_name',
        'ceo_email',

        // Financing
        'loan_type',
        'expected_duration_years',
        'previous_investor_funding',

        // Documents
        'bank_account_statement_path',
    ];

    protected $casts = [
        'amount_requested' => 'decimal:2',
        'amount_approved' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'total_payable' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'disbursed_at' => 'date',
        'first_due_date' => 'date',
    ];

    public const WORKFLOW_LEVELS = [
        'risk_questionnaire'      => 'Risk Appraisal and Assessment Questionnaire',
        'risk_report'             => 'Risk Appraisal and Assessment Report',
        'funding_terms'           => 'Proposed Debt Financing/Funding Terms Agreement',
        'loan_disbursement_agree' => 'Official Loan and Disbursement Agreement',
        'rsd_agreement'           => 'Refundable Security Deposit Agreement',
        'rsd_invoice'             => 'RSD Invoice',
        'disbursement_approval'   => 'Loan Disbursement Approval',

    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function repayments(): HasMany
    {
        return $this->hasMany(LoanRepayment::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['disbursed', 'active']);
    }
    public function workflowLevels(): HasMany
    {
        return $this->hasMany(LoanWorkflowLevel::class);
    }
}
