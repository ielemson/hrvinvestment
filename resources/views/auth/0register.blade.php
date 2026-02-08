@extends('layouts.app')

{{-- Use minimal header for auth pages --}}
@section('header_variant', 'minimal')

@section('title', 'Create Account - HV Capitals')

@section('banner')
    @include('partials.page-banner', [
        'subtitle' => 'Account',
        'title' => 'Create Account',
        'bg' => 'assets/images/breadcrumbs-image-1.jpg',
        'breadcrumbs' => [['label' => 'Home', 'url' => route('home')], ['label' => 'Register']],
    ])
@endsection

@section('content')
    <section class="section section-lg bg-gray-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-10 col-md-8 col-lg-6">

                    <div class="card shadow-sm">
                        <div class="card-body p-4 p-md-5">

                            <div class="text-center mb-4">
                                <a href="{{ route('home') }}" class="d-inline-block mb-3">
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="HV Capitals" width="171"
                                        height="39">
                                </a>
                                <h3 class="mb-1">Create your account</h3>
                                <p class="text-muted mb-0">Get started with HV Capitals in minutes.</p>
                            </div>

                            <form method="POST" action="{{ route('register') }}" class="rd-form">
                                @csrf

                                {{-- Full Name --}}
                                <div class="form-wrap mb-3">
                                    <label class="form-label-outside" for="name">Full Name</label>
                                    <input class="form-input @error('name') is-invalid @enderror" id="name"
                                        type="text" name="name" value="{{ old('name') }}" required autofocus
                                        autocomplete="name">

                                    @error('name')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="form-wrap mb-3">
                                    <label class="form-label-outside" for="email">E-mail</label>
                                    <input class="form-input @error('email') is-invalid @enderror" id="email"
                                        type="email" name="email" value="{{ old('email') }}" required
                                        autocomplete="username">

                                    @error('email')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- Password --}}
                                        <div class="form-wrap mb-3">
                                            <label class="form-label-outside" for="password">Password</label>
                                            <input class="form-input @error('password') is-invalid @enderror" id="password"
                                                type="password" name="password" required autocomplete="new-password">

                                            @error('password')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {{-- Confirm Password --}}
                                        <div class="form-wrap mb-4">
                                            <label class="form-label-outside" for="password_confirmation">
                                                Confirm
                                            </label>
                                            <input class="form-input @error('password_confirmation') is-invalid @enderror"
                                                id="password_confirmation" type="password" name="password_confirmation"
                                                required autocomplete="new-password">

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
                            </form>

                            {{-- Compliance / security note --}}
                            <div class="text-center mt-4">
                                <small class="text-muted">
                                    By signing up, you agree to our
                                    <a href="javascript:;" class="link-default">Terms</a> and
                                    <a href="javascript:;" class="link-default">Privacy Policy</a>.
                                </small>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
