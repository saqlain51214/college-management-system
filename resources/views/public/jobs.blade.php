@extends('layouts.public')
@section('title', 'Job Opportunities — ' . ($college->college_name ?? 'JDCA'))
@section('meta_description', 'Career opportunities and job vacancies at Jinnah School & Degree College Astore (JDCA).')

@section('content')

{{-- ── Page Banner ─────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden bg-ink pt-28 pb-12 text-white sm:pt-32 sm:pb-14" aria-labelledby="page-title">
  <div class="absolute inset-0 bg-gradient-to-br from-brand/90 via-ink/95 to-ink"></div>
  <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
    <nav class="mb-4 text-xs text-white/70" aria-label="Breadcrumb">
      <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
      <span class="mx-2 text-white/40">/</span>
      <span class="text-white">Job Opportunities</span>
    </nav>
    <h1 id="page-title" class="font-display text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">Job Opportunities</h1>
    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white/90 sm:text-base">
      Join our team at {{ $college->college_name ?? 'Jinnah School & Degree College Astore' }}.
    </p>
  </div>
</section>

{{-- ── Success banner ─────────────────────────────────────────────────── --}}
@if(session('job_applied'))
<div class="bg-green-50 border-b border-green-200 py-3 px-4 text-center text-sm text-green-800">
  <strong>Thank you, {{ session('job_applied') }}!</strong> Your application has been submitted. We will contact you soon.
</div>
@endif

{{-- ── Main content ────────────────────────────────────────────────────── --}}
<section class="py-12 md:py-16" style="background:var(--site-body-bg)"
         x-data="{
           open: false,
           position: '',
           submitted: false,
           openFor(pos) { this.position = pos; this.open = true; this.submitted = false; }
         }">
  <div class="mx-auto max-w-4xl px-4 sm:px-6">

    {{-- How to apply info card --}}
    <div class="mb-10 rounded-xl border border-stone-200/80 bg-white p-6 shadow-md sm:p-8">
      <h2 class="mb-3 font-display text-xl font-semibold text-ink sm:text-2xl">How to Apply</h2>
      <p class="text-sm leading-relaxed text-stone-600 mb-4">
        Click <strong>Apply</strong> on any position below to fill in a short application form. We'll review your details and reach out if you're shortlisted. You can also walk in to the college office during working hours.
      </p>
      <div class="flex flex-wrap gap-3 text-sm text-stone-500">
        <span class="flex items-center gap-1.5">
          <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          Online application form
        </span>
        <span class="flex items-center gap-1.5">
          <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          Direct walk-in accepted
        </span>
        <span class="flex items-center gap-1.5">
          <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          No application fee
        </span>
      </div>
    </div>

    {{-- Job openings --}}
    <h2 class="mb-6 font-display text-xl font-semibold text-ink sm:text-2xl">Current Openings</h2>

    @php
    $openings = [
      ['title'=>'Lecturer — Computer Science',       'type'=>'Full-time', 'dept'=>'Department of Computer Science', 'quali'=>'MS / BS (Hons) Computer Science from HEC-recognised university'],
      ['title'=>'Lecturer — Mathematics',            'type'=>'Full-time', 'dept'=>'Department of Mathematics',      'quali'=>'MS / BS (Hons) Mathematics from HEC-recognised university'],
      ['title'=>'Lab Technician — Physics/Chemistry','type'=>'Full-time', 'dept'=>'Science Labs',                   'quali'=>'BSc with relevant lab experience preferred'],
      ['title'=>'Office Assistant',                  'type'=>'Full-time', 'dept'=>'Administration',                 'quali'=>'Intermediate / FA with computer literacy'],
    ];
    @endphp

    <div class="space-y-4">
      @foreach($openings as $job)
      <div class="rounded-xl border border-stone-200/80 bg-white p-5 shadow-sm sm:p-6 transition hover:shadow-md">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-2">
              <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white"
                    style="background:var(--site-brand)">{{ $job['type'] }}</span>
              <span class="text-xs text-stone-400">{{ $job['dept'] }}</span>
            </div>
            <h3 class="font-semibold text-ink text-base sm:text-lg">{{ $job['title'] }}</h3>
            <p class="mt-1.5 text-sm text-stone-500">
              <strong class="text-stone-700">Qualification:</strong> {{ $job['quali'] }}
            </p>
          </div>
          <div class="shrink-0">
            <button type="button"
                    @click="openFor('{{ addslashes($job['title']) }}')"
                    class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2"
                    style="background:var(--site-brand); --tw-ring-color:var(--site-brand)">
              Apply Now
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </button>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    {{-- General inquiry CTA --}}
    <div class="mt-10 rounded-xl border border-stone-200/60 p-6 text-center" style="background:color-mix(in srgb,var(--site-brand) 5%,white)">
      <p class="font-semibold mb-1" style="color:var(--site-brand)">Don't see a matching role?</p>
      <p class="text-sm text-stone-600 mb-4">Send us a general enquiry — we keep profiles on file for future openings.</p>
      <button type="button" @click="openFor('General Enquiry')"
              class="inline-flex items-center gap-2 rounded-lg px-6 py-2.5 text-sm font-semibold text-white transition hover:opacity-90"
              style="background:var(--site-brand)">Express Interest</button>
    </div>

  </div>

  {{-- ══ APPLY MODAL ══════════════════════════════════════════════════════ --}}
  <div x-show="open"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       @keydown.escape.window="open=false"
       class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
       style="display:none;">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60" @click="open=false"></div>

    {{-- Modal box --}}
    <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl overflow-hidden max-h-[92dvh] flex flex-col"
         @click.stop>

      {{-- Modal header --}}
      <div class="flex items-start justify-between px-6 pt-6 pb-4 border-b border-stone-100 shrink-0">
        <div>
          <h3 class="font-display text-xl font-semibold text-ink">Apply for Position</h3>
          <p class="mt-0.5 text-sm text-stone-500" x-text="position"></p>
        </div>
        <button @click="open=false" class="ml-4 mt-0.5 rounded-lg p-1.5 text-stone-400 hover:text-stone-700 hover:bg-stone-100 transition shrink-0" aria-label="Close">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      {{-- Form --}}
      <div class="overflow-y-auto flex-1">
        <form method="POST" action="{{ route('jobs.apply') }}" class="px-6 py-5 space-y-4">
          @csrf
          <input type="hidden" name="position" :value="position">

          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <label class="block text-xs font-semibold text-stone-600 mb-1.5 uppercase tracking-wide">Full Name <span class="text-red-500">*</span></label>
              <input type="text" name="name" required
                     class="w-full rounded-lg border border-stone-300 px-3.5 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20"
                     placeholder="Your full name">
            </div>
            <div>
              <label class="block text-xs font-semibold text-stone-600 mb-1.5 uppercase tracking-wide">Phone <span class="text-red-500">*</span></label>
              <input type="tel" name="phone" required
                     class="w-full rounded-lg border border-stone-300 px-3.5 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20"
                     placeholder="+92 300 0000000">
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-stone-600 mb-1.5 uppercase tracking-wide">Email Address <span class="text-red-500">*</span></label>
            <input type="email" name="email" required
                   class="w-full rounded-lg border border-stone-300 px-3.5 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20"
                   placeholder="your@email.com">
          </div>

          <div>
            <label class="block text-xs font-semibold text-stone-600 mb-1.5 uppercase tracking-wide">Highest Education / Degree <span class="text-red-500">*</span></label>
            <input type="text" name="education" required
                   class="w-full rounded-lg border border-stone-300 px-3.5 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20"
                   placeholder="e.g. MS Computer Science, NUCES Islamabad">
          </div>

          <div>
            <label class="block text-xs font-semibold text-stone-600 mb-1.5 uppercase tracking-wide">Relevant Experience</label>
            <input type="text" name="experience"
                   class="w-full rounded-lg border border-stone-300 px-3.5 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20"
                   placeholder="e.g. 2 years teaching at XYZ College (optional)">
          </div>

          <div>
            <label class="block text-xs font-semibold text-stone-600 mb-1.5 uppercase tracking-wide">Cover Letter / Why You? <span class="text-red-500">*</span></label>
            <textarea name="message" required rows="4"
                      class="w-full rounded-lg border border-stone-300 px-3.5 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 resize-none"
                      placeholder="Briefly describe your qualifications, motivation, and why you'd be a great fit..."></textarea>
          </div>

          <p class="text-xs text-stone-400">After submitting, the college administration will contact you by email or phone if shortlisted. You may also bring attested copies of your documents to the office.</p>

          <div class="flex gap-3 pt-1 pb-2">
            <button type="submit"
                    class="flex-1 rounded-lg py-3 text-sm font-bold text-white transition hover:opacity-90"
                    style="background:var(--site-brand)">
              Submit Application
            </button>
            <button type="button" @click="open=false"
                    class="rounded-lg border border-stone-300 px-5 py-3 text-sm font-semibold text-stone-700 hover:bg-stone-50 transition">
              Cancel
            </button>
          </div>
        </form>
      </div>

    </div>{{-- /.modal box --}}
  </div>{{-- /.modal --}}

</section>

{{-- Auto-open modal if validation errors returned --}}
@if($errors->any())
@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
  setTimeout(() => {
    const comp = document.querySelector('[x-data]')?._x_dataStack?.[0];
    if (comp) { comp.open = true; comp.position = '{{ old('position','') }}'; }
  }, 100);
});
</script>
@endpush
@endif

@endsection
