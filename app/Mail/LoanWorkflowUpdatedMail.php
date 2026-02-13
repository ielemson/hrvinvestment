<?php

namespace App\Mail;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanWorkflowUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Loan $loan,
        public string $levelKey,
        public string $status,
        public ?string $notes = null
    ) {}

    public function build()
    {
        $reference = $this->loan->reference ?? $this->loan->id;
        $levelLabel = \App\Models\Loan::WORKFLOW_LEVELS[$this->levelKey] ?? $this->levelKey;
        $statusText = ucwords(str_replace('_', ' ', $this->status));

        return $this->subject("Loan Update: {$reference}")
            ->view('emails.loan.workflow-updated')
            ->with([
                'loan'        => $this->loan,
                'reference'   => $reference,
                'levelLabel'  => $levelLabel,
                'statusText'  => $statusText,
                'notes'       => $this->notes,
            ]);
    }
}
