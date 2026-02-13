<?php

// app/Http/Controllers/ContactController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Support\SiteSettings;
use App\Mail\ContactMessageMail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|max:255',
            'phone'      => 'nullable|string|max:30',
            'message'    => 'required|string|max:5000',
        ]);

        $settings = SiteSettings::get(); // may be null if no row exists

        // ✅ DB email first, then MAIL_FROM_ADDRESS, then hard fallback
        $toEmail = $settings?->contact_email
            ?: config('mail.from.address')
            ?: 'ielemson@gmail.com';

        Mail::to($toEmail)->send(new ContactMessageMail($data));

        return back()->with('success', 'Your message has been sent successfully.');
    }
}
