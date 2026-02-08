@extends('layouts.admin')
@section('title', 'Loan Application Form & KYC Verification')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h4 class="card-title mb-1">Loan Application Form & KYC Verification</h4>
                                <p class="card-description mb-0">Complete your KYC and upload required documents to verify
                                    your account.</p>
                            </div>

                            {{-- STATUS BADGE --}}
                            @php
                                $status = $kyc->status ?? 'pending';
                                $badge = match ($status) {
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'under_review' => 'info',
                                    'submitted' => 'warning',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge badge-{{ $badge }} text-uppercase">
                                {{ str_replace('_', ' ', $status) }}
                            </span>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success mb-3">{{ session('status') }}</div>
                        @endif

                        @if ($status === 'rejected' && !empty($kyc->rejection_reason))
                            <div class="alert alert-danger mb-3">
                                <strong>Rejected:</strong> {{ $kyc->rejection_reason }}
                            </div>
                        @endif

                        {{-- Existing uploaded documents --}}
                        @if (!empty($kyc) && $kyc->documents && $kyc->documents->count())
                            <div class="mb-3">
                                <h6 class="mb-2">Existing Documents</h6>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($kyc->documents as $d)
                                        <li class="mb-1">
                                            <span
                                                class="badge badge-light text-dark text-uppercase">{{ $d->type }}</span>
                                            <strong class="ms-1">{{ $d->label }}</strong>
                                            — <a href="{{ asset('storage/' . $d->file_path) }}" target="_blank"
                                                rel="noopener">View</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="applyForm" class="forms-sample" method="POST" action="{{ route('user.kyc.update') }}"
                            enctype="multipart/form-data">
                            @csrf

                            {{-- ===================== KYC ===================== --}}
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

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" id="phone" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $kyc->phone ?? '') }}" placeholder="Enter phone number"
                                            required>
                                        <small class="text-muted d-block mt-1">Select your country code from the
                                            dropdown.</small>

                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select class="form-control @error('gender') is-invalid @enderror" id="gender"
                                            name="gender">
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
                                    placeholder="Street / LGA / Area" required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                            id="city" name="city" value="{{ old('city', $kyc->city ?? '') }}"
                                            placeholder="City">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" class="form-control @error('state') is-invalid @enderror"
                                            id="state" name="state" value="{{ old('state', $kyc->state ?? '') }}"
                                            placeholder="State">
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- hidden phone parts (populated by intl-tel-input) --}}
                            <input type="hidden" name="phone_country_code" id="phone_country_code">
                            <input type="hidden" name="phone_national" id="phone_national">
                            <input type="hidden" name="phone_e164" id="phone_e164">
                            <input type="hidden" name="phone_country_iso" id="phone_country_iso">

                            <hr class="my-4">

                            {{-- ===================== DOCUMENTS ===================== --}}
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <h5 class="mb-0">Upload Documents</h5>
                                    <p class="text-muted mb-0">Add as many documents as needed (JPG/PNG/PDF).</p>
                                </div>
                            </div>

                            <div id="documents-wrapper">
                                @php $oldDocs = old('documents', []); @endphp

                                @if (!empty($oldDocs))
                                    @foreach ($oldDocs as $i => $oldDoc)
                                        <div class="doc-row border rounded p-3 mb-3" data-dynamic-row="1">
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
                                                            required>
                                                        @error("documents.$i.label")
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label>Document Category</label>
                                                        <select name="documents[{{ $i }}][type]"
                                                            class="form-control @error("documents.$i.type") is-invalid @enderror">
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
                                                <label>Upload File (JPG/PNG/PDF) <span class="text-danger">*</span></label>
                                                <input type="file" name="documents[{{ $i }}][file]"
                                                    class="file-upload-default" accept=".jpg,.jpeg,.png,.pdf" required>

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
                                    {{-- First row: no data-dynamic-row so theme plugin can handle upload button without double-trigger --}}
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
                                                        placeholder="e.g. International Passport, Bank Statement" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mb-2">
                                                    <label>Document Category</label>
                                                    <select name="documents[0][type]" class="form-control">
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
                                            <label>Upload File (JPG/PNG/PDF) <span class="text-danger">*</span></label>
                                            <input type="file" name="documents[0][file]" class="file-upload-default"
                                                accept=".jpg,.jpeg,.png,.pdf" required>

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

                            {{-- Template for dynamic rows --}}
                            <template id="doc-template">
                                <div class="doc-row border rounded p-3 mb-3" data-dynamic-row="1">
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
                                                <label>Document Category</label>
                                                <select class="form-control doc-type">
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
                                        <label>Upload File (JPG/PNG/PDF) <span class="text-danger">*</span></label>
                                        <input type="file" class="file-upload-default doc-file"
                                            accept=".jpg,.jpeg,.png,.pdf" required>

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

                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn btn-primary">Submit KYC</button>
                                <a href="javascript:;" class="btn btn-light">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // -----------------------------
            // 1) intl-tel-input
            // -----------------------------
            const phoneInput = document.querySelector("#phone");
            const form = phoneInput?.closest('form');

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

            if (form) {
                form.addEventListener('submit', function(e) {
                    fillPhoneHiddenFields();

                    if (iti && phoneInput.value.trim() !== '' && !iti.isValidNumber()) {
                        e.preventDefault();
                        alert('Please enter a valid phone number.');
                        phoneInput.focus();
                    }
                });
            }

            // -----------------------------
            // 2) Dynamic documents add/remove + reindex
            // -----------------------------
            (function() {
                const wrapper = document.getElementById('documents-wrapper');
                const addBtn = document.getElementById('add-document');
                const tpl = document.getElementById('doc-template');

                if (!wrapper || !addBtn || !tpl) return;

                function rows() {
                    return wrapper.querySelectorAll('.doc-row');
                }

                function renumber() {
                    rows().forEach((row, idx) => {
                        const title = row.querySelector('.doc-title') || row.querySelector('strong');
                        if (title) title.textContent = `Document #${idx + 1}`;

                        const removeBtn = row.querySelector('.remove-doc');
                        if (removeBtn) {
                            removeBtn.classList.toggle('d-none', rows().length === 1 && idx === 0);
                        }
                    });
                }

                function reindexNames() {
                    rows().forEach((row, idx) => {
                        const label = row.querySelector('input[type="text"]');
                        const type = row.querySelector('select');
                        const file = row.querySelector('input[type="file"]');

                        if (label) label.name = `documents[${idx}][label]`;
                        if (type) type.name = `documents[${idx}][type]`;
                        if (file) file.name = `documents[${idx}][file]`;
                    });
                }

                function addRow() {
                    const idx = rows().length;
                    const node = tpl.content.cloneNode(true);

                    const row = node.querySelector('.doc-row');
                    row.querySelector('.doc-title').textContent = `Document #${idx + 1}`;

                    const label = row.querySelector('.doc-label');
                    label.name = `documents[${idx}][label]`;

                    const type = row.querySelector('.doc-type');
                    type.name = `documents[${idx}][type]`;

                    const file = row.querySelector('.doc-file');
                    file.name = `documents[${idx}][file]`;

                    row.setAttribute('data-dynamic-row', '1');

                    wrapper.appendChild(node);
                    renumber();
                }

                addBtn.addEventListener('click', addRow);

                wrapper.addEventListener('click', function(e) {
                    const btn = e.target.closest('.remove-doc');
                    if (!btn) return;

                    const row = btn.closest('.doc-row');
                    if (!row) return;

                    row.remove();
                    reindexNames();
                    renumber();
                });

                renumber();
            })();

            // -----------------------------
            // 3) Upload browse fallback (ONLY for dynamic rows)
            // -----------------------------
            (function() {
                const docWrapper = document.getElementById('documents-wrapper');
                if (!docWrapper) return;

                docWrapper.addEventListener('click', function(e) {
                    const btn = e.target.closest('.file-upload-browse');
                    if (!btn) return;

                    const row = btn.closest('.doc-row');
                    if (!row) return;

                    if (!row.hasAttribute('data-dynamic-row')) return;

                    e.preventDefault();
                    e.stopPropagation();

                    const fileInput = row.querySelector('input[type="file"].file-upload-default');
                    if (fileInput) fileInput.click();
                });

                docWrapper.addEventListener('change', function(e) {
                    const fileInput = e.target;
                    if (!fileInput.matches('input[type="file"].file-upload-default')) return;

                    const row = fileInput.closest('.doc-row') || docWrapper;
                    const infoInput = row.querySelector('.file-upload-info');

                    if (infoInput) {
                        infoInput.value = fileInput.files?.[0]?.name ?? '';
                    }
                });
            })();

        });
    </script>
@endpush
