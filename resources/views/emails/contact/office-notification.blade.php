@extends('emails.layout')
@section('subject', 'New Contact Message')

@section('content')
<h2 style="margin:0 0 16px; font-size:20px; color:#1c1917;">New Contact Message</h2>

<p style="margin:0 0 20px;">A new contact message was submitted from the public website.</p>

<div style="background:#faf9f6; border:1px solid #eee; border-radius:10px; padding:18px 20px;">
    <p class="field-label">Name</p>
    <p class="field-value">{{ $contactMessage->name }}</p>
    <p class="field-label">Email</p>
    <p class="field-value">{{ $contactMessage->email }}</p>
    <p class="field-label">Subject</p>
    <p class="field-value">{{ $contactMessage->subject }}</p>
    <p class="field-label" style="margin-bottom:2px;">Message</p>
    <p class="field-value" style="margin-bottom:0; white-space:pre-line;">{{ $contactMessage->message }}</p>
</div>
@endsection
