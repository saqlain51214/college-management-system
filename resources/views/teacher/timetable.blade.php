@extends('layouts.teacher-portal')
@section('title', 'Timetable')
@section('content')

<div class="space-y-6">
  <div>
    <h2 class="text-xl font-bold text-gray-900">My Timetable</h2>
    <p class="text-sm text-gray-500 mt-1">All active class slots assigned to you.</p>
  </div>

  <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    @foreach($days as $day)
      @php $daySlots = $slots->get($day, collect()); @endphp
      <div class="bg-white rounded-2xl overflow-hidden" style="border: 1px solid #e5e7eb">
        <div class="px-5 py-4" style="border-bottom: 1px solid #f3f4f6">
          <h3 class="font-semibold text-gray-800">{{ ucfirst($day) }}</h3>
        </div>
        @if($daySlots->isEmpty())
          <div class="px-5 py-6 text-sm text-gray-400">No classes scheduled.</div>
        @else
          <div class="divide-y divide-gray-100">
            @foreach($daySlots as $slot)
              <div class="px-5 py-4">
                <div class="flex items-start justify-between gap-4">
                  <div>
                    <div class="font-semibold text-gray-800">{{ $slot->course?->name ?? 'Course' }}</div>
                    <div class="text-xs text-gray-500 mt-1">
                      {{ $slot->course?->code ?? '—' }} • {{ $slot->academicProgram?->name ?? 'Program' }}
                    </div>
                  </div>
                  <div class="text-sm font-semibold text-gray-700">{{ $slot->time_range }}</div>
                </div>
                <div class="mt-2 text-xs text-gray-500">
                  Room {{ $slot->room ?? 'TBA' }}
                  @if($slot->section)
                    • Section {{ $slot->section }}
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    @endforeach
  </div>
</div>
@endsection
