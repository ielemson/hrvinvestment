@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['header' => 'Insights'])

    {{-- ===================== INSIGHTS INTRO ===================== --}}
    <section id="insights-section" class="section section-md bg-default novi-background"
        data-preset='{"title":"Insights","category":"content-boxes","reload":true,"id":"insights"}'>
        <div class="container container-wide">
            <div class="row justify-content-lg-center">
                <div class="col-xl-11">
                    <div class="section__header">
                        <h2>Insights</h2>
                        <div class="section__header-element">
                            <span class="link link-md">Case Studies & Industry News</span>
                        </div>
                    </div>

                    <p class="text-muted mt-2">
                        Explore selected partnerships, client success stories, and curated market news relevant to
                        investment, project development, and global business opportunities.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CASE STUDIES ===================== --}}
    @if ($caseStudies->isNotEmpty())
        <section id="case-studies" class="section section-md bg-default novi-background">
            <div class="container">
                <div class="row justify-content-lg-center mb-4">
                    <div class="col-xl-11">
                        <div class="section__header">
                            <h2>Case Studies</h2>
                            <div class="section__header-element">
                                <span class="link link-md">Selected Partnerships</span>
                            </div>
                        </div>

                        <p class="text-muted mt-2">
                            A snapshot of businesses and institutions connected to our work, partnerships, and strategic
                            engagement history.
                        </p>
                    </div>
                </div>

                <div class="row">
                    @foreach ($caseStudies as $item)
                        @php
                            $img = $item->image
                                ? asset('storage/' . $item->image)
                                : asset('assets/front/images/service-placeholder-639x524.jpg');
                        @endphp

                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="card svc-card h-100 border-0 shadow-sm">
                                <div class="svc-card__media">
                                    <img src="{{ $img }}" alt="{{ $item->title }}" loading="lazy">
                                </div>

                                <div class="card-body">
                                    <h6 class="svc-card__title mb-2">{{ $item->title }}</h6>

                                    @if (!empty($item->content))
                                        <p class="svc-card__text mb-0">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($item->content), 110) }}
                                        </p>
                                    @endif
                                </div>

                                <div class="card-footer bg-transparent border-0 pt-0">
                                    @if (!empty($item->website_url))
                                        <a href="{{ $item->website_url }}" target="_blank" rel="noopener noreferrer"
                                            class="svc-card__cta">
                                            Visit Website →
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== NEWS ===================== --}}
    @if ($news->count())
        <section id="news" class="section section-md bg-gray-4 novi-background">
            <div class="container">
                <div class="row justify-content-lg-center mb-4">
                    <div class="col-xl-11">
                        <div class="section__header">
                            <h2>Latest News</h2>
                            <div class="section__header-element">
                                <span class="link link-md">Curated from trusted sources</span>
                            </div>
                        </div>

                        <p class="text-muted mt-2">
                            Freshly curated international and industry-relevant headlines from external publishers.
                        </p>
                    </div>
                </div>

                <div class="row">
                    @foreach ($news as $item)
                        @php
                            $img = $item->image_url ?: asset('assets/front/images/service-placeholder-639x524.jpg');
                        @endphp

                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <a href="{{ $item->source_url }}" target="_blank" rel="noopener noreferrer"
                                class="svc-card-link d-block h-100">
                                <div class="card svc-card h-100 border-0 shadow-sm">
                                    <div class="svc-card__media">
                                        <img src="{{ $img }}" alt="{{ $item->title }}" loading="lazy">
                                    </div>

                                    <div class="card-body">
                                        <div class="mb-2 small text-muted">
                                            {{ $item->source_name ?? 'External Source' }}
                                            @if ($item->published_at)
                                                • {{ $item->published_at->format('M d, Y') }}
                                            @endif
                                        </div>

                                        <h6 class="svc-card__title mb-2">{{ $item->title }}</h6>

                                        @if (!empty($item->summary))
                                            <p class="svc-card__text mb-0">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($item->summary), 110) }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="card-footer bg-transparent border-0 pt-0">
                                        <span class="svc-card__cta">Read from source →</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- ✅ FIXED PAGINATION --}}
                @if (method_exists($news, 'links'))
                    <div class="mt-4 d-flex justify-content-center insights-pagination-wrap">
                        {{ $news->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- ===================== EMPTY STATE ===================== --}}
    @if ($caseStudies->isEmpty() && $news->count() === 0)
        <section class="section section-md bg-default novi-background">
            <div class="container">
                <div class="alert alert-light border text-center">
                    Insights will be available soon.
                </div>
            </div>
        </section>
    @endif
@endsection

@push('styles')
    <style>
        .svc-card-link {
            text-decoration: none;
            color: inherit;
        }

        .svc-card {
            border-radius: 14px;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
            background: #fff;
        }

        .svc-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 35px rgba(0, 0, 0, 0.10) !important;
        }

        .svc-card__media {
            height: 240px;
            overflow: hidden;
            background: #f8f8f8;
        }

        .svc-card__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .svc-card__title {
            font-size: 18px;
            line-height: 1.35;
            font-weight: 600;
            color: #111;
        }

        .svc-card__text {
            font-size: 14px;
            line-height: 1.7;
            color: #6c757d;
        }

        .svc-card__cta {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary, #0d6efd);
        }

        /* ✅ Pagination Fix */
        .insights-pagination-wrap .pagination {
            margin-bottom: 0;
        }

        .insights-pagination-wrap .page-link {
            border-radius: 6px;
            margin: 0 3px;
        }

        .insights-pagination-wrap .page-item.active .page-link {
            z-index: 1;
        }
    </style>
@endpush