@extends('layouts.teacher-portal')
@section('title', 'Assignments')
@section('content')

<div class="space-y-6">
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-gray-900">Assignments</h2>
      <p class="text-sm text-gray-500 mt-1">Assignments created under your teacher account.</p>
    </div>
    <a href="{{ route('teacher.assignments.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold text-white" style="background:#17324f">
      Add Assignment
    </a>
  </div>

  <div class="bg-white rounded-2xl overflow-hidden" style="border: 1px solid #e5e7eb">
    @if($assignments->isEmpty())
      <div class="p-8 text-sm text-gray-400 text-center">No assignments found.</div>
    @else
      <div class="divide-y divide-gray-100">
        @foreach($assignments as $assignment)
          <div class="px-6 py-5 flex flex-col lg:flex-row lg:items-start justify-between gap-4">
            <div class="min-w-0">
              <div class="flex items-center gap-2 flex-wrap">
                <h3 class="font-semibold text-gray-800">{{ $assignment->title }}</h3>
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $assignment->is_published ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                  {{ $assignment->is_published ? 'Published' : 'Draft' }}
                </span>
              </div>
              <p class="text-sm text-gray-500 mt-2">{{ $assignment->description ?: 'No description added.' }}</p>
              <div class="text-xs text-gray-500 mt-3">
                {{ $assignment->course?->code ?? '—' }}
                @if($assignment->total_marks)
                  • {{ number_format($assignment->total_marks, 0) }} marks
                @endif
                @if($assignment->submission_type)
                  • {{ ucfirst(str_replace('_', ' ', $assignment->submission_type)) }}
                @endif
              </div>
            </div>
            <div class="text-sm text-gray-600 lg:text-right">
              <div class="font-semibold">Due</div>
              <div>{{ $assignment->due_datetime?->format('d M Y h:i A') ?? 'Not set' }}</div>
              <a href="{{ route('teacher.assignments.edit', $assignment) }}" class="inline-flex mt-3 text-sm font-semibold" style="color:#17324f">
                Edit Assignment
              </a>
            </div>
          </div>
        @endforeach
      </div>
      <div class="px-5 py-4 border-t border-gray-100">
        {{ $assignments->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
