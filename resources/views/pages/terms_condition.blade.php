@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['header' => 'Terms & Conditions'])

    <section class="section section-lg bg-default novi-background">
        <div class="container">
            <div class="layout-bordered">

                <!-- MAIN CONTENT -->
                <div class="layout-bordered__main">
                    <div class="layout-bordered__main-inner">

                        <div class="text-center">
                            <h3>Terms & Conditions</h3>
                            <p class="mb-2">
                                These Terms & Conditions govern your use of the website and services of
                                <strong>HV UK RF1 INVESTMENTS LTD &amp; HV Royalty Acquisition II Trust</strong>
                                (“HV”, “we”, “us”, “our”).
                            </p>
                            <p class="small">
                                Effective Date: <strong>February 13, 2026</strong>
                            </p>
                        </div>

                        <hr class="my-4">

                        <div class="text-left">

                            <h5>1. Acceptance of Terms</h5>
                            <p>
                                By accessing or using our website, submitting inquiries, or engaging our services,
                                you agree to comply with these Terms & Conditions and all applicable laws and regulations.
                                If you do not agree, please discontinue use of the website.
                            </p>

                            <h5 class="mt-4">2. Nature of Services</h5>
                            <p>
                                HV provides strategic financing solutions including debt financing, joint ventures,
                                equity placement, investment structuring, and financial advisory services.
                                Information provided on this website is for general informational purposes only
                                and does not constitute a binding offer or financial advice.
                            </p>

                            <h5 class="mt-4">3. No Financial Advice</h5>
                            <p>
                                Nothing on this website constitutes investment, legal, tax, or accounting advice.
                                Engagement with HV services requires separate written agreements.
                                You should seek independent professional advice before making financial decisions.
                            </p>

                            <h5 class="mt-4">4. Eligibility</h5>
                            <p>
                                Our services are primarily intended for businesses, institutional clients,
                                and qualified individuals. By using this website, you confirm that you are
                                legally capable of entering into binding agreements under applicable law.
                            </p>

                            <h5 class="mt-4">5. Confidentiality</h5>
                            <p>
                                Information submitted through our contact forms or communications will be treated
                                as confidential. However, no website transmission is guaranteed to be completely secure.
                                Formal engagements may require execution of Non-Disclosure Agreements (NDAs).
                            </p>

                            <h5 class="mt-4">6. Intellectual Property</h5>
                            <p>
                                All website content including text, branding, logos, graphics, structure, and design
                                is owned by HV UK RF1 INVESTMENTS LTD &amp; HV Royalty Acquisition II Trust
                                and is protected under applicable intellectual property laws.
                                Unauthorized reproduction or distribution is prohibited.
                            </p>

                            <h5 class="mt-4">7. Limitation of Liability</h5>
                            <p>
                                HV shall not be liable for any direct, indirect, incidental, or consequential damages
                                arising from use of the website or reliance on information provided herein.
                                Financing approvals are subject to due diligence and separate contractual agreements.
                            </p>

                            <h5 class="mt-4">8. Investment Risk Disclaimer</h5>
                            <p>
                                All investments involve risk, including potential loss of capital.
                                Past performance is not indicative of future results.
                                Funding timelines and returns are subject to market conditions and regulatory review.
                            </p>

                            <h5 class="mt-4">9. Third-Party Links</h5>
                            <p>
                                Our website may contain links to third-party websites for convenience.
                                HV does not endorse or assume responsibility for external content.
                            </p>

                            <h5 class="mt-4">10. Compliance & Regulatory Matters</h5>
                            <p>
                                Services are subject to applicable UK, Nigerian, and international laws
                                including financial regulations and anti-money laundering requirements.
                                Clients may be required to provide documentation for due diligence and compliance.
                            </p>

                            <h5 class="mt-4">11. Governing Law</h5>
                            <p>
                                These Terms & Conditions shall be governed by and construed in accordance with
                                the laws of England and Wales, unless otherwise agreed in a separate contract.
                                Cross-border engagements may include additional jurisdictional clauses.
                            </p>

                            <h5 class="mt-4">12. Modifications</h5>
                            <p>
                                HV reserves the right to update or modify these Terms & Conditions at any time.
                                Changes become effective upon publication on this page.
                            </p>

                            <h5 class="mt-4">13. Contact Information</h5>
                            <p>
                                For questions regarding these Terms, contact:
                            </p>
                            <ul class="list-marked">
                                <li>
                                    <strong>Email:</strong>
                                    <a href="mailto:info@hvrf1investments.com">
                                        info@hvrf1investments.com
                                    </a>
                                </li>
                                <li>
                                    <strong>Advisory Desk:</strong>
                                    <a href="mailto:advisory@hvrf1investments.com">
                                        advisory@hvrf1investments.com
                                    </a>
                                </li>
                                <li>
                                    <strong>Office Hours:</strong>
                                    Monday–Friday, 9 AM–6 PM WAT/GMT
                                </li>
                            </ul>

                            <div class="mt-4">
                                <p class="small mb-0">
                                    © 2026 HV UK RF1 INVESTMENTS LTD &amp; HV Royalty Acquisition II Trust.
                                    All rights reserved.
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

                @php
                    $contactEmail = $siteSettings->contact_email ?? 'info@hvrf1investments.com';
                    $contactPhone = $siteSettings->contact_phone ?? '+44 (0)20 1234 5678';
                    $contactAddress =
                        $siteSettings->contact_address ?? '123 Finance Street, London EC1A 1AA, United Kingdom';
                @endphp

                <!-- ASIDE -->
                <div class="layout-bordered__aside">
                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Important Notice</p>
                        <ul class="list-marked">
                            <li>Information is general in nature</li>
                            <li>Formal engagement requires written agreement</li>
                            <li>Investments carry risk</li>
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
                        <p class="heading-8">Office</p>
                        <div class="unit flex-row unit-spacing-xxs">
                            <div class="unit-left">
                                <span class="icon novi-icon icon-sm icon-primary material-icons-location_on"></span>
                            </div>
                            <div class="unit-body">
                                <span class="small">{!! $contactAddress !!}</span>
                            </div>
                        </div>
                    </div>

                    <div class="layout-bordered__aside-item">
                        <p class="heading-8">Phone</p>
                        <div class="unit flex-row unit-spacing-xxs">
                            <div class="unit-left">
                                <span class="icon novi-icon icon-sm icon-primary material-icons-local_phone"></span>
                            </div>
                            <div class="unit-body">
                                <a href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}">
                                    {{ $contactPhone }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /ASIDE -->

            </div>
        </div>
    </section>
@endsection
