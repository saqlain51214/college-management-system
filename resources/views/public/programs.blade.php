@extends('layouts.public')
@section('title', 'Academic Programs — ' . ($college->college_name ?? 'JDCA'))

@section('content')

{{-- ============================================================
     SECTION 1: PAGE HERO
     ============================================================ --}}
<section class="relative overflow-hidden text-white" style="background:var(--c-ink); padding-top:7rem; padding-bottom:3.5rem;">
  <div class="absolute inset-0 pointer-events-none" style="opacity:.06;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:32px 32px;"></div>
  <div class="absolute inset-0 pointer-events-none" style="background:linear-gradient(to bottom,rgba(107,45,57,.35) 0%,transparent 100%);"></div>
  <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
    <nav class="mb-4 flex items-center gap-1.5 text-xs" style="color:rgba(255,255,255,.50);">
      <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      <span style="color:rgba(255,255,255,.80);">Programs</span>
    </nav>
    <h1 class="font-display text-3xl sm:text-4xl md:text-5xl font-semibold tracking-tight">
      Academic <span style="color:var(--c-gold);">Programs</span>
    </h1>
    <p class="mt-3 max-w-2xl text-sm sm:text-base leading-relaxed" style="color:rgba(255,255,255,.80);">
      Nationally recognised degree programmes designed to empower students from Astore and the wider Gilgit-Baltistan region. KIU-affiliated, HEC-recognised.
    </p>
  </div>
</section>

{{-- ============================================================
     SECTION 2: FILTER BAR
     ============================================================ --}}
@php
  $degreeTypes = isset($programs) ? $programs->pluck('degree_type')->map(fn($t) => $t?->value ?? (string)$t)->unique()->filter()->values() : collect();
@endphp

<div class="sticky top-0 z-30 bg-white shadow-sm border-b border-gray-100">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4">
    <div class="flex items-center justify-between gap-4 flex-wrap">

      <div class="flex items-center gap-2 flex-wrap" id="filterButtons">
        <button onclick="filterPrograms('all')" data-filter="all"
                class="filter-btn px-5 py-2 rounded-full text-sm font-semibold transition-all"
                style="background:var(--c-primary);color:#fff;border:2px solid var(--c-primary)">
          All Programs
        </button>
        @foreach($degreeTypes as $dtype)
        <button onclick="filterPrograms('{{ Str::slug((string)$dtype) }}')" data-filter="{{ Str::slug((string)$dtype) }}"
                class="filter-btn px-5 py-2 rounded-full text-sm font-semibold transition-all"
                style="background:transparent;color:var(--c-primary);border:2px solid var(--c-primary)">
          {{ $dtype }}
        </button>
        @endforeach
      </div>

      <div class="text-sm font-medium text-gray-500">
        <span id="programCount">{{ isset($programs) ? $programs->count() : 0 }}</span> program(s) shown
      </div>

    </div>
  </div>
</div>

{{-- ============================================================
     SECTION 3: PROGRAMS GRID
     ============================================================ --}}
<section class="py-16" style="background:var(--c-surface)" id="programsSection">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">

    @php
      $gradientMap = [
        'bs'           => 'linear-gradient(135deg,#5a1212,#7b1a1a)',
        'intermediate' => 'linear-gradient(135deg,#1e3a5f,#1e293b)',
        'ms'           => 'linear-gradient(135deg,#065f46,#047857)',
        'med'          => 'linear-gradient(135deg,#065f46,#047857)',
        'm-ed'         => 'linear-gradient(135deg,#065f46,#047857)',
        'mphil'        => 'linear-gradient(135deg,#4c1d95,#5b21b6)',
        'ba'           => 'linear-gradient(135deg,#1e3a5f,#1e4a8f)',
        'b-ed'         => 'linear-gradient(135deg,#7c2d12,#9a3412)',
      ];

      $grouped = isset($programs) ? $programs->groupBy(fn($p) => $p->degree_type?->value ?? '') : collect();
    @endphp

    @if(isset($programs) && $programs->isEmpty())
    <div class="text-center py-20">
      <div class="text-6xl mb-4">📚</div>
      <h3 class="text-2xl font-bold mb-2" style="color:var(--c-primary)">No Programs Listed Yet</h3>
      <p class="text-gray-500">Academic programs will be listed here once they are added to the system.</p>
    </div>
    @else

    {{-- No results panel (shown by JS) --}}
    <div id="noResults" class="hidden text-center py-16">
      <div class="text-5xl mb-4">🔍</div>
      <h3 class="text-xl font-bold mb-2" style="color:var(--c-primary)">No programs match this filter</h3>
      <button onclick="filterPrograms('all')" class="mt-4 px-6 py-2 rounded-xl text-white text-sm font-semibold"
              style="background:var(--c-primary)">Show All Programs</button>
    </div>

    @foreach($grouped as $degreeType => $groupPrograms)
    <div class="program-group mb-12" data-group="{{ Str::slug((string)$degreeType) }}">

      {{-- Group heading --}}
      <div class="flex items-center gap-4 mb-6">
        <h2 class="text-2xl font-extrabold" style="color:var(--c-primary)">{{ strtoupper($degreeType) }} Programs</h2>
        <div class="flex-1 h-px bg-gray-200"></div>
        <span class="text-sm font-semibold px-3 py-1 rounded-full"
              style="background:rgba(90,18,18,.1);color:var(--c-primary)">
          {{ $groupPrograms->count() }} program{{ $groupPrograms->count() !== 1 ? 's' : '' }}
        </span>
      </div>

      {{-- Cards grid --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($groupPrograms as $program)
        @php
          $gradientKey = Str::slug($program->degree_type?->value ?? '');
          $cardGradient = $gradientMap[$gradientKey] ?? 'linear-gradient(135deg,#5a1212,#1e293b)';
          $nameInitials = collect(explode(' ', $program->name ?? 'P'))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');
        @endphp
        <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:-translate-y-1 transition-transform program-card"
             data-type="{{ Str::slug($program->degree_type?->value ?? '') }}">

          {{-- Colored header --}}
          <div class="relative h-32 flex items-center px-6" style="background:{{ $cardGradient }}">
            <div class="absolute inset-0 pointer-events-none opacity-10"
                 style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:24px 24px"></div>
            <div class="relative flex items-center gap-4">
              <div class="w-14 h-14 rounded-xl flex items-center justify-center text-lg font-extrabold text-white"
                   style="background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.3)">
                {{ $nameInitials }}
              </div>
              <div>
                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-bold text-white mb-1"
                      style="background:rgba(255,255,255,.2)">{{ $program->degree_type?->shortLabel() ?? $program->degree_type?->value ?? '' }}</span>
                <p class="text-white font-bold leading-snug text-sm">{{ $program->short_name ?? $program->name }}</p>
              </div>
            </div>
          </div>

          {{-- Card body --}}
          <div class="p-6">
            <h3 class="font-bold text-lg mb-3 leading-snug" style="color:var(--c-primary)">{{ $program->name }}</h3>

            {{-- Duration / semesters row --}}
            <div class="flex items-center gap-4 mb-3 text-sm text-gray-500">
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $program->duration_years ?? '2' }} Year{{ ($program->duration_years ?? 2) != 1 ? 's' : '' }}
              </span>
              @if(!empty($program->total_semesters))
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                {{ $program->total_semesters }} Semesters
              </span>
              @endif
            </div>

            @if(!empty($program->description))
            <p class="text-sm text-gray-500 mb-4 leading-relaxed">
              {{ Str::limit($program->description, 100) }}
            </p>
            @endif

            {{-- Status + actions --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
              @if($program->is_active ?? true)
              <span class="flex items-center gap-1.5 text-xs font-semibold text-green-700">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Admissions Open
              </span>
              @else
              <span class="text-xs text-gray-400">Not accepting</span>
              @endif

              <div class="flex gap-2">
                <a href="{{ route('admissions') }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white"
                   style="background:var(--c-primary)">Apply Now</a>
                <a href="{{ route('programs') }}#{{ Str::slug($program->name) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold"
                   style="border:1.5px solid var(--c-primary);color:var(--c-primary)">Details</a>
              </div>
            </div>
          </div>

        </div>
        @endforeach
      </div>

    </div>
    @endforeach

    @endif

  </div>
</section>

{{-- ============================================================
     SECTION 4: GENERAL ADMISSION REQUIREMENTS
     ============================================================ --}}
<section class="py-20 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">

    <div class="text-center mb-12">
      <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--c-gold)">Eligibility</p>
      <h2 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight" style="color:var(--c-primary)">Admission Requirements</h2>
      <p class="mt-3 text-gray-500 max-w-xl mx-auto">
        General eligibility criteria for all programmes. Specific requirements may vary per programme.
      </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

      @php
        $reqCards = [
          [
            'accent'  => 'var(--c-primary)',
            'icon'    => '🎓',
            'title'   => 'Academic Qualification',
            'items'   => ['Matric (SSC) for Intermediate','FSc / FA / Matric for BS','Relevant Bachelor\'s for MS/MPhil','Minimum 2nd Division required'],
          ],
          [
            'accent'  => 'var(--c-gold)',
            'icon'    => '📅',
            'title'   => 'Age Limit',
            'items'   => ['Intermediate: 16–22 years','BS Programs: 17–25 years','MS / MPhil: 22–35 years','Relaxation on case-by-case basis'],
          ],
          [
            'accent'  => '#1e3a5f',
            'icon'    => '📄',
            'title'   => 'Required Documents',
            'items'   => ['Matric / FSc mark sheets','Domicile Certificate','CNIC / B-Form copy','2 passport-size photos'],
          ],
          [
            'accent'  => '#065f46',
            'icon'    => '📝',
            'title'   => 'Interview / Entry Test',
            'items'   => ['Entry test for BS programs','Interview for MS/MPhil','Conducted at college campus','Dates announced each semester'],
          ],
        ];
      @endphp

      @foreach($reqCards as $rc)
      <div class="bg-white rounded-2xl shadow-md p-6 border-t-4" style="border-top-color:{{ $rc['accent'] }}">
        <div class="text-4xl mb-4">{{ $rc['icon'] }}</div>
        <h3 class="font-bold text-lg mb-4" style="color:var(--c-primary)">{{ $rc['title'] }}</h3>
        <ul class="space-y-2">
          @foreach($rc['items'] as $item)
          <li class="flex items-start gap-2 text-sm text-gray-600">
            <span style="color:{{ $rc['accent'] }};margin-top:2px">&#10003;</span>
            {{ $item }}
          </li>
          @endforeach
        </ul>
      </div>
      @endforeach

    </div>

    {{-- Contact note --}}
    <div class="rounded-2xl p-6 text-center" style="background:rgba(196,151,58,.08);border:1px solid rgba(196,151,58,.25)">
      <p class="text-sm text-gray-700">
        For programme-specific requirements or queries, contact our Admissions Office:
        @if(!empty($college->phone))
        <a href="tel:{{ $college->phone }}" class="font-semibold hover:underline" style="color:var(--c-primary)">{{ $college->phone }}</a>
        @endif
        @if(!empty($college->email))
        &nbsp;&bull;&nbsp;
        <a href="mailto:{{ $college->email }}" class="font-semibold hover:underline" style="color:var(--c-primary)">{{ $college->email }}</a>
        @endif
      </p>
    </div>

  </div>
</section>

{{-- ============================================================
     SECTION 5: CTA
     ============================================================ --}}
<section class="py-20" style="background:var(--c-primary)">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

      {{-- Left --}}
      <div>
        <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-gold)">Get Started</p>
        <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Take the First Step Towards Your Degree</h2>
        <p class="mb-8" style="color:rgba(255,255,255,.75)">
          Applications for the upcoming academic year are now open. Complete your application online or
          visit the college admissions office in Astore.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="{{ route('admissions') }}"
             class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold transition-transform hover:-translate-y-0.5"
             style="background:var(--c-gold);color:#1a0a00">
            Apply Now
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
          </a>
          <a href="{{ route('contact') }}"
             class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold text-white transition-transform hover:-translate-y-0.5"
             style="border:2px solid rgba(255,255,255,.5)">
            Contact Admissions
          </a>
        </div>
      </div>

      {{-- Right: contact card --}}
      <div class="rounded-2xl p-8" style="background:rgba(0,0,0,.25);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.12)">
        <h3 class="font-bold text-lg text-white mb-6">Admissions Office</h3>
        <ul class="space-y-4">
          @if(!empty($college->phone))
          <li class="flex items-center gap-3 text-white">
            <svg class="w-5 h-5 flex-shrink-0" style="color:var(--c-gold)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            <a href="tel:{{ $college->phone }}" class="hover:underline">{{ $college->phone }}</a>
          </li>
          @endif
          @if(!empty($college->email))
          <li class="flex items-center gap-3 text-white">
            <svg class="w-5 h-5 flex-shrink-0" style="color:var(--c-gold)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <a href="mailto:{{ $college->email }}" class="hover:underline">{{ $college->email }}</a>
          </li>
          @endif
          @if(!empty($college->address))
          <li class="flex items-start gap-3 text-white">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color:var(--c-gold)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span>{{ $college->address }}</span>
          </li>
          @endif
          <li class="flex items-center gap-3 text-white">
            <svg class="w-5 h-5 flex-shrink-0" style="color:var(--c-gold)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>Principal: {{ $college->principal_name ?? 'Arif Ali' }}</span>
          </li>
        </ul>
      </div>

    </div>
  </div>
</section>

{{-- ============================================================
     FILTER JS
     ============================================================ --}}
<script>
function filterPrograms(type) {
  const buttons = document.querySelectorAll('.filter-btn');
  const groups  = document.querySelectorAll('.program-group');
  const noRes   = document.getElementById('noResults');

  // Update button states
  buttons.forEach(btn => {
    if (btn.dataset.filter === type) {
      btn.style.background = 'var(--c-primary)';
      btn.style.color      = '#fff';
      btn.style.border     = '2px solid var(--c-primary)';
    } else {
      btn.style.background = 'transparent';
      btn.style.color      = 'var(--c-primary)';
      btn.style.border     = '2px solid var(--c-primary)';
    }
  });

  let visibleCount = 0;

  if (type === 'all') {
    groups.forEach(g => { g.style.display = ''; });
    document.querySelectorAll('.program-card').forEach(c => { visibleCount++; });
    if (noRes) noRes.classList.add('hidden');
  } else {
    groups.forEach(g => {
      if (g.dataset.group === type) {
        g.style.display = '';
        visibleCount += g.querySelectorAll('.program-card').length;
      } else {
        g.style.display = 'none';
      }
    });
    if (noRes) {
      noRes.classList.toggle('hidden', visibleCount > 0);
    }
  }

  document.getElementById('programCount').textContent = visibleCount;

  // Smooth scroll to section
  document.getElementById('programsSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
}
</script>

@endsection
