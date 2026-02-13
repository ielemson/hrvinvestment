@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['header' => 'Forgot Password'])

    <section class="section section-lg bg-default novi-background">
        <div class="container">
            <div class="layout-bordered">

                <!-- MAIN: Forgot Password -->
                <div class="layout-bordered__main text-center">
                    <div class="layout-bordered__main-inner">

                        <h3>Forgot Your Password?</h3>
                        <p>
                            No worries. Enter your registered email address and we’ll send you a secure password reset link.
                        </p>

                        {{-- Status Message --}}
                        @if (session('status'))
                            <div class="alert alert-success text-left mb-3">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" class="rd-form" id="forgotPasswordForm"
                            data-parsley-validate novalidate>
                            @csrf

                            {{-- Email --}}
                            <div class="form-wrap mb-3 text-left">
                                <label class="form-label-outside" for="email">
                                    E-mail Address
                                </label>

                                <input class="form-input @error('email') is-invalid @enderror" id="email" type="email"
                                    name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                    data-parsley-trigger="input" data-parsley-type="email"
                                    data-parsley-required-message="Please enter your email address."
                                    data-parsley-type-message="Please enter a valid email address." />

                                @error('email')
                                    <small class="text-danger d-block mt-1">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <button class="button button-primary button-winona w-100" type="submit">
                                Send Password Reset Link
                            </button>

                            <div class="text-center mt-3">
                                <a class="link-default" href="{{ route('login') }}">
                                    Back to Sign In
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
                @php

                    $contactEmail = $siteSettings->contact_email ?? 'info@hvrfinvestments.com';

                @endphp
                <!-- ASIDE: Help + Trust (unchanged, reused) -->
                <div class="layout-bordered__aside">

                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Need Help?</p>
                        <p class="small">
                            If you no longer have access to your email, contact our support team.
                        </p>
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
                                <span class="small">
                                    Password reset links expire automatically for your protection.
                                </span>
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
