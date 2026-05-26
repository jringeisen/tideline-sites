<?php
use App\Enums\InquirySource;

$isSeoAssessment = $inquiry->source === InquirySource::SeoAssessment;
$heading = $isSeoAssessment ? 'New SEO assessment request' : 'New contact inquiry';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $heading }}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; line-height: 1.5; color: #0b2a2e; background: #f7f4ee; margin: 0; padding: 24px;">
    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 32px;">
        <p style="font-size: 12px; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: #047857; margin: 0;">
            All American Web Design
        </p>
        <h1 style="font-size: 22px; margin: 8px 0 24px;">{{ $heading }}</h1>

        <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 14px;">
            <tr>
                <td style="padding: 6px 0; color: #475569; width: 130px;">Name</td>
                <td style="padding: 6px 0;">{{ $inquiry->name }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #475569;">Email</td>
                <td style="padding: 6px 0;"><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></td>
            </tr>
            @if ($inquiry->business_name)
                <tr>
                    <td style="padding: 6px 0; color: #475569;">Business</td>
                    <td style="padding: 6px 0;">{{ $inquiry->business_name }}</td>
                </tr>
            @endif
            @if ($inquiry->website)
                <tr>
                    <td style="padding: 6px 0; color: #475569;">Website</td>
                    <td style="padding: 6px 0;"><a href="{{ $inquiry->website }}">{{ $inquiry->website }}</a></td>
                </tr>
            @endif
            @if ($inquiry->phone)
                <tr>
                    <td style="padding: 6px 0; color: #475569;">Phone</td>
                    <td style="padding: 6px 0;">{{ $inquiry->phone }}</td>
                </tr>
            @endif
            @if ($inquiry->plan)
                <tr>
                    <td style="padding: 6px 0; color: #475569;">Plan</td>
                    <td style="padding: 6px 0; text-transform: capitalize;">{{ $inquiry->plan }}</td>
                </tr>
            @endif
            @if ($inquiry->is_veteran)
                <tr>
                    <td style="padding: 6px 0; color: #475569;">Veteran</td>
                    <td style="padding: 6px 0;">
                        <span style="display: inline-block; background: #b4564b; color: #ffffff; padding: 2px 10px; border-radius: 999px; font-size: 12px; font-weight: 600;">Yes — apply 20% discount</span>
                    </td>
                </tr>
            @endif
        </table>

        @if ($inquiry->message)
            <div style="margin-top: 24px;">
                <p style="margin: 0 0 6px; color: #475569; font-size: 14px;">Message</p>
                <div style="white-space: pre-wrap; padding: 16px; background: #f8fafc; border-radius: 12px; font-size: 14px;">{{ $inquiry->message }}</div>
            </div>
        @endif

        <div style="margin-top: 28px;">
            <a href="{{ $adminUrl }}" style="display: inline-block; background: #0b2a2e; color: #ffffff; padding: 12px 20px; border-radius: 999px; text-decoration: none; font-weight: 600; font-size: 14px;">
                Open in admin
            </a>
        </div>
    </div>
</body>
</html>
