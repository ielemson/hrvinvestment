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

    // const WORKFLOW_LEVELS = [
    //     'procedure_agreement'     => 'Debt Financing Procedure Agreement', // (11%)
    //     'risk_questionnaire'      => 'Creditworthiness Evaluation Questionnaire', // (11%) renamed label only
    //     'risk_report'             => 'Risk Appraisal and Assessment Report', // (11%)
    //     'funding_terms'           => 'Proposed Debt Financing/Funding Terms Agreement', // (11%)
    //     'financial_cooperation'   => 'Financial Cooperation Agreement', // (11%)
    //     'loan_disbursement_agree' => 'Official Loan and Disbursement Agreement', // (11%)
    //     'rsd_agreement'           => 'Refundable Security Deposit Agreement', // (11%)
    //     'rsd_invoice'             => 'RSD Invoice', // (11%)
    //     'disbursement_approval'   => 'Loan Disbursement Approval', // (12%)
    // ];

    public const WORKFLOW_LEVELS = [
        'debt_procedure'          => 'Debt Financing Procedure Agreement',
        'credit_questionnaire'    => 'Creditworthiness Evaluation Questionnaire',
        'risk_report'             => 'Risk Appraisal and Assessment Report',
        'funding_terms'           => 'Proposed Debt Financing/Funding Terms Agreement',
        'financial_cooperation'   => 'Financial Cooperation Agreement',
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
