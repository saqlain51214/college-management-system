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
          {{ $student->roll_number }}
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
        <a href="{{ route('portal.results') }}" class="portal-chip">View Latest Results</a>
        <a href="{{ route('portal.fees') }}" class="portal-chip" style="background: rgba(196,151,58,0.16); color: #fde68a; border-color: rgba(245,158,11,0.2)">Fee Summary</a>
      </div>
    </div>
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0 text-white font-black text-2xl hidden sm:flex"
         style="background: #c4973a">
      {{ strtoupper(substr($student->name, 0, 1)) }}
    </div>
  </div>
</div>

{{-- Stats row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  @php
  $cards = [
    [
      'label'  => 'Fee Balance',
      'value'  => 'Rs. ' . number_format($feeStats['balance'], 0),
      'sub'    => $feeStats['balance'] > 0 ? 'Outstanding' : 'Fully Clear',
      'color'  => $feeStats['balance'] > 0 ? '#dc2626' : '#16a34a',
      'bg'     => $feeStats['balance'] > 0 ? '#fef2f2' : '#f0fdf4',
      'border' => $feeStats['balance'] > 0 ? '#fecaca' : '#bbf7d0',
      'icon'   => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
    ],
    [
      'label'  => 'Overdue',
      'value'  => (string)$feeStats['overdue'],
      'sub'    => 'Challan(s)',
      'color'  => $feeStats['overdue'] > 0 ? '#dc2626' : '#6b7280',
      'bg'     => $feeStats['overdue'] > 0 ? '#fef2f2' : '#f9fafb',
      'border' => $feeStats['overdue'] > 0 ? '#fecaca' : '#e5e7eb',
      'icon'   => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    ],
    [
      'label'  => 'Results',
      'value'  => (string)$recentResults->count(),
      'sub'    => 'Published',
      'color'  => '#1e3a5f',
      'bg'     => '#eff6ff',
      'border' => '#bfdbfe',
      'icon'   => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
    ],
    [
      'label'  => 'Avg GPA',
      'value'  => $avgGpa ? number_format($avgGpa, 2) : '—',
      'sub'    => 'Out of 4.00',
      'color'  => '#7c3aed',
      'bg'     => '#f5f3ff',
      'border' => '#ddd6fe',
      'icon'   => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
    ],
  ];
  @endphp

  @foreach($cards as $c)
  <div class="portal-stat-card rounded-2xl p-5">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">{{ $c['label'] }}</span>
      <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: {{ $c['bg'] }}20; border: 1px solid {{ $c['color'] }}33">
        <svg class="w-5 h-5" style="color: {{ $c['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

  {{-- Recent Results (2/3 width) --}}
  <div class="xl:col-span-2 portal-card rounded-2xl overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid #f3f4f6">
      <div>
        <h3 class="font-semibold text-gray-800">Recent Exam Results</h3>
        <p class="text-xs text-gray-400 mt-0.5">Latest published results for your account</p>
      </div>
      <a href="{{ route('portal.results') }}"
         class="text-xs font-semibold px-3 py-1.5 rounded-lg transition"
         style="color: #dbeafe; background: rgba(59,130,246,0.14); border: 1px solid rgba(96,165,250,0.16)">
        View All →
      </a>
    </div>

    @if($recentResults->isEmpty())
    <div class="flex flex-col items-center justify-center py-16 text-center px-6">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background: #f3f4f6">
        <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
      </div>
      <p class="font-medium text-gray-500 text-sm">No published results yet</p>
      <p class="text-xs text-gray-400 mt-1">Results will appear here once published</p>
    </div>
    @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr style="border-bottom: 1px solid #f9fafb">
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Course</th>
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Exam</th>
            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Marks</th>
            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Grade</th>
            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($recentResults as $r)
          @php
            $isPass = !$r->is_absent && $r->marks_obtained !== null && $r->marks_obtained >= ($r->exam?->passing_marks ?? 40);
          @endphp
          <tr class="hover:bg-gray-50 transition-colors" style="border-bottom: 1px solid #f9fafb">
            <td class="px-6 py-3.5">
              <div class="font-semibold text-gray-800">{{ $r->exam?->course?->code ?? '—' }}</div>
              <div class="text-xs text-gray-400 truncate max-w-[200px]">{{ $r->exam?->course?->name }}</div>
            </td>
            <td class="px-6 py-3.5 text-xs text-gray-500">{{ $r->exam?->title }}</td>
            <td class="px-6 py-3.5 text-center font-bold {{ $r->is_absent ? 'text-gray-400' : ($isPass ? 'text-green-600' : 'text-red-500') }}">
              {{ $r->is_absent ? 'ABS' : ($r->marks_obtained !== null ? number_format($r->marks_obtained, 1) : '—') }}
            </td>
            <td class="px-6 py-3.5 text-center">
              <span class="px-2 py-1 rounded-lg text-xs font-bold" style="background: #f3f4f6; color: #374151">
                {{ $r->is_absent ? 'AB' : ($r->grade ?? '—') }}
              </span>
            </td>
            <td class="px-6 py-3.5 text-center">
              @if($r->is_absent)
                <span class="px-2.5 py-1 rounded-full text-xs font-bold" style="background:#f3f4f6; color:#6b7280">ABSENT</span>
              @elseif($isPass)
                <span class="px-2.5 py-1 rounded-full text-xs font-bold" style="background:#dcfce7; color:#15803d">PASS</span>
              @else
                <span class="px-2.5 py-1 rounded-full text-xs font-bold" style="background:#fee2e2; color:#dc2626">FAIL</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  {{-- Right column: Notices + Quick Actions --}}
    <div class="space-y-5">

    {{-- Notices --}}
    <div class="portal-card rounded-2xl overflow-hidden">
      <div class="flex items-center justify-between px-5 py-4" style="border-bottom: 1px solid #f3f4f6">
        <h3 class="font-semibold text-gray-800 text-sm">Recent Notices</h3>
        <a href="{{ route('portal.notices') }}"
           class="text-xs font-semibold" style="color: #1e3a5f">All →</a>
      </div>
      @if($notices->isEmpty())
      <div class="py-10 text-center text-sm text-gray-400">No notices at this time.</div>
      @else
      <div>
        @foreach($notices as $notice)
        <div class="px-5 py-3.5" style="border-bottom: 1px solid #f9fafb">
          <div class="flex items-start gap-2.5">
            <span class="mt-1.5 flex-shrink-0 w-2 h-2 rounded-full"
                  style="background: {{ ($notice->priority ?? '') === 'urgent' ? '#ef4444' : (($notice->priority ?? '') === 'high' ? '#f59e0b' : '#3b82f6') }}"></span>
            <div class="min-w-0">
              <p class="text-sm font-medium text-gray-800 leading-snug line-clamp-2">{{ $notice->title }}</p>
              <p class="text-[11px] text-gray-400 mt-1">
                {{ $notice->publish_date ? \Carbon\Carbon::parse($notice->publish_date)->format('d M Y') : '' }}
              </p>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      @endif
    </div>

    {{-- Quick Actions --}}
    <div class="portal-card rounded-2xl p-5">
      <h3 class="font-semibold text-gray-800 text-sm mb-3">Quick Actions</h3>
      <div class="space-y-1.5">
        @foreach([
          ['route' => 'portal.fees',      'label' => 'View Fee Status',   'bg' => '#eff6ff', 'ic' => '#2563eb', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
          ['route' => 'portal.results',   'label' => 'Exam Results',      'bg' => '#f0fdf4', 'ic' => '#16a34a', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
          ['route' => 'portal.timetable', 'label' => 'Class Timetable',   'bg' => '#fdf4ff', 'ic' => '#7c3aed', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
          ['route' => 'portal.profile',   'label' => 'My Profile',        'bg' => '#fffbeb', 'ic' => '#d97706', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ] as $qa)
        <a href="{{ route($qa['route']) }}"
           class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition group">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background: {{ $qa['bg'] }}20; border: 1px solid {{ $qa['ic'] }}33">
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
</div>

@endsection
