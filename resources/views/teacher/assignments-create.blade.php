@extends('layouts.teacher-portal')
@section('title', 'Create Assignment')
@section('content')

<div class="max-w-5xl mx-auto space-y-6">
  <div>
    <h2 class="text-xl font-bold text-gray-900">Create Assignment</h2>
    <p class="text-sm text-gray-500 mt-1">Add a new assignment for your assigned classes.</p>
  </div>

  @if($courses->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4 text-sm text-amber-800">
      No assigned courses found yet. Please contact admin or make sure your timetable is assigned before creating assignments.
    </div>
  @else
    @include('teacher.partials.assignment-form')
  @endif
</div>
@endsection
