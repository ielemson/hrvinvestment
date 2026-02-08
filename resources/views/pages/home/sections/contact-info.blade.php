<section class="section section-sm">
    <div class="container">
        <div class="layout-bordered">

            {{-- PHONE --}}
            @php
                use Illuminate\Support\Str;

                $phonesRaw = $siteSettings->contact_phone ?? '';
                $phones = collect(explode(',', $phonesRaw))->map(fn($p) => trim($p))->filter()->values();
            @endphp

            <div class="layout-bordered-item wow-outer">
                <div class="layout-bordered-item-inner wow slideInUp">
                    <div class="icon icon-lg mdi mdi-phone text-primary"></div>

                    <ul class="list-0">
                        @forelse ($phones as $phone)
                            @php
                                $tel = preg_replace('/\s+/', '', $phone);
                            @endphp
                            <li>
                                <a class="link-default" href="tel:{{ $tel }}">
                                    {{ $phone }}
                                </a>
                            </li>
                        @empty
                            <li>
                                <span class="text-muted">Phone not available</span>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- EMAIL --}}
            <div class="layout-bordered-item wow-outer">
                <div class="layout-bordered-item-inner wow slideInUp">
                    <div class="icon icon-lg mdi mdi-email text-primary"></div>

                    @if (!empty($siteSettings->contact_email))
                        <a class="link-default" href="mailto:{{ $siteSettings->contact_email }}">
                            {{ $siteSettings->contact_email }}
                        </a>
                    @else
                        <span class="text-muted">Email not available</span>
                    @endif
                </div>
            </div>

            {{-- ADDRESS --}}
            <div class="layout-bordered-item wow-outer">
                <div class="layout-bordered-item-inner wow slideInUp">
                    <div class="icon icon-lg mdi mdi-map-marker text-primary"></div>

                    @php
                        // Recommended column: contact_address
                        $address = $siteSettings->contact_address ?? ($siteSettings->meta_description ?? '');
                    @endphp

                    @if (!empty($address))
                        <a class="link-default" href="#">
                            {!! nl2br(e(Str::limit($address, 50))) !!}
                        </a>
                    @else
                        <span class="text-muted">Address not available</span>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
