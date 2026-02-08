@props([
    'subtitle' => '',
    'title' => '',
    'bg' => 'images/breadcrumbs-image-1.jpg',
    'breadcrumbs' => [], // [['label' => 'Home', 'url' => route('home')], ['label' => 'Contacts']]
])

<section class="breadcrumbs-custom bg-image context-dark" style="background-image: url({{ asset($bg) }});">
    <div class="breadcrumbs-custom-inner">
        <div class="container breadcrumbs-custom-container">
            <div class="breadcrumbs-custom-main">
                @if ($subtitle)
                    <h6 class="breadcrumbs-custom-subtitle title-decorated">{{ $subtitle }}</h6>
                @endif
                <h1 class="breadcrumbs-custom-title">{{ $title }}</h1>
            </div>

            @if (!empty($breadcrumbs))
                <ul class="breadcrumbs-custom-path">
                    @foreach ($breadcrumbs as $crumb)
                        @if (isset($crumb['url']))
                            <li><a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a></li>
                        @else
                            <li class="active">{{ $crumb['label'] }}</li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</section>
