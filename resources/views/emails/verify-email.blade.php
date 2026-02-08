@php
    // Align these with your site theme (pulled from your landing page styles)
    $brandPrimary = '#3056D3';
    $brandPrimaryAlt = '#4F46E5';
    $brandDark = '#0B1120';
    $pageBg = '#F3F4F6';
    $cardBg = '#FFFFFF';
    $borderSoft = '#E5E7EB';
    $textDark = '#0F172A';
    $textMuted = '#6B7280';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $appName }} – Verify Email</title>
</head>

<body style="margin:0; padding:0; background: {{ $pageBg }}; font-family: Arial, Helvetica, sans-serif;">

    <!-- Outer wrapper -->
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
        style="background: {{ $pageBg }}; padding: 32px 12px;">
        <tr>
            <td align="center">

                <!-- Main container -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                    style="max-width: 640px; border-collapse: collapse;">

                    <!-- Top brand bar -->
                    <tr>
                        <td style="padding-bottom: 10px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left"
                                        style="font-size: 18px; font-weight: 700; color: {{ $brandDark }};">
                                        {{ $appName }}
                                    </td>
                                    <td align="right" style="font-size: 11px; color: {{ $textMuted }};">
                                        Account security
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Card -->
                    <tr>
                        <td
                            style="
                            background: {{ $cardBg }};
                            border-radius: 16px;
                            border: 1px solid {{ $borderSoft }};
                            box-shadow: 0 12px 30px rgba(15,23,42,0.08);
                        ">
                            <!-- Card inner padding -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding: 24px 24px 22px;">

                                        <!-- Small label -->
                                        <div
                                            style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: {{ $brandPrimary }}; font-weight: 600;">
                                            Verify your email
                                        </div>

                                        <!-- Main heading -->
                                        <div
                                            style="margin-top: 6px; font-size: 20px; font-weight: 700; color: {{ $textDark }}; line-height: 1.4;">
                                            Confirm your email to finish setting up your account.
                                        </div>

                                        <!-- Body copy -->
                                        <div
                                            style="margin-top: 12px; font-size: 14px; line-height: 1.7; color: {{ $textMuted }};">
                                            Hello{{ isset($user->name) ? ' ' . e($user->name) : '' }},
                                            <br>
                                            Thanks for creating an account with
                                            <span
                                                style="color: {{ $textDark }}; font-weight: 600;">{{ $appName }}</span>.
                                            Click the button below to confirm this email address and activate your
                                            account.
                                        </div>

                                        <!-- CTA -->
                                        <table role="presentation" cellspacing="0" cellpadding="0"
                                            style="margin-top: 20px;">
                                            <tr>
                                                <td
                                                    style="
                                                    border-radius: 999px;
                                                    background: linear-gradient(135deg, {{ $brandPrimary }} 0%, {{ $brandPrimaryAlt }} 100%);
                                                ">
                                                    <a href="{{ $verifyUrl }}"
                                                        style="
                                                            display:inline-block;
                                                            padding: 12px 28px;
                                                            font-size: 14px;
                                                            font-weight: 700;
                                                            letter-spacing: 0.04em;
                                                            text-transform: uppercase;
                                                            color:#FFFFFF;
                                                            text-decoration:none;
                                                            border-radius:999px;
                                                       ">
                                                        Verify email
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Meta line -->
                                        <div
                                            style="margin-top: 14px; font-size: 12px; line-height: 1.7; color: {{ $textMuted }};">
                                            This link will expire in
                                            <span
                                                style="color: {{ $textDark }}; font-weight: 600;">{{ $expireMinutes }}
                                                minutes</span>.
                                            If you didn’t try to sign up, you can safely ignore this message.
                                        </div>

                                        <!-- Divider -->
                                        <div style="margin-top: 18px; border-top: 1px solid {{ $borderSoft }};">
                                        </div>

                                        <!-- Fallback URL -->
                                        <div style="margin-top: 16px;">
                                            <div
                                                style="font-size: 12px; color: {{ $textMuted }}; margin-bottom: 6px;">
                                                If the button above does not work, copy and paste this link into your
                                                browser:
                                            </div>
                                            <div
                                                style="
                                                font-size: 12px;
                                                word-break: break-all;
                                                color: {{ $brandPrimary }};
                                                padding: 10px 12px;
                                                border-radius: 10px;
                                                background: #F9FAFB;
                                                border: 1px solid {{ $borderSoft }};
                                            ">
                                                {{ $verifyUrl }}
                                            </div>
                                        </div>

                                        <!-- Support hint -->
                                        <div style="margin-top: 16px; font-size: 12px; color: {{ $textMuted }};">
                                            Need help? Reply to this email and the support team will assist you.
                                        </div>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer note -->
                    <tr>
                        <td
                            style="padding-top: 14px; font-size: 11px; color: {{ $textMuted }}; text-align: center;">
                            © {{ date('Y') }} {{ $appName }}. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
