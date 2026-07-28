@extends('emails.layout')
@section('subject', $heading)

@section('content')
<h2 style="margin:0 0 16px; font-size:20px; color:#1c1917;">{{ $heading }}</h2>

<div style="white-space:pre-line; margin:0 0 20px;">{{ $body }}</div>

<div style="background:#faf9f6; border:1px solid #eee; border-radius:10px; padding:18px 20px;">
    <p class="field-label">Position</p>
    <p class="field-value" style="margin-bottom:0;">{{ $jobApplication->position }}</p>
</div>
@endsection
