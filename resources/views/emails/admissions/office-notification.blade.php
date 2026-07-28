@extends('emails.layout')
@section('subject', 'New Admission Inquiry')

@section('content')
<h2 style="margin:0 0 16px; font-size:20px; color:#1c1917;">New Admission Inquiry</h2>

<p style="margin:0 0 20px;">A new online admission application has been received.</p>

<div style="background:#faf9f6; border:1px solid #eee; border-radius:10px; padding:18px 20px;">
    <p class="field-label">Reference No</p>
    <p class="field-value">{{ $admissionInquiry->reference_no }}</p>
    <p class="field-label">Name</p>
    <p class="field-value">{{ $admissionInquiry->name }}</p>
    <p class="field-label">Email</p>
    <p class="field-value">{{ $admissionInquiry->email }}</p>
    <p class="field-label">Phone</p>
    <p class="field-value">{{ $admissionInquiry->phone }}</p>
    <p class="field-label">Programme</p>
    <p class="field-value">{{ $admissionInquiry->program?->name ?? 'N/A' }}</p>
    <p class="field-label" style="margin-bottom:2px;">Entry Path</p>
    <p class="field-value" style="margin-bottom:0;">{{ ucfirst((string) $admissionInquiry->entry_path) }}</p>
</div>
@endsection
