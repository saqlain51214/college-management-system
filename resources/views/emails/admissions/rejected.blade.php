@extends('emails.layout')
@section('subject', 'Admission Application Update')

@section('content')
<h2 style="margin:0 0 16px; font-size:20px; color:#1c1917;">Admission Application Update</h2>

<p style="margin:0 0 16px;">Dear {{ $admissionInquiry->name }},</p>
<p style="margin:0 0 20px;">Thank you for your interest in applying to Jinnah Degree College Astore.</p>

<div style="background:#faf9f6; border:1px solid #eee; border-radius:10px; padding:18px 20px; margin-bottom:20px;">
    <p class="field-label">Reference No</p>
    <p class="field-value">{{ $admissionInquiry->reference_no }}</p>
    <p class="field-label" style="margin-bottom:2px;">Programme</p>
    <p class="field-value" style="margin-bottom:0;">{{ $admissionInquiry->program?->name ?? 'N/A' }}</p>
</div>

<p style="margin:0 0 16px;">After reviewing your application, we are unable to offer you admission at this time.</p>

@if($admissionInquiry->admin_notes)
<p style="margin:0 0 16px; padding:14px 16px; background:#fdf3f4; border-left:3px solid #6b2d39; border-radius:6px; font-size:13px;">
    <strong>Note from the admissions office:</strong> {{ $admissionInquiry->admin_notes }}
</p>
@endif

<p style="margin:0;">We encourage you to apply again in a future admission cycle. If you have any questions, please contact the admissions office.</p>
@endsection
