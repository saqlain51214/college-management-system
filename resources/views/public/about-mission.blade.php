@extends('layouts.public')
@section('title', 'Mission & Vision')

@section('content')
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <a href="{{ route('about') }}" class="hover:text-white">About Us</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">Mission &amp; Vision</span>
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Mission &amp; Vision</h1>
        </div>
    </div>
</div>

<div class="mx-auto max-w-5xl px-4 sm:px-6 py-12 space-y-12">

    {{-- Vision --}}
    <section class="grid lg:grid-cols-2 gap-8 items-center">
        <div>
            <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold mb-4 text-white" style="background:var(--site-brand)">Our Vision</div>
            <h2 class="text-2xl font-bold text-stone-800 mb-4 font-display">Vision Statement</h2>
            <p class="text-stone-700 leading-relaxed text-base">
                To be a leading institution of higher education in Gilgit-Baltistan that empowers students from the Astore region with quality education, professional skills, and moral values — enabling them to contribute meaningfully to the development of society and the nation.
            </p>
        </div>
        <div class="rounded-2xl p-8 flex flex-col items-center text-center" style="background:var(--site-brand)">
            <svg class="w-16 h-16 text-white/80 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <p class="text-white font-display text-xl font-semibold">"Empowering Minds, Building Futures"</p>
            <p class="text-white/70 text-sm mt-2">in Gilgit-Baltistan</p>
        </div>
    </section>

    <hr class="border-stone-200">

    {{-- Mission --}}
    <section class="grid lg:grid-cols-2 gap-8 items-center">
        <div class="rounded-2xl p-8 flex flex-col items-center text-center order-2 lg:order-1" style="background:var(--site-gold)">
            <svg class="w-16 h-16 text-white/80 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            <p class="text-white font-display text-xl font-semibold">"Excellence Through Education"</p>
            <p class="text-white/80 text-sm mt-2">Service to community and nation</p>
        </div>
        <div class="order-1 lg:order-2">
            <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold mb-4 text-white" style="background:var(--site-gold)">Our Mission</div>
            <h2 class="text-2xl font-bold text-stone-800 mb-4 font-display">Mission Statement</h2>
            <p class="text-stone-700 leading-relaxed text-base">
                To provide accessible, high-quality education through competent teaching, modern facilities, and a nurturing environment — fostering critical thinking, ethical values, and a commitment to lifelong learning among students of Astore and surrounding areas.
            </p>
        </div>
    </section>

    <hr class="border-stone-200">

    {{-- Core Values --}}
    <section>
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-stone-800 font-display">Our Core Values</h2>
            <p class="text-stone-500 mt-1">The principles that guide everything we do</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach([
                ['Excellence','We pursue the highest standards in teaching, learning, and administration.','M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['Integrity','We uphold honesty, transparency, and ethical conduct in all our activities.','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                ['Inclusion','We welcome students from all backgrounds and provide equal opportunities.','M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['Innovation','We embrace new teaching methods and technologies to improve learning outcomes.','M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
                ['Service','We are committed to serving the educational needs of the Astore community.','M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                ['Respect','We foster mutual respect among students, faculty, and staff.','M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5'],
            ] as [$title, $desc, $icon])
            <div class="rounded-2xl border border-stone-200 bg-white p-5 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:var(--site-brand)">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                </div>
                <h3 class="font-bold text-stone-800 mb-1">{{ $title }}</h3>
                <p class="text-sm text-stone-600">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </section>

</div>
@endsection
