      @php
          // DB: site_settings fields
          $siteName = $siteSettings->site_name ?? config('app.name', 'Hello HV UK RF1 Investments');

          $logo = $siteSettings->logo_path ?? '';
          $logoMini = $siteSettings->logo_mini_path ?? $logo;

          $contactEmail = $siteSettings->contact_email ?? 'info@hvrfinvestments.com';
          $contactPhone = $siteSettings->contact_phone ?? '';
          $contactAddress = $siteSettings->contact_address ?? null;
      @endphp


      <header class="page-header section"
          data-preset='{"title":"Header Fullwidth","category":"headers","reload":true,"id":"header-fullwidth"}'>
          <!-- RD Navbar-->
          <div class="rd-navbar-wrap">
              <nav class="rd-navbar rd-navbar-creative" data-layout="rd-navbar-fixed" data-sm-layout="rd-navbar-fixed"
                  data-sm-device-layout="rd-navbar-fixed" data-md-layout="rd-navbar-fixed"
                  data-md-device-layout="rd-navbar-fixed" data-lg-device-layout="rd-navbar-fixed"
                  data-lg-layout="rd-navbar-fullwidth" data-xl-device-layout="rd-navbar-fullwidth"
                  data-xl-layout="rd-navbar-fullwidth" data-xxl-device-layout="rd-navbar-fullwidth"
                  data-xxl-layout="rd-navbar-fullwidth" data-stick-up-clone="false" data-md-stick-up-offset="150px"
                  data-lg-stick-up-offset="60px" data-md-stick-up="true" data-lg-stick-up="true">
                  <div class="rd-navbar-aside-outer rd-navbar-content-outer novi-background">
                      <div class="rd-navbar-content__toggle rd-navbar-fullwidth--hidden"
                          data-rd-navbar-toggle=".rd-navbar-content"><span></span></div>
                      <div class="rd-navbar-aside rd-navbar-content">
                          <div class="rd-navbar-aside__item">
                              <ul class="rd-navbar-items-list">
                                  <li>
                                      <div class="unit unit-spacing-xxs flex-row">
                                          <div class="unit-left">
                                              <div class="unit-left"><span class="text-primary">Location:</span></div>
                                          </div>
                                          <div class="unit-body">
                                              @if (!empty($contactAddress))
                                                  <span>{{ \Illuminate\Support\Str::limit($contactAddress, 50) }}</span>
                                              @endif
                                          </div>
                                      </div>
                                  </li>

                              </ul>
                          </div>
                          <div class="rd-navbar-aside__item">
                              <ul class="rd-navbar-items-list">
                                  <li>
                                      <div class="unit unit-spacing-xxs flex-row">
                                          <div class="unit-left"><span class="text-primary">Email:</span></div>
                                          <div class="unit-body">
                                              <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                                          </div>
                                      </div>
                                  </li>

                                  <li>
                                      <div class="unit unit-spacing-xxs flex-row">
                                          <div class="unit-left"><span class="text-primary">Phone:</span></div>
                                          <div class="unit-body">
                                              <a
                                                  href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}">{{ $contactPhone }}</a>
                                          </div>
                                      </div>
                                  </li>
                              </ul>
                          </div>
                      </div>
                  </div>


                  <div class="rd-navbar-main-outer novi-background">
                      <div class="rd-navbar-main">

                          <!-- Panel -->
                          <div class="rd-navbar-panel">
                              <button class="rd-navbar-toggle" type="button" aria-label="Toggle navigation"
                                  data-rd-navbar-toggle=".rd-navbar-nav-wrap">
                                  <span></span>
                              </button>

                              <!-- Brand (dynamic from site_settings) -->
                              {{-- <div class="rd-navbar-brand">
                                  <a class="brand" href="{{ route('home') }}" aria-label="{{ $siteName }} Home">
                                   
                                      <img class="brand-logo-dark" src="{{ asset($logo) }}" alt="{{ $siteName }}"
                                          width="171" height="39" loading="lazy"
                                          srcset="{{ asset($logo) }} 2x" />

                                      
                                      <img class="brand-logo-light" src="{{ asset($logoMini) }}"
                                          alt="{{ $siteName }}" width="171" height="39" loading="lazy"
                                          srcset="{{ asset($logoMini) }} 2x" />
                                  </a>
                              </div> --}}
                              <div class="rd-navbar-brand">
                                  <a class="brand" href="{{ route('home') }}" aria-label="Home">
                                      <img class="brand-logo-dark" src="{{ asset($logo) }}" alt="Nordan Industries"
                                          width="141" height="46" srcset="{{ asset($logo) }} 2x" />
                                      <img class="brand-logo-light" src="{{ asset($logo) }}" alt="Nordan Industries"
                                          width="141" height="46" srcset="{{ asset($logo) }} 2x" />
                                  </a>
                              </div>
                          </div>

                          <!-- Nav -->
                          <div class="rd-navbar-nav-wrap">

                              <!-- CTA -->
                              <div class="rd-navbar-main-item">
                                  <a class="button button-xs button-primary" href="{{ route('contact') }}">Contact
                                      Us</a>
                              </div>

                              <ul class="rd-navbar-nav">

                                  <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                                      <a href="{{ route('home') }}">Home</a>
                                  </li>

                                  <li class="{{ request()->routeIs('about') ? 'active' : '' }}">
                                      <a href="{{ route('about') }}">About</a>
                                  </li>

                                  {{-- <li class="{{ request()->routeIs('services.*') ? 'active' : '' }}">
                                <a href="{{ route('services.index') }}">Services</a>
                            </li> --}}
                                  @if (!empty($services) && count($services))
                                      <li>
                                          <a href="#">Services</a>
                                          <ul class="rd-navbar-dropdown">
                                              @foreach ($services as $service)
                                                  <li>
                                                      <a href="{{ route('services.show', $service->slug) }}">
                                                          {{ $service->title }}
                                                      </a>
                                                  </li>
                                              @endforeach
                                          </ul>
                                      </li>
                                  @endif

                                  <li class="{{ request()->routeIs('how-it-works') ? 'active' : '' }}">
                                      <a href="{{ route('how-it-works') }}">How It Works</a>
                                  </li>

                                  <li class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                                      <a href="{{ route('contact') }}">Contact</a>
                                  </li>

                                  <!-- Account -->
                                  <li
                                      class="{{ request()->routeIs('login', 'register', 'admin.*', 'user.*') ? 'active' : '' }}">
                                      <a href="#">Account</a>
                                      <ul class="rd-navbar-dropdown">

                                          @guest
                                              <li class="{{ request()->routeIs('login') ? 'active' : '' }}">
                                                  <a href="{{ route('login') }}">Login</a>
                                              </li>
                                              <li class="{{ request()->routeIs('register') ? 'active' : '' }}">
                                                  <a href="{{ route('register') }}">Sign Up</a>
                                              </li>
                                          @else
                                              @role('admin')
                                                  <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                                      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                                  </li>
                                                  @elserole('user')
                                                  <li class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                                                      <a href="{{ route('user.dashboard') }}">Dashboard</a>
                                                  </li>
                                              @endrole

                                              <li>
                                                  {{-- Logout Trigger --}}
                                                  <a href="{{ route('logout') }}" class="rd-nav-link ajax-logout"
                                                      onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                      Logout
                                                  </a>

                                                  {{-- Hidden Logout Form --}}
                                                  <form id="logout-form" method="POST" action="{{ route('logout') }}"
                                                      class="d-none">
                                                      @csrf
                                                  </form>
                                              </li>
                                          @endguest

                                      </ul>
                                  </li>

                              </ul>
                          </div>
                      </div>
                  </div>
              </nav>
          </div>
      </header>
      @push('scripts')
          <script>
              document.addEventListener('DOMContentLoaded', () => {
                  const btn = document.querySelector('.ajax-logout');
                  if (!btn) return;

                  btn.addEventListener('click', async (e) => {
                      e.preventDefault();

                      const url = btn.dataset.url;
                      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                          'content');

                      try {
                          const res = await fetch(url, {
                              method: 'POST',
                              headers: {
                                  'X-CSRF-TOKEN': token,
                                  'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                                  'Accept': 'text/html,application/xhtml+xml'
                              },
                              credentials: 'same-origin',
                              body: '_token=' + encodeURIComponent(token)
                          });

                          // Laravel often returns 302 redirect after logout
                          if (res.redirected) {
                              window.location.href = res.url;
                              return;
                          }

                          // fallback
                          window.location.href = '/';
                      } catch (err) {
                          console.error(err);
                          window.location.href = '/';
                      }
                  });
              });
          </script>
      @endpush

      @push('styles')
          <style>
              .brand-logo-dar {
                  width: 282px;
                  height: 92px;
                  object-fit: contain;
              }
          </style>
      @endpush
