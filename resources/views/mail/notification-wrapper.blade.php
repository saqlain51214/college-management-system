@component('mail::message')
# Assalamu Alaikum, {{ $recipientName }}

{!! \Illuminate\Support\Str::markdown($bodyMarkdown) !!}

@if($actionLabel && $actionUrl)
@component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
{{ $actionLabel }}
@endcomponent
@endif

---

**Jinnah Degree College Astore (JDCA)**
Astore, Gilgit-Baltistan, Pakistan

*This is an automated notification — please do not reply to this email.*
@endcomponent
