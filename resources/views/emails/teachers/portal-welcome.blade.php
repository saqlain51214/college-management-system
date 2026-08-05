@extends('emails.layout')
@section('subject', 'Teacher Portal Access')

@section('content')
<h2 style="margin:0 0 16px; font-size:20px; color:#1c1917;">Teacher Portal Access</h2>

<p style="margin:0 0 16px;">Dear {{ $teacher->name }},</p>
<p style="margin:0 0 20px;">Your teacher portal account is ready. You can log in using the details below.</p>

<div style="background:#faf9f6; border:1px solid #eee; border-radius:10px; padding:18px 20px; margin-bottom:20px;">
    <p class="field-label">Portal URL</p>
    <p class="field-value"><a href="{{ route('teacher.login') }}">{{ route('teacher.login') }}</a></p>
    <p class="field-label">Employee ID</p>
    <p class="field-value">{{ $teacher->employee_id }}</p>
    <p class="field-label" style="margin-bottom:2px;">Default Password</p>
    <p class="field-value" style="margin-bottom:0;">123456 (unless the administration has shared a different temporary password with you)</p>
</div>

<p style="margin:0 0 20px;">For security, please log in and change your password as soon as possible.</p>

<p style="text-align:center; margin:24px 0;">
    <a href="{{ route('teacher.login') }}" class="btn">Log In to Portal</a>
</p>
@endsection
