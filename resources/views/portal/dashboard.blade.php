@extends('layouts.portal')
@section('title', 'Dashboard')
@section('content')

{{-- Welcome banner --}}
<div class="rounded-3xl p-6 sm:p-7 mb-6 relative overflow-hidden shadow-2xl" style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 48%, #0f172a 100%)">
  <div class="absolute inset-0 opacity-40" style="background: radial-gradient(circle at top right, rgba(196,151,58,0.45), transparent 22%)"></div>
  <div class="relative flex flex-col sm:flex-row sm:items-start justify-between gap-4">
    <div>
      <div class="text-sm mb-1" style="color: rgba(255,255,255,0.6)">
        {{ now()->hour < 12 ? 'Good Morning' : (now()->hour < 17 ? 'Good Afternoon' : 'Good Evening') }},
      </div>
      <h2 class="text-white text-2xl font-bold">{{ $student->name }}</h2>
      <div class="flex flex-wrap gap-2 mt-3">
        <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full font-medium"
              style="background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.75)">
          <span class="w-1.5 h-1.5 rounded-full" style="background:#c4973a"></span>
          {{ $student->registration_number ?: $student->roll_number }}
        </span>
        <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full font-medium"
              style="background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.75)">
          {{ $student->academicProgram?->name ?? 'Program N/A' }}
        </span>
        <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full font-medium"
              style="background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.75)">
          Semester {{ $student->current_semester ?? '—' }}
        </span>
        <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full font-medium capitalize"
              style="background: rgba(196,151,58,0.25); color: #f4cc80">
          {{ $student->status instanceof \BackedEnum ? $student->status->value : ($student->status ?? 'active') }}
        </span>
      </div>
      <div class="flex flex-wrap gap-2 mt-4">
        <a href="{{ route('portal.fees') }}" class="portal-chip" style="background: rgba(196,151,58,0.16); color: #fde68a; border-color: rgba(245,158,11,0.2)">Fee Summary</a>
        <a href="{{ route('portal.notices') }}" class="portal-chip">Notices</a>
      </div>
    </div>
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0 text-white font-black text-2xl hidden sm:flex"
         style="background: #c4973a">
      {{ strtoupper(substr($student->name, 0, 1)) }}
    </div>
  </div>
</div>

{{-- Fee stats --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
  @php
  $cards = [
    [
      'label'  => 'Fee Balance',
      'value'  => 'Rs. ' . number_format($feeStats['balance'], 0),
      'sub'    => $feeStats['balance'] > 0 ? 'Outstanding' : 'Fully Clear',
      'color'  => $feeStats['balance'] > 0 ? '#dc2626' : '#16a34a',
      'bg'     => $feeStats['balance'] > 0 ? '#fef2f2' : '#f0fdf4',
      'icon'   => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
    ],
    [
      'label'  => 'Total Paid',
      'value'  => 'Rs. ' . number_format($feeStats['total_paid'], 0),
      'sub'    => 'Confirmed payments',
      'color'  => '#16a34a',
      'bg'     => '#f0fdf4',
      'icon'   => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    ],
    [
      'label'  => 'Overdue',
      'value'  => (string)$feeStats['overdue'],
      'sub'    => 'Challan(s) overdue',
      'color'  => $feeStats['overdue'] > 0 ? '#dc2626' : '#6b7280',
      'bg'     => $feeStats['overdue'] > 0 ? '#fef2f2' : '#f9fafb',
      'icon'   => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    ],
  ];
  @endphp

  @foreach($cards as $c)
  <div class="portal-stat-card rounded-2xl p-5">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">{{ $c['label'] }}</span>
      <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background: {{ $c['bg'] }}; border: 1px solid {{ $c['color'] }}33">
        <svg class="w-4 h-4" style="color: {{ $c['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $c['icon'] }}"/>
        </svg>
      </div>
    </div>
    <div class="text-2xl font-bold" style="color: {{ $c['color'] }}">{{ $c['value'] }}</div>
    <div class="text-xs mt-1" style="color: #9ca3af">{{ $c['sub'] }}</div>
  </div>
  @endforeach
</div>

{{-- Content grid --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

  {{-- Notices (2/3 width) --}}
  <div class="xl:col-span-2 portal-card rounded-2xl overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid #f3f4f6">
      <div>
        <h3 class="font-semibold text-gray-800">Recent Notices</h3>
        <p class="text-xs text-gray-400 mt-0.5">Latest announcements for students</p>
      </div>
      <a href="{{ route('portal.notices') }}"
         class="text-xs font-semibold px-3 py-1.5 rounded-lg transition"
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

  {{-- Quick Actions --}}
  <div class="portal-card rounded-2xl p-5 h-fit">
    <h3 class="font-semibold text-gray-800 text-sm mb-3">Quick Actions</h3>
    <div class="space-y-1.5">
      @foreach([
        ['route' => 'portal.fees',    'label' => 'View Fee Status', 'bg' => '#eff6ff', 'ic' => '#2563eb', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
        ['route' => 'portal.notices', 'label' => 'Notices',         'bg' => '#f0fdf4', 'ic' => '#16a34a', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
        ['route' => 'portal.profile', 'label' => 'My Profile',      'bg' => '#fffbeb', 'ic' => '#d97706', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
      ] as $qa)
      <a href="{{ route($qa['route']) }}"
         class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition group">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background: {{ $qa['bg'] }}; border: 1px solid {{ $qa['ic'] }}33">
          <svg class="w-4 h-4" style="color: {{ $qa['ic'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $qa['icon'] }}"/>
          </svg>
        </div>
        <span class="text-sm font-medium text-gray-700 flex-1 group-hover:text-gray-900 transition">{{ $qa['label'] }}</span>
        <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </a>
      @endforeach
    </div>
  </div>

</div>

@endsection
