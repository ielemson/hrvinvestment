@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['header' => 'Login'])

    <section class="section section-lg bg-default novi-background">
        <div class="container">
            <div class="layout-bordered">
                <!-- MAIN: Login -->
                <div class="layout-bordered__main text-center">
                    <div class="layout-bordered__main-inner">
                        <h3>Welcome Back</h3>
                        <p>Sign in to HV Capitals to manage your loans, investments, and savings securely.</p>

                        <form method="POST" action="{{ route('login') }}" class="rd-form" id="loginForm" data-parsley-validate
                            novalidate>
                            @csrf

                            <div class="form-wrap mb-3 text-left">
                                <label class="form-label-outside" for="email">E-mail</label>
                                <input class="form-input @error('email') is-invalid @enderror" id="email" type="email"
                                    name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                    data-parsley-trigger="input" data-parsley-type="email"
                                    data-parsley-required-message="Please enter your email address."
                                    data-parsley-type-message="Please enter a valid email address." />

                                @error('email')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-wrap mb-3 text-left">
                                <label class="form-label-outside" for="password">Password</label>
                                <input class="form-input @error('password') is-invalid @enderror" id="password"
                                    type="password" name="password" required autocomplete="current-password"
                                    data-parsley-trigger="input" data-parsley-minlength="6"
                                    data-parsley-required-message="Please enter your password."
                                    data-parsley-minlength-message="Password must be at least 6 characters." />

                                @error('password')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <label class="checkbox-inline m-0">
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    Remember me
                                </label>

                                @if (Route::has('password.request'))
                                    <a class="link-default" href="{{ route('password.request') }}">Forgot password?</a>
                                @endif
                            </div>

                            <button class="button button-primary button-winona w-100" type="submit">
                                Sign in
                            </button>

                            <div class="text-center mt-3">
                                <span class="text-muted">New here?</span>
                                @if (Route::has('register'))
                                    <a class="link-default" href="{{ route('register') }}">Create an account</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                @php
                    // DB: site_settings fields
                    $siteName = $siteSettings->site_name ?? config('app.name', 'Hello HV UK RF1 Investments');

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
                        <p class="small">If you can’t sign in, reach our support team.</p>
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
                                <span class="small">Encrypted connection &amp; protected sessions.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /ASIDE -->
            </div>
        </div>
    </section>

    @pushOnce('scripts')
        <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('loginForm');
                if (!form) return;

                // Initialize Parsley
                const parsley = window.Parsley ? window.Parsley(form) : null;

                // Ensure normal submit (no AJAX). Parsley only prevents submit when invalid.
                form.addEventListener('submit', function(e) {
                    if (!parsley) return; // if parsley isn't loaded, allow normal submit
                    if (!parsley.isValid()) {
                        e.preventDefault();
                        parsley.validate();
                    }
                });
            });
        </script>
    @endPushOnce
@endsection
