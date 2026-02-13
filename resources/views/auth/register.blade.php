@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['header' => 'Register'])

    <section class="section section-lg bg-default novi-background">
        <div class="container">
            <div class="layout-bordered">
                <!-- MAIN: Register -->
                <div class="layout-bordered__main text-center">
                    <div class="layout-bordered__main-inner">
                        <h3>Create Your Account</h3>
                        <p>Join HV Capitals to access loans, investments, and savings in one secure platform.</p>

                        <form method="POST" action="{{ route('register') }}" class="rd-form" id="registerForm"
                            data-parsley-validate novalidate>
                            @csrf

                            {{-- Full Name --}}
                            <div class="form-wrap mb-3 text-left">
                                <label class="form-label-outside" for="name">Full Name</label>
                                <input class="form-input @error('name') is-invalid @enderror" id="name" type="text"
                                    name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                                    data-parsley-trigger="input"
                                    data-parsley-required-message="Please enter your full name." data-parsley-minlength="3"
                                    data-parsley-minlength-message="Name must be at least 3 characters." />

                                @error('name')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="form-wrap mb-3 text-left">
                                <label class="form-label-outside" for="email">E-mail</label>
                                <input class="form-input @error('email') is-invalid @enderror" id="email" type="email"
                                    name="email" value="{{ old('email') }}" required autocomplete="username"
                                    data-parsley-trigger="input" data-parsley-type="email"
                                    data-parsley-required-message="Please enter your email address."
                                    data-parsley-type-message="Please enter a valid email address." />

                                @error('email')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    {{-- Password --}}
                                    <div class="form-wrap mb-3 text-left">
                                        <label class="form-label-outside" for="password">Password</label>
                                        <input class="form-input @error('password') is-invalid @enderror" id="password"
                                            type="password" name="password" required autocomplete="new-password"
                                            data-parsley-trigger="input" data-parsley-minlength="8"
                                            data-parsley-required-message="Please create a password."
                                            data-parsley-minlength-message="Password must be at least 8 characters." />

                                        @error('password')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror

                                        {{-- <small class="text-muted d-block mt-1">
                                            Use at least 8 characters (letters + numbers recommended).
                                        </small> --}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    {{-- Confirm Password --}}
                                    <div class="form-wrap mb-4 text-left">
                                        <label class="form-label-outside" for="password_confirmation">Confirm
                                            Password</label>
                                        <input class="form-input @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" type="password" name="password_confirmation" required
                                            autocomplete="new-password" data-parsley-trigger="input"
                                            data-parsley-equalto="#password"
                                            data-parsley-required-message="Please confirm your password."
                                            data-parsley-equalto-message="Passwords do not match." />

                                        @error('password_confirmation')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button class="button button-primary button-winona w-100" type="submit">
                                Create Account
                            </button>

                            <div class="text-center mt-3">
                                <span class="text-muted">Already have an account?</span>
                                <a class="link-default" href="{{ route('login') }}">Sign in</a>
                            </div>

                            <div class="text-center mt-4">
                                <small class="text-muted">
                                    By creating an account, you agree to our
                                    <a href="" class="link-default">Terms</a>
                                    and
                                    <a href="" class="link-default">Privacy Policy</a>.
                                </small>
                            </div>
                        </form>
                    </div>
                </div>


                @php
                    // DB: site_settings fields
                    $siteName = $siteSettings->site_name ?? config('app.name', 'HV Capitals');

                    $logo = $siteSettings->logo_path ?? 'assets/images/logo.png';
                    $logoMini = $siteSettings->logo_mini_path ?? $logo;

                    $contactEmail = $siteSettings->contact_email ?? 'info@hvrfinvestments.com';
                    $contactPhone = $siteSettings->contact_phone ?? '+2348030001234';
                    $contactAddress = $siteSettings->contact_address ?? null;
                @endphp

                <!-- ASIDE: Help + Trust -->
                <div class="layout-bordered__aside">
                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Need Help?</p>
                        <p class="small">Having trouble creating your account? Reach our support team.</p>
                    </div>

                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Support</p>
                        <div class="unit flex-row unit-spacing-xxs">
                            <div class="unit-left">
                                <span class="icon novi-icon icon-sm icon-primary fas fa-envelope"></span>
                            </div>
                            <div class="unit-body">
                                <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Security</p>
                        <div class="unit flex-row unit-spacing-xxs">
                            <div class="unit-left">
                                <span class="icon novi-icon icon-sm icon-primary fl-bigmug-line-lock4"></span>
                            </div>
                            <div class="unit-body">
                                <span class="small">Your data is encrypted and protected at all times.</span>
                            </div>
                        </div>
                    </div>

                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Why HV Capitals?</p>
                        <ul class="list-marked">
                            <li>Simple onboarding</li>
                            <li>Clear terms &amp; pricing</li>
                            <li>Fast support response</li>
                        </ul>
                    </div>
                </div>
                <!-- /ASIDE -->
            </div>
        </div>
    </section>
@endsection

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            if (!form || !window.Parsley) return;

            const parsley = window.Parsley(form);

            // Non-AJAX: only prevent submit if invalid, otherwise submit normally.
            form.addEventListener('submit', function(e) {
                if (!parsley.isValid()) {
                    e.preventDefault();
                    parsley.validate();
                }
            });
        });
    </script>
@endPushOnce
