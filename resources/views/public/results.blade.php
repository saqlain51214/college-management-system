@extends('layouts.public')
@section('title', 'Exam Results')
@section('content')

<section class="bg-navy py-20 pt-32">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-xs text-white/50 mb-3"><a href="{{ route('home') }}" class="hover:text-white">Home</a> / Results</div>
    <h1 class="text-4xl font-bold text-white">Exam Results</h1>
    <p class="text-white/60 mt-3 max-w-xl">Enter your roll number to view your published exam results.</p>
  </div>
</section>

<section class="py-20 bg-gray-50">
  <div class="max-w-2xl mx-auto px-4 sm:px-6">

    {{-- Search Form --}}
    <form method="GET" action="{{ route('results') }}" class="bg-white rounded-2xl p-8 border border-gray-200 shadow-sm mb-10">
      <h2 class="text-xl font-bold text-navy mb-6">Search Results by Roll Number</h2>
      <div class="flex gap-3">
        <input
          type="text"
          name="roll_number"
          value="{{ request('roll_number') }}"
          placeholder="e.g. CS-2024-0001"
          required
          class="flex-1 px-4 py-3.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy"
        >
        <button type="submit" class="px-6 py-3.5 bg-navy text-white font-semibold rounded-xl hover:bg-navy-dark transition">
          Search
        </button>
      </div>
      <p class="text-xs text-gray-400 mt-3">Only published results are shown. Contact the college for unpublished results.</p>
    </form>

    {{-- Error --}}
    @if($error)
    <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-8 text-red-700 text-sm">
      {{ $error }}
    </div>
    @endif

    {{-- Student info + results --}}
    @if($student && $results !== null)
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

      {{-- Student card --}}
      <div class="bg-navy p-6">
        <div class="text-gold text-xs font-semibold uppercase tracking-wider mb-1">Student Found</div>
        <h3 class="text-white font-bold text-xl">{{ $student->name }}</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-4 text-sm">
          <div><div class="text-white/50 text-xs">Roll Number</div><div class="text-white font-medium">{{ $student->roll_number }}</div></div>
          <div><div class="text-white/50 text-xs">Program</div><div class="text-white font-medium">{{ $student->academicProgram?->name ?? '—' }}</div></div>
          <div><div class="text-white/50 text-xs">Semester</div><div class="text-white font-medium">Semester {{ $student->current_semester ?? '—' }}</div></div>
        </div>
      </div>

      {{-- Results table --}}
      @if($results->isEmpty())
      <div class="text-center text-gray-400 py-16 px-6">
        <div class="text-4xl mb-3">&#128196;</div>
        <p class="font-medium text-gray-500">No published results found for this student.</p>
        <p class="text-sm text-gray-400 mt-1">Results are published after evaluation. Check again later or contact the college.</p>
      </div>
      @else
      <div class="p-6">
        <h4 class="font-bold text-navy mb-4">Published Exam Results</h4>
        <div class="overflow-x-auto rounded-xl border border-gray-100">
          <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
              <tr>
                <th class="px-4 py-3 text-left">Exam</th>
                <th class="px-4 py-3 text-left">Course</th>
                <th class="px-4 py-3 text-left">Type</th>
                <th class="px-4 py-3 text-center">Total</th>
                <th class="px-4 py-3 text-center">Obtained</th>
                <th class="px-4 py-3 text-center">Grade</th>
                <th class="px-4 py-3 text-center">Result</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @foreach($results as $r)
              @php
                $isPass = !$r->is_absent && $r->marks_obtained !== null && $r->marks_obtained >= ($r->exam?->passing_marks ?? 40);
              @endphp
              <tr class="hover:bg-gray-50 transition @if($r->is_absent) opacity-60 @endif">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $r->exam?->title ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $r->exam?->course?->code }}</td>
                <td class="px-4 py-3 text-gray-400">{{ $r->exam?->exam_type instanceof \BackedEnum ? ucfirst($r->exam->exam_type->value) : ucfirst($r->exam?->exam_type ?? '') }}</td>
                <td class="px-4 py-3 text-center text-gray-600">{{ $r->exam?->total_marks }}</td>
                <td class="px-4 py-3 text-center font-semibold {{ $r->is_absent ? 'text-gray-400' : ($isPass ? 'text-green-600' : 'text-red-500') }}">
                  {{ $r->is_absent ? 'ABSENT' : ($r->marks_obtained !== null ? number_format($r->marks_obtained, 1) : '—') }}
                </td>
                <td class="px-4 py-3 text-center">
                  <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">{{ $r->is_absent ? 'AB' : ($r->grade ?? '—') }}</span>
                </td>
                <td class="px-4 py-3 text-center">
                  <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $r->is_absent ? 'bg-gray-100 text-gray-500' : ($isPass ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600') }}">
                    {{ $r->is_absent ? 'ABSENT' : ($isPass ? 'PASS' : 'FAIL') }}
                  </span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @php
          $appeared = $results->where('is_absent', false)->count();
          $passed   = $results->where('is_absent', false)->filter(fn($r) => $r->marks_obtained !== null && $r->marks_obtained >= ($r->exam?->passing_marks ?? 40))->count();
          $avgGpa   = $results->whereNotNull('grade_points')->avg('grade_points');
        @endphp
        <div class="mt-4 grid grid-cols-3 gap-4 text-center text-sm">
          <div class="bg-gray-50 rounded-xl py-3"><div class="font-bold text-navy text-lg">{{ $results->count() }}</div><div class="text-gray-400 text-xs">Exams</div></div>
          <div class="bg-green-50 rounded-xl py-3"><div class="font-bold text-green-700 text-lg">{{ $passed }}</div><div class="text-gray-400 text-xs">Passed</div></div>
          <div class="bg-blue-50 rounded-xl py-3"><div class="font-bold text-navy text-lg">{{ $avgGpa ? number_format($avgGpa, 2) : '—' }}</div><div class="text-gray-400 text-xs">Avg GPA</div></div>
        </div>
      </div>
      @endif
    </div>
    @endif

  </div>
</section>

@endsection
