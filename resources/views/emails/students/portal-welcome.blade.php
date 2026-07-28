@extends('emails.layout')
@section('subject', 'Student Portal Access')

@section('content')
<h2 style="margin:0 0 16px; font-size:20px; color:#1c1917;">Student Portal Access</h2>

<p style="margin:0 0 16px;">Dear {{ $student->name }},</p>
<p style="margin:0 0 20px;">Your student portal account is ready. You can log in using the details below.</p>

<div style="background:#faf9f6; border:1px solid #eee; border-radius:10px; padding:18px 20px; margin-bottom:20px;">
    <p class="field-label">Portal URL</p>
    <p class="field-value"><a href="{{ route('portal.login') }}">{{ route('portal.login') }}</a></p>
    <p class="field-label">Roll Number</p>
    <p class="field-value">{{ $student->roll_number }}</p>
    <p class="field-label" style="margin-bottom:2px;">Default Password</p>
    <p class="field-value" style="margin-bottom:0;">123456 (unless the administration has shared a different temporary password with you)</p>
</div>

<p style="margin:0 0 20px;">For security, please log in and change your password as soon as possible.</p>

<p style="text-align:center; margin:24px 0;">
    <a href="{{ route('portal.login') }}" class="btn">Log In to Portal</a>
</p>

<p style="margin:0; font-size:13px; color:#78716c;">Need help? Contact: {{ config('platform.notifications.student_support_email') }}</p>
@endsection
