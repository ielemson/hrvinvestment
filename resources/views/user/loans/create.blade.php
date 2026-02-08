@extends('layouts.admin')
@section('title', 'Loan Application')

@section('content')
    <div class="content-wrapper">
        @php $currencySymbol = $siteSettings->currency_symbol ?? '₦'; @endphp
        @include('user.partials.kyc_notification')

        <div class="row">
            <div class="col-lg-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Loan Application Form</h4>
                        <p class="card-description text-muted">Provide accurate details. Your request will be reviewed.</p>

                        <form id="loanForm" data-parsley-validate novalidate>
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    {{-- Amount Requested --}}
                                    <div class="form-group">
                                        <label for="amount_requested">Amount Requested ({{ $currencySymbol }})</label>
                                        <input type="number" class="form-control" id="amount_requested"
                                            name="amount_requested" required min="1000" step="100"
                                            data-parsley-required="true" data-parsley-min="1000"
                                            data-parsley-required-message="Enter loan amount"
                                            data-parsley-min-message="Minimum amount is {{ $currencySymbol }}1,000"
                                            placeholder="e.g. 500000">
                                        <div class="invalid-feedback" id="error-amount_requested"></div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    {{-- Tenure --}}
                                    <div class="form-group">
                                        <label for="tenure_months">Tenure (Months)</label>
                                        <select class="form-control" id="tenure_months" name="tenure_months" required
                                            data-parsley-required="true" data-parsley-required-message="Select loan tenure">
                                            <option value="">Select tenure</option>
                                            @foreach ([3, 6, 9, 12, 18, 24] as $m)
                                                <option value="{{ $m }}">{{ $m }} months</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="error-tenure_months"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    {{-- Interest Rate (Optional) --}}
                                    <div class="form-group">
                                        <label for="interest_rate">Interest Rate (%) <span
                                                class="text-muted">(optional)</span></label>
                                        <input type="number" class="form-control" id="interest_rate" name="interest_rate"
                                            min="0" max="100" step="0.01" data-parsley-type="number"
                                            data-parsley-min="0" data-parsley-max="100" placeholder="e.g. 5">
                                        <small class="text-muted">If left blank, the system/admin rate will be
                                            applied.</small>
                                        <div class="invalid-feedback" id="error-interest_rate"></div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    {{-- Repayment Method (Required) --}}
                                    <div class="form-group">
                                        <label for="repayment_method">Preferred Repayment Method</label>
                                        <select class="form-control" id="repayment_method" name="repayment_method" required
                                            data-parsley-required="true"
                                            data-parsley-required-message="Select repayment method">
                                            <option value="">Select method</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="direct_debit">Direct Debit</option>
                                            <option value="wallet">Wallet Balance</option>
                                        </select>
                                        <div class="invalid-feedback" id="error-repayment_method"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    {{-- Employment / Income Source (Required) --}}
                                    <div class="form-group">
                                        <label for="employment_type">Employment / Income Source</label>
                                        <select class="form-control" id="employment_type" name="employment_type" required
                                            data-parsley-required="true"
                                            data-parsley-required-message="Select your income source">
                                            <option value="">Select source</option>
                                            <option value="salary">Salaried Employment</option>
                                            <option value="business">Business / Self-Employed</option>
                                            <option value="freelance">Freelance / Contract</option>
                                            <option value="agriculture">Agriculture</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <div class="invalid-feedback" id="error-employment_type"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- Monthly Income Band (Required) --}}
                                    <div class="form-group">
                                        <label for="income_band">Monthly Income Range</label>
                                        <select class="form-control" id="income_band" name="income_band" required
                                            data-parsley-required="true"
                                            data-parsley-required-message="Select your monthly income range">
                                            <option value="">Select income range</option>
                                            <option value="below_100k">Below {{ $currencySymbol }}100,000</option>
                                            <option value="100k_300k">{{ $currencySymbol }}100,000 –
                                                {{ $currencySymbol }}300,000
                                            </option>
                                            <option value="300k_700k">{{ $currencySymbol }}300,000 –
                                                {{ $currencySymbol }}700,000
                                            </option>
                                            <option value="above_700k">Above {{ $currencySymbol }}700,000</option>
                                        </select>
                                        <div class="invalid-feedback" id="error-income_band"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Purpose (Optional) --}}
                            <div class="form-group">
                                <label for="purpose">Purpose <span class="text-muted">(optional)</span></label>
                                <textarea class="form-control" id="purpose" name="purpose" rows="4" maxlength="2000"
                                    data-parsley-maxlength="2000" data-parsley-maxlength-message="Max 2000 characters"
                                    placeholder="Briefly explain why you need the loan..."></textarea>
                                <div class="invalid-feedback" id="error-purpose"></div>
                            </div>

                            {{-- Repayment Preview (UX) --}}
                            <div class="alert alert-info d-none" id="repaymentPreview">
                                <strong>Estimated Monthly Repayment:</strong>
                                <span id="monthlyRepayment">—</span>
                                <div class="small text-muted mt-1">
                                    This is an estimate. Final terms may change after review.
                                </div>
                            </div>
                            {{-- Consent (Required) --}}
                            <div class="form-group mt-3">
                                <div class="d-flex align-items-start gap-2 loan-consent">
                                    <input type="checkbox" class="form-check-input m-0 mt-1" id="loan_consent"
                                        name="loan_consent" required data-parsley-required="true"
                                        data-parsley-required-message="You must agree before submitting" />

                                    <label class="mb-0" for="loan_consent">
                                        I confirm that the information provided is accurate and I agree to the loan terms
                                        and repayment
                                        obligations.
                                    </label>
                                </div>

                                <div class="invalid-feedback d-block" id="error-loan_consent"></div>
                            </div>


                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('user.loans.index') }}" class="btn btn-light">Cancel</a>

                                <button type="submit" id="submitLoanBtn" class="btn btn-primary btn-icon-text"
                                    {{ !auth()->user()->kyc || auth()->user()->kyc->status !== 'approved' ? 'disabled' : '' }}>
                                    <i class="mdi mdi-send btn-icon-prepend"></i>
                                    <span id="btnText">Submit Application</span>
                                </button>
                            </div>
                        </form>



                    </div>
                </div>
            </div>

            {{-- Side info unchanged --}}
            <div class="col-lg-5 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">What happens next?</h4>
                        <ul class="list-arrow">
                            <li>Your application is recorded as <span class="badge badge-warning">SUBMITTED</span></li>
                            <li>Admin reviews and may request supporting documents</li>
                            <li>You'll receive email + in-app notification on every status change</li>
                            <li>If approved, disbursement and repayment schedule follow</li>
                        </ul>
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="mdi mdi-information-outline"></i> Tip: Keep your KYC updated to speed up approval.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Parsley
            let form = $('#loanForm');
            form.parsley();

            // Form submit handler
            form.on('submit', function(e) {
                e.preventDefault();

                // Reset previous errors
                $('.invalid-feedback').text('').hide();
                $('.form-control').removeClass('is-invalid');

                // Parsley client validation
                if (!form.parsley().validate()) {
                    form.parsley().validate(); // Trigger UI updates
                    return false;
                }

                // Submit button control
                let $btn = $('#submitLoanBtn');
                let $btnText = $('#btnText');
                let originalText = $btnText.text();

                $btn.prop('disabled', true);
                $btnText.text('Submitting...');

                // AJAX submit
                $.ajax({
                    url: "{{ route('user.loans.store') }}",
                    type: "POST",
                    data: form.serialize(),
                    success: function(response) {
                        // Success - redirect
                        window.location.href = "{{ route('user.loans.index') }}";
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false);
                        $btnText.text(originalText);

                        if (xhr.status === 422) {
                            // Laravel validation errors
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                let errorId = '#error-' + field.replace('.', '_');
                                $(errorId).text(messages[0]).show();
                                $('[name="' + field + '"]').addClass('is-invalid');
                            });
                        } else {
                            // Generic error
                            alert('Something went wrong. Please try again.');
                        }
                    }
                });
            });
        });
    </script>
@endpush
