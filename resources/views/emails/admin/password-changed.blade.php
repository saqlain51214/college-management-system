@extends('emails.layout')
@section('subject', 'Password Changed')

@section('content')
<h2 style="margin:0 0 16px; font-size:20px; color:#1c1917;">Password Changed</h2>

<p style="margin:0 0 16px;">Dear {{ $user->name }},</p>
<p style="margin:0 0 20px;">Your admin panel password was changed successfully.</p>

<p style="margin:0 0 12px;">If you made this change, no action is needed.</p>
<p style="margin:0; padding:14px 16px; background:#fdf3f4; border-left:3px solid #6b2d39; border-radius:6px; font-size:13px;">
    If you did <strong>not</strong> make this change, please contact another administrator immediately.
</p>
@endsection
