@extends('layouts.teacher-portal')
@section('title', 'Attendance')
@section('content')

<div class="space-y-6">
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-gray-900">Attendance Sessions</h2>
      <p class="text-sm text-gray-500 mt-1">Create and review your attendance sessions.</p>
    </div>
    <a href="{{ route('teacher.attendance.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold text-white" style="background:#17324f">
      Log Attendance
    </a>
  </div>

  <div class="bg-white rounded-2xl overflow-hidden" style="border: 1px solid #e5e7eb">
    @if($sessions->isEmpty())
      <div class="p-8 text-sm text-gray-400 text-center">No attendance sessions created yet.</div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Date</th>
              <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Course</th>
              <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Program</th>
              <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Semester</th>
              <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Time</th>
              <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Topic</th>
              <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-400">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach($sessions as $session)
              <tr>
                <td class="px-5 py-4 text-gray-700">{{ $session->session_date?->format('d M Y') ?? '—' }}</td>
                <td class="px-5 py-4">
                  <div class="font-medium text-gray-800">{{ $session->course?->code ?? '—' }}</div>
                  <div class="text-xs text-gray-500 mt-1">{{ $session->course?->name ?? 'Course' }}</div>
                </td>
                <td class="px-5 py-4 text-gray-600">{{ $session->academicProgram?->name ?? '—' }}</td>
                <td class="px-5 py-4 text-gray-600">Semester {{ $session->semester_number ?? '—' }}</td>
                <td class="px-5 py-4 text-gray-600">
                  @if($session->start_time && $session->end_time)
                    {{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }}
                  @else
                    —
                  @endif
                </td>
                <td class="px-5 py-4 text-gray-600">{{ $session->topic_covered ?? '—' }}</td>
                <td class="px-5 py-4 text-right">
                  <a href="{{ route('teacher.attendance.mark', $session) }}" class="text-sm font-semibold" style="color:#17324f">
                    Mark Attendance
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="px-5 py-4 border-t border-gray-100">
        {{ $sessions->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
