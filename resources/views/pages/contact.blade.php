@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['header' => 'Contact Us'])
    <!-- Get in Touch-->
    <section class="section section-lg bg-default novi-background">

        <div class="container">
            <div class="layout-bordered">
                <div class="layout-bordered__main text-center">
                    @include('partials.alerts')
                    <div class="layout-bordered__main-inner">
                        <h3>Get in Touch</h3>
                        <p>We are available 24/7 by fax, e-mail or by phone. You can also use our quick contact form to ask
                            a
                            question about our services and projects.</p>
                        <!-- RD Mailform-->

                        <form method="POST" action="{{ route('contact.send') }}" class="rd-form" id="contactForm"
                            data-parsley-validate novalidate>
                            @csrf

                            <div class="row row-10">

                                {{-- FIRST NAME --}}
                                <div class="col-md-6 wow-outer">
                                    <div class="form-wrap wow fadeSlideInUp">
                                        <label class="form-label-outside" for="contact-first-name">First Name</label>
                                        <input class="form-input @error('first_name') is-invalid @enderror"
                                            id="contact-first-name" type="text" name="first_name"
                                            value="{{ old('first_name') }}" required autocomplete="given-name"
                                            data-parsley-trigger="input" data-parsley-minlength="2"
                                            data-parsley-required-message="Please enter your first name."
                                            data-parsley-minlength-message="First name must be at least 2 characters." />

                                        @error('first_name')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- LAST NAME --}}
                                <div class="col-md-6 wow-outer">
                                    <div class="form-wrap wow fadeSlideInUp">
                                        <label class="form-label-outside" for="contact-last-name">Last Name</label>
                                        <input class="form-input @error('last_name') is-invalid @enderror"
                                            id="contact-last-name" type="text" name="last_name"
                                            value="{{ old('last_name') }}" required autocomplete="family-name"
                                            data-parsley-trigger="input" data-parsley-minlength="2"
                                            data-parsley-required-message="Please enter your last name."
                                            data-parsley-minlength-message="Last name must be at least 2 characters." />

                                        @error('last_name')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- EMAIL --}}
                                <div class="col-md-6 wow-outer">
                                    <div class="form-wrap wow fadeSlideInUp">
                                        <label class="form-label-outside" for="contact-email">E-mail</label>
                                        <input class="form-input @error('email') is-invalid @enderror" id="contact-email"
                                            type="email" name="email" value="{{ old('email') }}" required
                                            autocomplete="email" data-parsley-trigger="input" data-parsley-type="email"
                                            data-parsley-required-message="Please enter your email address."
                                            data-parsley-type-message="Please enter a valid email address." />

                                        @error('email')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- PHONE (optional) --}}
                                <div class="col-md-6 wow-outer">
                                    <div class="form-wrap wow fadeSlideInUp">
                                        <label class="form-label-outside" for="contact-phone">Phone (optional)</label>
                                        <input class="form-input @error('phone') is-invalid @enderror" id="contact-phone"
                                            type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                                            data-parsley-trigger="input" data-parsley-pattern="^[0-9+\s().-]{7,20}$"
                                            data-parsley-pattern-message="Please enter a valid phone number." />

                                        @error('phone')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- MESSAGE --}}
                                <div class="col-12 wow-outer">
                                    <div class="form-wrap wow fadeSlideInUp">
                                        <label class="form-label-outside" for="contact-message">Your Message</label>
                                        <textarea class="form-input @error('message') is-invalid @enderror" id="contact-message" name="message" rows="4"
                                            required data-parsley-trigger="input" data-parsley-minlength="10"
                                            data-parsley-required-message="Please type your message."
                                            data-parsley-minlength-message="Message must be at least 10 characters.">{{ old('message') }}</textarea>

                                        @error('message')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            {{-- ACTIONS --}}
                            <div class="group group-middle">
                                <div class="wow-outer">
                                    <button class="button button-primary button-winona wow slideInRight" type="submit">
                                        Send Message
                                    </button>
                                </div>

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
                <div class="layout-bordered__aside">

                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Phone</p>
                        <div class="unit flex-row unit-spacing-xxs">
                            <div class="unit-left"><span class="icon novi-icon icon-sm icon-primary fas fa-phone"></span>
                            </div>
                            <div class="unit-body"><a href="tel:{{ $contactPhone }}">{{ $contactPhone }}</a></div>
                        </div>
                    </div>
                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">E-mail</p>
                        <div class="unit flex-row unit-spacing-xxs">
                            <div class="unit-left"><span class="icon novi-icon icon-sm icon-primary fas fa-envelope"></span>
                            </div>
                            <div class="unit-body"><a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></div>
                        </div>
                    </div>
                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Address</p>
                        <div class="unit flex-row unit-spacing-xxs">
                            <div class="unit-left"><span
                                    class="icon novi-icon icon-sm icon-primary fas fa-map-marker"></span></div>
                            <div class="unit-body"><a href="#">{{ $contactAddress }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@pushOnce('scripts')
    <!-- Parsley (non-AJAX validation only) -->
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('contactForm');
            if (!form || !window.Parsley) return;

            const parsley = window.Parsley(form);

            // Manual submission (no AJAX): block only when invalid, otherwise submit normally.
            form.addEventListener('submit', function(e) {
                if (!parsley.isValid()) {
                    e.preventDefault();
                    parsley.validate();
                }
            });
        });
    </script>
@endPushOnce
