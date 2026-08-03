@extends('layouts.public')
@section('title', 'Academics — ' . ($college->college_name ?? 'JDCA'))

@section('content')

<section class="relative overflow-hidden bg-ink pt-28 pb-12 text-white sm:pt-32 sm:pb-14" aria-labelledby="page-title">
  <div class="absolute inset-0 bg-[url('{{ asset('assets/images/photo-1523050854058-8df90110c9f1.jpg') }}')] bg-cover bg-center opacity-20"></div>
  <div class="absolute inset-0 bg-gradient-to-br from-brand/90 via-ink/95 to-ink"></div>
  <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
    <nav class="mb-4 text-xs text-white/70" aria-label="Breadcrumb">
      <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
      <span class="mx-2 text-white/40">/</span>
      <span class="text-white">Academics</span>
    </nav>
    <h1 id="page-title" class="font-display text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">{{ $pageContent['intro_title'] ?? 'Academics' }}</h1>
    <p class="mt-3 max-w-2xl text-sm text-white/90 sm:text-base">{{ $pageContent['intro_text'] ?? 'Programmes, pathways, and how we support board and university entry.' }}</p>
  </div>
</section>

@if(!empty($pageContent['body_html']))
<section class="border-b border-stone-200/80 bg-white py-10 md:py-12">
  <div class="mx-auto max-w-4xl px-4 sm:px-6">
    <div class="prose prose-stone max-w-none">
      {!! $pageContent['body_html'] !!}
    </div>
  </div>
</section>
@endif

<section class="border-b border-stone-200/80 bg-white py-12 md:py-16">
  <div class="mx-auto max-w-6xl px-4 sm:px-6">
    <p class="mx-auto max-w-3xl text-center text-sm leading-relaxed text-stone-600 sm:text-base">{{ $college->college_name ?? 'Jinnah Degree College Astore' }} delivers <strong class="text-ink">intermediate (FSc, FA, ICS, I.Com)</strong> streams plus affiliated <strong class="text-ink">BS / ADP</strong> routes where formally approved. Curriculum, assessment patterns, and practical schemes follow board notifications and HEC guidelines for higher programmes.</p>
  </div>
</section>

<section class="py-12 md:py-16">
  <div class="mx-auto max-w-6xl px-4 sm:px-6">
    <h2 class="mb-8 text-center font-display text-2xl font-semibold text-ink sm:text-3xl">Programmes Offered</h2>
    @if($programs->isEmpty())
      <div class="rounded-2xl border border-dashed border-stone-300 p-16 text-center">
        <p class="text-stone-500 font-medium text-lg">Programmes are being set up.</p>
        <p class="text-stone-400 mt-1">Please check back soon.</p>
      </div>
    @else
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($programs as $program)
        <article id="{{ $program->slug }}" class="group rounded-xl border border-stone-200/80 bg-white shadow-md overflow-hidden transition hover:shadow-lg hover:-translate-y-0.5">
          @if($program->banner_image_url)
            <div class="h-32 overflow-hidden">
              <img src="{{ $program->banner_image_url }}" alt="{{ $program->name }}"
                   class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
          @else
            <div class="h-32 site-brand-gradient flex items-center justify-center">
              <svg class="w-10 h-10 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.42A12.02 12.02 0 0121 15.5V17a2 2 0 01-2 2H5a2 2 0 01-2-2v-1.5a12.02 12.02 0 012.84-4.92L12 14z"/></svg>
            </div>
          @endif
          <div class="p-6">
            @if($program->degree_type)
              <span class="inline-block rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wide bg-brand/10 text-brand">{{ $program->degree_type->shortLabel() }}</span>
            @endif
            <h3 class="mt-2 font-semibold text-ink">{{ $program->name }}</h3>
            <p class="mt-2 text-sm text-stone-600 line-clamp-2">{{ $program->description ?? 'Structured preparation for board and university exams.' }}</p>
            @if($program->duration_years || $program->total_semesters)
            <p class="mt-3 text-xs font-medium text-stone-500">
              @if($program->duration_years){{ $program->duration_years }} {{ Str::plural('Year', $program->duration_years) }}@endif
              @if($program->total_semesters) ({{ $program->total_semesters }} Semesters)@endif
            </p>
            @endif
            <a href="{{ route('admissions') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-brand hover:underline">
              Apply Now
              <svg class="w-3.5 h-3.5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
          </div>
        </article>
        @endforeach
      </div>
    @endif
  </div>
</section>

<section class="border-t border-stone-200/80 bg-white py-12 md:py-16" aria-labelledby="prog-spec-heading">
  <div class="mx-auto max-w-6xl px-4 sm:px-6">
    <h2 id="prog-spec-heading" class="mb-2 font-display text-2xl font-semibold text-ink sm:text-3xl">Programme details</h2>
    <p class="mb-8 max-w-3xl text-sm text-stone-600">Expand each row for subjects, typical weekly hours, and entry routes—similar to faculty handbooks published on college websites.</p>
    <div class="space-y-2">
      <details class="group rounded-xl border border-stone-200/80 bg-surface px-4 py-3 shadow-sm open:bg-white open:shadow-md">
        <summary class="cursor-pointer list-none font-semibold text-ink outline-none marker:content-none [&::-webkit-details-marker]:hidden">FSc Pre-Medical — subjects & outcomes</summary>
        <div class="mt-3 space-y-2 border-t border-stone-200/80 pt-3 text-sm text-stone-600">
          <p><strong class="text-ink">Core subjects:</strong> English, Urdu / Pakistan Studies, Islamiat, Biology, Chemistry, Physics.</p>
          <p><strong class="text-ink">Weekly contact time:</strong> ~36 periods including labs; extra remedial sessions before board exams.</p>
          <p><strong class="text-ink">Pathways:</strong> MDCAT preparation support, university fairs, and counselling for allied health programmes.</p>
        </div>
      </details>
      <details class="group rounded-xl border border-stone-200/80 bg-surface px-4 py-3 shadow-sm open:bg-white open:shadow-md">
        <summary class="cursor-pointer list-none font-semibold text-ink outline-none marker:content-none [&::-webkit-details-marker]:hidden">FSc Pre-Engineering — subjects & outcomes</summary>
        <div class="mt-3 space-y-2 border-t border-stone-200/80 pt-3 text-sm text-stone-600">
          <p><strong class="text-ink">Core subjects:</strong> English, Urdu / Pakistan Studies, Islamiat, Mathematics, Physics, Chemistry.</p>
          <p><strong class="text-ink">Labs:</strong> Physics and Chemistry practicals aligned with board practical schemes.</p>
          <p><strong class="text-ink">Pathways:</strong> ECAT-focused problem sets; engineering and CS orientation sessions.</p>
        </div>
      </details>
      <details class="group rounded-xl border border-stone-200/80 bg-surface px-4 py-3 shadow-sm open:bg-white open:shadow-md">
        <summary class="cursor-pointer list-none font-semibold text-ink outline-none marker:content-none [&::-webkit-details-marker]:hidden">ICS — combinations</summary>
        <div class="mt-3 space-y-2 border-t border-stone-200/80 pt-3 text-sm text-stone-600">
          <p><strong class="text-ink">Typical combinations:</strong> Computer Science + Mathematics + Physics or Statistics (per board rules).</p>
          <p><strong class="text-ink">Facilities:</strong> Dedicated lab periods, project work, and safe internet use policy for research.</p>
        </div>
      </details>
      <details class="group rounded-xl border border-stone-200/80 bg-surface px-4 py-3 shadow-sm open:bg-white open:shadow-md">
        <summary class="cursor-pointer list-none font-semibold text-ink outline-none marker:content-none [&::-webkit-details-marker]:hidden">I.Com & FA — overview</summary>
        <div class="mt-3 space-y-2 border-t border-stone-200/80 pt-3 text-sm text-stone-600">
          <p><strong class="text-ink">I.Com:</strong> Accounting, commerce, business maths / stats where applicable; foundation for BBA, CA, ACCA pathways.</p>
          <p><strong class="text-ink">FA:</strong> Elective groups in arts and humanities; support for law aptitude and civil service reading lists.</p>
        </div>
      </details>
    </div>
  </div>
</section>

<section class="border-t border-stone-200/80 bg-surface py-12 md:py-16">
  <div class="mx-auto max-w-6xl px-4 sm:px-6">
    <div class="grid gap-10 md:grid-cols-2">
      <div>
        <h2 class="font-display text-xl font-semibold text-ink sm:text-2xl">Assessment & progression</h2>
        <p class="mt-3 text-sm leading-relaxed text-stone-600">Monthly tests, send-ups, and full-board pattern papers. Report cards each term with comments; parent–teacher meetings before board practicals and theory papers.</p>
      </div>
      <div>
        <h2 class="font-display text-xl font-semibold text-ink sm:text-2xl">Academic calendar</h2>
        <p class="mt-3 text-sm leading-relaxed text-stone-600">The academic calendar follows regional notifications for summer/winter vacations and annual / supplementary schedules. <a href="{{ route('news') }}" class="font-semibold text-brand hover:underline">Notices</a> and PDF calendars are posted on the website.</p>
      </div>
    </div>
  </div>
</section>

<section class="bg-brand py-12 text-center text-white md:py-14">
  <div class="mx-auto max-w-2xl px-4 sm:px-6">
    <h2 class="font-display text-2xl font-semibold sm:text-3xl">Questions?</h2>
    <p class="mt-3 text-sm text-white/90 sm:text-base">Reach the office or explore admissions.</p>
    <div class="mt-6 flex flex-col items-center justify-center gap-3 sm:flex-row sm:gap-4">
      <a href="{{ route('admissions') }}" class="inline-flex items-center justify-center rounded-md bg-white px-7 py-3 text-sm font-semibold text-brand shadow-lg transition hover:bg-stone-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-brand">Admissions</a>
      <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-md border-2 border-white px-7 py-3 text-sm font-semibold text-white transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-brand">Contact</a>
    </div>
  </div>
</section>

@endsection
