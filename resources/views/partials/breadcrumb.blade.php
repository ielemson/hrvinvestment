  <!-- Breadcrumbs-->
  {{-- <section class="breadcrumbs-custom bg-cover bg-image" style="background-image: url({{ asset('images/banner.jpg)') }};">
      <div class="container">
          <h2 class="breadcrumbs-custom__title">{{ $header ?? '' }}</h2>
          <ul class="breadcrumbs-custom__path">
              <li><a href="{{ route('home') }}">Home</a></li>
              <li class="active">{{ $header ?? '' }}</li>
          </ul>
      </div>
  </section> --}}


  <section class="section breadcrumbs-classic"
      data-preset='{"title":"Corporate breadcrumbs","category":"breadcrumbs","reload":false,"id":"corporate-breadcrumbs"}'>
      <div class="breadcrumbs-classic-main bg-gray-dark bg-image"
          style="background-image: url({{ asset('images/bg-image.jpg)') }};">
          <div class="container">
              <h3 class="breadcrumbs-classic-title">{{ $header ?? '' }}</h3>
          </div>
      </div>
      <div class="breadcrumbs-classic-aside novi-background">
          <div class="container">
              <ul class="breadcrumbs-classic-path">
                  <li><a href="{{ route('home') }}">Home</a></li>
                  <li class="active">{{ $header ?? '' }}</li>
              </ul>
          </div>
      </div>
  </section>
