@extends('layouts.app')


@section('content')
    @include('partials.breadcrumb', ['header' => 'Our Services'])
    <section class="section section-md bg-default novi-background">
        <div class="container">

            {{-- Intro --}}
            <div class="row justify-content-center text-center mb-4">
                <div class="col-md-10 col-lg-8">
                    <h2>Our Services</h2>
                    <p class="text-muted">
                        We provide structured, transparent, and customer-friendly solutions designed to help you achieve
                        your goals.
                    </p>
                </div>
            </div>

            @if ($services->count())
                <div class="row row-30">
                    @foreach ($services as $service)
                        @php
                            $title = $service->name ?? ($service->title ?? 'Service');
                            $summary =
                                $service->short_description ??
                                ($service->description ?? 'Service description will be provided here.');
                            $icon = $service->icon_class ?? 'mdi mdi-briefcase-outline';

                            // ✅ Your image path rule
                            $imageUrl = !empty($service->image)
                                ? asset('storage/' . $service->image)
                                : asset('assets/images/placeholders/service-default.jpg');
                        @endphp

                        <div class="col-sm-6 col-lg-4">
                            <div class="card shadow-sm h-100 border-0 service-card">

                                {{-- Image --}}
                                <div class="service-card__image-wrap">
                                    <img src="{{ $imageUrl }}" alt="{{ $title }}" class="service-card__image">
                                </div>

                                <div class="card-body p-4 d-flex flex-column">

                                    {{-- Icon + Title --}}
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="mr-3">
                                            <span class="icon icon-lg text-primary">
                                                <i class="{{ $icon }}"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $title }}</h5>
                                            <small class="text-muted">Professional Service</small>
                                        </div>
                                    </div>

                                    {{-- Summary --}}
                                    <p class="text-muted mb-4">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($summary), 130) }}
                                    </p>

                                    {{-- CTA --}}
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <a class="badge badge-light px-3 py-2"
                                            href="{{ route('services.show', $service->slug) }}">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            </i>Read More
                                        </a>

                                        <a href="{{ route('contact') ?? '#' }}" class="btn btn-sm btn-primary">
                                            Contact Us <i class="fas fa-arrow-right ml-1"></i>

                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="alert alert-light border text-center p-4">
                            <i class="mdi mdi-information-outline mdi-2x d-block mb-2"></i>
                            <h6 class="mb-1">No services available yet</h6>
                            <p class="text-muted mb-0">Please check back later or contact us for enquiries.</p>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection
