<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanRepayment extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'partial', 'paid', 'overdue'];

    protected $fillable = [
        'loan_id',
        'installment_no',
        'due_date',
        'principal_due',
        'interest_due',
        'amount_due',
        'amount_paid',
        'paid_at',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'principal_due' => 'decimal:2',
        'interest_due' => 'decimal:2',
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    // Remaining amount on this installment
    public function getOutstandingAttribute(): float
    {
        $due = (float) $this->amount_due;
        $paid = (float) $this->amount_paid;
        return max($due - $paid, 0);
    }
}
