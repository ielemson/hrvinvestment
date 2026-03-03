@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['header' => 'Confirm Password'])

    <section class="section section-lg bg-default novi-background">
        <div class="container">
            <div class="layout-bordered">

                <!-- MAIN: Confirm Password -->
                <div class="layout-bordered__main text-center">
                    <div class="layout-bordered__main-inner">

                        <h3>Confirm Your Password</h3>
                        <p>
                            This is a secure area. Please confirm your password to continue.
                        </p>

                        <form method="POST" action="{{ route('password.confirm') }}" class="rd-form" id="confirmPasswordForm"
                            data-parsley-validate novalidate>
                            @csrf

                            {{-- Password --}}
                            <div class="form-wrap mb-3 text-left">
                                <label class="form-label-outside" for="password">
                                    Password
                                </label>

                                <input class="form-input @error('password') is-invalid @enderror" id="password"
                                    type="password" name="password" required autocomplete="current-password"
                                    data-parsley-trigger="input"
                                    data-parsley-required-message="Please enter your password." />

                                @error('password')
                                    <small class="text-danger d-block mt-1">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <button class="button button-primary button-winona w-100" type="submit">
                                Confirm
                            </button>

                            @if (Route::has('password.request'))
                                <div class="text-center mt-3">
                                    <a class="link-default" href="{{ route('password.request') }}">
                                        Forgot your password?
                                    </a>
                                </div>
                            @endif

                        </form>
                    </div>
                </div>

                @php
                    $contactEmail = $siteSettings->contact_email ?? 'info@hvrfinvestments.com';
                @endphp

                <!-- ASIDE: Help + Trust -->
                <div class="layout-bordered__aside">

                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Need Help?</p>
                        <p class="small">
                            If you're unable to confirm your password, you can reset it or contact support.
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
                                    Confirming your password helps keep your account protected.
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
                const form = document.getElementById('confirmPasswordForm');
                if (!form) return;

                const parsley = window.Parsley ? window.Parsley(form) : null;

                form.addEventListener('submit', function(e) {
                    if (!parsley) return;
                    if (!parsley.isValid()) {
                        e.preventDefault();
                        parsley.validate();
                    }
                });
            });
        </script>
    @endPushOnce
@endsection
