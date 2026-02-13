@php
    // Theme
    $brandPrimary = '#1E88E5'; // Carbon-like blue
    $brandDark = '#111827';
    $pageBg = '#F3F4F6';
    $cardBg = '#FFFFFF';
    $borderSoft = '#E5E7EB';
    $textMuted = '#6B7280';

    // Branding
    $appName = $appName ?? config('app.name', 'HV RF Investments');

    // Public logo url (email-safe)
    $logoPath = $siteSettings->logo_path ?? null;
    $logoUrl = $logoPath ? 'https://www.hvrfinvestments.com/' . ltrim($logoPath, '/') : null;

    // Optional support email (fallback)
    $supportEmail = $siteSettings->contact_email ?? 'info@hvrfinvestments.com';

    // Social links (optional)
    $twitter = $siteSettings->twitter_url ?? null;
    $linkedin = $siteSettings->linkedin_url ?? null;
    $telegram = $siteSettings->telegram_url ?? null;
    $facebook = $siteSettings->facebook_url ?? null;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $appName }} – Verify Email</title>
</head>

<body
    style="margin:0; padding:0; background: {{ $pageBg }}; font-family: Arial, Helvetica, sans-serif; color: {{ $brandDark }};">

    {{-- Outer wrapper --}}
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
        style="background: {{ $pageBg }}; padding: 28px 12px;">
        <tr>
            <td align="center">

                {{-- Container --}}
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                    style="max-width: 720px; border-collapse: collapse;">

                    {{-- Logo --}}
                    <tr>
                        <td align="center" style="padding: 6px 0 18px;">
                            @if ($logoUrl)
                                <img src="{{ $logoUrl }}" alt="{{ $appName }}"
                                    style="display:block; max-height:42px; height:auto; width:auto;">
                            @else
                                <div style="font-weight:800; letter-spacing: .04em; font-size: 18px;">
                                    {{ $appName }}</div>
                            @endif
                        </td>
                    </tr>

                    {{-- Card --}}
                    <tr>
                        <td align="center" style="padding: 0 10px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                style="background: {{ $cardBg }}; border: 1px solid {{ $borderSoft }}; border-radius: 6px; border-collapse: separate;">
                                <tr>
                                    <td style="padding: 28px 28px 22px;">

                                        <div style="font-size: 22px; font-weight: 800; margin: 0 0 14px;">
                                            Verify This Email Address
                                        </div>

                                        <div style="font-size: 14px; color: {{ $brandDark }}; line-height: 1.7;">
                                            Hi {{ isset($user->name) ? e($user->name) : 'there' }},
                                        </div>

                                        <div
                                            style="font-size: 14px; color: {{ $brandDark }}; line-height: 1.7; margin-top: 10px;">
                                            Welcome to {{ $appName }}!
                                        </div>

                                        <div
                                            style="font-size: 14px; color: {{ $brandDark }}; line-height: 1.7; margin-top: 10px;">
                                            Please click the button below to verify your email address.
                                        </div>

                                        <div
                                            style="font-size: 13px; color: {{ $textMuted }}; line-height: 1.7; margin-top: 12px;">
                                            If you did not sign up to {{ $appName }}, please ignore this email or
                                            contact us at
                                            <a href="mailto:{{ $supportEmail }}"
                                                style="color: {{ $brandPrimary }}; text-decoration: none;">
                                                {{ $supportEmail }}
                                            </a>.
                                        </div>

                                        {{-- CTA --}}
                                        <table role="presentation" cellspacing="0" cellpadding="0"
                                            style="margin: 20px auto 10px;">
                                            <tr>
                                                <td align="center"
                                                    style="border-radius: 4px; background: {{ $brandPrimary }};">
                                                    <a href="{{ $verifyUrl }}"
                                                        style="display:inline-block; padding: 12px 22px; font-size: 14px; font-weight: 700; color: #fff; text-decoration: none; border-radius: 4px;">
                                                        Verify Email
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>

                                        {{-- Expiry --}}
                                        <div
                                            style="text-align:center; font-size: 12px; color: {{ $textMuted }}; margin-top: 10px;">
                                            This link expires in <strong
                                                style="color: {{ $brandDark }};">{{ $expireMinutes }}
                                                minutes</strong>.
                                        </div>

                                        {{-- Signature --}}
                                        <div
                                            style="font-size: 13px; color: {{ $brandDark }}; margin-top: 16px; line-height: 1.6;">
                                            {{ $appName }} Support Team
                                        </div>

                                        {{-- Fallback URL --}}
                                        <div style="margin-top: 18px; border-top: 1px solid {{ $borderSoft }};">
                                        </div>
                                        <div style="margin-top: 14px; font-size: 12px; color: {{ $textMuted }};">
                                            If the button above does not work, copy and paste this link into your
                                            browser:
                                        </div>
                                        <div
                                            style="margin-top: 8px; font-size: 12px; color: {{ $brandPrimary }}; word-break: break-all; background: #F9FAFB; border: 1px solid {{ $borderSoft }}; border-radius: 6px; padding: 10px 12px;">
                                            {{ $verifyUrl }}
                                        </div>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Social row (simple, email-safe “circle buttons”) --}}
                    <tr>
                        <td align="center" style="padding: 18px 0 6px;">
                            <table role="presentation" cellspacing="0" cellpadding="0">
                                <tr>
                                    @php
                                        $social = [
                                            ['url' => $twitter, 'label' => 'X'],
                                            ['url' => $linkedin, 'label' => 'in'],
                                            ['url' => $telegram, 'label' => 'tg'],
                                            ['url' => $facebook, 'label' => 'f'],
                                        ];
                                    @endphp

                                    @foreach ($social as $s)
                                        <td style="padding: 0 6px;">
                                            @if (!empty($s['url']))
                                                <a href="{{ $s['url'] }}" style="text-decoration:none;">
                                                    <span
                                                        style="display:inline-block; width:30px; height:30px; line-height:30px; text-align:center; border-radius:999px; background:#DCEBFF; color:#1C4ED8; font-weight:700; font-size:12px;">
                                                        {{ $s['label'] }}
                                                    </span>
                                                </a>
                                            @else
                                                <span
                                                    style="display:inline-block; width:30px; height:30px; line-height:30px; text-align:center; border-radius:999px; background:#EEF2F7; color:#94A3B8; font-weight:700; font-size:12px;">
                                                    {{ $s['label'] }}
                                                </span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Need Support --}}
                    <tr>
                        <td align="left" style="padding: 14px 10px 4px;">
                            <div style="font-weight: 800; font-size: 13px; color: {{ $brandDark }};">Need Support?
                            </div>
                            <div
                                style="margin-top: 6px; font-size: 12px; color: {{ $textMuted }}; line-height: 1.7;">
                                Feel free to email us if you have any questions, comments or suggestions. We’ll be happy
                                to resolve your issues.
                                <br>
                                <a href="mailto:{{ $supportEmail }}"
                                    style="color: {{ $brandPrimary }}; text-decoration:none;">
                                    {{ $supportEmail }}
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td align="center" style="padding: 14px 10px 0; font-size: 11px; color: {{ $textMuted }};">
                            © {{ date('Y') }} {{ $appName }}. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
