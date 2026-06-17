<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Jinnah School & Degree College Astore') — JDCA</title>
    <meta name="description" content="@yield('meta_description', 'Jinnah School & Degree College Astore (JDCA) — Quality education in Gilgit-Baltistan, Pakistan.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,500;0,600;0,700&family=Playfair+Display:wght@0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --c-primary:    #6B2D39;
            --c-primary-dk: #5A2430;
            --c-gold:       #C4973A;
            --c-ink:        #1A1A1A;
            --c-surface:    #F5F5F5;
        }

        *, *::before, *::after { box-sizing: border-box; }
        [x-cloak] { display: none !important; }

        html { scroll-padding-top: 5rem; }

        body {
            margin: 0;
            font-family: 'Open Sans', sans-serif;
            color: var(--c-ink);
            background-color: var(--c-surface);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        body ::selection { background: rgba(107,45,57,.20); }

        .font-display { font-family: 'Playfair Display', Georgia, serif; }

        /* ── Scroll reveal ── */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity .7s cubic-bezier(.22,1,.36,1), transform .7s cubic-bezier(.22,1,.36,1);
        }
        .reveal.from-left  { transform: translateX(-40px); }
        .reveal.from-right { transform: translateX(40px); }
        .reveal.is-visible { opacity: 1 !important; transform: none !important; }
        .reveal.d1 { transition-delay: .08s; }
        .reveal.d2 { transition-delay: .16s; }
        .reveal.d3 { transition-delay: .24s; }
        .reveal.d4 { transition-delay: .32s; }
        .reveal.d5 { transition-delay: .40s; }
        .reveal.d6 { transition-delay: .48s; }

        /* ── Hero entry animations ── */
        @keyframes heroUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .h-anim-1 { animation: heroUp .65s ease both; }
        .h-anim-2 { animation: heroUp .65s .10s ease both; }
        .h-anim-3 { animation: heroUp .65s .20s ease both; }
        .h-anim-4 { animation: heroUp .65s .32s ease both; }
        .h-anim-5 { animation: heroUp .65s .44s ease both; }

        /* ── Card hover lift ── */
        .lift-card {
            transition: transform .28s cubic-bezier(.22,1,.36,1), box-shadow .28s ease;
        }
        .lift-card:hover { transform: translateY(-4px); box-shadow: 0 18px 36px rgba(0,0,0,.10); }

        /* ── Section gradient divider bar ── */
        .section-bar {
            display: block;
            height: 4px; width: 56px;
            border-radius: 9999px;
            background: linear-gradient(90deg, var(--c-primary), var(--c-primary-dk));
        }

        /* ── Flash ── */
        @keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
        .flash-msg { animation: slideDown .3s ease-out; }

        /* ── Footer links ── */
        .footer-link { transition: color .18s; }
        .footer-link:hover { color: var(--c-gold); }

        /* ── Nav active underline ── */
        .nav-active {
            position: relative;
        }
        .nav-active::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0; right: 0;
            height: 2px;
            background: var(--c-primary);
            border-radius: 1px;
        }
    </style>

    @yield('head')
    @stack('styles')
</head>

<body>

{{-- ═══════════════════════════════════════
     HEADER  (sticky — no offset hack needed)
     ═══════════════════════════════════════ --}}
<header class="sticky top-0 z-50" x-data="{ open: false }">

    {{-- Top info bar (sm+) --}}
    <div class="hidden sm:block text-xs text-white/90" style="background: var(--c-primary);">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 flex items-center justify-between h-9">
            <div class="flex items-center gap-5">
                <a href="tel:+923129776585" class="flex items-center gap-1.5 transition hover:text-white">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    +92 312 9776585
                </a>
                <span class="opacity-30">|</span>
                <a href="mailto:jinnahschooldegreecollege@gmail.com" class="flex items-center gap-1.5 transition hover:text-white">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    jinnahschooldegreecollege@gmail.com
                </a>
            </div>
            <a href="{{ route('admissions') }}" class="font-semibold transition hover:text-white">Apply Now</a>
        </div>
    </div>

    {{-- Main nav bar --}}
    <div class="border-b border-white/10 text-white"
         style="background: rgba(30,12,12,.92); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 flex items-center justify-between h-14 sm:h-16 gap-4">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 min-w-0">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-white text-xs font-bold sm:h-10 sm:w-10 sm:text-sm"
                      style="color: var(--c-primary);">JDCA</span>
                <div class="hidden sm:block min-w-0">
                    <p class="font-display font-semibold text-white text-base leading-tight truncate">Jinnah School &amp; Degree College</p>
                    <p class="text-white/50 text-[10px] leading-tight">Astore, Gilgit-Baltistan</p>
                </div>
            </a>

            {{-- Desktop nav --}}
            @php
                $navLinks = [
                    ['Home','home'],['About','about'],['Programs','programs'],
                    ['Faculty','faculty'],['Admissions','admissions'],
                    ['News','news'],['Notices','notices'],['Results','results'],['Contact','contact'],
                ];
            @endphp
            <ul class="hidden lg:flex items-center gap-0.5 list-none text-sm flex-1 justify-center">
                @foreach($navLinks as [$label,$routeName])
                <li>
                    <a href="{{ route($routeName) }}"
                       class="block rounded-md px-2 py-1.5 text-white/80 transition hover:bg-white/10 hover:text-white whitespace-nowrap
                              {{ request()->routeIs($routeName) || request()->routeIs($routeName.'.*') ? '!text-white font-semibold nav-active' : '' }}">
                        {{ $label }}
                    </a>
                </li>
                @endforeach
            </ul>

            {{-- Right: Apply + hamburger --}}
            <div class="flex shrink-0 items-center gap-2">
                <a href="{{ route('admissions') }}"
                   class="hidden lg:inline-flex items-center rounded-md bg-white px-4 py-1.5 text-sm font-semibold transition hover:bg-white/90"
                   style="color: var(--c-primary);">
                    Apply online
                </a>
                <button @click="open = !open"
                        class="lg:hidden flex items-center justify-center w-9 h-9 rounded-md transition hover:bg-white/10 text-white"
                        :aria-expanded="open.toString()" aria-label="Toggle menu">
                    <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>

        {{-- Mobile menu --}}
        <nav x-show="open" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             class="lg:hidden border-t border-white/10"
             style="background: var(--c-primary);">
            <div class="mx-auto max-w-6xl px-4 py-3 space-y-0.5">
                @foreach($navLinks as [$label,$routeName])
                <a href="{{ route($routeName) }}" @click="open = false"
                   class="block rounded-lg px-4 py-2.5 text-sm text-white/90 transition hover:bg-white/10 hover:text-white
                          {{ request()->routeIs($routeName) ? '!text-white font-semibold bg-white/15' : '' }}">
                    {{ $label }}
                </a>
                @endforeach
                <div class="pt-2 pb-1">
                    <a href="{{ route('admissions') }}" @click="open = false"
                       class="flex items-center justify-center w-full rounded-lg bg-white px-6 py-3 text-sm font-semibold"
                       style="color: var(--c-primary);">
                        Apply Now — Admissions 2025
                    </a>
                </div>
            </div>
        </nav>
    </div>

</header>

{{-- Flash messages --}}
@if(session('success') || session('error') || session('warning') || $errors->any())
<div class="mx-auto max-w-6xl px-4 sm:px-6 pt-4 space-y-3">
    @if(session('success'))
    <div class="flash-msg flex items-start gap-3 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm shadow-sm" x-data="{ show: true }" x-show="show">
        <svg class="w-5 h-5 shrink-0 mt-0.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="flex-1">{{ session('success') }}</span>
        <button @click="show=false" class="text-green-400 hover:text-green-700 transition"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    @endif
    @if(session('error'))
    <div class="flash-msg flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm shadow-sm" x-data="{ show: true }" x-show="show">
        <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="flex-1">{{ session('error') }}</span>
        <button @click="show=false" class="text-red-400 hover:text-red-700 transition"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    @endif
    @if(session('warning'))
    <div class="flash-msg flex items-start gap-3 p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm shadow-sm" x-data="{ show: true }" x-show="show">
        <svg class="w-5 h-5 shrink-0 mt-0.5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span class="flex-1">{{ session('warning') }}</span>
        <button @click="show=false" class="text-yellow-400 hover:text-yellow-700 transition"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    @endif
    @if($errors->any())
    <div class="flash-msg flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm shadow-sm" x-data="{ show: true }" x-show="show">
        <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="flex-1">
            <p class="font-semibold mb-1">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-0.5 text-red-700">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        <button @click="show=false" class="text-red-400 hover:text-red-700 transition"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    @endif
</div>
@endif

{{-- ═══════════════════════════════════════
     MAIN CONTENT
     ═══════════════════════════════════════ --}}
<main>
    @yield('content')
</main>

{{-- ═══════════════════════════════════════
     FOOTER
     ═══════════════════════════════════════ --}}
<footer style="background: var(--c-ink);" class="text-gray-300">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 pt-14 pb-10">
        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">

            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-md flex items-center justify-center shrink-0" style="background: var(--c-primary);">
                        <span class="text-white font-bold text-xs">JDCA</span>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-white leading-snug">Jinnah School &amp; Degree</p>
                        <p class="text-xs text-gray-500">College Astore, GB</p>
                    </div>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed mb-4">Committed to quality education in Gilgit-Baltistan since 2010. Empowering students with knowledge and vision.</p>
                <p class="text-xs text-gray-500 mb-5">Affiliated with <span class="font-semibold" style="color: var(--c-gold);">KIU, Gilgit-Baltistan</span></p>
                <div class="flex items-center gap-3">
                    <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook" class="w-9 h-9 rounded-lg flex items-center justify-center bg-gray-700 hover:bg-blue-600 transition">
                        <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noopener" aria-label="Twitter" class="w-9 h-9 rounded-lg flex items-center justify-center bg-gray-700 hover:bg-sky-500 transition">
                        <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://youtube.com" target="_blank" rel="noopener" aria-label="YouTube" class="w-9 h-9 rounded-lg flex items-center justify-center bg-gray-700 hover:bg-red-600 transition">
                        <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="text-white font-bold text-xs uppercase tracking-widest mb-5 pb-2 border-b border-gray-700/80">Quick Links</h3>
                <ul class="space-y-2.5 list-none">
                    @foreach([['Home','home'],['About Us','about'],['Programs','programs'],['Faculty','faculty'],['Admissions','admissions'],['News','news'],['Notices','notices'],['Results','results'],['Contact','contact']] as [$l,$r])
                    <li><a href="{{ route($r) }}" class="footer-link flex items-center gap-2 text-sm text-gray-400">
                        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>{{ $l }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Programs --}}
            <div>
                <h3 class="text-white font-bold text-xs uppercase tracking-widest mb-5 pb-2 border-b border-gray-700/80">Programs</h3>
                <ul class="space-y-2.5 list-none">
                    @php $footerPrograms = isset($programs) && $programs->count() ? $programs : \App\Models\AcademicProgram::query()->limit(6)->get(); @endphp
                    @foreach($footerPrograms->take(6) as $fp)
                    <li><a href="{{ route('programs') }}" class="footer-link flex items-center gap-2 text-sm text-gray-400">
                        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        {{ $fp->name ?? 'Program' }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="text-white font-bold text-xs uppercase tracking-widest mb-5 pb-2 border-b border-gray-700/80">Contact Us</h3>
                <ul class="space-y-4 mb-6 list-none">
                    <li class="flex items-start gap-3">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--c-gold)"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-sm text-gray-400 leading-relaxed">Astore, Gilgit-Baltistan, Pakistan</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--c-gold)"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:+923129776585" class="footer-link text-sm text-gray-400">+92 312 9776585</a>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--c-gold)"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:jinnahschooldegreecollege@gmail.com" class="footer-link text-sm text-gray-400 break-all">jinnahschooldegreecollege@gmail.com</a>
                    </li>
                </ul>
                <div class="border-t border-gray-700 pt-4 space-y-2.5">
                    <a href="{{ route('portal.login') }}" class="flex items-center gap-2 text-sm font-semibold" style="color:var(--c-gold)">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Student Portal
                    </a>
                    <a href="{{ route('filament.admin.auth.login') }}" class="footer-link flex items-center gap-2 text-sm text-gray-400">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Staff Login
                    </a>
                </div>
            </div>

        </div>
    </div>
    <div class="border-t border-gray-800">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Jinnah School &amp; Degree College Astore. Affiliated with KIU, Gilgit-Baltistan.</p>
            <p class="text-xs text-gray-600">Designed with &#10084;&#65039; for education</p>
        </div>
    </div>
</footer>

<script>
(function(){
    if('IntersectionObserver' in window){
        var ro = new IntersectionObserver(function(e){ e.forEach(function(x){ if(x.isIntersecting){ x.target.classList.add('is-visible'); ro.unobserve(x.target); } }); },{threshold:.10});
        document.querySelectorAll('.reveal').forEach(function(el){ ro.observe(el); });
    } else { document.querySelectorAll('.reveal').forEach(function(el){ el.classList.add('is-visible'); }); }

    function animCount(el){
        var t=parseInt(el.getAttribute('data-counter'),10), s=el.getAttribute('data-suffix')||'+', d=1600, t0=performance.now();
        (function tick(now){ var p=Math.min((now-t0)/d,1), v=1-Math.pow(1-p,3); el.textContent=Math.floor(v*t).toLocaleString()+s; if(p<1) requestAnimationFrame(tick); })(t0);
    }
    if('IntersectionObserver' in window){
        var co=new IntersectionObserver(function(e){ e.forEach(function(x){ if(x.isIntersecting){ animCount(x.target); co.unobserve(x.target); } }); },{threshold:.5});
        document.querySelectorAll('[data-counter]').forEach(function(el){ co.observe(el); });
    }
})();
</script>

@stack('scripts')
</body>
</html>
