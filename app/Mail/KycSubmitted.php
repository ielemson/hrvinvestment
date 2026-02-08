<?php

namespace App\Mail;

use App\Models\Kyc;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KycSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $kyc;
    public $user;
    public $appName;

    public function __construct(Kyc $kyc, $appName = 'HV Capitals')
    {
        $this->kyc = $kyc;
        $this->user = $kyc->user;
        $this->appName = $appName;
    }

    public function build()
    {
        return $this->subject('KYC Submission Received - Awaiting Review')
            ->view('emails.kyc-submitted');
    }
}
