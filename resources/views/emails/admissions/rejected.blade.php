<h1>Admission Application Update</h1>

<p>Dear {{ $admissionInquiry->name }},</p>

<p>Thank you for your interest in applying to Jinnah Degree College Astore.</p>

<p><strong>Reference No:</strong> {{ $admissionInquiry->reference_no }}</p>
<p><strong>Programme:</strong> {{ $admissionInquiry->program?->name ?? 'N/A' }}</p>

<p>After reviewing your application, we are unable to offer you admission at this time.</p>

@if($admissionInquiry->admin_notes)
<p><strong>Note from the admissions office:</strong> {{ $admissionInquiry->admin_notes }}</p>
@endif

<p>We encourage you to apply again in a future admission cycle. If you have any questions, please contact the admissions office.</p>
