<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Jinnah Degree College Astore') — JDCA</title>
    <meta name="description" content="@yield('meta_description', 'Jinnah Degree College Astore (JDCA) — Quality education in Gilgit-Baltistan, Pakistan.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon: uploaded college logo when set, otherwise the bundled JDCA mark --}}
    @php $siteFavicon = !empty($college->logo_url ?? null) ? $college->logo_url : asset('assets/images/jdca-logo.svg'); @endphp
    <link rel="icon" href="{{ $siteFavicon }}" sizes="any">
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/jdca-logo.svg') }}">
    <link rel="apple-touch-icon" href="{{ $siteFavicon }}">

    @php
        $pageVisibility = \App\Models\WebsitePage::query()->pluck('is_published', 'slug')->all();
        $pageVisible = fn (string $slug): bool => ! array_key_exists($slug, $pageVisibility) || (bool) $pageVisibility[$slug];
        $showAboutMenu = $pageVisible('about');
        $showAcademicsMenu = $pageVisible('programs');
        $showAdmissionsMenu = $pageVisible('admissions');
        $showStudentLifeMenu = $pageVisible('news');
        $fontStacks = [
            'open-sans' => '"Open Sans", ui-sans-serif, system-ui, sans-serif',
            'inter' => '"Inter", ui-sans-serif, system-ui, sans-serif',
            'nunito' => '"Nunito", ui-sans-serif, system-ui, sans-serif',
            'lato' => '"Lato", ui-sans-serif, system-ui, sans-serif',
            'playfair-display' => '"Playfair Display", Georgia, ui-serif, serif',
            'merriweather' => '"Merriweather", Georgia, ui-serif, serif',
            'lora' => '"Lora", Georgia, ui-serif, serif',
            'source-serif-4' => '"Source Serif 4", Georgia, ui-serif, serif',
        ];
        $siteBrand = \App\Models\CollegeSetting::get('website_theme_brand', '#1A3A5F');
        $siteBrandDark = \App\Models\CollegeSetting::get('website_theme_brand_dark', '#122A45');
        $siteGold = \App\Models\CollegeSetting::get('website_theme_gold', '#c4973a');
        $siteFooterBg = \App\Models\CollegeSetting::get('website_theme_footer_bg', '#1A3A5F');
        $siteBodyBg = \App\Models\CollegeSetting::get('website_theme_body_bg', '#F8FAFC');
        $siteSurface = \App\Models\CollegeSetting::get('website_theme_surface', '#F1F5F9');
        $siteSansFontKey = \App\Models\CollegeSetting::get('website_font_sans', 'inter');
        $siteDisplayFontKey = \App\Models\CollegeSetting::get('website_font_display', 'inter');
        $siteSansFont = $fontStacks[$siteSansFontKey] ?? $fontStacks['open-sans'];
        $siteDisplayFont = $fontStacks[$siteDisplayFontKey] ?? $fontStacks['playfair-display'];
        $siteFooterAbout = \App\Models\CollegeSetting::get('website_footer_about', 'Intermediate and college programmes in Astore, Gilgit-Baltistan, aligned with national standards, student welfare, and pathways to universities across Pakistan.');
        $siteFooterCopyright = \App\Models\CollegeSetting::get('website_footer_copyright', 'Jinnah Degree College Astore. All rights reserved.');
    @endphp

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lato:wght@400;700&family=Lora:wght@400;500;600;700&family=Merriweather:wght@400;700&family=Nunito:wght@400;600;700&family=Open+Sans:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=Source+Serif+4:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">

    @stack('styles')
    <style>
        :root {
            --site-brand: {{ $siteBrand }};
            --site-brand-dark: {{ $siteBrandDark }};
            --site-gold: {{ $siteGold }};
            --site-footer-bg: {{ $siteFooterBg }};
            --site-body-bg: {{ $siteBodyBg }};
            --site-surface: {{ $siteSurface }};
            --site-header-offset: 6rem;
            --font-sans: {!! $siteSansFont !!};
            --font-display: {!! $siteDisplayFont !!};
            --color-brand: {{ $siteBrand }};
            --color-brand-dark: {{ $siteBrandDark }};
            --color-gold: {{ $siteGold }};
            --color-gold-dark: #a07830;
            --color-surface: {{ $siteBodyBg }};
            --color-surface-alt: {{ $siteSurface }};
        }
        .site-body { background-color: var(--site-body-bg); }
        .site-topbar { background-color: var(--site-brand); }
        .site-footer { background-color: var(--site-footer-bg); }
        .site-brand-gradient { background: linear-gradient(135deg, var(--site-brand), var(--site-brand-dark)); }

        /* ── Subtle scroll-reveal animations (only when JS enables them) ── */
        .has-reveal [data-reveal] { opacity: 0; transform: translateY(26px); transition: opacity .7s cubic-bezier(.2,.7,.2,1), transform .7s cubic-bezier(.2,.7,.2,1); will-change: opacity, transform; }
        .has-reveal [data-reveal].is-visible { opacity: 1; transform: none; }
        .has-reveal [data-reveal][data-reveal-delay="1"] { transition-delay: .08s; }
        .has-reveal [data-reveal][data-reveal-delay="2"] { transition-delay: .16s; }
        .has-reveal [data-reveal][data-reveal-delay="3"] { transition-delay: .24s; }
        @media (prefers-reduced-motion: reduce) { .has-reveal [data-reveal] { opacity: 1 !important; transform: none !important; transition: none !important; } }
        .site-gold-gradient { background: linear-gradient(135deg, var(--site-gold), #a07830); }
        .site-brand-text { color: var(--site-brand); }
        /* ── Navigation dropdowns ───────────────────────────────────────── */
        .dd-menu {
            position: absolute; left: 0; top: 100%; z-index: 50; list-style: none;
            border-radius: 0.75rem; background: #fff; padding: 0.5rem 0; color: #1c1917;
            visibility: hidden; opacity: 0; pointer-events: none;
            transform: translateY(-6px) scaleY(0.95); transform-origin: top;
            transition: opacity 0.18s ease, transform 0.18s ease, visibility 0s linear 0.18s;
            box-shadow: 0 12px 40px -4px rgba(0,0,0,0.18); min-width: 13rem;
        }
        .group:hover > .dd-menu, .group:focus-within > .dd-menu {
            visibility: visible; opacity: 1; pointer-events: auto;
            transform: translateY(0) scaleY(1);
            transition: opacity 0.18s ease, transform 0.18s ease, visibility 0s linear 0s;
        }
        .mob-link { display:block; border-radius:0.5rem; padding:0.625rem 1rem; transition: background-color 0.15s; }
        .mob-link:hover { background: rgba(255,255,255,0.1); }
        .event-details-btn:hover { background: var(--site-brand); color: #fff; }
    </style>
    <script>document.documentElement.classList.add('has-reveal');</script>
</head>

<body class="font-sans text-stone-800 antialiased overflow-x-hidden" style="background:var(--site-body-bg)">

<a href="#main"
   class="absolute left-[-9999px] top-0 z-[300] whitespace-nowrap rounded-md bg-white px-4 py-2 text-sm font-semibold text-brand shadow-lg transition-none focus:left-4 focus:top-4 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-brand">Skip to main content</a>

<div id="site-preloader"
     class="fixed inset-0 z-[200] flex flex-col items-center justify-center bg-gradient-to-br from-brand via-brand to-brand-dark transition-opacity duration-500 ease-out motion-reduce:duration-150"
     role="status" aria-live="polite" aria-busy="true">
    <p class="font-display text-2xl font-semibold tracking-tight text-white sm:text-3xl">{{ \App\Models\CollegeSetting::get('college_short_name', 'JDCA') }}</p>
    <div
        class="mt-8 h-10 w-10 rounded-full border-[3px] border-white/25 border-t-white motion-reduce:animate-none motion-reduce:border-white/60 animate-spin"
        aria-hidden="true"></div>
    <span class="sr-only">Loading</span>
</div>

<header id="siteHeader" class="fixed inset-x-0 top-0 z-50 text-white">

    {{-- ── ROW 1: Identity bar — logo + name + search + hamburger ──────── --}}
    <div class="site-topbar">
        <div class="mx-auto flex max-w-6xl items-center gap-3 px-4 h-[68px] sm:h-[80px]">

            {{-- Logo + College Name --}}
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3 self-stretch min-w-0">
                @php $logoCustom = $college->logo_url ?? null; @endphp
                <img src="{{ $logoCustom ?: asset('assets/images/default/cologe-logo-web.png') }}"
                     alt="{{ $college->short_name ?? 'JDCA' }}"
                     onerror="this.onerror=null;this.src='{{ asset('assets/images/default/cologe-logo-web.png') }}'"
                     class="h-[56px] w-auto sm:h-[68px] shrink-0 object-contain">

                <div class="min-w-0">
                    <div class="font-display font-bold leading-tight text-base sm:text-lg lg:text-xl">{{ $college->college_name ?? 'Jinnah Degree College Astore' }}</div>
                    <div class="hidden sm:block text-[10px] text-white/65 mt-0.5">Astore, Gilgit-Baltistan</div>
                </div>
            </a>

            <div class="flex-1"></div>

            {{-- Contact info (large screens only) --}}
            <div class="hidden lg:flex flex-col items-end gap-0.5 text-[10px] text-white/70 shrink-0">
                <a href="tel:{{ preg_replace('/\s+/', '', $college->phone ?? '') }}" class="hover:text-white transition flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                    {{ $college->phone ?? '+92 312 9776585' }}
                </a>
                <a href="mailto:{{ $college->email ?? '' }}" class="hover:text-white transition flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                    {{ $college->email ?? 'jinnahschooldegreecollege@gmail.com' }}
                </a>
            </div>

            {{-- Search --}}
            <form action="{{ route('search') }}" method="GET" class="hidden sm:flex relative shrink-0">
                <input type="search" name="q" placeholder="Search..."
                       class="rounded-full pl-4 pr-9 py-1.5 text-xs bg-white/15 text-white placeholder-white/55 border border-white/20 focus:outline-none focus:bg-white/25 focus:border-white/40 transition w-32 lg:w-44">
                <button type="submit" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-white/60 hover:text-white transition" aria-label="Search">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>

            {{-- Portal link (xl only) --}}
            <a href="{{ route('portal.login') }}" class="hidden xl:flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold bg-white/15 hover:bg-white/25 border border-white/20 transition shrink-0">
                Student Portal
            </a>

            {{-- Hamburger --}}
            <button type="button" id="menuToggle"
                    class="flex items-center gap-2 rounded-lg p-2 xl:hidden focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-brand shrink-0"
                    aria-expanded="false" aria-controls="mobileMenu" aria-label="Open navigation menu">
                <div id="hamburger" class="flex h-[18px] w-6 flex-col justify-center gap-1.5" aria-hidden="true">
                    <span id="hb1" class="block h-0.5 w-full origin-center rounded bg-white transition-all duration-300"></span>
                    <span id="hb2" class="block h-0.5 w-full rounded bg-white transition-all duration-300"></span>
                    <span id="hb3" class="block h-0.5 w-full origin-center rounded bg-white transition-all duration-300"></span>
                </div>
            </button>
        </div>
    </div>

    {{-- ── ROW 2: Nav bar — centered menu items ─────────────────────────── --}}
    @php $deptList = \App\Models\Department::visible()->ordered()
        ->with(['academicPrograms' => fn ($q) => $q->where('is_active', true)->where('show_on_website', true)->orderBy('sort_order')->orderBy('name')])
        ->get(); @endphp
    <div class="relative border-t border-white/10" style="background:rgba(0,0,0,0.52); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);">
        <div class="mx-auto max-w-6xl px-4">

            {{-- Desktop nav --}}
            <ul class="hidden xl:flex items-center justify-center gap-0 list-none h-10 text-[11.5px]" role="menubar">

                <li><a href="{{ route('home') }}" class="nav-link px-3">Home</a></li>

                {{-- About Us --}}
                <li class="group relative">
                    <a href="{{ route('about') }}" class="nav-link px-3 flex items-center gap-0.5">
                        About Us<svg class="h-3 w-3 ml-0.5 shrink-0 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                    <ul class="dd-menu w-60">
                        <li><a href="{{ route('about.history') }}"   class="dropdown-link">History &amp; Geography</a></li>
                        <li><a href="{{ route('about.mission') }}"   class="dropdown-link">Mission &amp; Vision</a></li>
                        <li><a href="{{ route('about.message') }}"   class="dropdown-link">Message from VC</a></li>
                        <li><a href="{{ route('about.director') }}"  class="dropdown-link">Message from Director</a></li>
                        <li><a href="{{ route('about.principal') }}" class="dropdown-link">Message from Principal</a></li>
                        <li><a href="{{ route('campus-facilities') }}" class="dropdown-link">Campus Facilities</a></li>
                        <li><a href="{{ route('gallery') }}" class="dropdown-link">Campus Gallery</a></li>
                    </ul>
                </li>

                {{-- Academics --}}
                <li class="group relative">
                    <a href="{{ route('departments') }}" class="nav-link px-3 flex items-center gap-0.5">
                        Academics<svg class="h-3 w-3 ml-0.5 shrink-0 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                    <ul class="dd-menu w-56">
                        <li class="group/depts relative">
                            <a href="{{ route('departments') }}" class="dropdown-link flex items-center justify-between">
                                Departments<svg class="h-3 w-3 shrink-0 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <ul class="invisible absolute left-full top-0 z-50 w-72 list-none rounded-xl bg-white py-2 text-stone-800 shadow-xl pointer-events-none opacity-0 transition-all duration-200 group-hover/depts:pointer-events-auto group-hover/depts:visible group-hover/depts:opacity-100">
                                @foreach($deptList as $dept)
                                <li class="group/dept relative">
                                    <a href="{{ route('departments.show', $dept->slug) }}" class="dropdown-link flex items-center justify-between">
                                        {{ $dept->name }}
                                        @if($dept->academicPrograms->isNotEmpty())
                                        <svg class="h-3 w-3 shrink-0 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                        @endif
                                    </a>
                                    @if($dept->academicPrograms->isNotEmpty())
                                    <ul class="invisible absolute left-full top-0 z-50 w-72 list-none rounded-xl bg-white py-2 text-stone-800 shadow-xl pointer-events-none opacity-0 transition-all duration-200 group-hover/dept:pointer-events-auto group-hover/dept:visible group-hover/dept:opacity-100">
                                        @foreach($dept->academicPrograms as $prog)
                                        <li><a href="{{ route('departments.show', $dept->slug) }}" class="dropdown-link">{{ $prog->name }}</a></li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                                @endforeach
                                @if($deptList->isEmpty())<li class="px-4 py-2 text-xs text-stone-400">No departments yet</li>@endif
                            </ul>
                        </li>
                        <li><a href="{{ route('faculty') }}"                   class="dropdown-link">Faculty Profile</a></li>
                        <li><a href="{{ route('admissions.semester-rules') }}" class="dropdown-link">Semester Rules</a></li>
                        <li><a href="{{ route('course-outlines') }}" class="dropdown-link">Course Outlines</a></li>
                    </ul>
                </li>

                {{-- Admission --}}
                <li class="group relative">
                    <a href="{{ route('admissions') }}" class="nav-link px-3 flex items-center gap-0.5">
                        Admission<svg class="h-3 w-3 ml-0.5 shrink-0 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                    <ul class="dd-menu w-56">
                        <li><a href="{{ route('admissions.procedure') }}"    class="dropdown-link">Admission Procedure</a></li>
                        <li><a href="{{ route('admissions') }}"              class="dropdown-link">Online Admission</a></li>
                        <li><a href="{{ route('admissions') }}"              class="dropdown-link">Admission Form</a></li>
                        <li><a href="{{ route('admissions.fee-structure') }}" class="dropdown-link">Fee Structure</a></li>
                        <li><a href="{{ route('fee-challan.download') }}" class="dropdown-link">Download Fee Challan</a></li>
                        <li class="group/schol relative">
                            <a href="{{ route('scholarships') }}" class="dropdown-link flex items-center justify-between">
                                Scholarships<svg class="h-3 w-3 shrink-0 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <ul class="invisible absolute left-full top-0 z-50 w-64 list-none rounded-xl bg-white py-2 text-stone-800 shadow-xl pointer-events-none opacity-0 transition-all duration-200 group-hover/schol:pointer-events-auto group-hover/schol:visible group-hover/schol:opacity-100">
                                <li><a href="{{ route('scholarships') }}" class="dropdown-link">Merit-Based Scholarship</a></li>
                                <li><a href="{{ route('scholarships') }}" class="dropdown-link">Need-Based Scholarship</a></li>
                                <li><a href="{{ route('scholarships') }}" class="dropdown-link">Orphan Scholarship</a></li>
                                <li><a href="{{ route('scholarships') }}" class="dropdown-link">Special Category Scholarship</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li><a href="https://colleges.kiu.edu.pk/" target="_blank" rel="noopener" class="nav-link px-3">College LMS</a></li>
                <li><a href="{{ route('jobs') }}"     class="nav-link px-3">Jobs</a></li>
                <li><a href="{{ route('downloads') }}" class="nav-link px-3">Downloads</a></li>
                <li><a href="{{ route('contact') }}"  class="nav-link px-3">Contact Us</a></li>

                <li class="ml-2">
                    <a href="{{ route('admissions') }}" class="site-gold-gradient rounded-md px-3 py-1.5 text-[11px] font-semibold text-white shadow transition hover:opacity-90">Apply Now</a>
                </li>
            </ul>

            {{-- Mobile menu (below nav bar) --}}
            <nav id="mobileMenu"
                 class="absolute left-0 right-0 top-full z-40 hidden max-h-[min(85dvh,36rem)] overflow-y-auto overscroll-contain bg-brand pb-[env(safe-area-inset-bottom,0px)] shadow-lg xl:hidden"
                 aria-label="Mobile navigation">
                <div class="px-4">
                    <ul class="flex list-none flex-col gap-0.5 py-3 text-white text-sm">
                        <li><a href="{{ route('home') }}" class="mob-link font-semibold">Home</a></li>

                        <li class="px-4 pt-3 pb-0.5 text-[10px] font-bold uppercase tracking-widest text-white/40">About Us</li>
                        <li><a href="{{ route('about.history') }}"   class="mob-link pl-7">History &amp; Geography</a></li>
                        <li><a href="{{ route('about.mission') }}"   class="mob-link pl-7">Mission &amp; Vision</a></li>
                        <li><a href="{{ route('about.message') }}"   class="mob-link pl-7">Message from VC</a></li>
                        <li><a href="{{ route('about.director') }}"  class="mob-link pl-7">Message from Director</a></li>
                        <li><a href="{{ route('about.principal') }}" class="mob-link pl-7">Message from Principal</a></li>
                        <li><a href="{{ route('campus-facilities') }}" class="mob-link pl-7">Campus Facilities</a></li>
                        <li><a href="{{ route('gallery') }}" class="mob-link pl-7">Campus Gallery</a></li>

                        <li class="px-4 pt-3 pb-0.5 text-[10px] font-bold uppercase tracking-widest text-white/40">Academics</li>
                        <li><a href="{{ route('departments') }}" class="mob-link pl-7">Departments</a></li>
                        @foreach($deptList as $dept)
                        <li><a href="{{ route('departments.show', $dept->slug) }}" class="mob-link pl-12 text-white/70">{{ $dept->name }}</a></li>
                        @endforeach
                        <li><a href="{{ route('faculty') }}"                   class="mob-link pl-7">Faculty Profile</a></li>
                        <li><a href="{{ route('admissions.semester-rules') }}" class="mob-link pl-7">Semester Rules</a></li>
                        <li><a href="{{ route('course-outlines') }}" class="mob-link pl-7">Course Outlines</a></li>

                        <li class="px-4 pt-3 pb-0.5 text-[10px] font-bold uppercase tracking-widest text-white/40">Admission</li>
                        <li><a href="{{ route('admissions.procedure') }}"     class="mob-link pl-7">Admission Procedure</a></li>
                        <li><a href="{{ route('admissions') }}"               class="mob-link pl-7">Online Admission</a></li>
                        <li><a href="{{ route('admissions') }}"               class="mob-link pl-7">Admission Form</a></li>
                        <li><a href="{{ route('admissions.fee-structure') }}" class="mob-link pl-7">Fee Structure</a></li>
                        <li class="px-4 pt-1 pb-0.5 text-[10px] font-bold uppercase tracking-widest text-white/30">Scholarships</li>
                        <li><a href="{{ route('scholarships') }}" class="mob-link pl-12 text-white/70">Merit-Based Scholarship</a></li>
                        <li><a href="{{ route('scholarships') }}" class="mob-link pl-12 text-white/70">Need-Based Scholarship</a></li>
                        <li><a href="{{ route('scholarships') }}" class="mob-link pl-12 text-white/70">Orphan Scholarship</a></li>
                        <li><a href="{{ route('scholarships') }}" class="mob-link pl-12 text-white/70">Special Category Scholarship</a></li>

                        <li class="my-2 border-t border-white/10"></li>
                        <li><a href="https://colleges.kiu.edu.pk/" target="_blank" rel="noopener" class="mob-link">College LMS</a></li>
                        <li><a href="{{ route('jobs') }}"      class="mob-link">Jobs</a></li>
                        <li><a href="{{ route('downloads') }}" class="mob-link">Downloads</a></li>
                        <li><a href="{{ route('contact') }}"   class="mob-link">Contact Us</a></li>
                        <li class="my-2 border-t border-white/10"></li>
                        <li><a href="{{ route('portal.login') }}" class="mob-link text-white/70">Student Portal</a></li>
                        <li>
                            <a href="{{ route('admissions') }}"
                               class="mob-link mx-2 mt-1 mb-2 flex items-center justify-center rounded-lg py-2.5 text-center text-sm font-bold text-white"
                               style="background:var(--site-gold)">Apply for Admission</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</header>

<main id="main" tabindex="-1"
      class="site-main outline-none [&_h1]:font-display [&_h2]:font-display [&_h3]:font-sans [&_h4]:font-sans [&_h1]:tracking-tight [&_h2]:tracking-tight [&_h3]:tracking-tight [&_h4]:tracking-tight">
    @yield('content')
</main>

<footer class="site-footer px-4 py-14 text-white sm:px-6 sm:py-16">
    <div class="mx-auto grid max-w-6xl gap-10 md:grid-cols-2 lg:grid-cols-4 lg:gap-12">
        <div class="text-left">
            <div class="mb-4 flex items-center gap-3">
                <img src="{{ $logoCustom ?: asset('assets/images/default/cologe-logo-web.png') }}"
                     alt="{{ $college->short_name ?? 'JDCA' }}"
                     onerror="this.onerror=null;this.src='{{ asset('assets/images/default/cologe-logo-web.png') }}'"
                     class="h-16 w-auto shrink-0 object-contain">

                <span class="font-display text-xl font-semibold">{{ $college->college_name ?? 'Jinnah Degree College Astore' }}</span>
            </div>
            <p class="text-sm leading-relaxed text-stone-400">
                {{ $siteFooterAbout }}
            </p>
        </div>
        <div class="text-left">
            <p class="mb-4 font-display text-xl font-semibold text-white">About &amp; Academics</p>
            <ul class="space-y-2.5 text-sm text-stone-400">
                <li><a href="{{ route('about') }}" class="transition hover:text-white">About Us</a></li>
                <li><a href="{{ route('about.history') }}" class="transition hover:text-white">History &amp; Geography</a></li>
                <li><a href="{{ route('about.mission') }}" class="transition hover:text-white">Mission &amp; Vision</a></li>
                <li><a href="{{ route('departments') }}" class="transition hover:text-white">Departments</a></li>
                <li><a href="{{ route('faculty') }}" class="transition hover:text-white">Faculty Profile</a></li>
                <li><a href="{{ route('campus-facilities') }}" class="transition hover:text-white">Campus Facilities</a></li>
            </ul>
        </div>
        <div class="text-left">
            <p class="mb-4 font-display text-xl font-semibold text-white">Quick Links</p>
            <ul class="space-y-2.5 text-sm text-stone-400">
                <li><a href="{{ route('admissions') }}" class="transition hover:text-white">Online Admission</a></li>
                <li><a href="{{ route('admissions.fee-structure') }}" class="transition hover:text-white">Fee Structure</a></li>
                <li><a href="{{ route('scholarships') }}" class="transition hover:text-white">Scholarships</a></li>
                <li><a href="{{ route('jobs') }}" class="transition hover:text-white">Jobs</a></li>
                <li><a href="{{ route('notices') }}" class="transition hover:text-white">Notices</a></li>
                <li><a href="{{ route('portal.login') }}" class="transition hover:text-white">Student Portal</a></li>
                <li><a href="https://colleges.kiu.edu.pk/" target="_blank" class="transition hover:text-white">College LMS</a></li>
            </ul>
        </div>
        <div class="text-left">
            <p class="mb-4 font-display text-xl font-semibold text-white">Contact</p>
            <ul class="space-y-2.5 text-sm text-stone-400">
                <li>{{ $college->address ?? 'Astore, Gilgit-Baltistan, Pakistan' }}</li>
                <li><a href="tel:{{ preg_replace('/\s+/', '', $college->phone ?? '') }}" class="transition hover:text-white">{{ $college->phone ?? '+92 312 9776585' }}</a></li>
                <li><a href="mailto:{{ $college->email ?? '' }}" class="transition hover:text-white">{{ $college->email ?? 'jinnahschooldegreecollege@gmail.com' }}</a></li>
                @if($pageVisible('contact'))
                    <li><a href="{{ route('contact') }}" class="transition hover:text-white">Contact Us</a></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="mx-auto mt-12 max-w-6xl border-t border-white/10 pt-8">
        <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
            <p class="text-xs text-stone-500 sm:text-sm">&copy; {{ date('Y') }} {{ $siteFooterCopyright }}</p>
            <div class="flex flex-wrap items-center justify-center gap-4 text-stone-400" aria-label="Legal and utility links">
                <a href="{{ route('contact') }}" class="transition hover:text-white">Privacy</a>
                <a href="{{ route('contact') }}" class="transition hover:text-white">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<script src="{{ asset('assets/js/site.js') }}" defer></script>
<script>
    (() => {
        const syncSiteHeaderOffset = () => {
            const siteHeader = document.getElementById('siteHeader');

            if (! siteHeader) {
                return;
            }

            document.documentElement.style.setProperty('--site-header-offset', `${siteHeader.offsetHeight}px`);
        };

        document.addEventListener('DOMContentLoaded', syncSiteHeaderOffset);
        window.addEventListener('load', syncSiteHeaderOffset);
        window.addEventListener('resize', syncSiteHeaderOffset);

        const siteHeader = document.getElementById('siteHeader');

        if (siteHeader && 'ResizeObserver' in window) {
            new ResizeObserver(syncSiteHeaderOffset).observe(siteHeader);
        }
    })();
</script>
@stack('scripts')
<script>
    (function () {
        var els = document.querySelectorAll('[data-reveal]');
        if (!els.length) return;
        if (!('IntersectionObserver' in window)) {
            els.forEach(function (el) { el.classList.add('is-visible'); });
            return;
        }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
        els.forEach(function (el) { io.observe(el); });
    })();
</script>
</body>
</html>
