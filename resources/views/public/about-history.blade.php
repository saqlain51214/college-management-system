@extends('layouts.public')
@section('title', 'History & Geography')

@section('content')
@php $customBody = !empty($cmsPage) ? $cmsPage->customBodyHtml() : null; @endphp
@if($customBody)
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1"><a href="{{ route('home') }}" class="hover:text-white">Home</a><span class="mx-1.5">›</span><span class="text-white/90">{{ $cmsPage->title }}</span></p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $cmsPage->title }}</h1>
        </div>
    </div>
</div>
<div class="mx-auto max-w-4xl px-4 sm:px-6 py-12"><div class="prose-content max-w-none">{!! $customBody !!}</div></div>
@else
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <a href="{{ route('about') }}" class="hover:text-white">About Us</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">History &amp; Geography</span>
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">History &amp; Geography</h1>
        </div>
    </div>
</div>

<div class="mx-auto max-w-5xl px-4 sm:px-6 py-12">
    <div class="grid lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 space-y-8">
            <section>
                <h2 class="text-xl font-bold text-stone-800 pb-2 mb-4 border-b-2" style="border-color:var(--site-brand)">Our History</h2>
                <div class="prose prose-stone max-w-none text-stone-700 leading-relaxed space-y-4">
                    <p>
                        Jinnah Degree College Astore (JDCA) was established with the vision of providing quality education to the people of Astore District, one of the most scenic yet educationally underserved regions of Gilgit-Baltistan, Pakistan. The college is affiliated with Karakoram International University (KIU), Gilgit, and offers programs in education and related disciplines.
                    </p>
                    <p>
                        Since its inception, JDCA has grown from a modest institution to a recognized centre of learning in the Astore Valley, committed to nurturing the next generation of educators, leaders, and professionals from the region.
                    </p>
                    <p>
                        The college stands as a beacon of hope for students who previously had to travel far to access higher education. By bringing quality education to their doorstep, JDCA has transformed educational access in the district.
                    </p>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold text-stone-800 pb-2 mb-4 border-b-2" style="border-color:var(--site-brand)">Geography &amp; Location</h2>
                <div class="prose prose-stone max-w-none text-stone-700 leading-relaxed space-y-4">
                    <p>
                        Astore is a district in the Gilgit-Baltistan region of northern Pakistan. Situated at the crossroads of the Karakoram and Himalayan mountain ranges, Astore is renowned for its breathtaking natural beauty — towering peaks, lush valleys, glaciers, and clear rivers.
                    </p>
                    <p>
                        The college is located in <strong>Eidgah, Astore</strong>, at the heart of the Astore Valley. The district borders Gilgit District to the north, Diamer District to the west, and Azad Kashmir to the south. Nanga Parbat — the world's ninth-highest mountain — towers over the region, making Astore a unique place where natural grandeur meets academic aspiration.
                    </p>
                    <p>
                        The region's population, primarily Shina-speaking, has a strong tradition of valuing education as a pathway to social and economic development.
                    </p>
                </div>
            </section>
        </div>

        <aside class="space-y-6">
            <div class="rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden">
                <div class="site-brand-gradient py-3 px-4">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wide">Quick Facts</h3>
                </div>
                <ul class="divide-y divide-stone-100 text-sm">
                    <li class="px-4 py-3 flex justify-between"><span class="text-stone-500">Location</span><span class="font-medium text-stone-800 text-right">Eidgah, Astore, GB</span></li>
                    <li class="px-4 py-3 flex justify-between"><span class="text-stone-500">Affiliation</span><span class="font-medium text-stone-800 text-right">KIU, Gilgit</span></li>
                    <li class="px-4 py-3 flex justify-between"><span class="text-stone-500">District</span><span class="font-medium text-stone-800">Astore</span></li>
                    <li class="px-4 py-3 flex justify-between"><span class="text-stone-500">Province</span><span class="font-medium text-stone-800">Gilgit-Baltistan</span></li>
                    <li class="px-4 py-3 flex justify-between"><span class="text-stone-500">Principal</span><span class="font-medium text-stone-800">{{ $college->principal }}</span></li>
                </ul>
            </div>

            <div class="rounded-2xl border border-stone-200 bg-blue-50 p-5">
                <h3 class="font-bold text-stone-800 mb-3">Explore More</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('about.mission') }}" class="flex items-center gap-2 hover:site-brand-text transition-colors" style="color:var(--site-brand)"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Mission &amp; Vision</a></li>
                    <li><a href="{{ route('about.message') }}" class="flex items-center gap-2 hover:site-brand-text transition-colors" style="color:var(--site-brand)"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Message from Principal</a></li>
                    <li><a href="{{ route('departments') }}" class="flex items-center gap-2 hover:site-brand-text transition-colors" style="color:var(--site-brand)"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Departments</a></li>
                </ul>
            </div>
        </aside>
    </div>
</div>
@endif
@endsection
