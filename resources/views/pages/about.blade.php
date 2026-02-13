@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['header' => 'About Us'])

    <section class="section section-lg bg-default novi-background">
        <div class="container">
            <div class="layout-bordered">

                <!-- MAIN: About Us -->
                <div class="layout-bordered__main text-center">
                    <div class="layout-bordered__main-inner">
                        <h3>About HV</h3>
                        <p>
                            HV UK RF1 INVESTMENTS LTD &amp; HV Royalty Acquisition II Trust specializes in strategic
                            financing and
                            investment solutions that empower businesses and individuals to thrive.
                        </p>

                        <div class="text-left mt-4">
                            <p>
                                We are a trusted partner in the world of finance, blending innovative debt and equity
                                strategies with
                                hands-on advisory services. Our mission is simple: bridge funding gaps for ambitious
                                ventures and help
                                clients execute deals with clarity, speed, and confidence.
                            </p>

                            <p>
                                Serving clients across sectors such as real estate, energy, and technology, our team—built
                                on deep market
                                experience across London and international markets focuses on long-term relationships, not
                                one-off
                                transactions. Clients choose HV for transparency, tailored solutions, and a disciplined
                                approach to risk
                                and execution.
                            </p>

                            <p class="mb-0">
                                Whether you’re scaling a business, raising capital, structuring a joint venture, or
                                safeguarding wealth
                                across borders, HV provides personalized guidance and practical strategies that translate to
                                measurable
                                outcomes.
                            </p>
                        </div>

                        {{-- <!-- CTA -->
                        <div class="group group-middle mt-4">
                            <div class="wow-outer">
                                <a class="button button-primary button-winona wow slideInRight" href="services.html">
                                    Explore Our Services
                                </a>
                            </div>

                            <p>or</p>

                            <div class="wow-outer">
                                <a class="button button-primary-outline button-icon button-icon-left button-winona wow slideInLeft"
                                    href="contacts.html" rel="noopener">
                                    <span class="icon text-primary mdi mdi-calendar-clock"></span>
                                    Schedule a Consultation
                                </a>
                            </div>
                        </div> --}}
                    </div>
                </div>

                @php
                    // DB: site_settings fields
                    $siteName = $siteSettings->site_name ?? config('app.name', 'Hello HV UK RF1 Investments');

                    $logo = $siteSettings->logo_path ?? 'assets/images/logo.png';
                    $logoMini = $siteSettings->logo_mini_path ?? $logo;

                    $contactEmail = $siteSettings->contact_email ?? 'info@hvrfinvestments.com';
                    $contactPhone = $siteSettings->contact_phone ?? '+2348030001234';
                    $contactAddress = $siteSettings->contact_address ?? null;
                @endphp

                <!-- ASIDE: Trust + Presence -->
                <div class="layout-bordered__aside">
                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">What We Stand For</p>
                        <ul class="list-marked">
                            <li>Confidential, client-first engagement</li>
                            <li>Transparent terms and structured solutions</li>
                            <li>Execution-focused advisory support</li>
                        </ul>
                    </div>


                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Support</p>
                        <div class="unit flex-row unit-spacing-xxs">
                            <div class="unit-left">
                                <span class="icon novi-icon icon-sm icon-primary fas fa-envelope"></span>
                            </div>
                            <div class="unit-body">
                                <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>

                            </div>
                        </div>
                    </div>

                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Offices</p>
                        <div class="unit flex-row unit-spacing-xxs">
                            <div class="unit-left">
                                <span class="icon novi-icon icon-sm icon-primary material-icons-location_on"></span>
                            </div>
                            <div class="unit-body">
                                <span class="small">
                                    {!! $contactAddress !!}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Contact</p>
                        <div class="unit flex-row unit-spacing-xxs">
                            <div class="unit-left">
                                <span class="icon novi-icon icon-sm icon-primary material-icons-local_phone"></span>
                            </div>
                            <div class="unit-body">
                                <a href="tel:+442012345678">{{ $contactPhone }}</a>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /ASIDE -->

            </div>
        </div>
    </section>
@endsection
