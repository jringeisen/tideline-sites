<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>New contact inquiry</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; line-height: 1.5; color: #0b2a2e; background: #f7f4ee; margin: 0; padding: 24px;">
    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 32px;">
        <p style="font-size: 12px; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: #047857; margin: 0;">
            Tideline Sites
        </p>
        <h1 style="font-size: 22px; margin: 8px 0 24px;">New contact inquiry</h1>

        <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 14px;">
            <tr>
                <td style="padding: 6px 0; color: #475569; width: 110px;">Name</td>
                <td style="padding: 6px 0;">{{ $inquiry->name }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #475569;">Email</td>
                <td style="padding: 6px 0;"><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #475569;">Phone</td>
                <td style="padding: 6px 0;">{{ $inquiry->phone ?? '—' }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #475569;">Plan</td>
                <td style="padding: 6px 0; text-transform: capitalize;">{{ $inquiry->plan ?? '—' }}</td>
            </tr>
        </table>

        <div style="margin-top: 24px;">
            <p style="margin: 0 0 6px; color: #475569; font-size: 14px;">Message</p>
            <div style="white-space: pre-wrap; padding: 16px; background: #f8fafc; border-radius: 12px; font-size: 14px;">{{ $inquiry->message }}</div>
        </div>

        <div style="margin-top: 28px;">
            <a href="{{ $adminUrl }}" style="display: inline-block; background: #0b2a2e; color: #ffffff; padding: 12px 20px; border-radius: 999px; text-decoration: none; font-weight: 600; font-size: 14px;">
                Open in admin
            </a>
        </div>
    </div>
</body>
</html>
