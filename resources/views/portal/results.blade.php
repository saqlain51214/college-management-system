@extends('layouts.portal')
@section('title', 'Exam Results')
@section('content')

{{-- CGPA banner --}}
@if($cgpa)
<div class="rounded-2xl p-5 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
     style="background: linear-gradient(135deg, #1e3a5f 0%, #2a4f80 100%)">
  <div>
    <div class="text-sm mb-1" style="color: rgba(255,255,255,0.5)">Overall Academic Performance</div>
    <div class="flex items-end gap-3">
      <span class="text-4xl font-black text-white">{{ number_format($cgpa, 2) }}</span>
      <span class="text-sm mb-1.5" style="color: rgba(255,255,255,0.5)">/ 4.00 CGPA</span>
    </div>
  </div>
  <div class="flex gap-6 sm:gap-8">
    @php
      $appeared = $results->where('is_absent', false)->count();
      $passed   = $results->where('is_absent', false)->filter(fn($r) => $r->marks_obtained !== null && $r->marks_obtained >= ($r->exam?->passing_marks ?? 40))->count();
    @endphp
    <div class="text-center">
      <div class="text-2xl font-bold text-white">{{ $results->count() }}</div>
      <div class="text-xs mt-0.5" style="color: rgba(255,255,255,0.5)">Total</div>
    </div>
    <div class="text-center">
      <div class="text-2xl font-bold" style="color: #86efac">{{ $passed }}</div>
      <div class="text-xs mt-0.5" style="color: rgba(255,255,255,0.5)">Passed</div>
    </div>
    <div class="text-center">
      <div class="text-2xl font-bold" style="color: #fca5a5">{{ $appeared - $passed }}</div>
      <div class="text-xs mt-0.5" style="color: rgba(255,255,255,0.5)">Failed</div>
    </div>
  </div>
</div>
@endif

@if($results->isEmpty())
<div class="bg-white rounded-2xl p-16 text-center" style="border: 1px solid #e5e7eb">
  <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: #f3f4f6">
    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
    </svg>
  </div>
  <h3 class="font-semibold text-gray-600 mb-1">No Published Results</h3>
  <p class="text-sm text-gray-400">Results will appear here once published by the examination office.</p>
</div>
@else

{{-- Grouped by semester --}}
@foreach($grouped as $semester => $semResults)
@php
  $semPassed = collect($semResults)->filter(fn($r) => !$r->is_absent && $r->marks_obtained !== null && $r->marks_obtained >= ($r->exam?->passing_marks ?? 40))->count();
  $semTotal  = collect($semResults)->count();
@endphp

<div class="bg-white rounded-2xl overflow-hidden mb-5" style="border: 1px solid #e5e7eb">
  {{-- Semester header --}}
  <div class="flex items-center justify-between px-6 py-3.5" style="background: #f9fafb; border-bottom: 1px solid #e5e7eb">
    <h3 class="font-semibold text-gray-700 text-sm">
      {{ is_numeric($semester) ? 'Semester ' . $semester : $semester }}
    </h3>
    <div class="flex items-center gap-3">
      <span class="text-xs text-gray-400">{{ $semTotal }} exams</span>
      <span class="text-xs px-2.5 py-1 rounded-full font-semibold"
            style="background: #dcfce7; color: #15803d">
        {{ $semPassed }} passed
      </span>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr style="border-bottom: 1px solid #f3f4f6">
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Course</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Exam</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Type</th>
          <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Total</th>
          <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Marks</th>
          <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">%</th>
          <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Grade</th>
          <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">GPA</th>
          <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Result</th>
        </tr>
      </thead>
      <tbody>
        @foreach($semResults as $r)
        @php
          $isPass = !$r->is_absent && $r->marks_obtained !== null && $r->marks_obtained >= ($r->exam?->passing_marks ?? 40);
          $tm     = $r->exam?->total_marks ?? 0;
          $pct    = (!$r->is_absent && $tm > 0 && $r->marks_obtained !== null) ? round($r->marks_obtained / $tm * 100, 1) : null;
        @endphp
        <tr class="hover:bg-gray-50 transition-colors {{ $r->is_absent ? 'opacity-60' : '' }}"
            style="border-bottom: 1px solid #f9fafb">
          <td class="px-6 py-3.5">
            <div class="font-semibold text-gray-800">{{ $r->exam?->course?->code ?? '—' }}</div>
            <div class="text-xs text-gray-400 max-w-[180px] truncate">{{ $r->exam?->course?->name }}</div>
          </td>
          <td class="px-6 py-3.5 text-xs text-gray-500">{{ $r->exam?->title }}</td>
          <td class="px-6 py-3.5 text-xs text-gray-400">
            {{ $r->exam?->exam_type instanceof \BackedEnum ? ucfirst($r->exam->exam_type->value) : ucfirst($r->exam?->exam_type ?? '') }}
          </td>
          <td class="px-6 py-3.5 text-center text-gray-500">{{ $r->exam?->total_marks ?? '—' }}</td>
          <td class="px-6 py-3.5 text-center font-bold {{ $r->is_absent ? 'text-gray-400' : ($isPass ? 'text-green-600' : 'text-red-500') }}">
            {{ $r->is_absent ? 'ABSENT' : ($r->marks_obtained !== null ? number_format($r->marks_obtained, 1) : '—') }}
          </td>
          <td class="px-6 py-3.5 text-center text-xs text-gray-500">{{ $pct !== null ? $pct . '%' : '—' }}</td>
          <td class="px-6 py-3.5 text-center">
            <span class="px-2 py-1 rounded-lg text-xs font-bold" style="background: #f3f4f6; color: #374151">
              {{ $r->is_absent ? 'AB' : ($r->grade ?? '—') }}
            </span>
          </td>
          <td class="px-6 py-3.5 text-center text-xs text-gray-500">
            {{ $r->grade_points !== null ? number_format($r->grade_points, 2) : '—' }}
          </td>
          <td class="px-6 py-3.5 text-center">
            @if($r->is_absent)
              <span class="px-2.5 py-1 rounded-full text-xs font-bold" style="background:#f3f4f6;color:#6b7280">ABSENT</span>
            @elseif($isPass)
              <span class="px-2.5 py-1 rounded-full text-xs font-bold" style="background:#dcfce7;color:#15803d">PASS</span>
            @else
              <span class="px-2.5 py-1 rounded-full text-xs font-bold" style="background:#fee2e2;color:#dc2626">FAIL</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endforeach

@endif
@endsection
