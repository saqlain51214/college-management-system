<h1>Admission Application Received</h1>

<p>Dear {{ $admissionInquiry->name }},</p>

<p>Your online admission application has been received successfully.</p>

<p><strong>Reference No:</strong> {{ $admissionInquiry->reference_no }}</p>
<p><strong>Programme:</strong> {{ $admissionInquiry->program?->name ?? 'N/A' }}</p>
<p><strong>Entry Path:</strong> {{ ucfirst((string) $admissionInquiry->entry_path) }}</p>

<p>Please keep your original documents ready. The admission office may contact you for verification or the next step.</p>
