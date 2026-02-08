<section class="section-lg text-center">
    <div class="container">
        <h3 class="wow-outer"><span class="wow slideInUp">Services</span></h3>

        <p class="wow-outer">
            <span class="wow slideInDown text-width-1">
                {{ $servicesIntro ?? 'We offer practical investment and funding solutions designed to help you grow wealth, access credit, and manage risk no matter your financial goal.' }}
            </span>
        </p>

        <div class="row row-30 offset-top-2">
            @foreach ($services as $index => $service)
                @php
                    $delay = $index === 0 ? null : number_format($index * 0.05, 2); // .05, .10, .15...
                    $href =
                        $service->cta_url ?:
                        (Route::has('services.show')
                            ? route('services.show', $service->slug)
                            : route('services.index'));

                    $img = $service->image
                        ? asset('storage/' . $service->image)
                        : asset('assets/front/images/service-placeholder-270x300.jpg');
                @endphp

                <div class="col-sm-6 col-lg-3 wow-outer">
                    <!-- Thumbnail Light-->
                    <article class="thumbnail-light wow slideInLeft"
                        @if ($delay) data-wow-delay="{{ $delay }}s" @endif>
                        <a class="thumbnail-light-media" href="{{ $href }}">
                            <img class="thumbnail-light-image" src="{{ $img }}" alt="{{ $service->title }}"
                                width="270" height="300" loading="lazy" />
                        </a>

                        <h5 class="thumbnail-light-title">
                            <a href="{{ $href }}">{{ $service->title }}</a>
                        </h5>
                    </article>
                </div>
            @endforeach
        </div>
    </div>

    <div class="wow-outer button-outer">
        <a class="button button-primary-outline button-winona offset-top-2 wow slideInUp"
            href="{{ route('services.index') }}">
            View all services
        </a>
    </div>
</section>
