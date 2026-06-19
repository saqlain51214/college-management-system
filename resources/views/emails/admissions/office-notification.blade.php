<h1>New Admission Inquiry</h1>

<p>A new online admission application has been received.</p>

<p><strong>Reference No:</strong> {{ $admissionInquiry->reference_no }}</p>
<p><strong>Name:</strong> {{ $admissionInquiry->name }}</p>
<p><strong>Email:</strong> {{ $admissionInquiry->email }}</p>
<p><strong>Phone:</strong> {{ $admissionInquiry->phone }}</p>
<p><strong>Programme:</strong> {{ $admissionInquiry->program?->name ?? 'N/A' }}</p>
<p><strong>Entry Path:</strong> {{ ucfirst((string) $admissionInquiry->entry_path) }}</p>
