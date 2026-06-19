<h1>Student Portal Access</h1>

<p>Dear {{ $student->name }},</p>

<p>Your student portal account is ready.</p>

<p><strong>Portal URL:</strong> {{ route('portal.login') }}</p>
<p><strong>Roll Number:</strong> {{ $student->roll_number }}</p>
<p><strong>Default Password:</strong> Your roll number, unless the administration has shared a different temporary password with you.</p>

<p>For security, please log in and change your password as soon as possible.</p>

<p>If you need help, contact: {{ config('platform.notifications.student_support_email') }}</p>
