@php
    $logo = $siteSettings->logo_path ?? 'assets/images/logo.png';

    $user = auth()->user();

    $avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : asset('admin/images/faces/face1.jpg');
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
            <form class="search-field ml-auto">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="mdi mdi-magnify"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
            </form>

            {{-- RIGHT ICONS --}}
            <ul class="navbar-nav navbar-nav-right mr-0">

                {{-- Notifications --}}
                <li class="nav-item dropdown ml-4">
                    <a class="nav-link count-indicator dropdown-toggle" href="#" data-toggle="dropdown">
                        <i class="mdi mdi-bell-outline"></i>
                        <span class="count bg-warning">
                            {{ $user->unreadNotifications()->count() }}
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown">
                        @forelse($user->unreadNotifications->take(5) as $notification)
                            <a class="dropdown-item" href="{{ route('notifications.index') }}">
                                {{ $notification->data['message'] ?? 'Notification' }}
                            </a>
                        @empty
                            <span class="dropdown-item text-muted">No new notifications</span>
                        @endforelse

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="{{ route('notifications.index') }}">
                            View All Notifications
                        </a>
                    </div>
                </li>

                {{-- USER --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                        <img class="img-xs rounded-circle" src="{{ $avatarUrl }}" alt="profile">
                    </a>

                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown">
                        <a class="dropdown-item" href="{{ route('user.profile.edit') }}">
                            <i class="mdi mdi-account-outline mr-2"></i> My Profile
                        </a>

                        <a class="dropdown-item" href="{{ route('user.profile.edit') }}#password">
                            <i class="mdi mdi-shield-lock-outline mr-2"></i> Security Settings
                        </a>

                        <a class="dropdown-item">
                            <i class="mdi mdi-help-circle-outline mr-2"></i> Help / Support
                        </a>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout mr-2"></i> Sign Out
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
                <li class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('user.dashboard') }}" class="nav-link">
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

                {{-- Loans --}}
                <li class="nav-item mega-menu {{ request()->routeIs('user.loans.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="mdi mdi-cash-multiple link-icon"></i>
                        <span class="menu-title">Loans</span>
                        <i class="menu-arrow"></i>
                    </a>

                    <div class="submenu">
                        <div class="row col-group-wrapper">

                            <div class="col-md-4">
                                <p class="category-heading">Apply & Manage</p>
                                <ul class="submenu-item">
                                    {{-- <li><a class="nav-link" href="{{ route('user.loans.create') }}">Apply for Loan</a> --}}
                </li>
                <li><a class="nav-link" href="{{ route('user.loans.index') }}">My Loans</a></li>
                <li>
                    <a class="nav-link" href="{{ route('user.loans.index', ['status' => 'active']) }}">
                        Active Loan
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('user.loans.index', ['status' => 'completed']) }}">
                        Loan History
                    </a>
                </li>
            </ul>
        </div>

        <div class="col-md-4">
            <p class="category-heading">Status Tracking</p>
            <ul class="submenu-item">
                <li>
                    <a class="nav-link" href="{{ route('user.loans.index', ['status' => 'under_review']) }}">
                        Under Review
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('user.loans.index', ['status' => 'approved']) }}">
                        Approved
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('user.loans.index', ['status' => 'disbursed']) }}">
                        Disbursement
                    </a>
                </li>
            </ul>
        </div>

        {{-- <div class="col-md-4">
                                <p class="category-heading">Offers</p>
                                <ul class="submenu-item">
                                    <li><a class="nav-link" href="{{ route('user.dashboard') }}#loan-limit">My Loan
                                            Limit</a></li>
                                    <li><a class="nav-link" href="{{ route('user.dashboard') }}#offers">Personalized
                                            Offers</a></li>
                                    <li><a class="nav-link"
                                            href="{{ route('user.dashboard') }}#eligibility">Eligibility Tips</a></li>
                                </ul>
                            </div> --}}

    </div>
    </div>
    </li>

    {{-- Repayments --}}
    {{-- <li class="nav-item mega-menu {{ request()->routeIs('repayments.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="mdi mdi-calendar-clock link-icon"></i>
                        <span class="menu-title">Repayments</span>
                        <i class="menu-arrow"></i>
                    </a>

                    <div class="submenu">
                        <div class="row col-group-wrapper">
                            <div class="col-md-4">
                                <p class="category-heading">Schedule</p>
                                <ul class="submenu-item">
                                    <li><a class="nav-link" href="#">Repayment Schedule</a></li>
                                    <li><a class="nav-link" href="#">Upcoming Due Dates</a></li>
                                </ul>
                            </div>

                            <div class="col-md-4">
                                <p class="category-heading">Payments</p>
                                <ul class="submenu-item">
                                    <li><a class="nav-link" href="#">Make a Payment</a></li>
                                    <li><a class="nav-link" href="#">Paid Installments</a></li>
                                    <li><a class="nav-link" href="#">Unpaid Installments</a></li>
                                </ul>
                            </div>

                            <div class="col-md-4">
                                <p class="category-heading">History</p>
                                <ul class="submenu-item">
                                    <li><a class="nav-link" href="#">Repayment History</a></li>
                                    <li><a class="nav-link" href="#">Receipts</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li> --}}

    {{-- Settings --}}
    <li class="nav-item {{ request()->routeIs('user.profile.*') ? 'active' : '' }}">
        <a href="{{ route('user.profile.edit') }}" class="nav-link">
            <i class="mdi mdi-settings-outline link-icon"></i>
            <span class="menu-title">Settings</span>
        </a>
    </li>

    </ul>
    </div>
    </div>
</nav>
