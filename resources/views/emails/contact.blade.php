@php
    $appName = config('app.name', 'HV RF Investments');

    $logoPath = $siteSettings->logo_path ?? null;
    $logoUrl = $logoPath ? 'https://www.hvrfinvestments.com/' . ltrim($logoPath, '/') : null;

    $brandPrimary = '#1E88E5';
    $brandDark = '#111827';
    $pageBg = '#F3F4F6';
    $cardBg = '#FFFFFF';
    $borderSoft = '#E5E7EB';
    $textMuted = '#6B7280';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Contact Message – {{ $appName }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin:0; padding:0; background:{{ $pageBg }}; font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:{{ $pageBg }}; padding:30px 15px;">
        <tr>
            <td align="center">

                <table width="640" cellpadding="0" cellspacing="0"
                    style="background:{{ $cardBg }};
              border-radius:8px;
              border:1px solid {{ $borderSoft }};
              box-shadow:0 10px 30px rgba(0,0,0,0.05);">

                    {{-- HEADER WITH LOGO --}}
                    <tr>
                        <td align="center" style="padding:25px 20px 10px;">
                            @if ($logoUrl)
                                <img src="{{ $logoUrl }}" alt="{{ $appName }}"
                                    style="max-height:50px; display:block;">
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:0 20px 20px;">
                            <div style="font-size:20px; font-weight:700; color:{{ $brandDark }};">
                                New Contact Enquiry
                            </div>
                            <div style="font-size:13px; color:{{ $textMuted }}; margin-top:4px;">
                                A new message has been submitted via your website.
                            </div>
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="padding:0 30px 25px;">

                            {{-- CONTACT DETAILS --}}
                            <table width="100%" cellpadding="8" cellspacing="0"
                                style="font-size:14px; color:{{ $brandDark }};">
                                <tr>
                                    <td width="130"><strong>Full Name:</strong></td>
                                    <td>{{ $first_name }} {{ $last_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email Address:</strong></td>
                                    <td>
                                        <a href="mailto:{{ $email }}"
                                            style="color:{{ $brandPrimary }}; text-decoration:none;">
                                            {{ $email }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $phone ?? 'Not Provided' }}</td>
                                </tr>
                            </table>

                            <div style="border-top:1px solid {{ $borderSoft }}; margin:20px 0;"></div>

                            {{-- MESSAGE CONTENT --}}
                            <div
                                style="font-size:14px; font-weight:600; margin-bottom:8px; color:{{ $brandDark }};">
                                Message Details:
                            </div>

                            <div
                                style="
                background:#F9FAFB;
                border-left:4px solid {{ $brandPrimary }};
                padding:16px;
                font-size:14px;
                line-height:1.7;
                color:{{ $brandDark }};
                border-radius:6px;
            ">
                                {!! nl2br(e($body)) !!}
                            </div>

                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td
                            style="background:#F9FAFB; padding:18px; text-align:center; font-size:12px; color:{{ $textMuted }};">
                            <p style="margin:0;">
                                This message was submitted through the contact form on
                                <strong>{{ $appName }}</strong>.
                            </p>
                            <p style="margin:6px 0 0;">
                                You may reply directly to this email to respond to the sender.
                            </p>
                            <p style="margin:10px 0 0;">
                                © {{ date('Y') }} {{ $appName }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
