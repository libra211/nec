<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NEC Verification Code</title>
</head>
<body style="margin:0;padding:0;background:#f3f6f4;font-family:-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f6f4;padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width:520px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);">
                    <tr>
                        <td style="background:#0b6b3a;padding:28px 32px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:22px;">National Elections Commission</h1>
                            <p style="margin:6px 0 0;color:#c8ecd9;font-size:13px;">Republic of South Sudan</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <h2 style="margin:0 0 8px;color:#1a202c;font-size:18px;">Your verification code</h2>
                            <p style="margin:0 0 18px;color:#4a5568;font-size:14px;line-height:1.6;">
                                Use the code below to complete your {{ $purpose === 'voter_registration' ? 'voter registration' : ($purpose === 'voter_account' ? 'voter portal account verification' : $purpose) }}.
                            </p>
                            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:20px;text-align:center;letter-spacing:8px;font-size:30px;font-weight:700;color:#0b6b3a;">
                                {{ $code }}
                            </div>
                            <p style="margin:20px 0 0;color:#718096;font-size:12px;line-height:1.6;">
                                This code expires in <strong>10 minutes</strong>. If you did not request this code, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f7fafc;padding:16px 32px;text-align:center;color:#a0aec0;font-size:11px;">
                            NEC · Formerly Aida Hotel, Bilpam Road, Juba · info@nec.gov.ss
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>