@extends('layouts.portal')
@section('title', 'Timetable')
@section('content')

{{-- Header info --}}
<div class="flex items-center justify-between mb-6">
  <div>
    <h2 class="font-bold text-gray-800 text-lg">Class Timetable</h2>
    <p class="text-sm text-gray-400 mt-0.5">
      {{ $student->academicProgram?->name ?? 'Program' }}
      @if($student->current_semester) &bull; Semester {{ $student->current_semester }} @endif
    </p>
  </div>
</div>

@if(collect($slots)->flatten()->isEmpty())
<div class="bg-white rounded-2xl p-16 text-center" style="border: 1px solid #e5e7eb">
  <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: #f3f4f6">
    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
  </div>
  <h3 class="font-semibold text-gray-600 mb-1">No Timetable Published</h3>
  <p class="text-sm text-gray-400">The timetable for your program and semester has not been published yet.</p>
</div>
@else
<div class="space-y-4">
  @foreach($days as $day)
  @if(isset($slots[$day]) && $slots[$day]->isNotEmpty())
  <div class="bg-white rounded-2xl overflow-hidden" style="border: 1px solid #e5e7eb">
    {{-- Day header --}}
    <div class="flex items-center gap-3 px-5 py-3.5" style="background: #1e3a5f">
      <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(255,255,255,0.12)">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
      <h3 class="text-white font-semibold text-sm">{{ ucfirst($day) }}</h3>
      <span class="text-xs ml-auto px-2 py-0.5 rounded-full font-medium" style="background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.7)">
        {{ $slots[$day]->count() }} class{{ $slots[$day]->count() > 1 ? 'es' : '' }}
      </span>
    </div>

    {{-- Slots --}}
    <div>
      @foreach($slots[$day] as $i => $slot)
      <div class="flex items-center gap-4 px-5 py-4 {{ !$loop->last ? 'border-b' : '' }}"
           style="{{ !$loop->last ? 'border-color: #f3f4f6' : '' }}">

        {{-- Time --}}
        <div class="text-center flex-shrink-0 w-20">
          <div class="text-xs font-bold text-gray-700">{{ substr($slot->start_time, 0, 5) }}</div>
          <div class="my-0.5 w-full h-px" style="background: #e5e7eb"></div>
          <div class="text-xs font-bold text-gray-700">{{ substr($slot->end_time, 0, 5) }}</div>
        </div>

        {{-- Divider line --}}
        <div class="w-px self-stretch flex-shrink-0" style="background: #e5e7eb"></div>

        {{-- Course info --}}
        <div class="flex-1 min-w-0">
          <div class="font-semibold text-gray-800 text-sm">{{ $slot->course?->name ?? '—' }}</div>
          <div class="flex items-center flex-wrap gap-2 mt-1.5">
            @if($slot->course?->code)
            <span class="text-xs px-2 py-0.5 rounded-md font-semibold" style="background: #eff6ff; color: #1e3a5f">
              {{ $slot->course->code }}
            </span>
            @endif
            @if($slot->teacher)
            <span class="text-xs text-gray-400 flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              {{ $slot->teacher->name }}
            </span>
            @endif
          </div>
        </div>

        {{-- Room --}}
        @if($slot->room)
        <div class="flex-shrink-0 text-right">
          <div class="text-xs px-3 py-1.5 rounded-xl font-medium" style="background: #f9fafb; color: #6b7280; border: 1px solid #e5e7eb">
            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ $slot->room }}
          </div>
        </div>
        @endif
      </div>
      @endforeach
    </div>
  </div>
  @endif
  @endforeach
</div>
@endif

@endsection
