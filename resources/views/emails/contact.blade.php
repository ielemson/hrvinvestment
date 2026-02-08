<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Contact Message</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; border-radius:6px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05);">

                    {{-- HEADER --}}
                    <tr>
                        <td style="background:#0d6efd; padding:20px; text-align:center; color:#ffffff;">
                            <h2 style="margin:0; font-size:20px;">
                                New Contact Message
                            </h2>
                            <p style="margin:5px 0 0; font-size:13px; opacity:0.9;">
                                {{ config('app.name') }}
                            </p>
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="padding:25px;">
                            <p style="margin-top:0; font-size:14px; color:#333;">
                                You have received a new message via the contact form.
                            </p>

                            <table width="100%" cellpadding="6" cellspacing="0" style="font-size:14px; color:#333;">
                                <tr>
                                    <td width="120"><strong>Name:</strong></td>
                                    <td>{{ $first_name }} {{ $last_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $phone ?? 'N/A' }}</td>
                                </tr>
                            </table>

                            <hr style="border:none; border-top:1px solid #e5e7eb; margin:20px 0;">

                            <p style="margin-bottom:8px; font-size:14px; color:#333;">
                                <strong>Message:</strong>
                            </p>

                            <div
                                style="background:#f9fafb; border-left:4px solid #0d6efd; padding:15px; font-size:14px; color:#333; line-height:1.6;">
                                {!! nl2br(e($body)) !!}
                            </div>
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background:#f9fafb; padding:15px; text-align:center; font-size:12px; color:#6b7280;">
                            <p style="margin:0;">
                                This email was generated from the contact form on
                                <strong>{{ config('app.name') }}</strong>.
                            </p>
                            <p style="margin:5px 0 0;">
                                Please reply directly to this email to respond to the sender.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>
