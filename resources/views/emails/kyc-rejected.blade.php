@php
    $brandPrimary = '#3056D3';
    $statusColor = '#EF4444'; // Red for rejected
@endphp

<!DOCTYPE html>
<html>

<body>
    <h2>KYC Review Update</h2>

    <p>Dear {{ $kyc->full_name }},</p>

    <p>Your KYC submission requires some updates before approval.</p>

    <div style="padding: 16px; background: #FEE2E2; border-radius: 12px; border-left: 4px solid {{ $statusColor }};">
        <strong>Status:</strong> ❌ Requires Changes
    </div>

    <h4>Reason for review:</h4>
    <div style="padding: 16px; background: #FEF3C7; border-radius: 8px; border-left: 4px solid #F59E0B;">
        <p>{{ $kyc->rejection_reason }}</p>
    </div>

    <p><strong>Next steps:</strong></p>
    <ul>
        <li>Login and resubmit updated documents</li>
        <li>Review admin feedback above</li>
        <li>We'll re-review within 24 hours</li>
    </ul>

    <p>Best,<br>{{ $appName }} Team</p>
</body>

</html>
