@extends('layouts.public')
@section('title', 'About Us — ' . ($college->college_name ?? 'JDCA'))

@section('content')

    <main id="main" tabindex="-1" class="site-main outline-none [&_h1]:font-display [&_h2]:font-display [&_h3]:font-sans [&_h4]:font-sans [&_h1]:tracking-tight [&_h2]:tracking-tight [&_h3]:tracking-tight [&_h4]:tracking-tight">

        <section class="relative overflow-hidden bg-ink pt-28 pb-12 text-white sm:pt-32 sm:pb-14" aria-labelledby="page-title">
            <div class="absolute inset-0 bg-[url('{{ asset('assets/images/photo-1562774053-701939374585.jpg') }}')] bg-cover bg-center opacity-25"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-brand/90 via-ink/95 to-ink"></div>
            <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
                <nav class="mb-4 text-xs text-white/70" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
                    <span class="mx-2 text-white/40" aria-hidden="true">/</span>
                    <span class="text-white">About Us</span>
                </nav>
                <h1 id="page-title" class="font-display text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">{{ $pageContent['intro_title'] ?? ('About ' . ($college->college_name ?? 'JDCA')) }}</h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white/90 sm:text-base">
                    {{ $pageContent['intro_text'] ?? 'A premier college combining board-focused intermediate programmes with modern labs, digital learning, and pathways into Pakistan top universities and professions.' }}
                </p>
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
                <h2 class="mb-8 text-center font-display text-2xl font-semibold text-ink sm:text-3xl">Mission, vision &amp; values</h2>
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="rounded-xl border border-stone-200/80 bg-surface p-6 shadow-md shadow-stone-900/5">
                        <h3 class="mb-2 text-base font-semibold text-brand">Mission</h3>
                        <p class="text-sm leading-relaxed text-stone-600">To deliver affordable, inclusive education that strengthens Pakistan’s youth with knowledge, ethics, and skills aligned with national curriculum goals and global opportunity.</p>
                    </div>
                    <div class="rounded-xl border border-stone-200/80 bg-surface p-6 shadow-md shadow-stone-900/5">
                        <h3 class="mb-2 text-base font-semibold text-brand">Vision</h3>
                        <p class="text-sm leading-relaxed text-stone-600">To be recognised as a leading college where every student is known, supported, and challenged to reach their potential.</p>
                    </div>
                    <div class="rounded-xl border border-stone-200/80 bg-surface p-6 shadow-md shadow-stone-900/5">
                        <h3 class="mb-2 text-base font-semibold text-brand">Values</h3>
                        <p class="text-sm leading-relaxed text-stone-600">Integrity, respect, curiosity, responsibility, and service to community guide everything we do on campus.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 md:py-16">
            <div class="mx-auto grid max-w-6xl items-start gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-14">
                <div>
                    <h2 class="mb-4 font-display text-2xl font-semibold text-ink sm:text-3xl">Our story</h2>
                    <p class="mb-4 text-sm leading-relaxed text-stone-600 sm:text-base">
                        Founded to serve families in <strong class="text-ink">{{ $college->city ?? 'Astore' }} and surrounding districts</strong>, {{ $college->college_name ?? 'JDCA' }} grew from a single campus into a recognised name for disciplined study, qualified faculty, and transparent admissions.
                    </p>
                    <p class="text-sm leading-relaxed text-stone-600 sm:text-base">
                        We are registered with the <strong class="text-ink">{{ $college->affiliation ?? 'Karakoram International University' }}</strong> for intermediate streams; any <strong class="text-ink">BS or ADP</strong> programme is offered only under valid affiliation and HEC approval.
                    </p>
                </div>
                <div class="overflow-hidden rounded-xl border border-stone-200/80 shadow-lg shadow-stone-900/10">
                    <img src="{{ asset('assets/images/photo-1523050854058-8df90110c9f1.jpg') }}" alt="Graduates celebrating on campus" class="h-56 w-full object-cover sm:h-72 lg:h-full lg:min-h-[280px]" width="900" height="600" loading="lazy" decoding="async" />
                </div>
            </div>
        </section>

        <section class="border-y border-stone-200/80 bg-white py-12 md:py-16">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <h2 class="mb-8 font-display text-2xl font-semibold text-ink sm:text-3xl">Leadership</h2>
                <div class="flex max-w-xl flex-col gap-6 rounded-xl border border-stone-200/80 bg-surface p-6 shadow-md sm:flex-row sm:items-center sm:p-8">
                    <img src="{{ asset('assets/images/photo-1573496359142-b8d87734a5a2.jpg') }}" alt="Principal portrait" class="h-28 w-28 shrink-0 rounded-lg object-cover sm:h-32 sm:w-32" width="200" height="200" loading="lazy" decoding="async" />
                    <div>
                        <p class="font-display text-lg font-semibold text-ink">{{ $college->principal_name ?? 'Principal Name' }}</p>
                        <p class="text-sm font-medium text-brand">Principal</p>
                        <p class="mt-2 text-sm leading-relaxed text-stone-600">Oversees academic quality, student welfare, liaison with boards and universities, and implementation of national student safety policies on campus.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 md:py-16">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl bg-brand px-5 py-6 text-center text-white shadow-lg shadow-brand/20">
                        <p class="font-display text-3xl font-semibold sm:text-4xl">{{ number_format($stats['students'] ?? 1500) }}+</p>
                        <p class="mt-1 text-xs text-white/90 sm:text-sm">Learners supported</p>
                    </div>
                    <div class="rounded-xl border border-stone-200/80 bg-white px-5 py-6 text-center shadow-md">
                        <p class="font-display text-3xl font-semibold text-brand sm:text-4xl">98%</p>
                        <p class="mt-1 text-xs text-stone-600 sm:text-sm">Completion / progression</p>
                    </div>
                    <div class="rounded-xl border border-stone-200/80 bg-white px-5 py-6 text-center shadow-md">
                        <p class="font-display text-3xl font-semibold text-brand sm:text-4xl">{{ number_format($stats['teachers'] ?? 50) }}+</p>
                        <p class="mt-1 text-xs text-stone-600 sm:text-sm">Faculty &amp; staff</p>
                    </div>
                    <div class="rounded-xl border border-stone-200/80 bg-white px-5 py-6 text-center shadow-md">
                        <p class="font-display text-3xl font-semibold text-brand sm:text-4xl">{{ date('Y') - ($college->established_year ?? 2010) }}+</p>
                        <p class="mt-1 text-xs text-stone-600 sm:text-sm">Years of excellence</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-t border-stone-200/80 bg-white py-12 md:py-16">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <h2 class="mb-8 text-center font-display text-2xl font-semibold text-ink sm:text-3xl">Why families choose us</h2>
                <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <li class="flex gap-3 rounded-xl border border-stone-200/60 bg-surface p-4 transition hover:border-brand/30 hover:shadow-md">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-brand/10 text-sm font-bold text-brand">1</span>
                        <div><p class="font-semibold text-ink">Experienced faculty</p><p class="mt-1 text-sm text-stone-600">Subject specialists and regular professional development.</p></div>
                    </li>
                    <li class="flex gap-3 rounded-xl border border-stone-200/60 bg-surface p-4 transition hover:border-brand/30 hover:shadow-md">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-brand/10 text-sm font-bold text-brand">2</span>
                        <div><p class="font-semibold text-ink">Clear pathways</p><p class="mt-1 text-sm text-stone-600">Guidance for board exams, entry tests, and university applications.</p></div>
                    </li>
                    <li class="flex gap-3 rounded-xl border border-stone-200/60 bg-surface p-4 transition hover:border-brand/30 hover:shadow-md">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-brand/10 text-sm font-bold text-brand">3</span>
                        <div><p class="font-semibold text-ink">Safe campus</p><p class="mt-1 text-sm text-stone-600">Structured supervision, counselling, and student support services.</p></div>
                    </li>
                    <li class="flex gap-3 rounded-xl border border-stone-200/60 bg-surface p-4 transition hover:border-brand/30 hover:shadow-md">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-brand/10 text-sm font-bold text-brand">4</span>
                        <div><p class="font-semibold text-ink">Modern facilities</p><p class="mt-1 text-sm text-stone-600">Labs, library, sports, and digital learning resources.</p></div>
                    </li>
                    <li class="flex gap-3 rounded-xl border border-stone-200/60 bg-surface p-4 transition hover:border-brand/30 hover:shadow-md">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-brand/10 text-sm font-bold text-brand">5</span>
                        <div><p class="font-semibold text-ink">Co-curricular depth</p><p class="mt-1 text-sm text-stone-600">Clubs, debates, sports, and leadership opportunities.</p></div>
                    </li>
                    <li class="flex gap-3 rounded-xl border border-stone-200/60 bg-surface p-4 transition hover:border-brand/30 hover:shadow-md">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-brand/10 text-sm font-bold text-brand">6</span>
                        <div><p class="font-semibold text-ink">Transparent admissions</p><p class="mt-1 text-sm text-stone-600">Published criteria, timelines, and merit processes.</p></div>
                    </li>
                </ul>
            </div>
        </section>

        <section class="border-t border-stone-200/80 bg-surface py-12 md:py-16" aria-labelledby="at-a-glance-heading">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <h2 id="at-a-glance-heading" class="mb-8 text-center font-display text-2xl font-semibold text-ink sm:text-3xl">At a glance</h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl border border-stone-200/80 bg-white p-6 text-center shadow-md">
                        <p class="font-display text-3xl font-bold text-brand">{{ number_format($stats['students'] ?? 1500) }}+</p>
                        <p class="mt-1 text-sm font-medium text-ink">Students on roll</p>
                        <p class="mt-2 text-xs text-stone-500">Intermediate &amp; undergraduate cohorts</p>
                    </div>
                    <div class="rounded-xl border border-stone-200/80 bg-white p-6 text-center shadow-md">
                        <p class="font-display text-3xl font-bold text-brand">{{ number_format($stats['teachers'] ?? 50) }}+</p>
                        <p class="mt-1 text-sm font-medium text-ink">Teaching faculty</p>
                        <p class="mt-2 text-xs text-stone-500">Subject leads &amp; lab demonstrators</p>
                    </div>
                    <div class="rounded-xl border border-stone-200/80 bg-white p-6 text-center shadow-md">
                        <p class="font-display text-3xl font-bold text-brand">{{ date('Y') - ($college->established_year ?? 2010) }}</p>
                        <p class="mt-1 text-sm font-medium text-ink">Years of service</p>
                        <p class="mt-2 text-xs text-stone-500">Founded {{ $college->established_year ?? 2010 }}</p>
                    </div>
                    <div class="rounded-xl border border-stone-200/80 bg-white p-6 text-center shadow-md">
                        <p class="font-display text-3xl font-bold text-brand">{{ number_format($stats['programs'] ?? 10) }}</p>
                        <p class="mt-1 text-sm font-medium text-ink">Academic Programs</p>
                        <p class="mt-2 text-xs text-stone-500">Intermediate &amp; BS Programs</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-brand py-12 text-center text-white md:py-14">
            <div class="mx-auto max-w-2xl px-4 sm:px-6">
                <h2 class="font-display text-2xl font-semibold sm:text-3xl">Ready to apply?</h2>
                <p class="mt-3 text-sm text-white/90 sm:text-base">View intake dates, fees, and how to submit your application.</p>
                <div class="mt-6 flex flex-col items-center justify-center gap-3 sm:flex-row sm:gap-4">
                    <a href="{{ route('admissions') }}#online-application" class="inline-flex items-center justify-center rounded-md bg-white px-7 py-3 text-sm font-semibold text-brand shadow-lg transition hover:bg-stone-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-brand">Apply online</a>
                    <a href="{{ route('admissions') }}" class="inline-flex items-center justify-center rounded-md border-2 border-white px-7 py-3 text-sm font-semibold text-white transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-brand">Admissions info</a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-md border-2 border-white px-7 py-3 text-sm font-semibold text-white transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-brand">Contact us</a>
                </div>
            </div>
        </section>

    </main>

@endsection
