@extends('layouts.teacher-portal')
@section('title', 'Log Attendance')
@section('content')

<div class="max-w-5xl mx-auto space-y-6">
  <div>
    <h2 class="text-xl font-bold text-gray-900">Log Attendance</h2>
    <p class="text-sm text-gray-500 mt-1">Create a new attendance session for one of your assigned classes.</p>
  </div>

  @if($courses->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4 text-sm text-amber-800">
      No assigned courses found yet. Please contact admin or make sure your timetable is assigned before logging attendance.
    </div>
  @else
    @if($errors->any())
      <div class="bg-red-50 border border-red-200 rounded-2xl px-5 py-4 text-sm text-red-700">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form action="{{ route('teacher.attendance.store') }}" method="POST" class="space-y-6">
      @csrf

      <div class="bg-white rounded-2xl p-6" style="border: 1px solid #e5e7eb">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Course</label>
            <select name="course_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
              <option value="">Select course</option>
              @foreach($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>
                  {{ $course->code }} - {{ $course->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Session Date</label>
            <input type="date" name="session_date" value="{{ old('session_date', now()->toDateString()) }}" required
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Start Time</label>
            <input type="time" name="start_time" value="{{ old('start_time') }}"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">End Time</label>
            <input type="time" name="end_time" value="{{ old('end_time') }}"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Section</label>
            <input type="text" name="section" value="{{ old('section') }}" maxlength="10"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Topic Covered</label>
            <input type="text" name="topic_covered" value="{{ old('topic_covered') }}" maxlength="200"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Remarks</label>
            <textarea name="remarks" rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">{{ old('remarks') }}</textarea>
          </div>
        </div>
      </div>

      <div class="flex items-center justify-between gap-3">
        <a href="{{ route('teacher.attendance') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold border border-gray-200 text-gray-700 bg-white">
          Cancel
        </a>
        <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white" style="background:#17324f">
          Create Session
        </button>
      </div>
    </form>
  @endif
</div>
@endsection
