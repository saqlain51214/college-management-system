@extends('layouts.teacher-portal')
@section('title', 'Edit Material')
@section('content')

<div class="max-w-5xl mx-auto space-y-6">
  <div>
    <h2 class="text-xl font-bold text-gray-900">Edit Material</h2>
    <p class="text-sm text-gray-500 mt-1">Update your material details.</p>
  </div>

  @include('teacher.partials.material-form')
</div>
@endsection
