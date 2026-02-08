<?php

// app/Http/Controllers/User/KycController.php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Models\KycDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;  // For Mail::to()
use Illuminate\Support\Facades\Log;   // For Log::warning()
use App\Mail\KycSubmitted;
use Illuminate\Validation\Rule;           // For the mailable
use Illuminate\Support\Facades\Storage;


class KycController extends Controller
{
    public function edit(Request $request)
    {
        $kyc = Kyc::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['status' => 'pending']
        );

        $kyc->load('documents');

        return view('user.kyc.edit', compact('kyc'));
    }



    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone'               => ['required', 'string', 'max:30'],
            'phone_country_code'  => ['nullable', 'string', 'max:10'],
            'phone_national'      => ['nullable', 'string', 'max:30'],
            'phone_e164'          => ['nullable', 'string', 'max:40'],
            'phone_country_iso'   => ['nullable', 'string', 'max:5'],
            // Your form now includes more gender values, so don't restrict to Male/Female only
            'gender'    => ['nullable', 'string', 'max:50'],
            'address'   => ['required', 'string', 'max:255'],
            'city'      => ['nullable', 'string', 'max:100'],
            'state'     => ['nullable', 'string', 'max:100'],

            /**
             * ✅ Dynamic documents
             * documents[0][label], documents[0][type], documents[0][file] ...
             */
            'documents'          => ['required', 'array', 'min:1'],
            'documents.*.label'  => ['required', 'string', 'max:255'],
            'documents.*.type'   => ['nullable', 'string', 'max:50'],
            'documents.*.file'   => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],

            // Optional: keep old label selectors if you still have them in the form
            'id_label'      => ['nullable', 'string', 'max:100'],
            'income_label'  => ['nullable', 'string', 'max:100'],
            'address_label' => ['nullable', 'string', 'max:100'],

        ]);

        $kyc = Kyc::firstOrCreate(['user_id' => $user->id]);

        // If rejected, allow resubmission -> submitted
        $kyc->fill([
            'full_name'         => $validated['full_name'],
            'phone'               => $validated['phone'],          // raw display
            'phone_country_code'  => $validated['phone_country_code'],
            'phone_national'      => $validated['phone_national'],
            'phone_e164'          => $validated['phone_e164'],     // ⭐ PRIMARY
            'phone_country_iso'   => $validated['phone_country_iso'],
            'gender'            => $validated['gender'] ?? null,
            'address'           => $validated['address'],
            'city'              => $validated['city'] ?? null,
            'state'             => $validated['state'] ?? null,

            'status'            => 'submitted',
            'rejection_reason'  => null,
            'reviewed_by'       => null,
            'reviewed_at'       => null,

        ])->save();

        /**
         * ✅ Store dynamic docs
         * Policy:
         * - If type is one of (id_card, proof_of_income, proof_of_address, selfie) => keep ONLY the latest one (replace)
         * - Otherwise (other / empty) => allow multiple
         */
        foreach ($validated['documents'] as $index => $doc) {
            $this->storeDynamicDoc($request, $kyc, $doc, $index);
        }

        try {
            Mail::to($user->email)->send(new \App\Mail\KycSubmitted($kyc));
        } catch (\Exception $e) {
            Log::warning('KYC submission email failed: ' . $e->getMessage());
            // Don't fail the request - just log it
        }

        return back()->with('status', 'KYC submitted successfully. Awaiting review.');
    }

    /**
     * Store one document row from documents[*]
     */
    private function storeDynamicDoc(Request $request, Kyc $kyc, array $docRow, int $index): void
    {
        // When validating, we require documents.*.file, so this is just extra safety
        if (
            !isset($docRow['file']) ||
            !$request->hasFile("documents.$index.file")
        ) {
            return;
        }

        $file = $request->file("documents.$index.file");

        $type  = $docRow['type'] ?? 'other';
        $label = $docRow['label'] ?? 'Document';

        // Normalize / lock allowed types (optional but recommended)
        $allowedTypes = ['id_card', 'proof_of_income', 'proof_of_address', 'selfie', 'other'];
        if (!in_array($type, $allowedTypes, true)) {
            $type = 'other';
        }

        /**
         * Replace-only types: keep just the latest for core KYC docs.
         * (You can remove this block if you want unlimited even for id_card, etc.)
         */
        $replaceTypes = ['id_card', 'proof_of_income', 'proof_of_address', 'selfie'];
        if (in_array($type, $replaceTypes, true)) {
            $existing = KycDocument::where('kyc_id', $kyc->id)->where('type', $type)->latest()->first();
            if ($existing) {
                // Delete old file safely (optional)
                try {
                    \Storage::disk('public')->delete($existing->file_path);
                } catch (\Exception $e) {
                    Log::warning("Failed deleting old KYC doc file: {$existing->file_path}. " . $e->getMessage());
                }
                $existing->delete();
            }
        }

        $safeType = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $type);
        $filename = $safeType . '_' . time() . '_' . ($index + 1) . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs("kyc/{$kyc->user_id}", $filename, 'public');

        KycDocument::create([
            'kyc_id'    => $kyc->id,
            'type'      => $type,
            'label'     => $label,
            'file_path' => $path,
            'mime'      => $file->getMimeType(),
            'size'      => $file->getSize(),
        ]);
    }
}
