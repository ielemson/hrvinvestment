@php
    $activeSliders = \App\Models\Slider::where('is_active', true)->orderBy('sort_order')->get();
@endphp

<section class="section swiper-slider_style-1"
    data-preset='{"title":"Slider 1","category":"sliders","reload":true,"id":"slider-1"}'>

    <div class="swiper-container swiper-slider swiper-slider_height-1" data-loop="true" data-autoplay="5000"
        data-simulate-touch="false" data-additional-slides="0" data-custom-prev="#swiper-prev"
        data-custom-next="#swiper-next" data-custom-slide-effect="interLeaveEffect">

        @if ($activeSliders->count())
            <div class="swiper-wrapper">
                @foreach ($activeSliders as $slide)
                    <div class="swiper-slide bg-gray-dark">
                        <div class="slide-inner"
                            style="background-image: url('{{ asset('storage/' . $slide->image) }}')">
                            <div class="swiper-slide-caption">
                                <div class="container">
                                    <h1 data-caption-animate="fadeInUpSmall">
                                        {!! $slide->title !!}
                                    </h1>

                                    @if ($slide->subtitle)
                                        <div class="object-decorated">
                                            <span class="object-decorated__divider"
                                                data-caption-animate="fadeInRightSmall" data-caption-delay="300"></span>
                                            <h4 data-caption-animate="fadeInRightSmall" data-caption-delay="550">
                                                {{ $slide->subtitle }}
                                            </h4>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="swiper-pagination"></div>
    </div>

    {{-- <div class="swiper-navigation swiper-navigation_modern">
        <div class="swiper-button-prev" id="swiper-prev"></div>
        <div class="swiper-button-next" id="swiper-next"></div>
    </div> --}}
</section>
