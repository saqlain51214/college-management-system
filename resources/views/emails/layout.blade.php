@php
    $collegeName = \App\Models\CollegeSetting::get('college_name', 'Jinnah Degree College Astore');
    $collegeAddress = \App\Models\CollegeSetting::get('college_address', 'Astore, Gilgit-Baltistan, Pakistan');
    $logoPath = \App\Models\CollegeSetting::get('college_logo');
    $logoUrl = $logoPath ? asset('storage/' . $logoPath) : asset('assets/images/default/cologe-logo-web.png');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('subject', $collegeName)</title>
<style>
    body { margin:0; padding:0; background:#f4f1ea; font-family:Arial,Helvetica,sans-serif; color:#292524; }
    a { color:#6b2d39; }
    .field-label { font-size:11px; text-transform:uppercase; letter-spacing:.04em; color:#a8a29e; margin:0 0 2px; }
    .field-value { font-size:14px; color:#292524; margin:0 0 14px; }
    .btn { display:inline-block; padding:11px 26px; border-radius:8px; background:#6b2d39; color:#ffffff !important; font-size:14px; font-weight:bold; text-decoration:none; }
    @media (max-width: 480px) {
        .email-container { width:100% !important; border-radius:0 !important; }
        .email-content { padding:24px 20px !important; }
    }
</style>
</head>
<body>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f1ea; padding:28px 12px;">
<tr><td align="center">
<table role="presentation" width="560" cellpadding="0" cellspacing="0" class="email-container" style="width:560px; max-width:100%; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 6px 24px rgba(0,0,0,0.08);">

    {{-- Header --}}
    <tr><td style="background:linear-gradient(135deg,#6b2d39,#5a2430); padding:30px 32px; text-align:center;">
        <img src="{{ $logoUrl }}" alt="{{ $collegeName }}" style="height:52px; margin-bottom:12px; display:inline-block;">
        <div style="color:#ffffff; font-size:18px; font-weight:bold; line-height:1.3;">{{ $collegeName }}</div>
        <div style="color:rgba(255,255,255,.75); font-size:12px; margin-top:2px;">{{ $collegeAddress }}</div>
    </td></tr>

    {{-- Content --}}
    <tr><td class="email-content" style="padding:34px 32px; font-size:14px; line-height:1.7;">
        @yield('content')
    </td></tr>

    {{-- Footer --}}
    <tr><td style="background:#f9f7f2; padding:20px 32px; text-align:center; border-top:1px solid #eee;">
        <p style="margin:0 0 4px; font-size:12px; color:#78716c;">This is an automated email from {{ $collegeName }} — please do not reply directly to this message.</p>
        <p style="margin:0; font-size:11px; color:#a8a29e;">&copy; {{ date('Y') }} {{ $collegeName }}. All rights reserved.</p>
    </td></tr>

</table>
</td></tr>
</table>
</body>
</html>
