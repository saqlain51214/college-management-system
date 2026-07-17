@extends('layouts.public')
@section('title', 'Campus Facilities')

@section('content')
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <a href="{{ route('departments') }}" class="hover:text-white">Academics</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">Campus Facilities</span>
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Campus Facilities</h1>
            <p class="text-white/70 text-sm mt-1">Modern facilities supporting student learning and campus life</p>
        </div>
    </div>
</div>

<div class="mx-auto max-w-6xl px-4 sm:px-6 py-12">
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach([
            ['Classrooms','Modern, well-ventilated classrooms equipped for effective teaching with proper seating and boards.','M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
            ['Library','A growing collection of academic books, journals, and reference materials for students and faculty.','M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['Computer Lab','ICT-equipped computer laboratory to support digital learning and technology education.','M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
            ['Administrative Block','Dedicated administrative offices for student services, registration, and college management.','M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
            ['Sports Area','Open sports grounds supporting physical education and extracurricular activities.','M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['Prayer Area','A dedicated space for daily prayers, supporting the spiritual well-being of our community.','M12 2a10 10 0 100 20A10 10 0 0012 2zm0 0v4m0 12v-4M4.22 4.22l2.83 2.83m9.9 9.9l2.83 2.83M2 12h4m12 0h4M4.22 19.78l2.83-2.83m9.9-9.9l2.83-2.83'],
            ['Canteen','Student canteen providing affordable meals and refreshments during college hours.','M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
            ['Safe Environment','A safe, inclusive campus environment with security measures for student welfare.','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
            ['Wi-Fi Campus','Internet connectivity available on campus to support research and online learning.','M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0'],
        ] as [$title, $desc, $icon])
        <div class="rounded-2xl border border-stone-200 bg-white p-6 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 shadow-sm" style="background:var(--site-brand)">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $icon }}"/></svg>
            </div>
            <h3 class="font-bold text-stone-800 mb-2">{{ $title }}</h3>
            <p class="text-sm text-stone-600 leading-relaxed">{{ $desc }}</p>
        </div>
        @endforeach
    </div>

    <div class="mt-12 rounded-2xl p-8 text-center" style="background:var(--site-brand)">
        <h2 class="text-white font-bold text-xl mb-2">Visit Our Campus</h2>
        <p class="text-white/80 text-sm max-w-lg mx-auto mb-5">We welcome prospective students and parents to visit JDCA. Schedule a campus visit to experience our facilities firsthand.</p>
        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-semibold bg-white transition hover:bg-stone-100" style="color:var(--site-brand)">
            Contact Us to Arrange a Visit
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</div>
@endsection
