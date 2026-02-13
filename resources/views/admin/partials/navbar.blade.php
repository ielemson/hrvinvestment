{{-- =========================
   NAVBAR (HORIZONTAL)
   ========================= --}}

@php
    $logo = $siteSettings->logo_path ?? 'assets/images/logo.png';
@endphp

<nav class="navbar horizontal-layout col-lg-12 col-12 p-0">
    <div class="container d-flex flex-row">

        {{-- BRAND --}}
        <div class="text-center navbar-brand-wrapper d-flex align-items-top">
            <a class="navbar-brand brand-logo" href="{{ route('user.dashboard') }}">
                <img src="{{ asset($logo) }}" alt="logo" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="{{ route('user.dashboard') }}">
                <img src="{{ asset($logo) }}" alt="logo" />
            </a>
        </div>

        {{-- TOP BAR --}}
        <div class="navbar-menu-wrapper d-flex align-items-center">

            {{-- SEARCH --}}
            <form class="search-field ml-auto" action="javascript:;">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
            </form>

            {{-- RIGHT ICONS --}}
            <ul class="navbar-nav navbar-nav-right mr-0">

                {{-- Notifications --}}
                <li class="nav-item dropdown ml-4">
                    <a class="nav-link count-indicator dropdown-toggle" href="javascript:;" data-toggle="dropdown">
                        <i class="mdi mdi-bell-outline"></i>
                        <span class="count bg-warning">4</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown">
                        <a class="dropdown-item" href="javascript:;">System Alerts</a>
                        <a class="dropdown-item" href="javascript:;">New Applications</a>
                        <a class="dropdown-item" href="{{ route('admin.kyc.index') }}">Pending KYC</a>
                        <a class="dropdown-item" href="javascript:;">View All Notifications</a>
                    </div>
                </li>

                {{-- USER --}}
                <li class="nav-item dropdown">
                    @php
                        $user = auth()->user();

                        $avatarUrl = $user->avatar
                            ? asset('storage/' . $user->avatar)
                            : asset('admin/images/faces/user.png');
                    @endphp

                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                        <img class="img-xs rounded-circle" src="{{ $avatarUrl }}" alt="profile">
                    </a>

                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown">
                        <a class="dropdown-item" href="javascript:;">My Profile</a>
                        <a class="dropdown-item" href="javascript:;">Security Settings</a>
                        <a class="dropdown-item" href="javascript:;">Help / Support</a>
                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout mr-2 text-danger"></i> Sign Out
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>

            <button class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
    </div>

    {{-- BOTTOM NAV --}}
    <div class="nav-bottom">
        <div class="container">
            <ul class="nav page-navigation">

                {{-- Dashboard --}}
                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="mdi mdi-view-dashboard link-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>

                {{-- Home Page --}}
                <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class="nav-link" target="_blank" rel="noopener">
                        <i class="mdi mdi-home link-icon"></i>
                        <span class="menu-title">Home Page</span>
                        <i class="mdi mdi-open-in-new ml-1"></i>
                    </a>
                </li>

                {{-- Operations --}}
                <li
                    class="nav-item mega-menu {{ request()->routeIs('admin.services.*') || request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                    <a href="javascript:;" class="nav-link">
                        <i class="mdi mdi-clipboard-check link-icon"></i>
                        <span class="menu-title">Operations</span>
                        <i class="menu-arrow"></i>
                    </a>

                    <div class="submenu">
                        <div class="row col-group-wrapper">
                            <div class="col-md-4">
                                <p class="category-heading">Website</p>
                                <ul class="submenu-item">
                                    <li>
                                        <a class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}"
                                            href="{{ route('admin.services.index') }}">
                                            Services
                                        </a>
                                    </li>
                                    <li>
                                        <a class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}"
                                            href="{{ route('admin.sliders.index') }}">
                                            Sliders
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-4">
                                <p class="category-heading">Workflow</p>
                                <ul class="submenu-item">
                                    <li><a class="nav-link" href="javascript:;">Approvals</a></li>
                                    <li><a class="nav-link" href="javascript:;">Audit Trail</a></li>
                                </ul>
                            </div>

                            <div class="col-md-4">
                                <p class="category-heading">System</p>
                                <ul class="submenu-item">
                                    <li><a class="nav-link" href="javascript:;">Notifications</a></li>
                                    <li><a class="nav-link" href="javascript:;">Email Templates</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                {{-- Users & Access --}}
                <li
                    class="nav-item mega-menu {{ request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.kyc.*') ? 'active' : '' }}">
                    <a href="javascript:;" class="nav-link">
                        <i class="mdi mdi-account-group link-icon"></i>
                        <span class="menu-title">Users & Access</span>
                        <i class="menu-arrow"></i>
                    </a>

                    <div class="submenu">
                        <div class="row col-group-wrapper">

                            <div class="col-md-4">
                                <p class="category-heading">Accounts</p>
                                <ul class="submenu-item">
                                    <li>
                                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                            href="{{ route('admin.users.index') }}">
                                            Borrowers
                                        </a>
                                    </li>

                                    <li>
                                        <a class="nav-link {{ request()->routeIs('admin.kyc.*') ? 'active' : '' }}"
                                            href="{{ route('admin.kyc.index') }}">
                                            KYC Verification
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-4">
                                <p class="category-heading">Access Control</p>
                                <ul class="submenu-item">
                                    <li>
                                        <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
                                            href="{{ route('admin.roles.index') }}">
                                            Roles
                                        </a>
                                    </li>
                                    <li><a class="nav-link" href="javascript:;">Audit Logs</a></li>
                                </ul>
                            </div>

                            <div class="col-md-4">
                                <p class="category-heading">Communication</p>
                                <ul class="submenu-item">
                                    <li><a class="nav-link" href="javascript:;">Notifications</a></li>
                                    <li><a class="nav-link" href="javascript:;">Support Tickets</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </li>

                {{-- Reports --}}
                <li class="nav-item mega-menu">
                    <a href="javascript:;" class="nav-link">
                        <i class="mdi mdi-chart-line link-icon"></i>
                        <span class="menu-title">Reports</span>
                        <i class="menu-arrow"></i>
                    </a>
                </li>

                {{-- Settings --}}
                <li class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings.edit') }}" class="nav-link">
                        <i class="mdi mdi-settings-outline link-icon"></i>
                        <span class="menu-title">Settings</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
