@extends('layouts.public')
@section('title', 'Message from the Director')
@section('content')
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <a href="{{ route('about') }}" class="hover:text-white">About Us</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">Message from Director</span>
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Message from the Director</h1>
        </div>
    </div>
</div>
<div class="mx-auto max-w-4xl px-4 sm:px-6 py-12">
    <div class="grid lg:grid-cols-3 gap-10 items-start">
        <aside class="lg:col-span-1">
            <div class="rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden text-center">
                <div class="site-brand-gradient py-6 px-4">
                    <div class="w-24 h-24 rounded-full mx-auto flex items-center justify-center text-3xl font-black text-white shadow-lg" style="background:rgba(255,255,255,0.2)">D</div>
                </div>
                <div class="p-5">
                    <h2 class="font-bold text-stone-800 text-lg">Director, JDCA</h2>
                    <p class="text-sm text-stone-500 mt-0.5">Director of Colleges</p>
                    <p class="text-sm text-stone-500">Gilgit-Baltistan Education Dept.</p>
                </div>
            </div>
        </aside>
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold text-stone-800 font-display mb-6">Message from the Director</h2>
            <div class="prose prose-stone max-w-none text-stone-700 leading-relaxed space-y-5">
                <p>On behalf of the Directorate of Colleges, Gilgit-Baltistan, I extend my warmest greetings to the students, faculty, and community of Jinnah Degree College Astore.</p>
                <p>The Government of Gilgit-Baltistan is deeply committed to expanding access to quality higher education in all districts, including the remote valleys of Astore. JDCA represents our vision of bringing world-class education to the doorstep of every deserving student in the region.</p>
                <p>I call upon all students to seize the opportunities provided at JDCA and work diligently toward building a knowledge-based future. The faculty and administration are here to support you at every step of your academic journey.</p>
                <p>I congratulate the college leadership on their continued efforts and look forward to seeing JDCA grow as a center of excellence in the years to come.</p>
            </div>
            <div class="mt-8 pt-6 border-t border-stone-200">
                <p class="font-bold text-stone-800 text-lg">Director of Colleges</p>
                <p class="text-stone-500 text-sm">Directorate of Colleges, Gilgit-Baltistan</p>
            </div>
        </div>
    </div>
</div>
@endsection
