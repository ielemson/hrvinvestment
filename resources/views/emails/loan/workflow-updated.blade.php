@php

    $siteName =
        $siteSettings->site_name ??
        config('app.name', 'HV UK RF1 INVESTMENTS LTD &amp; HV Royalty Acquisition II Trust');
    $logo = 'https://www.hvrfinvestments.com/' . ($siteSettings->logo_path ?? 'uploads/settings/default-logo.png');

    $contactEmail = $siteSettings->contact_email ?? 'info@hvrfinvestments.com';
    $contactPhone = $siteSettings->contact_phone ?? '';
    $contactAddress = $siteSettings->contact_address ?? null;

    $statusColor = match (strtolower($statusText ?? '')) {
        'approved' => '#28a745',
        'reviewed' => '#007bff',
        default => '#ffc107',
    };
@endphp

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Loan Update</title>
</head>

<body style="background:#f4f6f9;padding:30px 15px;font-family:Arial,Helvetica,sans-serif;">

    <div style="max-width:620px;margin:auto;">

        {{-- Header Logo --}}
        <div style="text-align:center;margin-bottom:25px;">
            <img src="{{ asset($logo) }}" alt="{{ $siteName }}" style="max-height:60px;">
        </div>

        {{-- Card --}}
        <div
            style="background:#ffffff;padding:30px;border-radius:8px;
                    box-shadow:0 3px 12px rgba(0,0,0,0.05);">

            <h2 style="margin-top:0;color:#343a40;">
                Loan Application Update
            </h2>

            <p style="color:#495057;">
                Hello <strong>{{ $loan->user->name }}</strong>,
            </p>

            <p style="color:#495057;">
                Your loan application has progressed to a new stage.
            </p>

            {{-- Details Box --}}
            <div style="background:#f8f9fa;padding:15px 20px;border-radius:6px;margin:20px 0;">
                <p style="margin:5px 0;">
                    <strong>Reference:</strong> {{ $reference }}
                </p>

                <p style="margin:5px 0;">
                    <strong>Stage:</strong> {{ $levelLabel }}
                </p>

                <p style="margin:5px 0;">
                    <strong>Status:</strong>
                    <span style="color:{{ $statusColor }};font-weight:600;">
                        {{ $statusText }}
                    </span>
                </p>
            </div>

            @if (!empty($notes))
                <div style="margin-bottom:20px;">
                    <strong>Admin Notes:</strong>
                    <p style="margin-top:6px;color:#495057;">
                        {{ $notes }}
                    </p>
                </div>
            @endif

            {{-- CTA Button --}}
            <div style="text-align:center;margin-top:25px;">
                <a href="{{ route('user.dashboard', $loan->id) }}"
                    style="background:#007bff;color:#ffffff;
                          padding:12px 22px;
                          border-radius:4px;
                          text-decoration:none;
                          font-weight:600;
                          display:inline-block;">
                    View Loan Details
                </a>
            </div>

        </div>

        {{-- Footer --}}
        <div style="text-align:center;margin-top:30px;font-size:13px;color:#6c757d;">
            <p style="margin:6px 0;">
                <strong>{{ $siteName }}</strong>
            </p>

            @if ($contactAddress)
                <p style="margin:4px 0;">{{ $contactAddress }}</p>
            @endif

            <p style="margin:4px 0;">
                {{ $contactPhone }} |
                <a href="mailto:{{ $contactEmail }}" style="color:#6c757d;text-decoration:none;">
                    {{ $contactEmail }}
                </a>
            </p>

            <p style="margin-top:12px;">
                © {{ date('Y') }} {{ $siteName }}. All rights reserved.
            </p>
        </div>

    </div>

</body>

</html>
