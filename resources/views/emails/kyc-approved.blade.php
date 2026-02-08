{{-- Use same structure/colors as your kyc-submitted template --}}
@php
    $brandPrimary = '#3056D3';
    $statusColor = '#10B981'; // Green for approved
@endphp

<!DOCTYPE html>
<html>

<body>
    <h2>KYC Approved! 🎉</h2>

    <p>Dear {{ $kyc->full_name }},</p>

    <p>Great news! Your KYC verification has been <strong>approved</strong>.</p>

    <div style="padding: 16px; background: #D1FAE5; border-radius: 12px; border-left: 4px solid {{ $statusColor }};">
        <strong>Status:</strong> ✅ Approved
    </div>

    <p><strong>What this means:</strong></p>
    <ul>
        <li>Access to loan applications unlocked</li>
        <li>Higher loan limits available</li>
        <li>Faster approval processing</li>
    </ul>

    <p>Start your first loan application from your dashboard.</p>

    <p>Best,<br>{{ $appName }} Team</p>
</body>

</html>
