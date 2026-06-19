<h1>Password Changed</h1>

<p>Dear {{ $student->name }},</p>

<p>Your student portal password was changed successfully.</p>

<p>If you made this change, no action is needed.</p>
<p>If you did not make this change, please contact support immediately: {{ config('platform.notifications.student_support_email') }}</p>
