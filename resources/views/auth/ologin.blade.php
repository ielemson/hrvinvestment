@extends('layouts.app')

{{-- Use the minimal header for auth pages --}}
@section('header_variant', 'minimal')

@section('title', 'Login - HV Capitals')

@section('banner')
    @include('partials.page-banner', [
        'subtitle' => 'Account',
        'title' => 'Login',
        'bg' => 'assets/images/breadcrumbs-image-1.jpg',
        'breadcrumbs' => [['label' => 'Home', 'url' => route('home')], ['label' => 'Login']],
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
                                <h3 class="mb-1">Welcome back</h3>
                                <p class="text-muted mb-0">Sign in to continue to your dashboard.</p>
                            </div>

                            <form method="POST" action="{{ route('login') }}" class="rd-form">
                                @csrf

                                <div class="form-wrap mb-3">
                                    <label class="form-label-outside" for="email">E-mail</label>
                                    <input class="form-input @error('email') is-invalid @enderror" id="email"
                                        type="email" name="email" value="{{ old('email') }}" required autofocus
                                        autocomplete="username">

                                    @error('email')
                                        <small class="text-danger d-block mt-1">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>


                                <div class="form-wrap mb-3">
                                    <label class="form-label-outside" for="password">Password</label>
                                    <input class="form-input @error('password') is-invalid @enderror" id="password"
                                        type="password" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <small class="text-danger d-block mt-1">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>


                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        Remember me
                                    </label>

                                    @if (Route::has('password.request'))
                                        <a class="link-default" href="{{ route('password.request') }}">
                                            Forgot password?
                                        </a>
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

                            {{-- Optional: small security note for Week 1 --}}
                            <div class="text-center mt-4">
                                <small class="text-muted">
                                    For your security, we support device/session control and 2FA on sensitive actions.
                                </small>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
