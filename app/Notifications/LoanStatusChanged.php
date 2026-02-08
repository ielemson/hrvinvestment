<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Loan $loan,
        public string $oldStatus,
        public string $newStatus
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    private function label(string $status): string
    {
        return strtoupper(str_replace('_', ' ', $status));
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Loan Status Update: ' . $this->label($this->newStatus))
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('Your loan status has been updated.')
            ->line('From: ' . $this->label($this->oldStatus))
            ->line('To: ' . $this->label($this->newStatus))
            ->line('Loan ID: #' . $this->loan->id);

        if ($this->newStatus === 'rejected' && $this->loan->rejection_reason) {
            $mail->line('Reason: ' . $this->loan->rejection_reason);
        }

        if ($this->newStatus === 'approved' && $this->loan->amount_approved) {
            $mail->line('Approved Amount: ₦' . number_format((float)$this->loan->amount_approved, 2));
        }

        return $mail->action('View Dashboard', url('/dashboard'))
            ->line('Thank you.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'loan_status_changed',
            'loan_id' => $this->loan->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => 'Loan status changed to ' . $this->label($this->newStatus),
        ];
    }
}
