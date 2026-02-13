@extends('layouts.app')
@section('content')
    {{-- Sliders --}}
    @include('partials.slider')
    {{-- Slider --}}

    <section class="section section-md bg-default novi-background"
        data-preset='{"title":"Content Box 1","category":"content-boxes","reload":true,"id":"content-box-1"}'>
        <div class="bg-gray-4 novi-background">
            <div class="container-fluid container-condensed">
                <div class="row row-30 flex-lg-row-reverse">

                    <!-- Text Content -->
                    <div class="col-md-12 col-lg-6">
                        <div class="section-md container-fluid-col">
                            <div class="box-centered box-width-1">
                                <h2>
                                    <span>Trusted Expertise</span>
                                    <span class="object-decorated object-decorated_inline" style="max-width: 160px;">
                                        <span class="object-decorated__divider"></span>
                                        <span class="heading-5">Global Reach</span>
                                    </span>
                                </h2>

                                <p>
                                    HV UK RF1 INVESTMENTS LTD &amp; HV Royalty Acquisition II Trust delivers strategic
                                    financing and investment solutions designed to unlock growth for businesses and
                                    high-net-worth individuals. With deep roots in UK and international markets, the firm
                                    combines innovative debt and equity structures with hands-on advisory support to
                                    turn complex opportunities into sustainable results.
                                </p>

                                <p>
                                    From real estate and energy projects to technology ventures, HV partners closely
                                    with clients—bridging funding gaps, protecting capital, and providing clarity at
                                    every stage of the investment journey.
                                </p>

                                <div class="group-md group-middle button-group">

                                    <a class="button button-primary" href="#services-section"> Explore Our Products</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Video / Visual -->
                    <div class="col-md-12 col-lg-6 d-flex">
                        <div class="thumb-video">
                            <img class="thumb-video__image" src="{{ asset('images/about-hv.jpg') }}"
                                alt="HV Investments Overview" width="962" height="465" />

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section id="services-section" class="section section-md bg-default novi-background"
        data-preset='{"title":"Services","category":"content-boxes","reload":true,"id":"services"}'>

        <div class="container container-wide">
            <div class="row justify-content-lg-center">
                <div class="col-xl-11">
                    <div class="section__header">
                        <h2>Our Services</h2>
                        <div class="section__header-element">
                            <a class="link link-md" href="{{ route('services.index') }}">
                                Explore Our Services
                            </a>
                        </div>
                    </div>

                    <p class="text-muted mt-2">
                        {{ $servicesIntro ?? 'Each service is built on a proven, real world foundation, delivering results without overcomplication. We turn experience into execution, helping you reach your goals with clarity and confidence.' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="container-fluid isotope-wrap">
            <div class="container">
                <div class="row">
                    @foreach ($services as $service)
                        @php
                            $href =
                                $service->cta_url ?:
                                (Route::has('services.show')
                                    ? route('services.show', $service->slug)
                                    : route('services.index'));

                            $img = $service->image
                                ? asset('storage/' . $service->image)
                                : asset('assets/front/images/service-placeholder-639x524.jpg');
                        @endphp

                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <a href="{{ $href }}" class="svc-card-link d-block h-100">
                                <div class="card svc-card h-100 border-0 shadow-sm">
                                    <div class="svc-card__media">
                                        <img src="{{ $img }}" alt="{{ $service->title }}" loading="lazy">
                                    </div>

                                    <div class="card-body">
                                        <h6 class="svc-card__title mb-1">{{ $service->title }}</h6>

                                        {{-- Optional: short text if you have it --}}
                                        @if (!empty($service->short_description))
                                            <p class="svc-card__text mb-0">
                                                {{ \Illuminate\Support\Str::limit($service->short_description, 90) }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Optional footer CTA --}}
                                    <div class="card-footer bg-transparent border-0 pt-0">
                                        <span class="svc-card__cta">Learn more →</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    <!-- Rapid, Precision, and Exceptional Service -->
    <section class="section section-md bg-gray-4 novi-background text-center"
        data-preset='{"title":"Content Box - Rapid Service","category":"content-boxes","reload":true,"id":"content-box-hv-rapid-service"}'>
        <div class="container">

            {{-- Section Header --}}
            <h2><strong>Rapid, Precision, and Exceptional Service</strong></h2>

            <p>
                Building confidence with the appropriate financiers is vital. Aligning with HV UK RF1 Investments Ltd.
                and HV Royalty Acquisition II Trust guarantees the promptness and assurance you require. We recognize
                that each financing arrangement is distinct, which is why we deliver customized bridging options
                crafted to meet your particular needs.
            </p>

            <p class="mb-4">
                <strong>Send us a personal email if your capital requirement is included in this classification:</strong>
            </p>

            <div class="row justify-content-center row-30">
                @php
                    $categories = [
                        'Farming / Cultivation',
                        'Renewable Energy / Sustainable Power',
                        'Infrastructure Links / Overpasses',
                        'Rail Networks',
                        'Waste Processing Systems',
                        'Personal / Private',
                        'Varied / Multifaceted Project financing',
                        'Leisure / Entertainment amenities',
                        'Electricity and Power',
                        'Communications / Networks',
                        'Lodging and Guest Services',
                        'Petroleum and Natural Gas',
                        'Flight / Aerospace',
                        'Construction / Edifice',
                    ];

                    $emailTo = $siteSettings->contact_email ?? 'info@hvrfinvestments.com';
                    $subject = rawurlencode('Private Capital Requirement');
                    $body = rawurlencode(
                        "Hello HV Team,\n\nMy capital requirement falls under:\n\nProject summary:\nTimeline:\nLocation:\n\nRegards,",
                    );
                    $mailto = "mailto:{$emailTo}?subject={$subject}&body={$body}";
                @endphp

                @foreach ($categories as $i => $cat)
                    <div class="col-12 col-sm-6 col-lg-6">
                        <a class="block-vacancy block-vacancy--badge block-vacancy--compact" href="{{ $mailto }}">

                            {{-- Number Badge --}}
                            <span class="badge-count">{{ $i + 1 }}</span>

                            {{-- Responsive Title --}}
                            <h4 class="block-vacancy__title vacancy-title">{{ $cat }}</h4>

                            <ul class="block-vacancy__meta">
                                <li>
                                    <span class="icon novi-icon icon-primary material-icons-mail_outline"></span>
                                    <span>Send Personal Email</span>
                                </li>

                            </ul>

                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ===================== CAPITAL TERMS (REFINED + MORE APPEALING) ===================== --}}
    <section class="section section-md bg-gray-4 novi-background text-center"
        data-preset='{"title":"Content Box - Capital Terms (Refined)","category":"content-boxes","reload":true,"id":"content-box-hv-capital-terms-refined"}'>
        <div class="container">

            {{-- Header --}}
            <div class="mb-4 mb-md-5">
                <h2 class="mb-2"><strong>Capital Terms</strong></h2>
                <p class="text-muted mb-0">
                    Indicative parameters for structuring. Final approval is jurisdiction-dependent and subject to country
                    risk assessment and due diligence.
                </p>
            </div>

            @php
                $capitalTerms = [
                    ['title' => 'Interest Rate', 'value' => '3% to 8%', 'icon' => 'trending_up'],
                    [
                        'title' => 'Jurisdiction-Dependent Approval',
                        'value' => 'Global – Subject to Country Risk Assessment',
                        'icon' => 'public',
                    ],
                    ['title' => 'Repayment Horizon', 'value' => '2 to 20 years', 'icon' => 'schedule'],
                    [
                        'title' => 'Debt Service Coverage Ratio (DSCR)',
                        'value' => 'Minimum 1.2x to 1.5x',
                        'icon' => 'assessment',
                    ],
                    [
                        'title' => 'Prepayment Flexibility',
                        'value' => 'Allowed with or without Penalty',
                        'icon' => 'autorenew',
                    ],
                    [
                        'title' => 'Investment Memorandum',
                        'value' => 'Feasibility Study, Business Plan',
                        'icon' => 'description',
                    ],
                    [
                        'title' => 'Governing Law & Dispute Resolution',
                        'value' => 'New York Law / English Law',
                        'icon' => 'gavel',
                    ],
                    [
                        'title' => 'Capital Commitment Range',
                        'value' => 'Minimum $1M – Maximum $900M',
                        'icon' => 'account_balance',
                    ],
                    [
                        'title' => 'Financial Structuring Options',
                        'value' => 'Debt Financing / Equity / Corporate Loan / Joint Venture / etc.',
                        'icon' => 'tune',
                    ],
                    ['title' => 'Denomination Options', 'value' => 'USD / EUR / GBP / Other', 'icon' => 'payments'],
                    [
                        'title' => 'Repayment Schedule',
                        'value' => 'Equal Installments / Balloon Payment',
                        'icon' => 'calendar_month',
                    ],
                    [
                        'title' => 'Guarantee Structure',
                        'value' => 'Project Assets, Revenue Pledge',
                        'icon' => 'verified_user',
                    ],
                    ['title' => 'Loan-to-Value (LTV)', 'value' => 'Typically 70:30 or 80:20', 'icon' => 'pie_chart'],
                    ['title' => 'Capital Deployment Plan', 'value' => 'Lump Sum / Tranches', 'icon' => 'call_split'],
                ];
            @endphp

            {{-- Cards --}}
            <div class="row justify-content-center row-30">
                @foreach ($capitalTerms as $i => $term)
                    <div class="col-12 col-sm-6 col-lg-6">
                        <div class="block-vacancy block-vacancy--compact block-vacancy--badge h-100"
                            style="
                            background: #fff;
                            border: 1px solid rgba(0,0,0,.06);
                            border-radius: 14px;
                            box-shadow: 0 10px 25px rgba(0,0,0,.06);
                            padding: 22px 20px;
                            text-align: left;
                            transition: transform .2s ease, box-shadow .2s ease;
                        "
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 14px 35px rgba(0,0,0,.10)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,.06)';">

                            {{-- Number badge (top-right) --}}
                            <span class="badge-count"
                                style="
                                position: absolute;
                                top: 14px;
                                right: 14px;
                                width: 34px;
                                height: 34px;
                                border-radius: 50%;
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                font-weight: 700;
                                background: rgba(0,0,0,.06);
                                color: #222;
                            ">
                                {{ $i + 1 }}
                            </span>

                            {{-- Icon + Title --}}
                            <div class="d-flex align-items-start mb-2" style="gap: 12px;">
                                <span class="icon novi-icon icon-primary material-icons-{{ $term['icon'] }}"
                                    style="
                                    font-size: 26px;
                                    line-height: 1;
                                    margin-top: 2px;
                                ">
                                </span>

                                <div>
                                    <h4 class="vacancy-title mb-1" style="font-size: 18px; line-height: 1.25;">
                                        {{ $term['title'] }}
                                    </h4>
                                    <div class="text-muted" style="font-size: 13px;">
                                        Indicative term
                                    </div>
                                </div>
                            </div>

                            {{-- Value --}}
                            <div class="mt-2"
                                style="
                                font-size: 15px;
                                font-weight: 600;
                                color: #111;
                                padding: 12px 14px;
                                border-radius: 12px;
                                background: rgba(0,0,0,.03);
                                border: 1px dashed rgba(0,0,0,.10);
                            ">
                                {{ $term['value'] }}
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            {{-- CTA --}}
            @php
                $emailTo = $siteSettings->contact_email ?? 'info@hvrfinvestments.com';
                $subject = rawurlencode('Capital Terms Enquiry');
                $body = rawurlencode(
                    "Hello HV Team,\n\nI would like to discuss capital terms for my project.\n\nProject summary:\nCapital required:\nLocation:\nPreferred currency:\nTimeline:\n\nRegards,",
                );
                $mailto = "mailto:{$emailTo}?subject={$subject}&body={$body}";
            @endphp

            <div class="mt-4 mt-md-5">
                <a href="{{ $mailto }}" class="btn btn-primary btn-lg"
                    style="border-radius: 999px; padding: 12px 22px; box-shadow: 0 10px 25px rgba(0,0,0,.12);">
                    <span class="icon novi-icon icon-primary material-icons-mail_outline mr-2"></span>
                    Email Us to Discuss Terms
                </a>
                <div class="small text-muted mt-2">
                    Response time may vary by jurisdiction and due diligence requirements.
                </div>
            </div>

        </div>
    </section>
@endsection
