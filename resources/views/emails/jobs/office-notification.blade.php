@extends('emails.layout')
@section('subject', 'New Job Application')

@section('content')
<h2 style="margin:0 0 16px; font-size:20px; color:#1c1917;">New Job Application</h2>

<p style="margin:0 0 20px;">A new job application was submitted from the public website.</p>

<div style="background:#faf9f6; border:1px solid #eee; border-radius:10px; padding:18px 20px; margin-bottom:20px;">
    <p class="field-label">Position</p>
    <p class="field-value">{{ $jobApplication->position }}</p>
    <p class="field-label">Name</p>
    <p class="field-value">{{ $jobApplication->name }}</p>
    <p class="field-label">Email</p>
    <p class="field-value">{{ $jobApplication->email }}</p>
    <p class="field-label">Phone</p>
    <p class="field-value">{{ $jobApplication->phone }}</p>
    <p class="field-label">Education</p>
    <p class="field-value">{{ $jobApplication->education }}</p>
    <p class="field-label">Experience</p>
    <p class="field-value">{{ $jobApplication->experience ?: 'Not specified' }}</p>
    <p class="field-label" style="margin-bottom:2px;">Cover Letter</p>
    <p class="field-value" style="margin-bottom:0; white-space:pre-line;">{{ $jobApplication->message }}</p>
</div>

@if($jobApplication->cv_path)
<p style="margin:0;">The applicant's CV is attached to this email.</p>
@else
<p style="margin:0; color:#78716c;">No CV was attached to this application.</p>
@endif
@endsection
