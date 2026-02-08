<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\KycApproved;
use App\Mail\KycRejected;   // ✅ For Log::warning()
use App\Models\Kyc;
use Illuminate\Http\Request;

class AdminKycController extends Controller
{
    public function index()
    {
        $kycs = Kyc::with('user')->latest()->paginate(20);
        return view('admin.kyc.index', compact('kycs'));
    }

    public function show(Kyc $kyc)
    {
        $kyc->load('user', 'documents');
        return view('admin.kyc.show', compact('kyc'));
    }

    // public function approve(Request $request, Kyc $kyc)
    // {
    //     $kyc->update([
    //         'status' => 'approved',
    //         'rejection_reason' => null,
    //         'reviewed_by' => $request->user()->id,
    //         'reviewed_at' => now(),
    //     ]);
    //     $kycs = Kyc::with('user')->latest()->paginate(20);

    //     return back()->with('status', 'KYC approved.');
    // }

    // public function reject(Request $request, Kyc $kyc)
    // {
    //     $data = $request->validate([
    //         'rejection_reason' => ['required', 'string', 'max:2000']
    //     ]);

    //     $kyc->update([
    //         'status' => 'rejected',
    //         'rejection_reason' => $data['rejection_reason'],
    //         'reviewed_by' => $request->user()->id,
    //         'reviewed_at' => now(),
    //     ]);

    //     return back()->with('status', 'KYC rejected.');
    // }

    public function approve(Request $request, Kyc $kyc)
    {
        $kyc->update([
            'status' => 'approved',
            'rejection_reason' => null,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        // ✅ Send approval email
        try {
            Mail::to($kyc->user->email)->send(new KycApproved($kyc, 'HV Capitals'));
        } catch (\Exception $e) {
            Log::warning('KYC approval email failed: ' . $e->getMessage());
        }

        return back()->with('status', 'KYC approved.');
    }

    public function reject(Request $request, Kyc $kyc)
    {
        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:2000']
        ]);

        $kyc->update([
            'status' => 'rejected',
            'rejection_reason' => $data['rejection_reason'],
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        // ✅ Send rejection email
        try {
            Mail::to($kyc->user->email)->send(new KycRejected($kyc, 'HV Capitals'));
        } catch (\Exception $e) {
            \Log::warning('KYC rejection email failed: ' . $e->getMessage());
        }

        return back()->with('status', 'KYC rejected.');
    }
}
