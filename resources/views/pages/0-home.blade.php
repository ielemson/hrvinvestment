@extends('layouts.app')

@section('header')
    @include('partials.corporate')
@endsection

@section('title', 'HV Capitals')

@section('content')

    {{-- Slider --}}
    @include('pages.home.sections.slider')

    {{-- About --}}
    @include('pages.home.sections.about_section')

    {{-- Small Features --}}
    @include('pages.home.sections.features')

    {{-- Thin CTA --}}
    @include('pages.home.sections.cta-thin')

    {{-- Services --}}
    @include('pages.home.sections.services')

    {{-- Contact / Map --}}
    @include('pages.home.sections.contact')

    {{-- Contact Info --}}
    @include('pages.home.sections.contact-info')

@endsection
