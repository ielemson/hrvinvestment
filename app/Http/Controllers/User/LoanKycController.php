<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Models\KycDocument;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LoanKycController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        $projectTypes = [
            'farming_cultivation',
            'renewable_energy',
            'infrastructure_links',
            'rail_networks',
            'waste_processing',
            'personal_private',
            'varied_multifaceted',
            'leisure_entertainment',
            'electricity_power',
            'communications_networks',
            'hospitality',
            'petroleum_gas',
            'aerospace',
            'construction_edifice',
        ];

        $validated = $request->validate([
            // ---------------- KYC ----------------
            'full_name'           => ['required', 'string', 'max:255'],
            'phone'               => ['required', 'string', 'max:30'],
            'phone_country_code'  => ['nullable', 'string', 'max:10'],
            'phone_national'      => ['nullable', 'string', 'max:30'],
            'phone_e164'          => ['nullable', 'string', 'max:40'],
            'phone_country_iso'   => ['nullable', 'string', 'max:5'],
            'gender'              => ['required', 'string', 'max:50'],
            'address'             => ['required', 'string', 'max:255'],
            'city'                => ['required', 'string', 'max:100'],
            'state'               => ['required', 'string', 'max:100'],

            'documents'           => ['required', 'array', 'min:1'],
            'documents.*.label'   => ['required', 'string', 'max:255'],
            'documents.*.type'    => ['required', 'in:id_card,proof_of_income,proof_of_address,selfie,other'],
            'documents.*.file'    => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],

            // ---------------- LOAN (personal) ----------------
            'amount_requested'    => ['required', 'numeric', 'min:1000'],
            'tenure_months'       => ['required', 'integer', 'in:3,6,9,12,18,24'],
            'repayment_method'    => ['required', 'in:bank_transfer,direct_debit,wallet'],
            'employment_type'     => ['required', 'in:salary,business,freelance,agriculture,other'],
            'income_band'         => ['required', 'in:below_100k,100k_300k,300k_700k,above_700k'],
            'interest_rate'       => ['required', 'numeric', 'min:0', 'max:100'],
            'purpose'             => ['required', 'string', 'max:2000'],
            'loan_consent'        => ['accepted'],

            // ---------------- PROJECT / COMPANY ----------------
            'project_name'              => ['required', 'string', 'max:255'],
            'company_name'              => ['required', 'string', 'max:255'],
            'company_address'           => ['required', 'string', 'max:255'],

            'ceo_name'                  => ['required', 'string', 'max:255'],
            'ceo_email'                 => ['required', 'email', 'max:255'],

            'project_location'          => ['required', 'string', 'max:255'],
            'project_urgency'           => ['required', 'in:urgent,not_urgent'],

            'project_type'              => ['required', Rule::in($projectTypes)],
            'project_summary'           => ['required', 'string', 'max:3000'],

            'loan_type'                 => ['required', 'in:debt_financing,equity,joint_venture,investment'],
            'expected_duration_years'   => ['required', 'integer', 'in:2,3,4,5,6,7,8,9,10'],
            'previous_investor_funding' => ['required', 'in:yes,no'],

            'bank_account_statement'    => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        try {
            DB::transaction(function () use ($user, $request, $validated) {

                // 1) Upsert KYC
                $kyc = Kyc::firstOrCreate(['user_id' => $user->id]);

                $kyc->fill([
                    'full_name'          => $validated['full_name'],
                    'phone'              => $validated['phone'],
                    'phone_country_code' => $validated['phone_country_code'] ?? null,
                    'phone_national'     => $validated['phone_national'] ?? null,
                    'phone_e164'         => $validated['phone_e164'] ?? null,
                    'phone_country_iso'  => $validated['phone_country_iso'] ?? null,
                    'gender'             => $validated['gender'],
                    'address'            => $validated['address'],
                    'city'               => $validated['city'],
                    'state'              => $validated['state'],

                    'status'             => 'submitted',
                    'rejection_reason'   => null,
                    'reviewed_by'        => null,
                    'reviewed_at'        => null,
                ])->save();

                // 2) Store dynamic KYC docs (now the method exists)
                foreach ($validated['documents'] as $index => $docRow) {
                    $this->storeDynamicDoc($request, $kyc, $docRow, $index);
                }

                // 3) Bank statement upload for loan
                $bankStatementPath = $request->file('bank_account_statement')
                    ->store('bank_statements', 'public');

                // 4) Create Loan
                $loan = Loan::create([
                    'user_id'                     => $user->id,

                    // personal loan
                    'amount_requested'            => $validated['amount_requested'],
                    'tenure_months'               => $validated['tenure_months'],
                    'repayment_method'            => $validated['repayment_method'],
                    'employment_type'             => $validated['employment_type'],
                    'income_band'                 => $validated['income_band'],
                    'interest_rate'               => $validated['interest_rate'],
                    'purpose'                     => $validated['purpose'],

                    // workflow
                    'status'                      => 'under_review',
                    'current_level'               => 'risk_questionnaire',
                    'current_level_status'        => 'under_review',

                    // project/company
                    'project_name'                => $validated['project_name'],
                    'company_name'                => $validated['company_name'],
                    'company_address'             => $validated['company_address'],

                    'ceo_name'                    => $validated['ceo_name'],
                    'ceo_email'                   => $validated['ceo_email'],

                    'project_location'            => $validated['project_location'],
                    'project_urgency'             => $validated['project_urgency'],
                    'project_type'                => $validated['project_type'],
                    'project_summary'             => $validated['project_summary'],

                    'loan_type'                   => $validated['loan_type'],
                    'expected_duration_years'     => $validated['expected_duration_years'],
                    'previous_investor_funding'   => $validated['previous_investor_funding'],

                    'bank_account_statement_path' => $bankStatementPath,
                ]);

                // 5) ✅ Workflow levels (idempotent: prevents duplicate key errors)
                foreach (array_keys(\App\Models\Loan::WORKFLOW_LEVELS) as $key) {
                    $loan->workflowLevels()->updateOrCreate(
                        [
                            'loan_id'   => $loan->id,
                            'level_key' => $key,
                        ],
                        [
                            'status'    => 'under_review',
                            'edited_at' => now(),
                        ]
                    );
                }
            });

            return redirect()
                ->route('user.loans.index')
                ->with('success', 'KYC + Loan application submitted successfully and is under review.');
        } catch (\Throwable $e) {
            Log::error('KYC+Loan submission failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Submission failed. Please try again.');
        }
    }

    /**
     * Store a single dynamic KYC document row.
     */
    private function storeDynamicDoc(Request $request, Kyc $kyc, array $docRow, int $index): void
    {
        // When validating, we require documents.*.file, so this is just extra safety
        if (!isset($docRow['file']) || !$request->hasFile("documents.$index.file")) {
            return;
        }

        $file = $request->file("documents.$index.file");

        $type  = $docRow['type'] ?? 'other';
        $label = $docRow['label'] ?? 'Document';

        // Normalize / lock allowed types
        $allowedTypes = ['id_card', 'proof_of_income', 'proof_of_address', 'selfie', 'other'];
        if (!in_array($type, $allowedTypes, true)) {
            $type = 'other';
        }

        /**
         * Replace-only types: keep just the latest for core KYC docs.
         * Remove this block if you want unlimited even for id_card, etc.
         */
        $replaceTypes = ['id_card', 'proof_of_income', 'proof_of_address', 'selfie'];
        if (in_array($type, $replaceTypes, true)) {
            $existing = KycDocument::where('kyc_id', $kyc->id)
                ->where('type', $type)
                ->latest()
                ->first();

            if ($existing) {
                // Delete old file safely (optional)
                try {
                    Storage::disk('public')->delete($existing->file_path);
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
