<?php

namespace App\Mail;

use App\Models\Kyc;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KycApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $kyc;
    public $appName;

    public function __construct(Kyc $kyc, $appName = 'HV Capitals')
    {
        $this->kyc = $kyc;
        $this->appName = $appName;
    }

    public function build()
    {
        return $this->subject('KYC Approved! 🎉')
            ->view('emails.kyc-approved');
    }
}
