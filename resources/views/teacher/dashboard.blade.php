@extends('layouts.teacher-portal')
@section('title', 'Dashboard')
@section('content')

<div class="rounded-3xl p-6 sm:p-7 mb-6 relative overflow-hidden shadow-2xl" style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 45%, #0f172a 100%)">
  <div class="absolute inset-0 opacity-40" style="background: radial-gradient(circle at top right, rgba(196,151,58,0.4), transparent 22%)"></div>
  <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
    <div>
      <div class="text-sm mb-1 relative" style="color: rgba(255,255,255,0.6)">
        {{ now()->hour < 12 ? 'Good Morning' : (now()->hour < 17 ? 'Good Afternoon' : 'Good Evening') }},
      </div>
      <h2 class="text-white text-2xl font-bold relative">{{ $teacher->name }}</h2>
      <div class="flex flex-wrap gap-2 mt-3">
        <span class="inline-flex items-center text-xs px-3 py-1 rounded-full font-medium" style="background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8)">
          {{ $teacher->employee_id }}
        </span>
        <span class="inline-flex items-center text-xs px-3 py-1 rounded-full font-medium" style="background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8)">
          {{ $teacher->designation ?? 'Teacher' }}
        </span>
        <span class="inline-flex items-center text-xs px-3 py-1 rounded-full font-medium" style="background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8)">
          {{ $teacher->department?->name ?? 'Department not assigned' }}
        </span>
      </div>
      <div class="flex flex-wrap gap-2 mt-4">
        <a href="{{ route('teacher.notices') }}" class="portal-chip">Notices</a>
        <a href="{{ route('teacher.profile') }}" class="portal-chip" style="background: rgba(196,151,58,0.16); color: #fde68a; border-color: rgba(245,158,11,0.2)">My Profile</a>
      </div>
    </div>
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0 text-white font-black text-2xl hidden sm:flex"
         style="background: #c4973a">
      {{ strtoupper(substr($teacher->name, 0, 1)) }}
    </div>
  </div>
</div>

{{-- Notices --}}
<div class="portal-card rounded-2xl overflow-hidden">
  <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid #f3f4f6">
    <div>
      <h3 class="font-semibold text-gray-800">Recent Notices</h3>
      <p class="text-xs text-gray-400 mt-0.5">Latest announcements for faculty</p>
    </div>
    <a href="{{ route('teacher.notices') }}"
       class="text-xs font-semibold px-3 py-1.5 rounded-lg"
       style="color: #dbeafe; background: rgba(59,130,246,0.14); border: 1px solid rgba(96,165,250,0.16)">
      View All →
    </a>
  </div>

  @if($notices->isEmpty())
  <div class="flex flex-col items-center justify-center py-16 text-center px-6">
    <p class="font-medium text-gray-500 text-sm">No notices at this time</p>
    <p class="text-xs text-gray-400 mt-1">Announcements will appear here once published</p>
  </div>
  @else
  <div class="divide-y divide-gray-50">
    @foreach($notices as $notice)
    <div class="px-6 py-4 flex items-start gap-3">
      <span class="mt-1.5 flex-shrink-0 w-2 h-2 rounded-full"
            style="background: {{ ($notice->priority ?? '') === 'urgent' ? '#ef4444' : (($notice->priority ?? '') === 'high' ? '#f59e0b' : '#3b82f6') }}"></span>
      <div class="min-w-0">
        <p class="text-sm font-medium text-gray-800 leading-snug">{{ $notice->title }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $notice->publish_date ? \Carbon\Carbon::parse($notice->publish_date)->format('d M Y') : '' }}</p>
      </div>
    </div>
    @endforeach
  </div>
  @endif
</div>

@endsection
