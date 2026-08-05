@extends('emails.layout')
@section('subject', 'Reply to Your Message')

@section('content')
<h2 style="margin:0 0 16px; font-size:20px; color:#1c1917;">Reply to Your Message</h2>

<p style="margin:0 0 16px;">Dear {{ $contactMessage->name }},</p>

<div style="margin:0 0 20px; white-space:pre-line;">{{ $contactMessage->reply }}</div>

<div style="background:#faf9f6; border:1px solid #eee; border-radius:10px; padding:18px 20px; margin-bottom:0;">
    <p class="field-label" style="margin-bottom:2px;">Your original message</p>
    <p class="field-value" style="margin-bottom:0; white-space:pre-line;">{{ $contactMessage->message }}</p>
</div>
@endsection
