@extends('layouts.public')
@section('title', 'Admissions 2025 — ' . ($college->college_name ?? 'JDCA'))

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
      <span style="color:rgba(255,255,255,.80);">Admissions</span>
    </nav>
    <div class="mb-4 inline-flex items-center gap-2 rounded-md border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold backdrop-blur-sm">
      <span class="h-1.5 w-1.5 rounded-full bg-green-400 animate-pulse"></span>
      Applications Open — Academic Year {{ date('Y') }}
    </div>
    <h1 class="font-display text-3xl sm:text-4xl md:text-5xl font-semibold tracking-tight">
      Admissions <span style="color:var(--c-gold);">{{ date('Y') }}</span>
    </h1>
    <p class="mt-3 max-w-2xl text-sm sm:text-base leading-relaxed" style="color:rgba(255,255,255,.80);">
      Begin your academic journey at {{ $college->college_name ?? 'Jinnah Degree College Astore' }}. KIU-affiliated, HEC-recognised degrees in the heart of Gilgit-Baltistan.
    </p>
    <div class="mt-6 flex flex-wrap gap-3">
      <a href="#inquiry" class="inline-flex items-center gap-2 rounded-md px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-90" style="background:var(--c-primary);">
        Apply Now
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
      </a>
      <a href="#how-to-apply" class="inline-flex items-center gap-2 rounded-md border-2 border-white/40 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
        How to Apply
      </a>
    </div>
  </div>
</section>

{{-- ============================================================
     SECTION 2: WHY JDCA
     ============================================================ --}}
<section class="py-20 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">

    <div class="text-center mb-12">
      <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--c-gold)">Why Choose Us</p>
      <h2 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight" style="color:var(--c-primary)">
        Why Study at {{ $college->short_name ?? 'JDCA' }}?
      </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      {{-- Quality Education --}}
      <div class="rounded-2xl p-8 border-l-4" style="background:#fafafa;border-left-color:var(--c-primary)">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6"
             style="background:linear-gradient(135deg,#5a1212,#7b1a1a)">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <h3 class="text-xl font-bold mb-3" style="color:var(--c-primary)">Quality Education</h3>
        <p class="text-gray-600 leading-relaxed mb-4">
          Experienced and qualified faculty, a structured curriculum aligned with KIU standards, and a
          supportive learning environment ensure every student receives an education they can be proud of.
        </p>
        <div class="flex flex-wrap gap-2">
          <span class="text-xs px-3 py-1 rounded-full" style="background:rgba(90,18,18,.1);color:var(--c-primary)">KIU Curriculum</span>
          <span class="text-xs px-3 py-1 rounded-full" style="background:rgba(90,18,18,.1);color:var(--c-primary)">Qualified Faculty</span>
        </div>
      </div>

      {{-- Beautiful Location --}}
      <div class="rounded-2xl p-8 border-l-4" style="background:#fafafa;border-left-color:var(--c-gold)">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6"
             style="background:linear-gradient(135deg,#b8862a,#c4973a)">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-xl font-bold mb-3" style="color:var(--c-primary)">Beautiful Location</h3>
        <p class="text-gray-600 leading-relaxed mb-4">
          Nestled in the breathtaking Astore Valley of Gilgit-Baltistan, our campus offers an environment
          of natural beauty, clean mountain air, and a close-knit community atmosphere that promotes focus
          and wellbeing.
        </p>
        <div class="flex flex-wrap gap-2">
          <span class="text-xs px-3 py-1 rounded-full" style="background:rgba(184,134,42,.12);color:#7c5e1e">Astore Valley</span>
          <span class="text-xs px-3 py-1 rounded-full" style="background:rgba(184,134,42,.12);color:#7c5e1e">Gilgit-Baltistan</span>
        </div>
      </div>

      {{-- KIU Affiliation --}}
      <div class="rounded-2xl p-8 border-l-4" style="background:#fafafa;border-left-color:#1e3a5f">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6"
             style="background:linear-gradient(135deg,#1e293b,#1e3a5f)">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
        </div>
        <h3 class="text-xl font-bold mb-3" style="color:var(--c-primary)">KIU Affiliation &amp; HEC Recognition</h3>
        <p class="text-gray-600 leading-relaxed mb-4">
          Every degree earned at JDCA is affiliated with Karakoram International University and recognised
          by the Higher Education Commission of Pakistan — guaranteeing full national validity and acceptance
          by employers and institutions across the country.
        </p>
        <div class="flex flex-wrap gap-2">
          <span class="text-xs px-3 py-1 rounded-full text-white" style="background:#1e3a5f">KIU Affiliated</span>
          <span class="text-xs px-3 py-1 rounded-full text-white" style="background:#1e3a5f">HEC Recognised</span>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ============================================================
     SECTION 3: HOW TO APPLY
     ============================================================ --}}
<section class="py-20" id="how-to-apply" style="background:var(--c-surface)">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-12">
      <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--c-gold)">Process</p>
      <h2 class="font-display text-2xl sm:text-3xl font-semibold tracking-tight" style="color:var(--c-primary)">How to Apply</h2>
      <p class="mt-3 text-gray-500">Follow these simple steps to secure your place at JDCA.</p>
    </div>

    <div class="space-y-6">

      @php
        $steps = [
          [
            'num'    => '1',
            'title'  => 'Check Eligibility',
            'text'   => 'Review the academic requirements for your desired programme. Ensure you hold the minimum qualification (Matric for Intermediate; FSc/FA for BS programmes) with at least a Second Division.',
            'docs'   => [],
            'gold'   => false,
          ],
          [
            'num'    => '2',
            'title'  => 'Collect Required Documents',
            'text'   => 'Gather all necessary documents before visiting the admissions office. Having complete paperwork ready will speed up your application considerably.',
            'docs'   => ['Mark Sheets (all previous)', 'Domicile Certificate', 'CNIC / B-Form (copy)', '2 Passport Photos', 'Character Certificate'],
            'gold'   => false,
          ],
          [
            'num'    => '3',
            'title'  => 'Visit Admissions Office',
            'text'   => 'Come to the JDCA Admissions Office in Astore during office hours (Monday to Friday, 8:00 AM – 3:00 PM). Our staff will guide you through form submission and fee payment.',
            'docs'   => [],
            'gold'   => false,
          ],
          [
            'num'    => '4',
            'title'  => 'Entry Test / Interview',
            'text'   => 'Depending on your programme, you may be required to sit an entry test or attend a brief interview with the academic panel. Dates are announced at the time of form submission.',
            'docs'   => [],
            'gold'   => false,
          ],
          [
            'num'    => '5',
            'title'  => 'Confirmation & Enrolment',
            'text'   => 'Once your application is approved and fee deposited, you will receive a confirmation slip. Attend the orientation session to complete your enrolment and receive your timetable.',
            'docs'   => [],
            'gold'   => true,
          ],
        ];
      @endphp

      @foreach($steps as $i => $step)
      <div class="relative flex gap-6">

        {{-- Connector line --}}
        @if(!$loop->last)
        <div class="hidden md:block absolute left-6 top-14 bottom-0 w-0.5" style="background:rgba(90,18,18,.15);transform:translateX(-50%)"></div>
        @endif

        {{-- Number circle --}}
        <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center font-extrabold text-white z-10"
             style="background:{{ $step['gold'] ? 'var(--c-gold)' : 'var(--c-primary)' }};min-width:3rem">
          {{ $step['num'] }}
        </div>

        {{-- Content card --}}
        <div class="flex-1 bg-white rounded-2xl p-6 shadow-md mb-2 {{ $step['gold'] ? 'border-l-4' : '' }}"
             style="{{ $step['gold'] ? 'border-left-color:var(--c-gold)' : '' }}">
          <h3 class="font-bold text-lg mb-2" style="color:var(--c-primary)">{{ $step['title'] }}</h3>
          <p class="text-gray-600 leading-relaxed">{{ $step['text'] }}</p>

          @if(!empty($step['docs']))
          <div class="flex flex-wrap gap-2 mt-4">
            @foreach($step['docs'] as $doc)
            <span class="text-xs px-3 py-1 rounded-full font-medium"
                  style="background:rgba(90,18,18,.08);color:var(--c-primary)">{{ $doc }}</span>
            @endforeach
          </div>
          @endif

          @if($step['gold'])
          <div class="flex items-center gap-2 mt-4 text-sm font-semibold text-green-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            You are enrolled!
          </div>
          @endif
        </div>

      </div>
      @endforeach

    </div>
  </div>
</section>

{{-- ============================================================
     SECTION 4: REQUIREMENTS TABLE
     ============================================================ --}}
<section class="py-16 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">

    <div class="text-center mb-10">
      <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--c-gold)">Programmes</p>
      <h2 class="text-3xl font-extrabold" style="color:var(--c-primary)">Available Programmes &amp; Requirements</h2>
    </div>

    <div class="overflow-x-auto rounded-2xl shadow-md">
      <table class="w-full text-sm">
        <thead>
          <tr style="background:var(--c-primary);color:#fff">
            <th class="px-6 py-4 text-left font-semibold">Programme</th>
            <th class="px-6 py-4 text-left font-semibold">Min. Qualification</th>
            <th class="px-6 py-4 text-left font-semibold">Duration</th>
            <th class="px-6 py-4 text-left font-semibold">Seats</th>
            <th class="px-6 py-4 text-left font-semibold">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse(isset($programs) ? $programs : [] as $prog)
          <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} border-b border-gray-100 hover:bg-orange-50 transition-colors">
            <td class="px-6 py-4">
              <span class="font-semibold" style="color:var(--c-primary)">{{ $prog->name }}</span>
              @if(!empty($prog->short_name))
              <span class="ml-2 text-xs text-gray-400">({{ $prog->short_name }})</span>
              @endif
            </td>
            <td class="px-6 py-4 text-gray-600">
              @php
                $level = strtolower($prog->level ?? $prog->degree_type?->value ?? '');
                if (str_contains($level, 'intermediate')) $minQ = 'Matric (SSC)';
                elseif (str_contains($level, 'ms') || str_contains($level, 'mphil') || str_contains($level, 'm-ed')) $minQ = 'Bachelor\'s Degree';
                elseif (str_contains($level, 'bs') || str_contains($level, 'ba') || str_contains($level, 'b-ed')) $minQ = 'FSc / FA (Intermediate)';
                else $minQ = 'As per programme';
              @endphp
              {{ $minQ }}
            </td>
            <td class="px-6 py-4 text-gray-600">
              {{ $prog->duration_years ?? '2' }} Year{{ ($prog->duration_years ?? 2) != 1 ? 's' : '' }}
              @if(!empty($prog->total_semesters))
              / {{ $prog->total_semesters }} Semesters
              @endif
            </td>
            <td class="px-6 py-4">
              <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-full text-green-700 bg-green-50">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                Limited
              </span>
            </td>
            <td class="px-6 py-4">
              <a href="#inquiry" class="text-xs font-semibold px-3 py-1.5 rounded-lg text-white"
                 style="background:var(--c-primary)">Inquire</a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
              Programme information will be listed here once added.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <p class="mt-4 text-xs text-gray-400">
      * Seats are limited and filled on a first-come, first-served basis. Contact the admissions office for the latest seat availability.
    </p>

  </div>
</section>

{{-- ============================================================
     SECTION 5: INQUIRY FORM
     ============================================================ --}}
<section class="py-20" id="inquiry" style="background:var(--c-surface)">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-10">
      <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--c-gold)">Get in Touch</p>
      <h2 class="text-3xl font-extrabold" style="color:var(--c-primary)">Submit an Admission Inquiry</h2>
      <p class="mt-3 text-gray-500">Fill in the form below and our admissions team will contact you shortly.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">

      {{-- Success banner --}}
      @if(session('success'))
      <div class="flex items-start gap-3 rounded-xl p-4 mb-6 text-green-800 bg-green-50 border border-green-200">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="font-medium">{{ session('success') }}</p>
      </div>
      @endif

      {{-- Validation errors --}}
      @if($errors->any())
      <div class="flex items-start gap-3 rounded-xl p-4 mb-6 text-red-800 bg-red-50 border border-red-200">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <div>
          <p class="font-semibold mb-1">Please fix the following errors:</p>
          <ul class="list-disc list-inside space-y-0.5 text-sm">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
      @endif

      <form action="{{ route('admissions.inquiry') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

          {{-- Full Name --}}
          <div>
            <label class="block text-sm font-semibold mb-1.5" style="color:var(--c-primary)">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   placeholder="Your full name"
                   class="w-full px-4 py-3 rounded-xl border text-sm focus:outline-none focus:ring-2 transition-all {{ $errors->has('name') ? 'border-red-400 ring-red-200' : 'border-gray-200 focus:ring-red-100' }}"
                   style="focus:border-color:var(--c-primary)">
            @error('name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Phone --}}
          <div>
            <label class="block text-sm font-semibold mb-1.5" style="color:var(--c-primary)">Phone Number <span class="text-red-500">*</span></label>
            <input type="tel" name="phone" value="{{ old('phone') }}" required
                   placeholder="03xx-xxxxxxx"
                   class="w-full px-4 py-3 rounded-xl border text-sm focus:outline-none focus:ring-2 transition-all {{ $errors->has('phone') ? 'border-red-400 ring-red-200' : 'border-gray-200 focus:ring-red-100' }}">
            @error('phone')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

        </div>

        {{-- Email --}}
        <div>
          <label class="block text-sm font-semibold mb-1.5" style="color:var(--c-primary)">Email Address <span class="text-gray-400 text-xs font-normal">(optional)</span></label>
          <input type="email" name="email" value="{{ old('email') }}"
                 placeholder="your@email.com"
                 class="w-full px-4 py-3 rounded-xl border text-sm focus:outline-none focus:ring-2 transition-all {{ $errors->has('email') ? 'border-red-400 ring-red-200' : 'border-gray-200 focus:ring-red-100' }}">
          @error('email')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

          {{-- Program --}}
          <div>
            <label class="block text-sm font-semibold mb-1.5" style="color:var(--c-primary)">Program of Interest <span class="text-red-500">*</span></label>
            <select name="program_id" required
                    class="w-full px-4 py-3 rounded-xl border text-sm focus:outline-none focus:ring-2 transition-all {{ $errors->has('program_id') ? 'border-red-400 ring-red-200' : 'border-gray-200 focus:ring-red-100' }}">
              <option value="">-- Select Program --</option>
              @foreach(isset($programs) ? $programs : [] as $prog)
              <option value="{{ $prog->id }}" {{ old('program_id') == $prog->id ? 'selected' : '' }}>
                {{ $prog->name }}
              </option>
              @endforeach
            </select>
            @error('program_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Last Qualification --}}
          <div>
            <label class="block text-sm font-semibold mb-1.5" style="color:var(--c-primary)">Last Qualification <span class="text-red-500">*</span></label>
            <select name="qualification" required
                    class="w-full px-4 py-3 rounded-xl border text-sm focus:outline-none focus:ring-2 transition-all {{ $errors->has('qualification') ? 'border-red-400 ring-red-200' : 'border-gray-200 focus:ring-red-100' }}">
              <option value="">-- Select --</option>
              <option value="matric" {{ old('qualification') == 'matric' ? 'selected' : '' }}>Matric (SSC)</option>
              <option value="intermediate" {{ old('qualification') == 'intermediate' ? 'selected' : '' }}>Intermediate (FSc / FA)</option>
              <option value="bachelor" {{ old('qualification') == 'bachelor' ? 'selected' : '' }}>Bachelor's Degree</option>
              <option value="master" {{ old('qualification') == 'master' ? 'selected' : '' }}>Master's Degree</option>
              <option value="other" {{ old('qualification') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('qualification')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

        </div>

        {{-- Message --}}
        <div>
          <label class="block text-sm font-semibold mb-1.5" style="color:var(--c-primary)">Message <span class="text-gray-400 text-xs font-normal">(optional)</span></label>
          <textarea name="message" rows="4"
                    placeholder="Any questions or additional information..."
                    class="w-full px-4 py-3 rounded-xl border text-sm focus:outline-none focus:ring-2 transition-all resize-none {{ $errors->has('message') ? 'border-red-400 ring-red-200' : 'border-gray-200 focus:ring-red-100' }}">{{ old('message') }}</textarea>
          @error('message')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <button type="submit"
                class="w-full py-4 rounded-xl font-bold text-white text-base transition-transform hover:-translate-y-0.5"
                style="background:var(--c-primary)">
          Submit Inquiry
        </button>

      </form>
    </div>
  </div>
</section>

{{-- ============================================================
     SECTION 6: CONTACT STRIP
     ============================================================ --}}
<section class="py-14" style="background:var(--c-primary)">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      {{-- Phone --}}
      <div class="flex items-start gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background:rgba(255,255,255,.1);backdrop-filter:blur(6px)">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
        </div>
        <div>
          <p class="font-semibold text-white mb-1">Phone</p>
          @if(!empty($college->phone))
          <a href="tel:{{ $college->phone }}" class="text-sm hover:underline" style="color:rgba(255,255,255,.8)">{{ $college->phone }}</a>
          @else
          <p class="text-sm" style="color:rgba(255,255,255,.6)">Contact number coming soon</p>
          @endif
          <p class="text-xs mt-0.5" style="color:rgba(255,255,255,.5)">Mon – Fri, 8:00 AM – 3:00 PM</p>
        </div>
      </div>

      {{-- Email --}}
      <div class="flex items-start gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background:rgba(255,255,255,.1);backdrop-filter:blur(6px)">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <div>
          <p class="font-semibold text-white mb-1">Email</p>
          @if(!empty($college->email))
          <a href="mailto:{{ $college->email }}" class="text-sm hover:underline" style="color:rgba(255,255,255,.8)">{{ $college->email }}</a>
          @else
          <p class="text-sm" style="color:rgba(255,255,255,.6)">Email coming soon</p>
          @endif
          <p class="text-xs mt-0.5" style="color:rgba(255,255,255,.5)">We reply within 24 hours</p>
        </div>
      </div>

      {{-- Office Hours --}}
      <div class="flex items-start gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background:rgba(255,255,255,.1);backdrop-filter:blur(6px)">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
          <p class="font-semibold text-white mb-1">Office Hours</p>
          <p class="text-sm" style="color:rgba(255,255,255,.8)">Mon – Fri: 8:00 AM – 3:00 PM</p>
          <p class="text-xs mt-0.5" style="color:rgba(255,255,255,.5)">{{ $college->address ?? 'Astore, Gilgit-Baltistan' }}</p>
        </div>
      </div>

    </div>
  </div>
</section>

@endsection
