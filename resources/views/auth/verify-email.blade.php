@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['header' => 'Verify'])


    @php

        $contactEmail = $siteSettings->contact_email ?? 'info@hvrfinvestments.com';
        $contactPhone = $siteSettings->contact_phone ?? '';
        $logo = $siteSettings->logo_path ?? '';

    @endphp

    <section class="section section-lg bg-default novi-background">
        <div class="container">
            <div class="layout-bordered">

                {{-- MAIN: Verify Email --}}
                <div class="layout-bordered__main text-center">
                    <div class="layout-bordered__main-inner">

                        {{-- Status --}}
                        @if (session('status') === 'verification-link-sent')
                            <div class="alert alert-success mb-4" role="alert">
                                A fresh verification link has been sent to your email address.
                            </div>
                        @endif

                        {{-- Brand --}}
                        <div class="mb-4">
                            <a href="{{ route('home') }}" class="d-inline-block" aria-label="{{ config('app.name') }}">
                                <img src="{{ asset($logo) }}" alt="{{ config('app.name') }}" width="171" height="39"
                                    loading="lazy" />
                            </a>
                        </div>

                        <h3>Verify Your Email</h3>
                        <p>
                            Before you continue, please confirm your email address by clicking the link we sent to your
                            inbox.
                        </p>

                        <div class="alert alert-info text-left mb-4" role="alert">
                            <div class="d-flex align-items-start">
                                <span class="icon novi-icon icon-sm icon-primary fas fa-envelope mr-2"
                                    style="font-size:20px;line-height:1;"></span>

                                <div>
                                    <div class="mb-1">
                                        <strong>Sent to:</strong> {{ auth()->user()->email }}
                                    </div>
                                    <small class="d-block">
                                        Didn’t see it? Check your spam/junk folder or resend the email below.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row row-10">
                            <div class="col-md-6">
                                {{-- Resend --}}
                                <form method="POST" action="{{ route('verification.send') }}" class="m-0">
                                    @csrf
                                    <button class="button button-primary button-winona w-100" type="submit">
                                        Resend Email
                                    </button>
                                </form>
                            </div>

                            <div class="col-md-6">
                                {{-- Logout --}}
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button class="button button-secondary button-winona w-100" type="submit">
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <small class="text-muted">
                                If you used the wrong email address, sign out and register again with the correct one.
                            </small>
                        </div>

                    </div>
                </div>

                {{-- ASIDE: Help + Trust --}}
                <div class="layout-bordered__aside">

                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Need Help?</p>
                        <p class="small">
                            If you’re not receiving the email, our support team can assist.
                        </p>
                    </div>

                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Support</p>

                        <div class="unit flex-row unit-spacing-xxs mb-2">
                            <div class="unit-left">
                                <span class="icon novi-icon icon-sm icon-primary fas fa-envelope"></span>
                            </div>
                            <div class="unit-body">
                                <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                            </div>
                        </div>

                        <div class="unit flex-row unit-spacing-xxs">
                            <div class="unit-left">
                                <span class="icon novi-icon icon-sm icon-primary fas fa-phone"></span>
                            </div>
                            <div class="unit-body">
                                <a href="tel:{{ $contactPhone }}">{{ $contactPhone }}</a>
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
                                    Email verification helps protect your account and transactions.
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- /ASIDE --}}

            </div>
        </div>
    </section>
@endsection
