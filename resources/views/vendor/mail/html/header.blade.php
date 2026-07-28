@props(['url'])
@php
    $collegeName = \App\Models\CollegeSetting::get('college_name', 'Jinnah Degree College Astore');
    $collegeLogoPath = \App\Models\CollegeSetting::get('college_logo');
    $collegeLogoUrl = $collegeLogoPath ? asset('storage/' . $collegeLogoPath) : asset('assets/images/default/cologe-logo-web.png');
@endphp
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ $collegeLogoUrl }}" class="logo" alt="{{ $collegeName }}" style="max-height:56px;">
<div style="color:#ffffff; font-size:16px; font-weight:bold; margin-top:8px;">{{ $collegeName }}</div>
</a>
</td>
</tr>
