@extends('emails.layout')
@section('subject', 'Message Received')

@section('content')
<h2 style="margin:0 0 16px; font-size:20px; color:#1c1917;">Message Received</h2>

<p style="margin:0 0 16px;">Dear {{ $contactMessage->name }},</p>
<p style="margin:0 0 20px;">Thank you for contacting {{ \App\Models\CollegeSetting::get('college_name', config('app.name')) }}. We have received your message and our team will review it shortly.</p>

<div style="background:#faf9f6; border:1px solid #eee; border-radius:10px; padding:18px 20px;">
    <p class="field-label">Subject</p>
    <p class="field-value">{{ $contactMessage->subject }}</p>
    <p class="field-label" style="margin-bottom:2px;">Message</p>
    <p class="field-value" style="margin-bottom:0; white-space:pre-line;">{{ $contactMessage->message }}</p>
</div>

<p style="margin:20px 0 0;">If your matter is urgent, please contact the college office directly.</p>
@endsection
