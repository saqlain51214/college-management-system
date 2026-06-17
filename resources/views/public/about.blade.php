@extends('layouts.public')
@section('title', 'About Us — ' . ($college->college_name ?? 'JDCA'))

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
      <span style="color:rgba(255,255,255,.80);">About</span>
    </nav>
    <h1 class="font-display text-3xl sm:text-4xl md:text-5xl font-semibold tracking-tight">
      About <span style="color:var(--c-gold);">{{ $college->short_name ?? 'JDCA' }}</span>
    </h1>
    <p class="mt-3 max-w-2xl text-sm sm:text-base leading-relaxed" style="color:rgba(255,255,255,.80);">
      Empowering the youth of Gilgit-Baltistan through quality, accessible, and accredited higher education since {{ $college->established_year ?? '2010' }}.
    </p>
  </div>
</section>

{{-- ============================================================
     SECTION 2: OUR STORY
     ============================================================ --}}
<section class="py-20 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

      {{-- Left: narrative --}}
      <div>
        <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--c-gold)">Our Story</p>
        <h2 class="font-display text-2xl sm:text-3xl md:text-[2rem] font-semibold leading-snug mb-5" style="color:var(--c-primary)">
          Empowering Minds Since {{ $college->established_year ?? '2010' }}
        </h2>

        <p class="text-gray-600 leading-relaxed mb-4">
          {{ $college->college_name ?? 'Jinnah Degree College Astore' }} was founded with a single, clear purpose: to bring
          recognised degree-level education to the remote but vibrant communities of Astore, Gilgit-Baltistan.
          Before its establishment, students from this region faced the daunting challenge of travelling hundreds
          of kilometres to access higher education — a barrier that left countless talented young people behind.
        </p>
        <p class="text-gray-600 leading-relaxed mb-4">
          Situated in the heart of Astore District in Gilgit-Baltistan, the college occupies a unique position
          both geographically and academically. Surrounded by some of the world's most spectacular mountain
          scenery, our campus provides an environment that inspires curiosity, resilience, and a deep connection
          to both heritage and the wider world.
        </p>
        <p class="text-gray-600 leading-relaxed mb-8">
          As an affiliated institution of <strong>Karakoram International University (KIU)</strong> and recognised
          by the <strong>Higher Education Commission (HEC) of Pakistan</strong>, every degree and certificate
          awarded carries full national accreditation — opening doors across Pakistan and beyond for our graduates.
        </p>

        <div class="flex flex-wrap gap-4">
          <a href="{{ route('programs') }}"
             class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white transition-transform hover:-translate-y-0.5"
             style="background:var(--c-primary)">
            View Programs
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
          </a>
          <a href="{{ route('admissions') }}"
             class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold transition-transform hover:-translate-y-0.5"
             style="border:2px solid var(--c-gold);color:var(--c-gold)">
            Apply for Admission
          </a>
        </div>
      </div>

      {{-- Right: 2x2 stat cards --}}
      <div class="grid grid-cols-2 gap-6">

        @php
          $aboutStats = [
            ['icon'=>'🏛️','value'=>$college->established_year ?? '2010','label'=>'Year Founded','accent'=>'var(--c-primary)'],
            ['icon'=>'🎓','value'=>($college->total_students ?? '500') . '+','label'=>'Students Enrolled','accent'=>'var(--c-gold)'],
            ['icon'=>'👨‍🏫','value'=>($college->total_faculty ?? '25') . '+','label'=>'Faculty Members','accent'=>'var(--c-primary)'],
            ['icon'=>'📚','value'=>(isset($programs) ? $programs->count() : '4') . '+','label'=>'Academic Programs','accent'=>'var(--c-gold)'],
          ];
        @endphp

        @foreach($aboutStats as $stat)
        <div class="bg-white rounded-2xl p-6 shadow-md border-t-4 hover:-translate-y-1 transition-transform"
             style="border-top-color:{{ $stat['accent'] }}">
          <div class="text-3xl mb-3">{{ $stat['icon'] }}</div>
          <div class="text-3xl font-extrabold mb-1" style="color:{{ $stat['accent'] }}">{{ $stat['value'] }}</div>
          <div class="text-sm text-gray-500">{{ $stat['label'] }}</div>
        </div>
        @endforeach

      </div>
    </div>
  </div>
</section>

{{-- ============================================================
     SECTION 3: MISSION, VISION, VALUES
     ============================================================ --}}
<section class="py-20" style="background:var(--c-surface)">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">

    <div class="text-center mb-12">
      <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--c-gold)">Our Purpose</p>
      <h2 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight" style="color:var(--c-primary)">Mission, Vision &amp; Values</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      {{-- Mission --}}
      <div class="bg-white rounded-xl shadow-md p-6 sm:p-8 border-l-4" style="border-left-color:var(--c-primary)">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mb-6"
             style="background:linear-gradient(135deg,#5a1212,#7b1a1a)">🎯</div>
        <h3 class="text-xl font-bold mb-4" style="color:var(--c-primary)">Our Mission</h3>
        <p class="text-gray-600 leading-relaxed">
          To provide accessible, high-quality, and HEC-recognised higher education to the students of Astore
          and the broader Gilgit-Baltistan region — equipping graduates with the academic knowledge, practical
          skills, and ethical grounding needed to contribute meaningfully to society and national development.
        </p>
      </div>

      {{-- Vision --}}
      <div class="bg-white rounded-xl shadow-md p-6 sm:p-8 border-l-4" style="border-left-color:var(--c-gold)">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mb-6"
             style="background:linear-gradient(135deg,#b8862a,#c4973a)">🔭</div>
        <h3 class="text-xl font-bold mb-4" style="color:var(--c-primary)">Our Vision</h3>
        <p class="text-gray-600 leading-relaxed">
          To be the leading institution of higher learning in the mountainous north of Pakistan — a beacon of
          academic excellence, cultural pride, and community uplift that transforms Gilgit-Baltistan through
          the power of education, research, and informed citizenship.
        </p>
      </div>

      {{-- Values --}}
      <div class="bg-white rounded-xl shadow-md p-6 sm:p-8 border-l-4" style="border-left-color:#1e3a5f">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mb-6"
             style="background:linear-gradient(135deg,#1e293b,#1e3a5f)">⭐</div>
        <h3 class="text-xl font-bold mb-4" style="color:var(--c-primary)">Core Values</h3>
        <ul class="space-y-2 text-gray-600">
          @foreach(['Academic Excellence','Integrity & Honesty','Inclusivity & Accessibility','Community Service','Continuous Improvement','Respect for All'] as $coreValue)
          <li class="flex items-center gap-2">
            <span style="color:var(--c-gold);font-size:1.1rem">•</span>
            {{ $coreValue }}
          </li>
          @endforeach
        </ul>
      </div>

    </div>
  </div>
</section>

{{-- ============================================================
     SECTION 4: PRINCIPAL'S MESSAGE
     ============================================================ --}}
<section class="py-20 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">

    <div class="text-center mb-12">
      <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--c-gold)">Leadership</p>
      <h2 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight" style="color:var(--c-primary)">Principal's Message</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

      {{-- Left: avatar card --}}
      <div class="flex flex-col items-center text-center">
        @php
          $principalName = $college->principal_name ?? 'Arif Ali';
          $nameParts = explode(' ', $principalName);
          $initials = strtoupper(substr($nameParts[0] ?? 'A', 0, 1)) . strtoupper(substr($nameParts[1] ?? 'A', 0, 1));
        @endphp

        {{-- Avatar circle --}}
        <div class="relative mb-6">
          <div class="w-40 h-40 rounded-full flex items-center justify-center text-5xl font-extrabold text-white"
               style="background:linear-gradient(135deg,#5a1212,#1e3a5f);box-shadow:0 0 0 6px rgba(196,151,58,.4),0 0 0 12px rgba(196,151,58,.12)">
            {{ $initials }}
          </div>
          {{-- Gold check badge --}}
          <div class="absolute -bottom-2 -right-2 w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold"
               style="background:var(--c-gold)">&#10003;</div>
        </div>

        <h3 class="text-2xl font-bold mb-1" style="color:var(--c-primary)">{{ $principalName }}</h3>
        <p class="text-sm text-gray-500 mb-4">Principal, {{ $college->short_name ?? 'JDCA' }}</p>

        @if(!empty($college->email))
        <a href="mailto:{{ $college->email }}" class="text-sm hover:underline" style="color:var(--c-gold)">
          {{ $college->email }}
        </a>
        @endif
        @if(!empty($college->phone))
        <a href="tel:{{ $college->phone }}" class="block text-sm text-gray-500 mt-1 hover:underline">
          {{ $college->phone }}
        </a>
        @endif
      </div>

      {{-- Right: quote --}}
      <div class="relative">
        {{-- Decorative quote mark --}}
        <div class="absolute -top-8 -left-4 text-9xl font-serif leading-none pointer-events-none select-none"
             style="color:rgba(90,18,18,.08)">"</div>

        <div class="relative space-y-4 text-gray-700 leading-relaxed">
          <p>
            Education is the most powerful gift we can offer to the young people of Gilgit-Baltistan. Here at
            {{ $college->college_name ?? 'Jinnah Degree College Astore' }}, we do not merely impart knowledge —
            we strive to ignite a lifelong love of learning that transcends the boundaries of our valley and
            reaches the wider world.
          </p>
          <p>
            Our students come from villages scattered across Astore District, many overcoming significant
            personal and geographic challenges simply to walk through our gates each morning. Their dedication
            inspires every member of our faculty and administration to raise the standard of teaching,
            resources, and pastoral support we provide.
          </p>
          <p>
            As an institution affiliated with KIU and recognised by HEC, we hold ourselves to the highest
            national standards. I personally invite every eligible student of our region to seize the
            opportunity that this college represents — your future, and the future of Astore, depends on it.
          </p>
        </div>

        {{-- Signature row --}}
        <div class="flex items-center gap-4 mt-8 pt-6 border-t border-gray-100">
          <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm"
               style="background:linear-gradient(135deg,#5a1212,#1e3a5f)">
            {{ $initials }}
          </div>
          <div>
            <p class="font-semibold text-sm" style="color:var(--c-primary)">{{ $principalName }}</p>
            <p class="text-xs text-gray-400">Principal — {{ $college->short_name ?? 'JDCA' }}</p>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

{{-- ============================================================
     SECTION 5: AFFILIATION
     ============================================================ --}}
<section class="py-14" style="background:var(--c-surface)">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">

    <div class="text-center mb-10">
      <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--c-gold)">Recognition</p>
      <h2 class="text-3xl font-extrabold" style="color:var(--c-primary)">Our Affiliations</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

      {{-- KIU --}}
      <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4" style="border-top-color:var(--c-primary)">
        <div class="flex items-center gap-4 mb-4">
          <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-extrabold text-lg"
               style="background:var(--c-primary)">KIU</div>
          <div>
            <h3 class="font-bold text-lg" style="color:var(--c-primary)">Karakoram International University</h3>
            <p class="text-sm text-gray-500">Gilgit, Gilgit-Baltistan</p>
          </div>
        </div>
        <p class="text-gray-600 leading-relaxed">
          JDCA is formally affiliated with Karakoram International University (KIU), the premier public university
          of Gilgit-Baltistan. All academic programmes, examinations, and degree certificates are conducted and
          issued under KIU's framework, ensuring nationally recognised credentials for every graduate.
        </p>
      </div>

      {{-- HEC --}}
      <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4" style="border-top-color:var(--c-gold)">
        <div class="flex items-center gap-4 mb-4">
          <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-extrabold text-lg"
               style="background:var(--c-gold)">HEC</div>
          <div>
            <h3 class="font-bold text-lg" style="color:var(--c-primary)">Higher Education Commission</h3>
            <p class="text-sm text-gray-500">Government of Pakistan</p>
          </div>
        </div>
        <p class="text-gray-600 leading-relaxed">
          The Higher Education Commission of Pakistan provides national oversight and quality assurance for all
          degree-awarding institutions across the country. JDCA's recognition by HEC means our programmes meet
          federal standards, and our graduates' qualifications are accepted by employers and universities nationwide.
        </p>
      </div>

    </div>

    {{-- Summary strip --}}
    <div class="rounded-2xl py-6 px-8 text-center text-white"
         style="background:linear-gradient(135deg,#5a1212,#1e293b)">
      <p class="text-base font-medium">
        Affiliated with <strong>KIU</strong> &bull; Recognised by <strong>HEC Pakistan</strong> &bull;
        Degrees accepted nationwide and beyond
      </p>
    </div>

  </div>
</section>

{{-- ============================================================
     SECTION 6: FACULTY HIGHLIGHT
     ============================================================ --}}
<section class="py-20 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">

    <div class="text-center mb-12">
      <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--c-gold)">Our People</p>
      <h2 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight" style="color:var(--c-primary)">Dedicated Faculty &amp; Staff</h2>
      <p class="mt-3 text-gray-500 max-w-2xl mx-auto">
        Our team of qualified educators, counsellors, and administrators is committed to every student's success.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">

      @php
        $facultyHighlights = [
          [
            'icon'       => '👨‍🏫',
            'gradient'   => 'linear-gradient(135deg,#5a1212,#7b1a1a)',
            'title'      => 'Qualified Educators',
            'text'       => 'Our faculty members hold advanced degrees from recognised Pakistani and international universities. Many bring years of field experience alongside their academic qualifications.',
            'statColor'  => 'var(--c-primary)',
            'stat'       => ($college->total_faculty ?? '25') . '+ Faculty Members',
          ],
          [
            'icon'       => '🤝',
            'gradient'   => 'linear-gradient(135deg,#b8862a,#c4973a)',
            'title'      => 'Student-Centred Support',
            'text'       => 'Beyond the classroom, our counselling and student-affairs team ensures that every student — regardless of background or learning need — receives the guidance required to thrive.',
            'statColor'  => 'var(--c-gold)',
            'stat'       => 'Personalised Counselling Available',
          ],
          [
            'icon'       => '🏢',
            'gradient'   => 'linear-gradient(135deg,#1e293b,#1e3a5f)',
            'title'      => 'Professional Administration',
            'text'       => 'Our administrative staff manages registrations, examinations, finances, and campus operations with professionalism and transparency, creating a smooth experience for students and families.',
            'statColor'  => '#1e3a5f',
            'stat'       => 'Transparent & Efficient Operations',
          ],
        ];
      @endphp

      @foreach($facultyHighlights as $fCard)
      <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-100 hover:-translate-y-1 transition-transform">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mb-6"
             style="background:{{ $fCard['gradient'] }}">{{ $fCard['icon'] }}</div>
        <h3 class="text-xl font-bold mb-3" style="color:var(--c-primary)">{{ $fCard['title'] }}</h3>
        <p class="text-gray-600 leading-relaxed mb-4">{{ $fCard['text'] }}</p>
        <p class="text-sm font-semibold flex items-center gap-2" style="color:{{ $fCard['statColor'] }}">
          <span>•</span> {{ $fCard['stat'] }}
        </p>
      </div>
      @endforeach

    </div>

    <div class="text-center">
      <a href="{{ route('faculty') }}"
         class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold text-white transition-transform hover:-translate-y-0.5"
         style="background:var(--c-primary)">
        Meet Our Faculty
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
      </a>
    </div>

  </div>
</section>

{{-- ============================================================
     CLOSING CTA
     ============================================================ --}}
<section class="py-20" style="background:var(--c-surface)">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="rounded-3xl py-16 px-8 text-center text-white"
         style="background:linear-gradient(135deg,#5a1212 0%,#1e293b 100%)">
      <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Ready to Begin Your Journey?</h2>
      <p class="text-lg mb-8" style="color:rgba(255,255,255,.75)">
        Join hundreds of students building their future at {{ $college->short_name ?? 'JDCA' }}.
        Applications for the next academic year are now open.
      </p>
      <div class="flex flex-wrap justify-center gap-4">
        <a href="{{ route('admissions') }}"
           class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold transition-transform hover:-translate-y-0.5"
           style="background:var(--c-gold);color:#1a0a00">
          Apply for Admission
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
        <a href="{{ route('contact') }}"
           class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold text-white transition-transform hover:-translate-y-0.5"
           style="border:2px solid rgba(255,255,255,.5)">
          Contact Us
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
