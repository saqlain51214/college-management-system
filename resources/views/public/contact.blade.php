@extends('layouts.public')
@section('title', 'Contact Us — ' . ($college->college_name ?? 'JDCA'))

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
      <span style="color:rgba(255,255,255,.80);">Contact</span>
    </nav>
    <h1 class="font-display text-3xl sm:text-4xl md:text-5xl font-semibold tracking-tight">
      Contact <span style="color:var(--c-gold);">Us</span>
    </h1>
    <p class="mt-3 max-w-xl text-sm sm:text-base leading-relaxed" style="color:rgba(255,255,255,.80);">
      Reach out to the {{ $college->short_name ?? 'JDCA' }} team — we're happy to answer your questions about admissions, programmes, or anything else.
    </p>
  </div>
</section>

{{-- ============================================================
     SECTION 2: CONTACT INFO + FORM
     ============================================================ --}}
<section class="py-20 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">

      {{-- Left: info cards (2/5) --}}
      <div class="lg:col-span-2 space-y-4">

        <h2 class="font-display text-xl sm:text-2xl font-semibold mb-5" style="color:var(--c-primary)">Get In Touch</h2>

        @php
          $infoCards = [
            [
              'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>',
              'label' => 'Address',
              'value' => $college->address ?? 'Astore, Gilgit-Baltistan, Pakistan',
              'href'  => null,
            ],
            [
              'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>',
              'label' => 'Phone',
              'value' => $college->phone ?? null,
              'href'  => 'tel:' . ($college->phone ?? ''),
            ],
            [
              'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
              'label' => 'Email',
              'value' => $college->email ?? null,
              'href'  => 'mailto:' . ($college->email ?? ''),
            ],
            [
              'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
              'label' => 'Principal',
              'value' => $college->principal_name ?? 'Arif Ali',
              'href'  => null,
            ],
            [
              'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
              'label' => 'Office Hours',
              'value' => 'Monday – Friday: 8:00 AM – 3:00 PM',
              'href'  => null,
            ],
          ];
        @endphp

        @foreach($infoCards as $card)
        @if(!empty($card['value']))
        <div class="flex items-start gap-4 p-5 rounded-2xl bg-gray-50 border border-gray-100 hover:-translate-y-0.5 transition-transform">
          <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
               style="background:linear-gradient(135deg,#5a1212,#7b1a1a)">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              {!! $card['icon'] !!}
            </svg>
          </div>
          <div>
            <p class="text-xs font-bold uppercase tracking-wide mb-0.5" style="color:var(--c-gold)">{{ $card['label'] }}</p>
            @if($card['href'])
            <a href="{{ $card['href'] }}" class="text-sm font-medium hover:underline" style="color:var(--c-primary)">{{ $card['value'] }}</a>
            @else
            <p class="text-sm text-gray-700">{{ $card['value'] }}</p>
            @endif
          </div>
        </div>
        @endif
        @endforeach

      </div>

      {{-- Right: contact form (3/5) --}}
      <div class="lg:col-span-3">

        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10 border border-gray-100">

          <h2 class="font-display text-xl sm:text-2xl font-semibold mb-5" style="color:var(--c-primary)">Send Us a Message</h2>

          {{-- Success alert --}}
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
            <ul class="text-sm list-disc list-inside space-y-0.5">
              @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <form action="{{ route('contact.send') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

              {{-- Name --}}
              <div>
                <label class="block text-sm font-semibold mb-1.5" style="color:var(--c-primary)">Your Name <span class="text-red-500">*</span></label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  </span>
                  <input type="text" name="name" value="{{ old('name') }}" required
                         placeholder="Full name"
                         class="w-full pl-9 pr-4 py-3 rounded-xl border text-sm focus:outline-none focus:ring-2 transition-all {{ $errors->has('name') ? 'border-red-400 ring-red-200' : 'border-gray-200 focus:ring-red-100' }}">
                </div>
                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
              </div>

              {{-- Email --}}
              <div>
                <label class="block text-sm font-semibold mb-1.5" style="color:var(--c-primary)">Email Address <span class="text-red-500">*</span></label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  </span>
                  <input type="email" name="email" value="{{ old('email') }}" required
                         placeholder="your@email.com"
                         class="w-full pl-9 pr-4 py-3 rounded-xl border text-sm focus:outline-none focus:ring-2 transition-all {{ $errors->has('email') ? 'border-red-400 ring-red-200' : 'border-gray-200 focus:ring-red-100' }}">
                </div>
                @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
              </div>

            </div>

            {{-- Subject --}}
            <div>
              <label class="block text-sm font-semibold mb-1.5" style="color:var(--c-primary)">Subject <span class="text-red-500">*</span></label>
              <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                </span>
                <input type="text" name="subject" value="{{ old('subject') }}" required
                       placeholder="What is your message about?"
                       class="w-full pl-9 pr-4 py-3 rounded-xl border text-sm focus:outline-none focus:ring-2 transition-all {{ $errors->has('subject') ? 'border-red-400 ring-red-200' : 'border-gray-200 focus:ring-red-100' }}">
              </div>
              @error('subject')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Message --}}
            <div>
              <label class="block text-sm font-semibold mb-1.5" style="color:var(--c-primary)">Message <span class="text-red-500">*</span></label>
              <div class="relative">
                <span class="absolute left-3 top-4 pointer-events-none">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </span>
                <textarea name="message" rows="5" required
                          placeholder="Write your message here..."
                          class="w-full pl-9 pr-4 py-3 rounded-xl border text-sm focus:outline-none focus:ring-2 transition-all resize-none {{ $errors->has('message') ? 'border-red-400 ring-red-200' : 'border-gray-200 focus:ring-red-100' }}">{{ old('message') }}</textarea>
              </div>
              @error('message')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                    class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold text-white transition-transform hover:-translate-y-0.5"
                    style="background:var(--c-primary)">
              Send Message
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </button>

          </form>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ============================================================
     SECTION 3: MAP PLACEHOLDER
     ============================================================ --}}
<section class="py-0">
  <div class="relative overflow-hidden h-72 md:h-96" style="background:linear-gradient(135deg,#1e293b,#5a1212)">

    {{-- Dot-grid --}}
    <div class="absolute inset-0 pointer-events-none" style="opacity:0.08;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:32px 32px"></div>

    {{-- Concentric rings --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full pointer-events-none" style="border:1px solid rgba(255,255,255,.06)"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 rounded-full pointer-events-none" style="border:1px solid rgba(255,255,255,.08)"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 rounded-full pointer-events-none" style="border:1px solid rgba(255,255,255,.12)"></div>

    {{-- Centre content --}}
    <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
      <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4" style="background:var(--c-gold)">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      </div>
      <p class="text-white font-bold text-lg mb-1">{{ $college->college_name ?? 'Jinnah Degree College Astore' }}</p>
      <p class="text-sm mb-1" style="color:rgba(255,255,255,.6)">{{ $college->address ?? 'Astore, Gilgit-Baltistan' }}</p>
      <p class="text-xs mb-6" style="color:rgba(255,255,255,.4)">35.3753° N, 74.8589° E</p>
      <a href="https://www.google.com/maps/search/Astore+Gilgit-Baltistan" target="_blank" rel="noopener"
         class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm transition-transform hover:-translate-y-0.5"
         style="background:var(--c-gold);color:#1a0a00">
        Open in Google Maps
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
      </a>
    </div>

    {{-- Bottom info strip --}}
    <div class="absolute bottom-0 left-0 right-0 py-3 px-6 flex flex-wrap justify-center gap-6 text-xs"
         style="background:rgba(0,0,0,.3);color:rgba(255,255,255,.6)">
      <span>&#128336; Mon – Fri: 8:00 AM – 3:00 PM</span>
      @if(!empty($college->phone))<span>&#128222; {{ $college->phone }}</span>@endif
      @if(!empty($college->email))<span>&#9993; {{ $college->email }}</span>@endif
    </div>

  </div>
</section>

{{-- ============================================================
     SECTION 4: FAQ ACCORDION
     ============================================================ --}}
<section class="py-20 bg-white">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-12">
      <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--c-gold)">FAQ</p>
      <h2 class="text-3xl font-extrabold" style="color:var(--c-primary)">Frequently Asked Questions</h2>
    </div>

    @php
      $faqs = [
        [
          'q' => 'What programmes does JDCA currently offer?',
          'a' => 'JDCA offers Intermediate (FSc/FA), Bachelor\'s degree programmes (BS), and postgraduate programmes, all affiliated with Karakoram International University (KIU). Visit our Academic Programs page for a full and up-to-date list.',
        ],
        [
          'q' => 'How do I apply for admission?',
          'a' => 'You can submit an online inquiry through our Admissions page, or visit the college admissions office directly in Astore during working hours (Monday to Friday, 8:00 AM – 3:00 PM). Bring copies of your academic documents, CNIC/B-Form, domicile certificate, and passport photographs.',
        ],
        [
          'q' => 'Is the degree from JDCA recognised by HEC?',
          'a' => 'Yes. JDCA is affiliated with Karakoram International University (KIU) and is recognised by the Higher Education Commission (HEC) of Pakistan. Degrees awarded through JDCA carry full national validity and are accepted by employers and institutions across Pakistan.',
        ],
        [
          'q' => 'Who should I contact for more information?',
          'a' => 'For general enquiries, use the contact form on this page or call / email us directly. For admissions-specific questions, contact the Admissions Office. Our Principal, ' . ($college->principal_name ?? 'Arif Ali') . ', is also available for important matters by appointment.',
        ],
      ];
    @endphp

    <div x-data="{ open: null }" class="space-y-3">

      @foreach($faqs as $idx => $faq)
      <div class="rounded-2xl overflow-hidden border border-gray-100 shadow-sm">

        {{-- Question row --}}
        <button @click="open = (open === {{ $idx }}) ? null : {{ $idx }}"
                class="w-full flex items-center justify-between px-6 py-5 text-left transition-colors"
                :style="open === {{ $idx }} ? 'background:var(--c-primary);color:#fff' : 'background:#fff;color:inherit'">
          <span class="font-semibold text-sm md:text-base pr-4">{{ $faq['q'] }}</span>
          <svg class="w-5 h-5 flex-shrink-0 transition-transform"
               :class="open === {{ $idx }} ? 'rotate-180' : ''"
               fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>

        {{-- Answer panel --}}
        <div x-show="open === {{ $idx }}"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             class="px-6 py-5 bg-white border-l-4 text-gray-600 text-sm leading-relaxed"
             style="border-left-color:var(--c-gold)">
          {{ $faq['a'] }}
        </div>

      </div>
      @endforeach

    </div>

    {{-- CTA card --}}
    <div class="mt-10 rounded-2xl p-6 text-center" style="background:rgba(90,18,18,.06);border:1px solid rgba(90,18,18,.12)">
      <p class="font-semibold mb-2" style="color:var(--c-primary)">Still have questions?</p>
      <p class="text-sm text-gray-500 mb-4">We're happy to help. Send us a message and we'll get back to you.</p>
      <a href="#contact-form"
         onclick="document.querySelector('form[action*=contact]').scrollIntoView({behavior:'smooth'});return false;"
         class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-white transition-transform hover:-translate-y-0.5"
         style="background:var(--c-primary)">
        Send a Message
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
      </a>
    </div>

  </div>
</section>

@endsection
