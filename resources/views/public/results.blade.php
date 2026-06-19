@extends('layouts.public')
@section('title', 'Exam Results — ' . ($college->college_name ?? 'JDCA'))

@section('content')

<section class="relative overflow-hidden bg-ink pt-28 pb-12 text-white sm:pt-32 sm:pb-14" aria-labelledby="page-title">
  <div class="absolute inset-0 bg-[url('{{ asset('assets/images/photo-1523240795612-9a054b0db644.jpg') }}')] bg-cover bg-center opacity-20"></div>
  <div class="absolute inset-0 bg-gradient-to-br from-brand/90 via-ink/95 to-ink"></div>
  <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <nav class="mb-4 text-xs text-white/70" aria-label="Breadcrumb">
      <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
      <span class="mx-2 text-white/40">/</span>
      <span class="text-white">Results</span>
    </nav>
    <h1 id="page-title" class="font-display text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">{{ $pageContent['intro_title'] ?? 'Exam Results' }}</h1>
    <p class="mt-3 max-w-xl text-sm leading-relaxed text-white/90 sm:text-base">{{ $pageContent['intro_text'] ?? 'Enter your roll number to view your published exam results.' }}</p>
  </div>
</section>

@if(!empty($pageContent['body_html']))
<section class="border-b border-stone-200/80 bg-white py-10 md:py-12">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="prose prose-stone max-w-none">
      {!! $pageContent['body_html'] !!}
    </div>
  </div>
</section>
@endif

<section class="py-12 md:py-16" style="background-color:#f5f5f4;">
  <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

    <form method="GET" action="{{ route('results') }}" class="bg-white rounded-xl shadow-md p-8 border border-stone-200/80 mb-10">
      <h2 class="text-xl font-semibold text-ink mb-6">Search Results by Roll Number</h2>
      <div class="flex gap-3">
        <input
          type="text"
          name="roll_number"
          value="{{ request('roll_number') }}"
          placeholder="e.g., CS-2024-0001"
          required
          class="flex-1 px-4 py-3.5 border border-stone-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:border-brand transition"
        >
        <button type="submit" class="px-6 py-3.5 bg-brand text-white font-semibold rounded-md hover:bg-brand-dark transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2">
          Search
        </button>
      </div>
      <p class="text-xs text-stone-500 mt-3">Only published results are shown. Contact the college for unpublished results.</p>
    </form>

    @if($error)
    <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8 text-red-700 text-sm">
      {{ $error }}
    </div>
    @endif

    @if($student && $results !== null)
    <div class="bg-white rounded-xl border border-stone-200/80 shadow-md overflow-hidden">

      <div class="bg-brand p-6">
        <div class="text-brand-light text-xs font-semibold uppercase tracking-wide mb-1">Student Found</div>
        <h3 class="text-white font-semibold text-xl">{{ $student->name }}</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-4 text-sm">
          <div><div class="text-white/50 text-xs">Roll Number</div><div class="text-white font-medium">{{ $student->roll_number }}</div></div>
          <div><div class="text-white/50 text-xs">Program</div><div class="text-white font-medium">{{ $student->academicProgram?->name ?? '—' }}</div></div>
          <div><div class="text-white/50 text-xs">Semester</div><div class="text-white font-medium">Semester {{ $student->current_semester ?? '—' }}</div></div>
        </div>
      </div>

      @if($results->isEmpty())
      <div class="text-center text-stone-500 py-16 px-6">
        <div class="text-4xl mb-3">📄</div>
        <p class="font-medium text-stone-600">No published results found for this student.</p>
        <p class="text-sm text-stone-500 mt-1">Results are published after evaluation. Check again later or contact the college.</p>
      </div>
      @else
      <div class="p-6">
        <h4 class="font-semibold text-ink mb-4">Published Exam Results</h4>
        <div class="overflow-x-auto rounded-md border border-stone-200">
          <table class="w-full text-sm">
            <thead class="bg-stone-50 text-xs text-stone-500 uppercase tracking-wide">
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
            <tbody class="divide-y divide-stone-200">
              @foreach($results as $r)
              @php
                $isPass = !$r->is_absent && $r->marks_obtained !== null && $r->marks_obtained >= ($r->exam?->passing_marks ?? 40);
              @endphp
              <tr class="hover:bg-stone-50 transition @if($r->is_absent) opacity-60 @endif">
                <td class="px-4 py-3 font-medium text-stone-800">{{ $r->exam?->title ?? '—' }}</td>
                <td class="px-4 py-3 text-stone-600">{{ $r->exam?->course?->code ?? '—' }}</td>
                <td class="px-4 py-3 text-stone-500">{{ $r->exam?->exam_type instanceof \BackedEnum ? ucfirst($r->exam->exam_type->value) : ucfirst($r->exam?->exam_type ?? '') }}</td>
                <td class="px-4 py-3 text-center text-stone-600">{{ $r->exam?->total_marks ?? '—' }}</td>
                <td class="px-4 py-3 text-center font-semibold {{ $r->is_absent ? 'text-stone-400' : ($isPass ? 'text-green-700' : 'text-red-600') }}">
                  {{ $r->is_absent ? 'ABSENT' : ($r->marks_obtained !== null ? number_format($r->marks_obtained, 1) : '—') }}
                </td>
                <td class="px-4 py-3 text-center">
                  <span class="px-2 py-0.5 rounded-full bg-stone-100 text-stone-700 text-xs font-semibold">{{ $r->is_absent ? 'AB' : ($r->grade ?? '—') }}</span>
                </td>
                <td class="px-4 py-3 text-center">
                  <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $r->is_absent ? 'bg-stone-100 text-stone-500' : ($isPass ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
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
          <div class="bg-stone-50 rounded-md py-3"><div class="font-semibold text-brand text-lg">{{ $results->count() }}</div><div class="text-stone-500 text-xs">Exams</div></div>
          <div class="bg-green-50 rounded-md py-3"><div class="font-semibold text-green-700 text-lg">{{ $passed }}</div><div class="text-stone-500 text-xs">Passed</div></div>
          <div class="bg-blue-50 rounded-md py-3"><div class="font-semibold text-brand text-lg">{{ $avgGpa ? number_format($avgGpa, 2) : '—' }}</div><div class="text-stone-500 text-xs">Avg GPA</div></div>
        </div>
      </div>
      @endif
    </div>
    @endif
  </div>
</section>

<section class="border-t border-stone-200/80 bg-white py-12 text-center md:py-14">
  <div class="mx-auto max-w-2xl px-4 sm:px-6">
    <h2 class="font-display text-2xl font-semibold sm:text-3xl text-ink">Need Help with Results?</h2>
    <p class="mt-3 text-sm leading-relaxed text-stone-600 sm:text-base">If you have any questions about your results or need further assistance, please don't hesitate to contact us.</p>
    <div class="mt-6 flex flex-wrap gap-3 justify-center">
      <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-md bg-brand px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2">
        Contact Us
      </a>
      <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-md border border-stone-300 bg-white px-5 py-2.5 text-sm font-semibold text-stone-700 transition hover:bg-stone-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2">
        Back to Home
      </a>
    </div>
  </div>
</section>

@endsection
