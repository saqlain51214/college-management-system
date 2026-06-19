@extends('layouts.teacher-portal')
@section('title', 'Mark Attendance')
@section('content')

<div class="space-y-6">
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-gray-900">Mark Attendance</h2>
      <p class="text-sm text-gray-500 mt-1">
        {{ $session->course?->code ?? 'Course' }} - {{ $session->course?->name ?? '—' }}
        • {{ $session->session_date?->format('d M Y') ?? '—' }}
        • Semester {{ $session->semester_number ?? '—' }}
      </p>
    </div>
    <a href="{{ route('teacher.attendance') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold border border-gray-200 text-gray-700 bg-white">
      Back
    </a>
  </div>

  @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-2xl px-5 py-4 text-sm text-red-700">
      @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  @if($students->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4 text-sm text-amber-800">
      No active students found for this program / semester / section combination.
    </div>
  @else
    <form action="{{ route('teacher.attendance.save', $session) }}" method="POST" class="space-y-6">
      @csrf

      <div class="bg-white rounded-2xl overflow-hidden" style="border: 1px solid #e5e7eb">
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Student</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Roll Number</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Status</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Remarks</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @foreach($students as $student)
                @php
                  $record = $existingRecords->get($student->id);
                  $currentStatus = old("attendance.{$student->id}.status", $record?->status?->value ?? 'present');
                  $currentRemarks = old("attendance.{$student->id}.remarks", $record?->remarks ?? '');
                @endphp
                <tr>
                  <td class="px-5 py-4">
                    <div class="font-medium text-gray-800">{{ $student->name }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $student->section ? 'Section ' . $student->section : 'No section' }}</div>
                  </td>
                  <td class="px-5 py-4 text-gray-600">{{ $student->roll_number }}</td>
                  <td class="px-5 py-4">
                    <div class="flex flex-wrap gap-4">
                      @foreach(['present' => 'Present', 'absent' => 'Absent', 'late' => 'Late'] as $value => $label)
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                          <input type="radio" name="attendance[{{ $student->id }}][status]" value="{{ $value }}" @checked($currentStatus === $value) class="text-[#17324f]">
                          {{ $label }}
                        </label>
                      @endforeach
                    </div>
                  </td>
                  <td class="px-5 py-4">
                    <input type="text" name="attendance[{{ $student->id }}][remarks]" value="{{ $currentRemarks }}" placeholder="Optional note"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#17324f]">
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div class="flex justify-end">
        <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white" style="background:#17324f">
          Save Attendance
        </button>
      </div>
    </form>
  @endif
</div>
@endsection
