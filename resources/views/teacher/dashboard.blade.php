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
        <a href="{{ route('teacher.attendance') }}" class="portal-chip">Take Attendance</a>
        <a href="{{ route('teacher.assignments.create') }}" class="portal-chip" style="background: rgba(196,151,58,0.16); color: #fde68a; border-color: rgba(245,158,11,0.2)">New Assignment</a>
      </div>
    </div>
    <div class="rounded-2xl px-4 py-3 text-sm text-white self-start relative" style="background: rgba(255,255,255,0.08)">
      <div class="opacity-70">Today</div>
      <div class="font-semibold">{{ $today }}</div>
    </div>
  </div>
</div>

<div class="grid grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
  @foreach([
    ['label' => 'Active Classes', 'value' => $stats['active_classes'], 'sub' => 'Scheduled slots', 'color' => '#1d4ed8', 'bg' => '#eff6ff'],
    ['label' => 'Today Classes', 'value' => $stats['today_classes'], 'sub' => 'For today', 'color' => '#7c3aed', 'bg' => '#f5f3ff'],
    ['label' => 'Materials', 'value' => $stats['materials'], 'sub' => 'Uploaded items', 'color' => '#0891b2', 'bg' => '#ecfeff'],
    ['label' => 'Assignments', 'value' => $stats['assignments'], 'sub' => 'Created tasks', 'color' => '#16a34a', 'bg' => '#f0fdf4'],
    ['label' => 'Attendance', 'value' => $stats['attendance_sessions'], 'sub' => 'Sessions taken', 'color' => '#d97706', 'bg' => '#fffbeb'],
  ] as $card)
    <div class="portal-stat-card rounded-2xl p-5">
      <div class="text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">{{ $card['label'] }}</div>
      <div class="mt-3 text-2xl font-bold" style="color: {{ $card['color'] }}">{{ $card['value'] }}</div>
      <div class="mt-1 text-xs" style="color: #9ca3af">{{ $card['sub'] }}</div>
    </div>
  @endforeach
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
  <div class="xl:col-span-2 space-y-6">
    <div class="portal-card rounded-2xl overflow-hidden">
      <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid #f3f4f6">
        <div>
          <h3 class="font-semibold text-gray-800">Today's Schedule</h3>
          <p class="text-xs text-gray-400 mt-0.5">Classes assigned to you for {{ $today }}</p>
        </div>
        <a href="{{ route('teacher.timetable') }}" class="text-xs font-semibold" style="color: #17324f">Full Timetable →</a>
      </div>
      @if($todaySchedule->isEmpty())
        <div class="p-8 text-sm text-gray-400 text-center">No active classes scheduled for today.</div>
      @else
        <div class="divide-y divide-gray-100">
          @foreach($todaySchedule as $slot)
            <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div>
                <div class="font-semibold text-gray-800">{{ $slot->course?->name ?? 'Course' }}</div>
                <div class="text-xs text-gray-500 mt-1">
                  {{ $slot->academicProgram?->name ?? 'Program' }} • Room {{ $slot->room ?? 'TBA' }}
                </div>
              </div>
              <div class="text-sm font-semibold text-gray-700">{{ $slot->time_range }}</div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="portal-card rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4" style="border-bottom: 1px solid #f3f4f6">
          <h3 class="font-semibold text-gray-800">Recent Materials</h3>
          <a href="{{ route('teacher.materials') }}" class="text-xs font-semibold" style="color: #17324f">View all →</a>
        </div>
        @if($recentMaterials->isEmpty())
          <div class="p-6 text-sm text-gray-400">No materials uploaded yet.</div>
        @else
          <div class="divide-y divide-gray-100">
            @foreach($recentMaterials as $material)
              <div class="px-5 py-4">
                <div class="font-medium text-gray-800">{{ $material->title }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ $material->course?->code ?? 'Course' }} • {{ $material->is_published ? 'Published' : 'Draft' }}</div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <div class="portal-card rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4" style="border-bottom: 1px solid #f3f4f6">
          <h3 class="font-semibold text-gray-800">Assignments</h3>
          <a href="{{ route('teacher.assignments') }}" class="text-xs font-semibold" style="color: #17324f">View all →</a>
        </div>
        @if($upcomingAssignments->isEmpty())
          <div class="p-6 text-sm text-gray-400">No assignments available yet.</div>
        @else
          <div class="divide-y divide-gray-100">
            @foreach($upcomingAssignments as $assignment)
              <div class="px-5 py-4">
                <div class="font-medium text-gray-800">{{ $assignment->title }}</div>
                <div class="text-xs text-gray-500 mt-1">
                  {{ $assignment->course?->code ?? 'Course' }}
                  @if($assignment->due_datetime)
                    • Due {{ $assignment->due_datetime->format('d M Y h:i A') }}
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="portal-card rounded-2xl overflow-hidden h-fit">
    <div class="flex items-center justify-between px-5 py-4" style="border-bottom: 1px solid #f3f4f6">
      <h3 class="font-semibold text-gray-800">Teacher Notices</h3>
      <a href="{{ route('teacher.notices') }}" class="text-xs font-semibold" style="color: #17324f">All →</a>
    </div>
    @if($notices->isEmpty())
      <div class="p-6 text-sm text-gray-400">No notices right now.</div>
    @else
      <div class="divide-y divide-gray-100">
        @foreach($notices as $notice)
          <div class="px-5 py-4">
            <div class="font-medium text-gray-800">{{ $notice->title }}</div>
            <div class="text-xs text-gray-500 mt-1">{{ $notice->publish_date?->format('d M Y') ?? '—' }}</div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</div>
@endsection
