@extends('emails.layout')
@section('subject', $heading)

@section('content')
<h2 style="margin:0 0 16px; font-size:20px; color:#1c1917;">{{ $heading }}</h2>

<p style="margin:0 0 16px;">Dear {{ $admissionInquiry->name }},</p>

<div style="background:#faf9f6; border:1px solid #eee; border-radius:10px; padding:18px 20px; margin-bottom:20px;">
    <p class="field-label">Reference No</p>
    <p class="field-value">{{ $admissionInquiry->reference_no }}</p>
    <p class="field-label" style="margin-bottom:2px;">Programme</p>
    <p class="field-value" style="margin-bottom:0;">{{ $admissionInquiry->program?->name ?? 'N/A' }}</p>
</div>

@if($admissionInquiry->status === 'enrolled')
<p style="margin:0 0 16px;">We are delighted to confirm your enrollment at Jinnah Degree College Astore. Our office will be in touch with next steps, or you may contact us directly with any questions.</p>
@else
<p style="margin:0 0 16px;">Our admissions office has reached out regarding your application. If you have not yet heard from us directly, please contact the admissions office at your earliest convenience.</p>
@endif

@if($admissionInquiry->admin_notes)
<p style="margin:0; padding:14px 16px; background:#fdf3f4; border-left:3px solid #6b2d39; border-radius:6px; font-size:13px;">
    <strong>Note from the admissions office:</strong> {{ $admissionInquiry->admin_notes }}
</p>
@endif
@endsection
