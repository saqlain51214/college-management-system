<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Jinnah School & Degree College Astore') — JDCA</title>
    <meta name="description" content="@yield('meta_description', 'Jinnah School & Degree College Astore (JDCA) — Quality education in Gilgit-Baltistan, Pakistan.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        $siteBrand = \App\Models\CollegeSetting::get('website_theme_brand', '#6B2D39');
        $siteBrandDark = \App\Models\CollegeSetting::get('website_theme_brand_dark', '#5a2430');
        $siteGold = \App\Models\CollegeSetting::get('website_theme_gold', '#c4973a');
        $siteFooterBg = \App\Models\CollegeSetting::get('website_theme_footer_bg', '#1A1A1A');
        $siteBodyBg = \App\Models\CollegeSetting::get('website_theme_body_bg', '#F8FAFC');
        $siteSurface = \App\Models\CollegeSetting::get('website_theme_surface', '#F1F5F9');
        $siteSansFontKey = \App\Models\CollegeSetting::get('website_font_sans', 'open-sans');
        $siteDisplayFontKey = \App\Models\CollegeSetting::get('website_font_display', 'playfair-display');
        $siteSansFont = $fontStacks[$siteSansFontKey] ?? $fontStacks['open-sans'];
        $siteDisplayFont = $fontStacks[$siteDisplayFontKey] ?? $fontStacks['playfair-display'];
        $siteFooterAbout = \App\Models\CollegeSetting::get('website_footer_about', 'Intermediate and college programmes in Astore, Gilgit-Baltistan, aligned with national standards, student welfare, and pathways to universities across Pakistan.');
        $siteFooterCopyright = \App\Models\CollegeSetting::get('website_footer_copyright', 'Jinnah School & Degree College Astore. All rights reserved.');
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
        .site-gold-gradient { background: linear-gradient(135deg, var(--site-gold), #a07830); }
        .site-brand-text { color: var(--site-brand); }
    </style>
</head>

<body class="site-body bg-surface font-sans text-stone-800 antialiased overflow-x-hidden">

<a href="#main"
   class="absolute left-[-9999px] top-0 z-[300] whitespace-nowrap rounded-md bg-white px-4 py-2 text-sm font-semibold text-brand shadow-lg transition-none focus:left-4 focus:top-4 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-brand">Skip to main content</a>

<div id="site-preloader"
     class="fixed inset-0 z-[200] flex flex-col items-center justify-center bg-gradient-to-br from-brand via-brand to-brand-dark transition-opacity duration-500 ease-out motion-reduce:duration-150"
     role="status" aria-live="polite" aria-busy="true">
    <p class="font-display text-2xl font-semibold tracking-tight text-white sm:text-3xl">JDCA</p>
    <div
        class="mt-8 h-10 w-10 rounded-full border-[3px] border-white/25 border-t-white motion-reduce:animate-none motion-reduce:border-white/60 animate-spin"
        aria-hidden="true"></div>
    <span class="sr-only">Loading</span>
</div>

<header id="siteHeader" class="fixed inset-x-0 top-0 z-50 text-white">
    <div class="site-topbar px-4 py-2 text-[11px] sm:text-xs">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-2 sm:px-2">
            <div class="min-w-0 flex flex-1 flex-wrap items-center gap-x-3 gap-y-1 text-white/95 sm:gap-x-4">
                <a href="tel:{{ preg_replace('/\s+/', '', $college->phone ?? '') }}" class="transition hover:text-white">{{ $college->phone ?? '+92 312 9776585' }}</a>
                <span class="hidden text-white/40 sm:inline" aria-hidden="true">|</span>
                <a href="mailto:{{ $college->email ?? '' }}" class="transition hover:text-white">{{ $college->email ?? 'jinnahschooldegreecollege@gmail.com' }}</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('portal.login') }}" class="hidden transition hover:text-white sm:inline">Student Portal</a>
            </div>
        </div>
    </div>
    <div class="border-b border-white/15 bg-black/30 backdrop-blur-md">
        <div class="mx-auto grid min-w-0 max-w-6xl grid-cols-[1fr_auto] items-center gap-3 px-4 py-3 sm:px-6 sm:py-3.5 sm:gap-4 xl:grid-cols-[auto_minmax(0,1fr)_auto]">
            <a href="{{ route('home') }}" class="flex min-w-0 max-w-[70vw] items-center gap-2 sm:max-w-none">
                <span class="site-gold-gradient flex h-10 w-10 shrink-0 items-center justify-center rounded-xl shadow-lg shadow-gold/30 text-sm font-black tracking-tighter text-white sm:h-11 sm:w-11 sm:text-base" aria-hidden="true">{{ $college->short_name ?? 'JDCA' }}</span>
                <span class="truncate font-display text-lg font-semibold tracking-tight sm:text-xl">{{ $college->college_name ?? 'Jinnah School & Degree College' }}</span>
            </a>

            <ul class="hidden min-w-0 list-none items-center justify-end gap-1 text-[12px] xl:flex xl:flex-nowrap xl:gap-2 2xl:gap-3" role="menubar">
                <li><a href="{{ route('home') }}" class="nav-link">Home</a></li>

                @if($showAboutMenu)
                    <li class="group relative">
                        <a href="{{ route('about') }}" class="nav-link flex items-center gap-0.5">
                            <span>About Us</span>
                            <svg class="h-3.5 w-3.5 shrink-0 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="invisible absolute left-0 z-50 mt-0 w-56 origin-top -translate-y-1 scale-95 list-none rounded-xl bg-white py-2 text-stone-800 opacity-0 shadow-xl shadow-stone-900/10 pointer-events-none transition-all duration-200 ease-out group-hover:pointer-events-auto group-hover:visible group-hover:translate-y-0 group-hover:scale-100 group-hover:opacity-100 group-focus-within:pointer-events-auto group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:scale-100 group-focus-within:opacity-100">
                            <li><a href="{{ route('about') }}" class="dropdown-link">About Overview</a></li>
                            @if($pageVisible('about-history'))
                                <li><a href="{{ route('about.history') }}" class="dropdown-link">History & Location</a></li>
                            @endif
                            @if($pageVisible('about-mission'))
                                <li><a href="{{ route('about.mission') }}" class="dropdown-link">Mission & Vision</a></li>
                            @endif
                            @if($pageVisible('faculty'))
                                <li><a href="{{ route('faculty') }}" class="dropdown-link">Faculty & Leadership</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if($showAcademicsMenu)
                    <li class="group relative">
                        <a href="{{ route('programs') }}" class="nav-link flex items-center gap-0.5">
                            <span>Academics</span>
                            <svg class="h-3.5 w-3.5 shrink-0 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="invisible absolute left-0 z-50 mt-0 w-56 origin-top -translate-y-1 scale-95 list-none rounded-xl bg-white py-2 text-stone-800 opacity-0 shadow-xl shadow-stone-900/10 pointer-events-none transition-all duration-200 ease-out group-hover:pointer-events-auto group-hover:visible group-hover:translate-y-0 group-hover:scale-100 group-hover:opacity-100 group-focus-within:pointer-events-auto group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:scale-100 group-focus-within:opacity-100">
                            <li><a href="{{ route('programs') }}" class="dropdown-link">All Programs</a></li>
                            @if($pageVisible('gallery'))
                                <li><a href="{{ route('gallery') }}" class="dropdown-link">Campus Gallery</a></li>
                            @endif
                            @if($pageVisible('timetable'))
                                <li><a href="{{ route('timetable') }}" class="dropdown-link">Timetable</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if($showAdmissionsMenu)
                    <li class="group relative">
                        <a href="{{ route('admissions') }}" class="nav-link flex items-center gap-0.5">
                            <span>Admission</span>
                            <svg class="h-3.5 w-3.5 shrink-0 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="invisible absolute left-0 z-50 mt-0 w-56 origin-top -translate-y-1 scale-95 list-none rounded-xl bg-white py-2 text-stone-800 opacity-0 shadow-xl shadow-stone-900/10 pointer-events-none transition-all duration-200 ease-out group-hover:pointer-events-auto group-hover:visible group-hover:translate-y-0 group-hover:scale-100 group-hover:opacity-100 group-focus-within:pointer-events-auto group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:scale-100 group-focus-within:opacity-100">
                            <li><a href="{{ route('admissions') }}" class="dropdown-link">Admission Procedure</a></li>
                            <li><a href="{{ route('admissions') }}#online-application" class="dropdown-link">Apply Online</a></li>
                            @if($pageVisible('contact'))
                                <li><a href="{{ route('contact') }}" class="dropdown-link">Contact Admissions</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if($showStudentLifeMenu)
                    <li class="group relative">
                        <a href="{{ route('news') }}" class="nav-link flex items-center gap-0.5">
                            <span>Student Life</span>
                            <svg class="h-3.5 w-3.5 shrink-0 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                        <ul class="invisible absolute left-0 z-50 mt-0 w-56 origin-top -translate-y-1 scale-95 list-none rounded-xl bg-white py-2 text-stone-800 opacity-0 shadow-xl shadow-stone-900/10 pointer-events-none transition-all duration-200 ease-out group-hover:pointer-events-auto group-hover:visible group-hover:translate-y-0 group-hover:scale-100 group-hover:opacity-100 group-focus-within:pointer-events-auto group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:scale-100 group-focus-within:opacity-100">
                            <li><a href="{{ route('news') }}" class="dropdown-link">News & Updates</a></li>
                            @if($pageVisible('events'))
                                <li><a href="{{ route('events') }}" class="dropdown-link">Events & Activities</a></li>
                            @endif
                            @if($pageVisible('notices'))
                                <li><a href="{{ route('notices') }}" class="dropdown-link">Notices & Circulars</a></li>
                            @endif
                            @if($pageVisible('results'))
                                <li><a href="{{ route('results') }}" class="dropdown-link">Exam Results</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if($pageVisible('contact'))
                    <li><a href="{{ route('contact') }}" class="nav-link">Contact Us</a></li>
                @endif
            </ul>

            <div class="flex shrink-0 flex-wrap items-center justify-end gap-2">
                @if($showAdmissionsMenu)
                    <a href="{{ route('admissions') }}"
                       class="site-gold-gradient hidden rounded-md px-3 py-2 text-xs font-semibold text-white shadow-md shadow-gold/30 transition hover:shadow-lg hover:shadow-gold/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-brand xl:inline-flex xl:shrink-0 xl:text-sm">Apply Now</a>
                @endif
                <button type="button" id="menuToggle"
                        class="flex items-center gap-2 rounded-lg p-2 xl:hidden focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-brand"
                        aria-expanded="false" aria-controls="mobileMenu" aria-label="Open navigation menu">
                    <div id="hamburger" class="flex h-[18px] w-6 flex-col justify-center gap-1.5" aria-hidden="true">
                        <span id="hb1" class="block h-0.5 w-full origin-center rounded bg-white transition-all duration-300"></span>
                        <span id="hb2" class="block h-0.5 w-full rounded bg-white transition-all duration-300"></span>
                        <span id="hb3" class="block h-0.5 w-full origin-center rounded bg-white transition-all duration-300"></span>
                    </div>
                </button>
            </div>
        </div>

        <nav id="mobileMenu"
             class="absolute left-0 right-0 top-full z-40 hidden max-h-[min(85dvh,36rem)] overflow-y-auto overscroll-contain bg-brand pb-[env(safe-area-inset-bottom,0px)] shadow-lg shadow-stone-900/25 xl:hidden"
             aria-label="Mobile navigation">
            <div class="mx-auto max-w-6xl px-4">
                <ul class="flex list-none flex-col gap-0.5 py-3 text-white">
                    <li><a href="{{ route('home') }}" class="block rounded-lg px-4 py-2.5 text-sm transition-colors hover:bg-white/10">Home</a></li>
                    @if($showAboutMenu)
                        <li><a href="{{ route('about') }}" class="block rounded-lg px-4 py-2.5 text-sm transition-colors hover:bg-white/10">About Us</a></li>
                    @endif
                    @if($showAcademicsMenu)
                        <li><a href="{{ route('programs') }}" class="block rounded-lg px-4 py-2.5 text-sm transition-colors hover:bg-white/10">Academics</a></li>
                    @endif
                    @if($showAdmissionsMenu)
                        <li><a href="{{ route('admissions') }}" class="block rounded-lg px-4 py-2.5 text-sm transition-colors hover:bg-white/10">Admission</a></li>
                    @endif
                    @if($showStudentLifeMenu)
                        <li><a href="{{ route('news') }}" class="block rounded-lg px-4 py-2.5 text-sm transition-colors hover:bg-white/10">Student Life</a></li>
                    @endif
                    <li><a href="{{ route('portal.login') }}" class="block rounded-lg px-4 py-2.5 text-sm transition-colors hover:bg-white/10">Student Portal</a></li>
                    @if($pageVisible('contact'))
                        <li><a href="{{ route('contact') }}" class="block rounded-lg px-4 py-2.5 text-sm transition-colors hover:bg-white/10">Contact Us</a></li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</header>

<main id="main" tabindex="-1"
      class="site-main outline-none [&_h1]:font-display [&_h2]:font-display [&_h3]:font-sans [&_h4]:font-sans [&_h1]:tracking-tight [&_h2]:tracking-tight [&_h3]:tracking-tight [&_h4]:tracking-tight">
    @yield('content')
</main>

<footer class="site-footer px-4 py-14 text-white sm:px-6 sm:py-16">
    <div class="mx-auto grid max-w-6xl gap-10 md:grid-cols-2 lg:grid-cols-4 lg:gap-12">
        <div class="text-left">
            <div class="mb-4 flex items-center gap-2">
                <span class="site-gold-gradient flex h-10 w-10 shrink-0 items-center justify-center rounded-xl shadow-lg shadow-gold/30 text-sm font-black tracking-tighter text-white" aria-hidden="true">{{ $college->short_name ?? 'JDCA' }}</span>
                <span class="font-display text-xl font-semibold">{{ $college->college_name ?? 'Jinnah School & Degree College' }}</span>
            </div>
            <p class="text-sm leading-relaxed text-stone-400">
                {{ $siteFooterAbout }}
            </p>
        </div>
        <div class="text-left">
            <p class="mb-4 font-display text-xl font-semibold text-white">About & Academics</p>
            <ul class="space-y-2.5 text-sm text-stone-400">
                <li><a href="{{ route('home') }}" class="transition hover:text-white">Home</a></li>
                @if($pageVisible('about'))
                    <li><a href="{{ route('about') }}" class="transition hover:text-white">About Us</a></li>
                @endif
                @if($pageVisible('programs'))
                    <li><a href="{{ route('programs') }}" class="transition hover:text-white">Programs</a></li>
                @endif
                @if($pageVisible('faculty'))
                    <li><a href="{{ route('faculty') }}" class="transition hover:text-white">Faculty Profile</a></li>
                @endif
            </ul>
        </div>
        <div class="text-left">
            <p class="mb-4 font-display text-xl font-semibold text-white">Quick Links</p>
            <ul class="space-y-2.5 text-sm text-stone-400">
                @if($pageVisible('admissions'))
                    <li><a href="{{ route('admissions') }}" class="transition hover:text-white">Admissions</a></li>
                @endif
                @if($pageVisible('news'))
                    <li><a href="{{ route('news') }}" class="transition hover:text-white">News & Updates</a></li>
                @endif
                @if($pageVisible('events'))
                    <li><a href="{{ route('events') }}" class="transition hover:text-white">Events & Activities</a></li>
                @endif
                @if($pageVisible('notices'))
                    <li><a href="{{ route('notices') }}" class="transition hover:text-white">Notices</a></li>
                @endif
                @if($pageVisible('results'))
                    <li><a href="{{ route('results') }}" class="transition hover:text-white">Results</a></li>
                @endif
                <li><a href="{{ route('portal.login') }}" class="transition hover:text-white">Student Portal</a></li>
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
</body>
</html>
