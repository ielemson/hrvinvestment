<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanWorkflowLevel extends Model
{
    protected $fillable = [
        'loan_id',
        'level_key',
        'status',
        'notes',
        'content',
        'edited_by',
        'edited_at'
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}
