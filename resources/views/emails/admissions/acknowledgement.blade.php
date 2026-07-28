@extends('emails.layout')
@section('subject', 'Admission Application Received')

@section('content')
<h2 style="margin:0 0 16px; font-size:20px; color:#1c1917;">Admission Application Received</h2>

<p style="margin:0 0 16px;">Dear {{ $admissionInquiry->name }},</p>
<p style="margin:0 0 20px;">Your online admission application has been received successfully.</p>

<div style="background:#faf9f6; border:1px solid #eee; border-radius:10px; padding:18px 20px; margin-bottom:20px;">
    <p class="field-label">Reference No</p>
    <p class="field-value">{{ $admissionInquiry->reference_no }}</p>
    <p class="field-label">Programme</p>
    <p class="field-value">{{ $admissionInquiry->program?->name ?? 'N/A' }}</p>
    <p class="field-label" style="margin-bottom:2px;">Entry Path</p>
    <p class="field-value" style="margin-bottom:0;">{{ ucfirst((string) $admissionInquiry->entry_path) }}</p>
</div>

<p style="margin:0;">Please keep your original documents ready. The admission office may contact you for verification or the next step.</p>
@endsection
