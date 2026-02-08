@extends('layouts.app')
@section('content')
    @include('partials.breadcrumb', ['header' => $service->title])

    {{-- services/show.blade.php --}}

    <!-- Single post-->
    <section class="section section-md bg-default novi-background"
        data-preset='{"title":"Post","category":"blog","reload":true,"id":"post"}'>
        <div class="container blog">
            <div class="row row-90">
                <div class="col-lg-8 col-xl-9 blog__main">
                    <article class="post-single"><img class="post-single__image"
                            src="{{ asset('storage/' . $service->image) }}" alt="" />
                        <h4 class="post-single__title">{{ $service->title }}</h4>
                        <div class="post-single__main">
                            <p>
                                {!! $service->description !!}
                            </p>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-xl-3 blog__aside">
                    <div class="blog-aside__column">
                        <div class="blog__aside-item">
                            <p class="heading-8 blog-title">More Services</p>
                            <div class="post-inline-wrap">
                                @if (count($relatedServices) > 0)
                                    @foreach ($relatedServices as $relatedService)
                                        <!-- Post inline-->
                                        <article class="post-inline">
                                            <div class="post-inline__main">
                                                <p class="post-inline__title"><a
                                                        href="{{ route('services.show', $relatedService->slug) }}">
                                                        {{ $relatedService->title }}
                                                    </a>
                                                </p>
                                            </div>
                                        </article>
                                        <!-- Post inline-->
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
