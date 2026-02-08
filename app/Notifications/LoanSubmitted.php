<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Loan $loan) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Loan Application Submitted')
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('Your loan application has been submitted successfully.')
            ->line('Amount Requested: ₦' . number_format((float)$this->loan->amount_requested, 2))
            ->line('Tenure: ' . $this->loan->tenure_months . ' months')
            ->line('Status: ' . strtoupper(str_replace('_', ' ', $this->loan->status)))
            ->action('View Loan Status', url('/dashboard'))
            ->line('Thank you.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'loan_submitted',
            'loan_id' => $this->loan->id,
            'status' => $this->loan->status,
            'message' => 'Your loan application was submitted.',
        ];
    }
}
