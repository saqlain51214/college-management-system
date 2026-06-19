<h1>Message Received</h1>

<p>Dear {{ $contactMessage->name }},</p>

<p>Thank you for contacting {{ config('app.name') }}. We have received your message and our team will review it shortly.</p>

<p><strong>Subject:</strong> {{ $contactMessage->subject }}</p>
<p><strong>Message:</strong><br>{{ $contactMessage->message }}</p>

<p>If your matter is urgent, please contact the college office directly.</p>
