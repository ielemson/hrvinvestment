@extends('layouts.admin')
@section('title', 'Loan Application')

@section('content')
    <div class="content-wrapper">
        @php $currencySymbol = $siteSettings->currency_symbol ?? '$'; @endphp

        <div class="row">
            <div class="col-12 grid-margin mx-auto">

                {{-- GLOBAL SUCCESS --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- GLOBAL ERROR --}}
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- VALIDATION SUMMARY --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Submission failed.</strong> Please fix the highlighted fields.
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="applyForm" class="forms-sample" method="POST" action="{{ route('user.loan.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- ===================== KYC CARD ===================== --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="card-title">KYC Information</h4>
                            <p class="card-description text-muted">Tell us about yourself and upload required documents.</p>

                            <div class="row">
                                {{-- full_name --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="full_name">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                            id="full_name" name="full_name"
                                            value="{{ old('full_name', $kyc->full_name ?? auth()->user()->name) }}"
                                            placeholder="Your full name" required>
                                        @error('full_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- gender --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Gender <span class="text-danger">*</span></label>
                                        <select class="form-control @error('gender') is-invalid @enderror" id="gender"
                                            name="gender" required>
                                            <option value="">-- Select --</option>
                                            @foreach (['Male', 'Female', 'Non-binary', 'Prefer not to say', 'Self-describe'] as $g)
                                                <option value="{{ $g }}"
                                                    {{ old('gender', $kyc->gender ?? '') === $g ? 'selected' : '' }}>
                                                    {{ $g }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- phone --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" id="phone" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $kyc->phone ?? '') }}" placeholder="Enter phone number"
                                            required>
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- hidden fields (do NOT mark required) --}}
                                    <input type="hidden" name="phone_country_code" id="phone_country_code"
                                        value="{{ old('phone_country_code') }}">
                                    <input type="hidden" name="phone_national" id="phone_national"
                                        value="{{ old('phone_national') }}">
                                    <input type="hidden" name="phone_e164" id="phone_e164"
                                        value="{{ old('phone_e164') }}">
                                    <input type="hidden" name="phone_country_iso" id="phone_country_iso"
                                        value="{{ old('phone_country_iso') }}">
                                </div>

                                {{-- address --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Residential Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                            id="address" name="address" value="{{ old('address', $kyc->address ?? '') }}"
                                            placeholder="Street" required>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- country --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Country <span class="text-danger">*</span></label>
                                        <select class="form-control @error('country') is-invalid @enderror" id="country"
                                            name="country" required>
                                            <option value="">Select Country</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->name }}">
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- state --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="state">State <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('state') is-invalid @enderror"
                                            id="state" name="state" value="{{ old('state', $kyc->state ?? '') }}"
                                            placeholder="State" required>
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- city --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city">City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                            id="city" name="city" value="{{ old('city', $kyc->city ?? '') }}"
                                            placeholder="City" required>
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-2">Upload Documents <span class="text-danger">*</span></h5>
                            <p class="text-muted mb-3">At least one document is required. (jpg/png/pdf)</p>

                            <div id="documents-wrapper">
                                @php $oldDocs = old('documents', []); @endphp

                                @if (!empty($oldDocs))
                                    @foreach ($oldDocs as $i => $oldDoc)
                                        <div class="doc-row border rounded p-3 mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong class="doc-title">Document #{{ $i + 1 }}</strong>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger remove-doc {{ $i == 0 ? 'd-none' : '' }}">
                                                    Remove
                                                </button>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label>Document Name (Label) <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            name="documents[{{ $i }}][label]"
                                                            value="{{ old("documents.$i.label", $oldDoc['label'] ?? '') }}"
                                                            class="form-control doc-label @error("documents.$i.label") is-invalid @enderror"
                                                            placeholder="e.g. International Passport, Bank Statement"
                                                            required>
                                                        @error("documents.$i.label")
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label>Document Category <span class="text-danger">*</span></label>
                                                        <select name="documents[{{ $i }}][type]"
                                                            class="form-control doc-type @error("documents.$i.type") is-invalid @enderror"
                                                            required>
                                                            <option value="">-- Select --</option>
                                                            <option value="passport"
                                                                {{ old("documents.$i.type", $oldDoc['type'] ?? '') === 'passport' ? 'selected' : '' }}>
                                                                International Passport
                                                            </option>
                                                            <option value="id_card"
                                                                {{ old("documents.$i.type", $oldDoc['type'] ?? '') === 'id_card' ? 'selected' : '' }}>
                                                                Government ID
                                                            </option>
                                                            <option value="proof_of_income"
                                                                {{ old("documents.$i.type", $oldDoc['type'] ?? '') === 'proof_of_income' ? 'selected' : '' }}>
                                                                Proof of Income
                                                            </option>
                                                            <option value="proof_of_address"
                                                                {{ old("documents.$i.type", $oldDoc['type'] ?? '') === 'proof_of_address' ? 'selected' : '' }}>
                                                                Proof of Address
                                                            </option>
                                                            <option value="other"
                                                                {{ old("documents.$i.type", $oldDoc['type'] ?? '') === 'other' ? 'selected' : '' }}>
                                                                Other
                                                            </option>
                                                        </select>
                                                        @error("documents.$i.type")
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label>Upload File <span class="text-danger">*</span></label>
                                                <input type="file" name="documents[{{ $i }}][file]"
                                                    class="form-control doc-file @error("documents.$i.file") is-invalid @enderror"
                                                    accept=".jpg,.jpeg,.png,.pdf" required>
                                                @error("documents.$i.file")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    {{-- Default first document row --}}
                                    <div class="doc-row border rounded p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong class="doc-title">Document #1</strong>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger remove-doc d-none">Remove</button>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-2">
                                                    <label>Document Name (Label) <span class="text-danger">*</span></label>
                                                    <input type="text" name="documents[0][label]"
                                                        class="form-control doc-label @error('documents.0.label') is-invalid @enderror"
                                                        placeholder="e.g. International Passport, Bank Statement" required>
                                                    @error('documents.0.label')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mb-2">
                                                    <label>Document Category <span class="text-danger">*</span></label>
                                                    <select name="documents[0][type]"
                                                        class="form-control doc-type @error('documents.0.type') is-invalid @enderror"
                                                        required>
                                                        <option value="">-- Select --</option>
                                                        <option value="passport">International Passport</option>
                                                        <option value="id_card">Government ID</option>
                                                        <option value="proof_of_income">Proof of Income</option>
                                                        <option value="proof_of_address">Proof of Address</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                    @error('documents.0.type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mb-0">
                                            <label>Upload File <span class="text-danger">*</span></label>
                                            <input type="file" name="documents[0][file]"
                                                class="form-control doc-file @error('documents.0.file') is-invalid @enderror"
                                                accept=".jpg,.jpeg,.png,.pdf" required>
                                            @error('documents.0.file')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <button type="button" id="add-document" class="btn btn-outline-primary btn-sm mb-3">
                                + Add another document
                            </button>

                            {{-- Template for dynamic documents --}}
                            <template id="doc-template">
                                <div class="doc-row border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong class="doc-title">Document</strong>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger remove-doc">Remove</button>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-2">
                                                <label>Document Name (Label) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control doc-label"
                                                    placeholder="e.g. International Passport, Bank Statement" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-2">
                                                <label>Document Category <span class="text-danger">*</span></label>
                                                <select class="form-control doc-type" required>
                                                    <option value="">-- Select --</option>
                                                    <option value="passport">International Passport</option>
                                                    <option value="id_card">Government ID</option>
                                                    <option value="proof_of_income">Proof of Income</option>
                                                    <option value="proof_of_address">Proof of Address</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0">
                                        <label>Upload File <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control doc-file" accept=".jpg,.jpeg,.png,.pdf"
                                            required>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- ===================== LOAN CARD ===================== --}}
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Loan Application</h4>
                            <p class="card-description text-muted">Provide accurate details. Your request will be reviewed.
                            </p>

                            @php $currencySymbol = $siteSettings->currency_symbol ?? '$'; @endphp

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_name">Project Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('project_name') is-invalid @enderror"
                                            id="project_name" name="project_name" value="{{ old('project_name') }}"
                                            required>
                                        @error('project_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount_requested">Project Amount Requested ({{ $currencySymbol }})
                                            <span class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('amount_requested') is-invalid @enderror"
                                            id="amount_requested" name="amount_requested"
                                            value="{{ old('amount_requested') }}" min="0" step="100"
                                            required>
                                        @error('amount_requested')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_name">Company Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('company_name') is-invalid @enderror"
                                            id="company_name" name="company_name" value="{{ old('company_name') }}"
                                            required>
                                        @error('company_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_address">Company Address <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('company_address') is-invalid @enderror"
                                            id="company_address" name="company_address"
                                            value="{{ old('company_address') }}" required>
                                        @error('company_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ceo_name">CEO Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('ceo_name') is-invalid @enderror" id="ceo_name"
                                            name="ceo_name" value="{{ old('ceo_name') }}" required>
                                        @error('ceo_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ceo_email">CEO Email <span class="text-danger">*</span></label>
                                        <input type="email"
                                            class="form-control @error('ceo_email') is-invalid @enderror" id="ceo_email"
                                            name="ceo_email" value="{{ old('ceo_email') }}" required>
                                        @error('ceo_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_location">Project Location <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('project_location') is-invalid @enderror"
                                            id="project_location" name="project_location"
                                            value="{{ old('project_location') }}" required>
                                        @error('project_location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_urgency">Project Urgency <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control @error('project_urgency') is-invalid @enderror"
                                            id="project_urgency" name="project_urgency" required>
                                            <option value="">-- Select --</option>
                                            <option value="urgent"
                                                {{ old('project_urgency') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                            <option value="not_urgent"
                                                {{ old('project_urgency') === 'not_urgent' ? 'selected' : '' }}>Not Urgent
                                            </option>
                                        </select>
                                        @error('project_urgency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_type">Type of Project <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control @error('project_type') is-invalid @enderror"
                                            id="project_type" name="project_type" required>
                                            <option value="">-- Select --</option>
                                            @php
                                                $projectTypes = [
                                                    'farming_cultivation' => 'Farming/Cultivation',
                                                    'renewable_energy' => 'Renewable Energy/Sustainable Power',
                                                    'infrastructure_links' => 'Infrastructure Links/Overpasses',
                                                    'rail_networks' => 'Rail Networks',
                                                    'waste_processing' => 'Waste Processing Systems',
                                                    'personal_private' => 'Personal/Private',
                                                    'varied_multifaceted' => 'Varied/Multifaceted Project Financing',
                                                    'leisure_entertainment' =>
                                                        'Leisure/Entertainment Projects & Amenities',
                                                    'electricity_power' => 'Electricity and Power',
                                                    'communications_networks' => 'Communications/Networks',
                                                    'hospitality' => 'Hospitality',
                                                    'petroleum_gas' => 'Petroleum and Natural Gas',
                                                    'aerospace' => 'Aerospace',
                                                    'construction_edifice' => 'Construction/Edifice',
                                                ];
                                            @endphp
                                            @foreach ($projectTypes as $key => $label)
                                                <option value="{{ $key }}"
                                                    {{ old('project_type') === $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('project_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_summary">Brief Project Summary <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('project_summary') is-invalid @enderror" id="project_summary"
                                            name="project_summary" rows="3" maxlength="3000" required>{{ old('project_summary') }}</textarea>
                                        @error('project_summary')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="loan_type">Type of Loan <span class="text-danger">*</span></label>
                                        <select class="form-control @error('loan_type') is-invalid @enderror"
                                            id="loan_type" name="loan_type" required>
                                            <option value="">-- Select --</option>
                                            <option value="debt_financing"
                                                {{ old('loan_type') === 'debt_financing' ? 'selected' : '' }}>Debt
                                                Financing</option>
                                            <option value="equity" {{ old('loan_type') === 'equity' ? 'selected' : '' }}>
                                                Equity</option>
                                            <option value="joint_venture"
                                                {{ old('loan_type') === 'joint_venture' ? 'selected' : '' }}>Joint Venture
                                            </option>
                                            <option value="investment"
                                                {{ old('loan_type') === 'investment' ? 'selected' : '' }}>Investment
                                            </option>
                                        </select>
                                        @error('loan_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="expected_duration_years">Expected Duration <span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="form-control @error('expected_duration_years') is-invalid @enderror"
                                            id="expected_duration_years" name="expected_duration_years" required>
                                            <option value="">-- Select --</option>
                                            @foreach ([2, 3, 4, 5, 6, 7, 8, 9, 10] as $y)
                                                <option value="{{ $y }}"
                                                    {{ old('expected_duration_years') == $y ? 'selected' : '' }}>
                                                    {{ $y }} yrs</option>
                                            @endforeach
                                        </select>
                                        @error('expected_duration_years')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="previous_investor_funding">Previously Funded? <span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="form-control @error('previous_investor_funding') is-invalid @enderror"
                                            id="previous_investor_funding" name="previous_investor_funding" required>
                                            <option value="">-- Select --</option>
                                            <option value="yes"
                                                {{ old('previous_investor_funding') === 'yes' ? 'selected' : '' }}>Yes
                                            </option>
                                            <option value="no"
                                                {{ old('previous_investor_funding') === 'no' ? 'selected' : '' }}>No
                                            </option>
                                        </select>
                                        @error('previous_investor_funding')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- bank statement (manual pick, no upload button) --}}
                            <div class="form-group">
                                <label for="bank_account_statement">Bank Account Statement <span
                                        class="text-danger">*</span></label>
                                <input type="file" id="bank_account_statement" name="bank_account_statement"
                                    class="form-control @error('bank_account_statement') is-invalid @enderror"
                                    accept=".pdf,.jpg,.jpeg,.png" required>
                                @error('bank_account_statement')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">
                            <h6 class="mb-2">Personal Details</h6>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tenure_months">Tenure (Months) <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control @error('tenure_months') is-invalid @enderror"
                                            id="tenure_months" name="tenure_months" required>
                                            <option value="">-- Select --</option>
                                            @foreach ([3, 6, 9, 12, 18, 24] as $m)
                                                <option value="{{ $m }}"
                                                    {{ old('tenure_months') == $m ? 'selected' : '' }}>{{ $m }}
                                                    months</option>
                                            @endforeach
                                        </select>
                                        @error('tenure_months')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="repayment_method">Repayment Method <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control @error('repayment_method') is-invalid @enderror"
                                            id="repayment_method" name="repayment_method" required>
                                            <option value="">-- Select --</option>
                                            <option value="bank_transfer"
                                                {{ old('repayment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank
                                                Transfer</option>
                                            <option value="direct_debit"
                                                {{ old('repayment_method') === 'direct_debit' ? 'selected' : '' }}>Direct
                                                Debit</option>
                                            <option value="wallet"
                                                {{ old('repayment_method') === 'wallet' ? 'selected' : '' }}>Wallet
                                            </option>
                                        </select>
                                        @error('repayment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="employment_type">Employment Type <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control @error('employment_type') is-invalid @enderror"
                                            id="employment_type" name="employment_type" required>
                                            <option value="">-- Select --</option>
                                            <option value="salary"
                                                {{ old('employment_type') === 'salary' ? 'selected' : '' }}>Salary</option>
                                            <option value="business"
                                                {{ old('employment_type') === 'business' ? 'selected' : '' }}>Business
                                            </option>
                                            <option value="freelance"
                                                {{ old('employment_type') === 'freelance' ? 'selected' : '' }}>Freelance
                                            </option>
                                            <option value="agriculture"
                                                {{ old('employment_type') === 'agriculture' ? 'selected' : '' }}>
                                                Agriculture</option>
                                            <option value="other"
                                                {{ old('employment_type') === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('employment_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="income_band">Income Band <span class="text-danger">*</span></label>
                                        <select class="form-control @error('income_band') is-invalid @enderror"
                                            id="income_band" name="income_band" required>
                                            <option value="">-- Select --</option>
                                            <option value="below_100k"
                                                {{ old('income_band') === 'below_100k' ? 'selected' : '' }}>Below 100k
                                            </option>
                                            <option value="100k_300k"
                                                {{ old('income_band') === '100k_300k' ? 'selected' : '' }}>100k - 300k
                                            </option>
                                            <option value="300k_700k"
                                                {{ old('income_band') === '300k_700k' ? 'selected' : '' }}>300k - 700k
                                            </option>
                                            <option value="above_700k"
                                                {{ old('income_band') === 'above_700k' ? 'selected' : '' }}>Above 700k
                                            </option>
                                        </select>
                                        @error('income_band')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                @php $interest_rate = $siteSettings->interest_rate ?? 0; @endphp
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="interest_rate">Interest Rate (%) <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('interest_rate') is-invalid @enderror"
                                            id="interest_rate" name="interest_rate"
                                            value="{{ old('interest_rate', $interest_rate) }}" readonly required>
                                        @error('interest_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="purpose">Purpose <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('purpose') is-invalid @enderror" id="purpose" name="purpose" rows="3"
                                    maxlength="2000" placeholder="Briefly explain why you need this loan" required>{{ old('purpose') }}</textarea>
                                @error('purpose')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input @error('loan_consent') is-invalid @enderror"
                                        type="checkbox" id="loan_consent" name="loan_consent" value="1"
                                        {{ old('loan_consent') ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="loan_consent">
                                        I confirm that the information provided is accurate and I consent to verification.
                                        <span class="text-danger">*</span>
                                    </label>
                                    @error('loan_consent')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Submit KYC + Loan Application
                            </button>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const form = document.getElementById('applyForm');
            const docsWrap = document.getElementById('documents-wrapper');
            const addDocBtn = document.getElementById('add-document');
            const tpl = document.getElementById('doc-template');

            // ============================================================
            // 1) intl-tel-input + hidden fields (single init, single handler)
            // ============================================================
            const phoneInput = document.querySelector('#phone');
            const hCountryCode = document.getElementById('phone_country_code');
            const hNational = document.getElementById('phone_national');
            const hE164 = document.getElementById('phone_e164');
            const hIso = document.getElementById('phone_country_iso');

            let iti = null;

            if (phoneInput && window.intlTelInput) {
                iti = window.intlTelInput(phoneInput, {
                    initialCountry: "auto",
                    separateDialCode: true,
                    preferredCountries: ["ng", "us", "gb", "ca"],
                    geoIpLookup: function(callback) {
                        fetch("https://ipapi.co/json/")
                            .then(res => res.json())
                            .then(data => callback((data.country_code || "us").toLowerCase()))
                            .catch(() => callback("us"));
                    },
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
                });
            }

            function fillPhoneHiddenFields() {
                if (!iti || !phoneInput) return;

                const data = iti.getSelectedCountryData() || {};
                const dialCode = data.dialCode ? `+${data.dialCode}` : '';
                const iso = data.iso2 ? data.iso2.toUpperCase() : '';

                const national = (phoneInput.value || '').replace(/\D/g, '');
                const e164 = iti.getNumber ? iti.getNumber() : '';

                if (hCountryCode) hCountryCode.value = dialCode;
                if (hIso) hIso.value = iso;
                if (hNational) hNational.value = national;
                if (hE164) hE164.value = e164;
            }

            if (phoneInput) {
                phoneInput.addEventListener('input', fillPhoneHiddenFields);
                phoneInput.addEventListener('blur', fillPhoneHiddenFields);
                phoneInput.addEventListener('countrychange', fillPhoneHiddenFields);
            }

            // ============================================================
            // 2) Dynamic documents: reindex names for Blade validation
            // ============================================================
            function reindexDocs() {
                if (!docsWrap) return;

                const rows = docsWrap.querySelectorAll('.doc-row');

                rows.forEach((row, i) => {
                    const title = row.querySelector('.doc-title');
                    if (title) title.textContent = `Document #${i + 1}`;

                    const label = row.querySelector('.doc-label');
                    const type = row.querySelector('.doc-type');
                    const file = row.querySelector('.doc-file');

                    if (label) label.setAttribute('name', `documents[${i}][label]`);
                    if (type) type.setAttribute('name', `documents[${i}][type]`);
                    if (file) file.setAttribute('name', `documents[${i}][file]`);

                    // remove button visibility
                    const removeBtn = row.querySelector('.remove-doc');
                    if (removeBtn) {
                        if (rows.length === 1) removeBtn.classList.add('d-none');
                        else removeBtn.classList.remove('d-none');
                    }
                });
            }

            // add document
            if (addDocBtn && tpl && docsWrap) {
                addDocBtn.addEventListener('click', function() {
                    const fragment = tpl.content.cloneNode(true);
                    docsWrap.appendChild(fragment);
                    reindexDocs();
                });

                // remove document
                docsWrap.addEventListener('click', function(e) {
                    const btn = e.target.closest('.remove-doc');
                    if (!btn) return;
                    const row = btn.closest('.doc-row');
                    if (row) row.remove();
                    reindexDocs();
                });

                reindexDocs();
            }

            // ============================================================
            // 3) Manual submit: prevent double-submit + ensure docs indexed
            // ============================================================
            if (form) {
                form.addEventListener('submit', function(e) {

                    // ensure dynamic doc names are correct for backend
                    reindexDocs();
                    fillPhoneHiddenFields();

                    // optional: block invalid phone at client (server still validates)
                    if (iti && phoneInput && phoneInput.value.trim() !== '' && iti.isValidNumber && !iti
                        .isValidNumber()) {
                        e.preventDefault();
                        phoneInput.classList.add('is-invalid');
                        phoneInput.focus();
                        return;
                    }

                    // prevent double submit
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerText = 'Submitting...';
                    }
                });
            }
        });
    </script>

    <style>
        .iti {
            width: 100%;
        }

        .iti--separate-dial-code .iti__selected-flag {
            background-color: #fff;
            border-right: 1px solid #ced4da;
        }

        .iti input.form-control {
            padding-left: 90px !important;
            height: 38px;
            font-size: 14px;
        }

        .iti__flag-container {
            padding: 0;
        }

        .iti__country-list {
            z-index: 9999;
        }
    </style>
@endpush
