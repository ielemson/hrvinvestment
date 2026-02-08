<header class="section page-header">
    <div class="rd-navbar-wrap">
        <nav class="rd-navbar rd-navbar-corporate" data-layout="rd-navbar-fixed" data-sm-layout="rd-navbar-fixed"
            data-md-layout="rd-navbar-fixed" data-md-device-layout="rd-navbar-fixed" data-lg-layout="rd-navbar-static"
            data-lg-device-layout="rd-navbar-fixed" data-xl-layout="rd-navbar-static"
            data-xl-device-layout="rd-navbar-fixed" data-lg-stick-up-offset="46px" data-xl-stick-up-offset="46px"
            data-xxl-stick-up-offset="46px" data-lg-stick-up="true" data-xl-stick-up="true" data-xxl-stick-up="true">

            <div class="rd-navbar-aside-outer">
                <div class="rd-navbar-aside">
                    <div class="rd-navbar-panel">
                        <button class="rd-navbar-toggle"
                            data-rd-navbar-toggle="#rd-navbar-nav-wrap-1"><span></span></button>

                        <a class="rd-navbar-brand" href="{{ route('home') }}">
                            @php
                                $logo = $siteSettings->logo_path ?? 'assets/images/logo-342.png';
                                $siteName = $siteSettings->site_name ?? 'HV Capitals';
                            @endphp

                            <img src="{{ asset($logo) }}" alt="{{ $siteName }}" class="navbar-logo" loading="lazy"
                                width="50" height="50" srcset="{{ asset($logo) }}">
                        </a>
                    </div>

                    <div class="rd-navbar-collapse">
                        <button class="rd-navbar-collapse-toggle rd-navbar-fixed-element-1"
                            data-rd-navbar-toggle="#rd-navbar-collapse-content-1"><span></span></button>

                        <div class="rd-navbar-collapse-content" id="rd-navbar-collapse-content-1">

                            {{-- PHONE --}}
                            @php
                                // allow multiple numbers separated by comma: "08012345678, 08098765432"
                                $phonesRaw = $siteSettings->contact_phone ?? '';
                                $phones = collect(explode(',', $phonesRaw))
                                    ->map(fn($p) => trim($p))
                                    ->filter()
                                    ->values();

                                $primaryPhone = $phones->first();
                                $telPrimary = $primaryPhone ? preg_replace('/\s+/', '', $primaryPhone) : null;
                            @endphp

                            <article class="unit align-items-center">
                                <div class="unit-left">
                                    <span class="icon icon-md icon-modern mdi mdi-phone"></span>
                                </div>
                                <div class="unit-body">
                                    <ul class="list-0">
                                        @forelse($phones as $phone)
                                            @php $tel = preg_replace('/\s+/', '', $phone); @endphp
                                            <li>
                                                <a class="link-default" href="{{ $tel ? 'tel:' . $tel : '#' }}">
                                                    {{ $phone }}
                                                </a>
                                            </li>
                                        @empty
                                            <li>
                                                <a class="link-default" href="#">
                                                    {{ __('Phone not set') }}
                                                </a>
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </article>

                            {{-- ADDRESS --}}
                            <article class="unit align-items-center">
                                <div class="unit-left">
                                    <span class="icon icon-md icon-modern mdi mdi-map-marker"></span>
                                </div>
                                <div class="unit-body">
                                    @php
                                        // Best: add a column like `contact_address` to site_settings.
                                        // For now: use meta_description as fallback if you haven't added address yet.
$address =
    $siteSettings->contact_address ?? ($siteSettings->meta_description ?? '');
                                    @endphp

                                    <a class="link-default" href="#">
                                        {!! nl2br(e(\Illuminate\Support\Str::limit($address, 40))) !!}

                                    </a>
                                </div>
                            </article>

                            {{-- REQUEST A CALL --}}
                            <a class="button button-primary-outline button-winona"
                                href="{{ $telPrimary ? 'tel:' . $telPrimary : '#' }}">
                                Request a call
                            </a>

                        </div>
                    </div>
                </div>
            </div>

            <div class="rd-navbar-main-outer">
                <div class="rd-navbar-main">
                    <div class="rd-navbar-nav-wrap" id="rd-navbar-nav-wrap-1">

                        {{-- Search --}}
                        <div class="rd-navbar-search" id="rd-navbar-search-1">
                            <button class="rd-navbar-search-toggle"
                                data-rd-navbar-toggle="#rd-navbar-search-1"><span></span></button>

                            <form class="rd-search" action="#" method="GET">
                                <div class="form-wrap">
                                    <label class="form-label" for="rd-navbar-search-form-input-1">Search...</label>
                                    <input class="form-input rd-navbar-search-form-input"
                                        id="rd-navbar-search-form-input-1" type="text" name="s"
                                        autocomplete="off">
                                </div>
                                <button class="rd-search-form-submit fa-search" type="submit"></button>
                            </form>
                        </div>

                        {{-- Nav --}}
                        <ul class="rd-navbar-nav">
                            <li class="rd-nav-item active">
                                <a class="rd-nav-link" href="{{ route('home') }}">Home</a>
                            </li>

                            <li class="rd-nav-item"><a class="rd-nav-link" href="#">Services</a></li>
                            <li class="rd-nav-item"><a class="rd-nav-link" href="{{ route('about') }}">About Us</a></li>
                            <li class="rd-nav-item"><a class="rd-nav-link" href="{{ route('contact') }}">Contacts</a>
                            </li>

                            {{-- Auth CTA --}}
                            @guest
                                <li class="rd-nav-item {{ request()->routeIs('login') ? 'active' : '' }}">
                                    <a class="rd-nav-link" href="{{ route('login') }}">Login</a>
                                </li>

                                <li class="rd-nav-item {{ request()->routeIs('register') ? 'active' : '' }}">
                                    <a class="rd-nav-link" href="{{ route('register') }}">Sign Up</a>
                                </li>
                            @else
                                {{-- ADMIN DASHBOARD --}}
                                @role('admin')
                                    <li class="rd-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                        <a class="rd-nav-link" href="{{ route('admin.dashboard') }}">
                                            Dashboard
                                        </a>
                                    </li>

                                    {{-- USER DASHBOARD --}}
                                    @elserole('user')
                                    <li class="rd-nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                                        <a class="rd-nav-link" href="{{ route('user.dashboard') }}">
                                            Dashboard
                                        </a>
                                    </li>

                                    {{-- FALLBACK --}}
                                @else
                                    <li class="rd-nav-item">
                                        <a class="rd-nav-link" href="{{ route('home') }}">Home</a>
                                    </li>
                                @endrole

                                {{-- LOGOUT --}}
                                <li class="rd-nav-item">
                                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                                        @csrf
                                        <button type="submit" class="rd-nav-link"
                                            style="background:none;border:none;padding:0;">
                                            Logout
                                        </button>
                                    </form>
                                </li>

                            @endguest

                        </ul>

                    </div>
                </div>
            </div>

        </nav>
    </div>
</header>
