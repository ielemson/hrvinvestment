@extends('layouts.admin')
@section('title', 'Loan Application')

@section('content')
    <div class="content-wrapper">
        @php $currencySymbol = $siteSettings->currency_symbol ?? '₦'; @endphp
        @include('user.partials.kyc_notification')

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

                {{-- LARAVEL VALIDATION SUMMARY --}}
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

                {{-- ONE FORM, TWO CARDS --}}
                <form id="applyForm" class="forms-sample" method="POST" action="{{ route('user.apply.store') }}"
                    enctype="multipart/form-data" data-parsley-validate novalidate>
                    @csrf

                    {{-- ===================== KYC CARD ===================== --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="card-title">KYC Information</h4>
                            <p class="card-description text-muted">Tell us about yourself and upload required documents.</p>

                            <div class="form-group">
                                <label for="full_name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                    id="full_name" name="full_name"
                                    value="{{ old('full_name', $kyc->full_name ?? auth()->user()->name) }}"
                                    placeholder="Your full name" required data-parsley-required="true"
                                    data-parsley-required-message="Full name is required">
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>

                                        <input type="tel" id="phone" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $kyc->phone ?? '') }}" placeholder="Enter phone number"
                                            required>

                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- REQUIRED hidden fields --}}
                                    <input type="hidden" name="phone_country_code" id="phone_country_code">
                                    <input type="hidden" name="phone_national" id="phone_national">
                                    <input type="hidden" name="phone_e164" id="phone_e164">
                                    <input type="hidden" name="phone_country_iso" id="phone_country_iso">
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Gender <span class="text-danger">*</span></label>
                                        <select class="form-control @error('gender') is-invalid @enderror" id="gender"
                                            name="gender" required data-parsley-required="true"
                                            data-parsley-required-message="Select a gender">
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
                            </div>

                            <div class="form-group">
                                <label for="address">Residential Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                    id="address" name="address" value="{{ old('address', $kyc->address ?? '') }}"
                                    placeholder="Street / LGA / Area" required data-parsley-required="true"
                                    data-parsley-required-message="Residential address is required">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                            id="city" name="city" value="{{ old('city', $kyc->city ?? '') }}"
                                            placeholder="City" required data-parsley-required="true"
                                            data-parsley-required-message="City is required">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state">State <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('state') is-invalid @enderror"
                                            id="state" name="state" value="{{ old('state', $kyc->state ?? '') }}"
                                            placeholder="State" required data-parsley-required="true"
                                            data-parsley-required-message="State is required">
                                        @error('state')
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
                                                    class="btn btn-sm btn-outline-danger remove-doc">Remove</button>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label>Document Name (Label) <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            name="documents[{{ $i }}][label]"
                                                            value="{{ $oldDoc['label'] ?? '' }}"
                                                            class="form-control @error("documents.$i.label") is-invalid @enderror"
                                                            placeholder="e.g. International Passport, Bank Statement"
                                                            required data-parsley-required="true"
                                                            data-parsley-required-message="Document label is required">
                                                        @error("documents.$i.label")
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label>Document Category <span class="text-danger">*</span></label>
                                                        <select name="documents[{{ $i }}][type]"
                                                            class="form-control @error("documents.$i.type") is-invalid @enderror"
                                                            required data-parsley-required="true"
                                                            data-parsley-required-message="Select a document category">
                                                            <option value="">-- Select --</option>
                                                            <option value="id_card"
                                                                {{ ($oldDoc['type'] ?? '') === 'id_card' ? 'selected' : '' }}>
                                                                Government ID</option>
                                                            <option value="proof_of_income"
                                                                {{ ($oldDoc['type'] ?? '') === 'proof_of_income' ? 'selected' : '' }}>
                                                                Proof of Income</option>
                                                            <option value="proof_of_address"
                                                                {{ ($oldDoc['type'] ?? '') === 'proof_of_address' ? 'selected' : '' }}>
                                                                Proof of Address</option>
                                                            <option value="selfie"
                                                                {{ ($oldDoc['type'] ?? '') === 'selfie' ? 'selected' : '' }}>
                                                                Selfie</option>
                                                            <option value="other"
                                                                {{ ($oldDoc['type'] ?? '') === 'other' ? 'selected' : '' }}>
                                                                Other</option>
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
                                                    class="file-upload-default" accept=".jpg,.jpeg,.png,.pdf" required
                                                    data-parsley-required="true"
                                                    data-parsley-required-message="Please upload a document">

                                                <div class="input-group col-xs-12">
                                                    <input type="text" class="form-control file-upload-info" disabled
                                                        placeholder="Choose file...">
                                                    <span class="input-group-append">
                                                        <button class="file-upload-browse btn btn-primary"
                                                            type="button">Upload</button>
                                                    </span>
                                                </div>

                                                @error("documents.$i.file")
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                @else
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
                                                    <input type="text" name="documents[0][label]" class="form-control"
                                                        placeholder="e.g. International Passport, Bank Statement" required
                                                        data-parsley-required="true"
                                                        data-parsley-required-message="Document label is required">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mb-2">
                                                    <label>Document Category <span class="text-danger">*</span></label>
                                                    <select name="documents[0][type]" class="form-control" required
                                                        data-parsley-required="true"
                                                        data-parsley-required-message="Select a document category">
                                                        <option value="">-- Select --</option>
                                                        <option value="id_card">Government ID</option>
                                                        <option value="proof_of_income">Proof of Income</option>
                                                        <option value="proof_of_address">Proof of Address</option>
                                                        <option value="selfie">Selfie</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mb-0">
                                            <label>Upload File <span class="text-danger">*</span></label>
                                            <input type="file" name="documents[0][file]" class="file-upload-default"
                                                accept=".jpg,.jpeg,.png,.pdf" required data-parsley-required="true"
                                                data-parsley-required-message="Please upload a document">

                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled
                                                    placeholder="Choose file...">
                                                <span class="input-group-append">
                                                    <button class="file-upload-browse btn btn-primary"
                                                        type="button">Upload</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <button type="button" id="add-document" class="btn btn-outline-primary btn-sm mb-3">
                                + Add another document
                            </button>

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
                                                    placeholder="e.g. International Passport, Bank Statement" required
                                                    data-parsley-required="true"
                                                    data-parsley-required-message="Document label is required">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-2">
                                                <label>Document Category <span class="text-danger">*</span></label>
                                                <select class="form-control doc-type" required
                                                    data-parsley-required="true"
                                                    data-parsley-required-message="Select a document category">
                                                    <option value="">-- Select --</option>
                                                    <option value="id_card">Government ID</option>
                                                    <option value="proof_of_income">Proof of Income</option>
                                                    <option value="proof_of_address">Proof of Address</option>
                                                    <option value="selfie">Selfie</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0">
                                        <label>Upload File <span class="text-danger">*</span></label>
                                        <input type="file" class="file-upload-default doc-file"
                                            accept=".jpg,.jpeg,.png,.pdf" required data-parsley-required="true"
                                            data-parsley-required-message="Please upload a document">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled
                                                placeholder="Choose file...">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-primary"
                                                    type="button">Upload</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- ===================== LOAN CARD (ALL REQUIRED) ===================== --}}
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Loan Application</h4>
                            <p class="card-description text-muted">Provide accurate details. Your request will be reviewed.
                            </p>

                            <h6 class="mt-2 mb-2">Project & Company Details</h6>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_name">Project Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('project_name') is-invalid @enderror"
                                            id="project_name" name="project_name" value="{{ old('project_name') }}"
                                            placeholder="e.g. Umuagwo Agro Expansion" required
                                            data-parsley-required="true"
                                            data-parsley-required-message="Project name is required">
                                        @error('project_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount_requested">Project Amount Requested
                                            ({{ $currencySymbol }}) <span class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('amount_requested') is-invalid @enderror"
                                            id="amount_requested" name="amount_requested"
                                            value="{{ old('amount_requested') }}" min="0" step="100"
                                            placeholder="e.g. 50000000" required data-parsley-required="true"
                                            data-parsley-required-message="Project amount requested is required">
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
                                            placeholder="e.g. Ometa Farms Ltd" required data-parsley-required="true"
                                            data-parsley-required-message="Company name is required">
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
                                            value="{{ old('company_address') }}" placeholder="Company address" required
                                            data-parsley-required="true"
                                            data-parsley-required-message="Company address is required">
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
                                            name="ceo_name" value="{{ old('ceo_name') }}" placeholder="CEO full name"
                                            required data-parsley-required="true"
                                            data-parsley-required-message="CEO name is required">
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
                                            name="ceo_email" value="{{ old('ceo_email') }}"
                                            placeholder="e.g. ceo@company.com" required data-parsley-required="true"
                                            data-parsley-type="email" data-parsley-type-message="Enter a valid CEO email"
                                            data-parsley-required-message="CEO email is required">
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
                                            value="{{ old('project_location') }}" placeholder="e.g. Owerri, Imo State"
                                            required data-parsley-required="true"
                                            data-parsley-required-message="Project location is required">
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
                                            id="project_urgency" name="project_urgency" required
                                            data-parsley-required="true"
                                            data-parsley-required-message="Select project urgency">
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

                            <div class="form-group">
                                <label for="project_type">Type of Project <span class="text-danger">*</span></label>
                                <select class="form-control @error('project_type') is-invalid @enderror"
                                    id="project_type" name="project_type" required data-parsley-required="true"
                                    data-parsley-required-message="Select a project type">
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
                                            'leisure_entertainment' => 'Leisure/Entertainment Projects & Amenities',
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

                            <div class="form-group">
                                <label for="project_summary">Brief Project Summary <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('project_summary') is-invalid @enderror" id="project_summary"
                                    name="project_summary" rows="4" maxlength="3000" required data-parsley-required="true"
                                    data-parsley-maxlength="3000" data-parsley-required-message="Project summary is required"
                                    data-parsley-maxlength-message="Maximum 3000 characters">{{ old('project_summary') }}</textarea>
                                @error('project_summary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="loan_type">Type of Loan <span class="text-danger">*</span></label>
                                        <select class="form-control @error('loan_type') is-invalid @enderror"
                                            id="loan_type" name="loan_type" required data-parsley-required="true"
                                            data-parsley-required-message="Select a loan type">
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
                                            id="expected_duration_years" name="expected_duration_years" required
                                            data-parsley-required="true" data-parsley-required-message="Select duration">
                                            <option value="">-- Select --</option>
                                            @foreach ([2, 3, 4, 5, 6, 7, 8, 9, 10] as $y)
                                                <option value="{{ $y }}"
                                                    {{ old('expected_duration_years') == $y ? 'selected' : '' }}>
                                                    {{ $y }} yrs
                                                </option>
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
                                            id="previous_investor_funding" name="previous_investor_funding" required
                                            data-parsley-required="true" data-parsley-required-message="Select yes or no">
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

                            <div class="form-group">
                                <label for="bank_account_statement">
                                    Bank Account Statement <span class="text-danger">*</span>
                                </label>

                                <input type="file" id="bank_account_statement" name="bank_account_statement"
                                    class="file-upload-default bank-upload-input @error('bank_account_statement') is-invalid @enderror"
                                    accept=".pdf,.jpg,.jpeg,.png" required data-parsley-required="true"
                                    data-parsley-required-message="Upload a bank statement">

                                <div class="input-group col-xs-12">
                                    <input type="text" class="form-control file-upload-info bank-upload-info" disabled
                                        placeholder="Choose file...">

                                    <span class="input-group-append">
                                        <button class="btn btn-primary bank-upload-browse" type="button">
                                            Upload
                                        </button>
                                    </span>
                                </div>

                                @error('bank_account_statement')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">
                            <h6 class="mb-2">Personal Loan Details</h6>

                            <div class="row">
                                {{-- Tenure --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tenure_months">Tenure (Months) <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control @error('tenure_months') is-invalid @enderror"
                                            id="tenure_months" name="tenure_months" required data-parsley-required="true"
                                            data-parsley-required-message="Tenure months is required">
                                            <option value="">-- Select --</option>
                                            @foreach ([3, 6, 9, 12, 18, 24] as $m)
                                                <option value="{{ $m }}"
                                                    {{ old('tenure_months') == $m ? 'selected' : '' }}>
                                                    {{ $m }} months
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tenure_months')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Repayment method --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="repayment_method">Repayment Method <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control @error('repayment_method') is-invalid @enderror"
                                            id="repayment_method" name="repayment_method" required
                                            data-parsley-required="true"
                                            data-parsley-required-message="Repayment method is required">
                                            <option value="">-- Select --</option>
                                            <option value="bank_transfer"
                                                {{ old('repayment_method') === 'bank_transfer' ? 'selected' : '' }}>
                                                Bank Transfer
                                            </option>
                                            <option value="direct_debit"
                                                {{ old('repayment_method') === 'direct_debit' ? 'selected' : '' }}>
                                                Direct Debit
                                            </option>
                                            <option value="wallet"
                                                {{ old('repayment_method') === 'wallet' ? 'selected' : '' }}>
                                                Wallet
                                            </option>
                                        </select>
                                        @error('repayment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Employment type --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="employment_type">Employment Type <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control @error('employment_type') is-invalid @enderror"
                                            id="employment_type" name="employment_type" required
                                            data-parsley-required="true"
                                            data-parsley-required-message="Employment type is required">
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
                                {{-- Income band --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="income_band">Income Band <span class="text-danger">*</span></label>
                                        <select class="form-control @error('income_band') is-invalid @enderror"
                                            id="income_band" name="income_band" required data-parsley-required="true"
                                            data-parsley-required-message="Income band is required">
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

                                {{-- Interest rate --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="interest_rate">Interest Rate (%) <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('interest_rate') is-invalid @enderror"
                                            id="interest_rate" name="interest_rate" value="{{ old('interest_rate') }}"
                                            min="0" max="100" step="0.01" placeholder="e.g. 7.5" required
                                            data-parsley-required="true"
                                            data-parsley-required-message="Interest rate is required">
                                        @error('interest_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- (Optional) You can also add amount_requested here if your backend expects it as personal loan amount --}}
                                {{-- But you already use amount_requested above, so leave it --}}
                            </div>

                            <div class="form-group">
                                <label for="purpose">Purpose <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('purpose') is-invalid @enderror" id="purpose" name="purpose" rows="3"
                                    maxlength="2000" placeholder="Briefly explain why you need this loan" required data-parsley-required="true"
                                    data-parsley-required-message="Purpose is required" data-parsley-maxlength="2000"
                                    data-parsley-maxlength-message="Maximum 2000 characters">{{ old('purpose') }}</textarea>
                                @error('purpose')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input @error('loan_consent') is-invalid @enderror"
                                        type="checkbox" id="loan_consent" name="loan_consent" value="1"
                                        {{ old('loan_consent') ? 'checked' : '' }} required data-parsley-required="true"
                                        data-parsley-required-message="You must accept the loan consent">
                                    <label class="form-check-label" for="loan_consent">
                                        I confirm that the information provided is accurate and I consent to verification.
                                        <span class="text-danger">*</span>
                                    </label>
                                    @error('loan_consent')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="button" id="submitApplyBtn" class="btn btn-primary">
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
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>

    <script>
        /**
         * ONE SCRIPT: jQuery + Vanilla KYC (intl-tel-input) + Dynamic Docs + Single File Upload
         * - Prevents double file-dialog trigger
         * - Keeps intl-tel-input hidden fields in sync
         * - Keeps dynamic documents working (reindex + add/remove)
         * - Works for bank statement upload too
         */
        $(function() {
            /**
             * ============================================================
             * CONFIG
             * ============================================================
             */
            const $applyForm = $('#applyForm'); // combined KYC + Loan form
            const $loanForm = $('#loanForm'); // optional standalone loan form (AJAX)
            const $documentsWrap = $('#documents-wrapper');
            const $addDocBtn = $('#add-document');
            const $docTpl = $('#doc-template');

            /**
             * ============================================================
             * 1) intl-tel-input (KYC phone) + hidden fields
             * ============================================================
             */
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

            /**
             * ============================================================
             * 2) DOCS: Reindex + Add/Remove (Dynamic KYC docs)
             * ============================================================
             */
            function reindexDocs() {
                if (!$documentsWrap.length) return;

                const $rows = $documentsWrap.find('.doc-row');

                $rows.each(function(i) {
                    const $row = $(this);

                    $row.find('.doc-title').text('Document #' + (i + 1));

                    const $label = $row.find('.doc-label').length ?
                        $row.find('.doc-label') :
                        $row.find('input[name*="[label]"]');

                    const $type = $row.find('.doc-type').length ?
                        $row.find('.doc-type') :
                        $row.find('select[name*="[type]"]');

                    const $file = $row.find('.doc-file').length ?
                        $row.find('.doc-file') :
                        $row.find('input[type="file"][name*="[file]"]');

                    if ($label.length) $label.attr('name', `documents[${i}][label]`);
                    if ($type.length) $type.attr('name', `documents[${i}][type]`);
                    if ($file.length) $file.attr('name', `documents[${i}][file]`);

                    const $remove = $row.find('.remove-doc');
                    if ($remove.length) {
                        ($rows.length === 1) ? $remove.addClass('d-none'): $remove.removeClass('d-none');
                    }
                });
            }
            window.reindexDocs = reindexDocs;

            // Add/Remove document rows + mark dynamic rows
            if ($addDocBtn.length && $docTpl.length && $documentsWrap.length) {
                $addDocBtn.on('click', function() {
                    const $node = $($docTpl.html());

                    $node.attr('data-dynamic-row',
                        '1'); // ✅ so upload fallback applies only to dynamic rows

                    // clear fields
                    $node.find('input[type="text"]').val('');
                    $node.find('select').val('');
                    $node.find('input[type="file"]').val('');
                    $node.find('.file-upload-info').val('');

                    $documentsWrap.append($node);
                    reindexDocs();
                });

                $documentsWrap.on('click', '.remove-doc', function() {
                    $(this).closest('.doc-row').remove();
                    reindexDocs();
                });

                // initial
                reindexDocs();
            }

            /**
             * ============================================================
             * 3) ✅ FILE UPLOAD HANDLERS (NO DOUBLE TRIGGER)
             * Strategy:
             * - For doc rows: trigger ONLY on rows marked data-dynamic-row="1"
             *   (so the first server-rendered row can keep template plugin binding without double firing)
             * - For bank statement: always trigger
             * ============================================================
             */
            $(document)
                .off('click.fileUploadBrowse')
                .on('click.fileUploadBrowse', '.file-upload-browse', function(e) {

                    const $btn = $(this);
                    const $row = $btn.closest('.doc-row');

                    // If inside a doc row, only handle dynamically added rows
                    if ($row.length) {
                        if (!$row.is('[data-dynamic-row]')) {
                            // Let your theme/plugin handle the initial row
                            return;
                        }

                        // Our fallback for dynamic rows
                        e.preventDefault();
                        e.stopImmediatePropagation();

                        const $fileInput = $row.find('input[type="file"].file-upload-default').first();
                        if ($fileInput.length) $fileInput.trigger('click');
                        return;
                    }

                    // Not a doc-row => e.g bank statement upload UI
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    const $container = $btn.closest('.form-group');
                    const $fileInput = $container.find('input[type="file"].file-upload-default').first();
                    if ($fileInput.length) $fileInput.trigger('click');
                });

            // Show filename for ANY file input
            $(document)
                .off('change.fileUploadDefault')
                .on('change.fileUploadDefault', 'input[type="file"].file-upload-default', function() {
                    const fileName = (this.files && this.files.length) ? this.files[0].name : '';
                    $(this).closest('.doc-row, .form-group').find('.file-upload-info').val(fileName);
                });

            /**
             * ============================================================
             * 4) APPLY FORM: Parsley validate, then submit manually (NO AJAX)
             * + fill phone hidden fields before submit
             * + optional phone validity check
             * ============================================================
             */
            if ($applyForm.length) {
                $applyForm.parsley();

                $('#submitApplyBtn').on('click', function(e) {
                    e.preventDefault();

                    if ($documentsWrap.length) reindexDocs();
                    fillPhoneHiddenFields();

                    const parsley = $applyForm.parsley();
                    parsley.validate();

                    if (!parsley.isValid()) return;

                    // Optional: block submit if invalid phone
                    if (iti && phoneInput && phoneInput.value.trim() !== '' && !iti.isValidNumber()) {
                        alert('Please enter a valid phone number.');
                        phoneInput.focus();
                        return;
                    }

                    $applyForm.trigger('submit');
                });

                // If user submits via Enter key
                $applyForm.on('submit', function() {
                    if ($documentsWrap.length) reindexDocs();
                    fillPhoneHiddenFields();

                    if (iti && phoneInput && phoneInput.value.trim() !== '' && !iti.isValidNumber()) {
                        // prevent submit if invalid phone
                        event.preventDefault();
                        alert('Please enter a valid phone number.');
                        phoneInput.focus();
                    }
                });
            }

            /**
             * ============================================================
             * 5) OPTIONAL: Standalone loan form (AJAX)
             * ============================================================
             */
            if ($loanForm.length) {
                $loanForm.parsley();

                $loanForm.on('submit', function(e) {
                    e.preventDefault();

                    $loanForm.find('.invalid-feedback').text('').hide();
                    $loanForm.find('.form-control').removeClass('is-invalid');

                    const parsley = $loanForm.parsley();
                    if (!parsley.validate()) return false;

                    const $btn = $('#submitLoanBtn');
                    const $btnText = $('#btnText');
                    const original = $btnText.text();

                    $btn.prop('disabled', true);
                    $btnText.text('Submitting...');

                    $.ajax({
                        url: "{{ route('user.loans.store') }}",
                        type: "POST",
                        data: $loanForm.serialize(),
                        success: function() {
                            window.location.href = "{{ route('user.loans.index') }}";
                        },
                        error: function(xhr) {
                            $btn.prop('disabled', false);
                            $btnText.text(original);

                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON
                                .errors) {
                                const errors = xhr.responseJSON.errors;

                                $.each(errors, function(field, messages) {
                                    const errorId = '#error-' + field.replace(/\./g,
                                        '_');
                                    $(errorId).text(messages[0]).show();
                                    $loanForm.find('[name="' + field + '"]').addClass(
                                        'is-invalid');
                                });
                            } else {
                                alert('Something went wrong. Please try again.');
                            }
                        }
                    });
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {

            const phoneInput = document.querySelector('#phone');
            if (!phoneInput || !window.intlTelInput) return;

            const form = phoneInput.closest('form');

            const hCountryCode = document.getElementById('phone_country_code');
            const hNational = document.getElementById('phone_national');
            const hE164 = document.getElementById('phone_e164');
            const hIso = document.getElementById('phone_country_iso');

            const iti = window.intlTelInput(phoneInput, {
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

            function fillPhoneHiddenFields() {
                if (!iti) return;

                const data = iti.getSelectedCountryData() || {};

                const dialCode = data.dialCode ? `+${data.dialCode}` : '';
                const iso = data.iso2 ? data.iso2.toUpperCase() : '';

                const national = (phoneInput.value || '').replace(/\D/g, '');
                const e164 = iti.getNumber();

                if (hCountryCode) hCountryCode.value = dialCode;
                if (hIso) hIso.value = iso;
                if (hNational) hNational.value = national;
                if (hE164) hE164.value = e164;
            }

            // Keep values updated live
            phoneInput.addEventListener('input', fillPhoneHiddenFields);
            phoneInput.addEventListener('blur', fillPhoneHiddenFields);
            phoneInput.addEventListener('countrychange', fillPhoneHiddenFields);

            // CRITICAL: populate before submit
            if (form) {
                form.addEventListener('submit', function(e) {
                    fillPhoneHiddenFields();

                    // Optional hard validation
                    if (phoneInput.value.trim() !== '' && !iti.isValidNumber()) {
                        e.preventDefault();
                        alert('Please enter a valid phone number.');
                        phoneInput.focus();
                    }
                });
            }
        });

        // ============================================================
        // Bank Statement Upload (single, isolated handler)
        // Prevents double-trigger issue
        // ============================================================

        $(document).off('click.bankUpload').on('click.bankUpload', '.bank-upload-browse', function(e) {
            e.preventDefault();
            e.stopPropagation();

            $('.bank-upload-input').first().trigger('click');
        });

        $(document).off('change.bankUpload').on('change.bankUpload', '.bank-upload-input', function() {
            const fileName = this.files && this.files.length ? this.files[0].name : '';
            $('.bank-upload-info').val(fileName);
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
