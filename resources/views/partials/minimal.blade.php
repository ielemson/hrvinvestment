<header class="section page-header">
    <div class="rd-navbar-wrap">
        <nav class="rd-navbar rd-navbar-minimal" data-layout="rd-navbar-fixed" data-sm-layout="rd-navbar-fixed"
            data-md-layout="rd-navbar-fixed" data-md-device-layout="rd-navbar-fixed" data-lg-layout="rd-navbar-static"
            data-lg-device-layout="rd-navbar-fixed" data-xl-layout="rd-navbar-static"
            data-xl-device-layout="rd-navbar-fixed" data-lg-stick-up-offset="46px" data-xl-stick-up-offset="46px"
            data-xxl-stick-up-offset="46px" data-lg-stick-up="true" data-xl-stick-up="true" data-xxl-stick-up="true">

            <div class="rd-navbar-main-outer">
                <div class="rd-navbar-main">

                    {{-- Panel --}}
                    <div class="rd-navbar-panel">
                        <button class="rd-navbar-toggle"
                            data-rd-navbar-toggle="#rd-navbar-nav-wrap-1"><span></span></button>

                        @php
                            $logo = $siteSettings->logo_path ?? 'images/logo.png';
                            $siteName = $siteSettings->site_name ?? config('app.name', 'Hello HV UK RF1 Investments');
                        @endphp

                        <a class="rd-navbar-brand" href="{{ route('home') }}">
                            <img src="{{ asset($logo) }}" alt="{{ $siteName }}" width="171" height="39"
                                loading="lazy" srcset="{{ asset($logo) }} 2x" />
                        </a>
                    </div>

                    <div class="rd-navbar-main-element">
                        <div class="rd-navbar-nav-wrap" id="rd-navbar-nav-wrap-1">

                            {{-- Nav --}}
                            <ul class="rd-navbar-nav">

                                <li class="rd-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                                    <a class="rd-nav-link" href="{{ route('home') }}">Home</a>
                                </li>

                                <li class="rd-nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                                    <a class="rd-nav-link" href="{{ route('about') }}">About Us</a>
                                </li>

                                <li class="rd-nav-item {{ request()->routeIs('services.index') ? 'active' : '' }}">
                                    <a class="rd-nav-link" href="{{ route('services.index') }}">Services</a>
                                </li>

                                <li class="rd-nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                                    <a class="rd-nav-link" href="{{ route('contact') }}">Contacts</a>
                                </li>

                                {{-- Auth links --}}
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

                        {{-- Search --}}
                        <div class="rd-navbar-search" id="rd-navbar-search-1">
                            <button class="rd-navbar-search-toggle rd-navbar-fixed-element-2"
                                data-rd-navbar-toggle="#rd-navbar-search-1">
                                <span></span>
                            </button>

                            <form class="rd-search" action="{{ route('home') }}" method="GET">
                                <div class="form-wrap">
                                    <label class="form-label" for="rd-navbar-search-form-input-1">Search...</label>
                                    <input class="form-input rd-navbar-search-form-input"
                                        id="rd-navbar-search-form-input-1" type="text" name="s"
                                        autocomplete="off">
                                </div>
                                <button class="rd-search-form-submit fa-search" type="submit"></button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </nav>
    </div>
</header>
