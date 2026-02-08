@php
    // Align these with your site theme
    $brandPrimary = '#3056D3';
    $brandPrimaryAlt = '#4F46E5';
    $brandDark = '#0B1120';
    $pageBg = '#F3F4F6';
    $cardBg = '#FFFFFF';
    $borderSoft = '#E5E7EB';
    $textDark = '#0F172A';
    $textMuted = '#6B7280';
    $statusColor = '#F59E0B'; // Orange for "under review"
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $appName }} – KYC Received</title>
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
                                        KYC Verification
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Card -->
                    <tr>
                        <td
                            style="background: {{ $cardBg }}; border-radius: 16px; border: 1px solid {{ $borderSoft }}; box-shadow: 0 12px 30px rgba(15,23,42,0.08);">
                            <!-- Card inner padding -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding: 24px 24px 22px;">

                                        <!-- Small label -->
                                        <div
                                            style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: {{ $brandPrimary }}; font-weight: 600;">
                                            KYC Submission
                                        </div>

                                        <!-- Main heading -->
                                        <div
                                            style="margin-top: 6px; font-size: 20px; font-weight: 700; color: {{ $textDark }}; line-height: 1.4;">
                                            Your documents have been received
                                        </div>

                                        <!-- Body copy -->
                                        <div
                                            style="margin-top: 12px; font-size: 14px; line-height: 1.7; color: {{ $textMuted }};">
                                            Hello {{ $kyc->full_name ?? ($user->name ?? 'User') }},<br><br>

                                            Thank you for submitting your KYC documents to <span
                                                style="color: {{ $textDark }}; font-weight: 600;">{{ $appName }}</span>.
                                            Your information is now under review by our verification team.
                                        </div>

                                        <!-- Status badge -->
                                        <div
                                            style="margin-top: 20px; padding: 12px 16px; background: #FFF7ED; border-radius: 12px; border: 1px solid #FCD34D;">
                                            <div
                                                style="display: flex; align-items: center; font-size: 14px; font-weight: 600; color: {{ $statusColor }};">
                                                <span
                                                    style="display: inline-block; width: 8px; height: 8px; background: {{ $statusColor }}; border-radius: 50%; margin-right: 8px;"></span>
                                                Status: Under Review
                                            </div>
                                        </div>

                                        <!-- Submitted details -->
                                        <div style="margin-top: 24px;">
                                            <div
                                                style="font-size: 13px; font-weight: 600; color: {{ $textDark }}; margin-bottom: 12px;">
                                                Submitted Information
                                            </div>
                                            <table style="width: 100%; font-size: 14px; color: {{ $textMuted }};"
                                                cellpadding="8">
                                                <tr>
                                                    <td
                                                        style="font-weight: 600; color: {{ $textDark }}; width: 35%;">
                                                        Full Name</td>
                                                    <td>{{ $kyc->full_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: 600; color: {{ $textDark }};">Phone
                                                    </td>
                                                    <td>{{ $kyc->phone }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: 600; color: {{ $textDark }};">Address
                                                    </td>
                                                    <td>{{ $kyc->address }}<br>
                                                        @if ($kyc->city || $kyc->state)
                                                            <span style="font-size: 13px; opacity: 0.8;">
                                                                {{ $kyc->city }}, {{ $kyc->state }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <!-- Next steps -->
                                        <div
                                            style="margin-top: 24px; padding: 16px; background: #F0F9FF; border-radius: 12px; border-left: 4px solid {{ $brandPrimary }};">
                                            <div
                                                style="font-size: 13px; font-weight: 600; color: {{ $brandDark }}; margin-bottom: 6px;">
                                                What happens next?
                                            </div>
                                            <div
                                                style="font-size: 14px; line-height: 1.6; color: {{ $textMuted }};">
                                                • Our team will review within <strong>24-48 hours</strong><br>
                                                • You'll receive email notification of approval or required changes<br>
                                                • Approved KYC unlocks loan applications and higher limits
                                            </div>
                                        </div>

                                        <!-- Divider -->
                                        <div style="margin-top: 24px; border-top: 1px solid {{ $borderSoft }};">
                                        </div>

                                        <!-- Support -->
                                        <div style="margin-top: 16px; font-size: 12px; color: {{ $textMuted }};">
                                            Need help? Reply to this email or contact support@{{ strtolower(str_replace(' ', '', $appName)) }}.com
                                        </div>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="padding-top: 24px; font-size: 11px; color: {{ $textMuted }}; text-align: center;">
                            © {{ date('Y') }} {{ $appName }}. All rights reserved. | Port Harcourt, Nigeria
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
