@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['header' => 'Reset Password'])

    <section class="section section-lg bg-default novi-background">
        <div class="container">
            <div class="layout-bordered">

                @php
                    $contactEmail = $siteSettings->contact_email ?? 'info@hvrfinvestments.com';
                    $siteName = $siteSettings->site_name ?? config('app.name', 'HV Capitals');
                    $logo = $siteSettings->logo_path ?? 'assets/images/logo.png';
                    $logoMini = $siteSettings->logo_mini_path ?? $logo;
                @endphp

                <!-- MAIN: Reset Password -->
                <div class="layout-bordered__main text-center">
                    <div class="layout-bordered__main-inner">

                        {{-- Brand --}}
                        <div class="mb-4">
                            <a href="{{ route('home') }}" class="d-inline-block" aria-label="{{ $siteName }}">
                                <img src="{{ asset($logo) }}" alt="{{ $siteName }}" width="171" height="39"
                                    loading="lazy" srcset="{{ asset($logo) }} 2x" />
                            </a>
                        </div>

                        <h3>Reset Your Password</h3>
                        <p>Create a new password to regain access to your HV Capitals account.</p>

                        <form method="POST" action="{{ route('password.store') }}" class="rd-form" id="resetPasswordForm"
                            data-parsley-validate novalidate>
                            @csrf

                            {{-- Reset Token --}}
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            {{-- Email --}}
                            <div class="form-wrap mb-3 text-left">
                                <label class="form-label-outside" for="email">E-mail</label>
                                <input class="form-input @error('email') is-invalid @enderror" id="email" type="email"
                                    name="email" value="{{ old('email', $request->email) }}" required autofocus
                                    autocomplete="username" data-parsley-trigger="input" data-parsley-type="email"
                                    data-parsley-required-message="Please enter your email address."
                                    data-parsley-type-message="Please enter a valid email address." />

                                @error('email')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    {{-- New Password --}}
                                    <div class="form-wrap mb-3 text-left">
                                        <label class="form-label-outside" for="password">New Password</label>
                                        <input class="form-input @error('password') is-invalid @enderror" id="password"
                                            type="password" name="password" required autocomplete="new-password"
                                            data-parsley-trigger="input" data-parsley-minlength="8"
                                            data-parsley-required-message="Please enter a new password."
                                            data-parsley-minlength-message="Password must be at least 8 characters." />

                                        @error('password')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    {{-- Confirm Password --}}
                                    <div class="form-wrap mb-4 text-left">
                                        <label class="form-label-outside" for="password_confirmation">
                                            Confirm Password
                                        </label>
                                        <input class="form-input @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" type="password" name="password_confirmation" required
                                            autocomplete="new-password" data-parsley-trigger="input"
                                            data-parsley-equalto="#password"
                                            data-parsley-required-message="Please confirm your new password."
                                            data-parsley-equalto-message="Passwords do not match." />

                                        @error('password_confirmation')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button class="button button-primary button-winona w-100" type="submit">
                                Reset Password
                            </button>

                            <div class="text-center mt-3">
                                <a class="link-default" href="{{ route('login') }}">Back to login</a>
                            </div>

                            <div class="text-center mt-4">
                                <small class="text-muted">
                                    For your security, choose a strong password you haven’t used before.
                                </small>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ASIDE: Help + Trust -->
                <div class="layout-bordered__aside">
                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Need Help?</p>
                        <p class="small">If your reset link expired, request a new one or contact support.</p>
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
                                <span class="small">Reset links expire automatically for your protection.</span>
                            </div>
                        </div>
                    </div>

                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Tips</p>
                        <ul class="list-marked">
                            <li>Use 8+ characters</li>
                            <li>Mix letters and numbers</li>
                            <li>Avoid common passwords</li>
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
            const form = document.getElementById('resetPasswordForm');
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
