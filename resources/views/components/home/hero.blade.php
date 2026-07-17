@php
    $heroSlides = $pageContent['hero']['slides'] ?? [];
    $panelItems = collect();
    foreach(($notices ?? []) as $n) {
        $panelItems->push(['type'=>'notice','title'=>$n->title,'date'=>$n->publish_date?\Carbon\Carbon::parse($n->publish_date)->format('d M'):'']);
    }
    foreach(($events ?? []) as $e) {
        $panelItems->push(['type'=>'event','title'=>$e->title,'date'=>optional($e->start_datetime)->format('d M')??'']);
    }
    $panelItems = $panelItems->take(9);
@endphp

<div class="flex w-full overflow-hidden"
     style="height:calc(100vh - var(--site-header-offset,6rem)); min-height:520px; max-height:880px;"
     x-data="heroSlider()"
     x-init="startAutoPlay()"
     @mouseenter="stopAutoPlay()"
     @mouseleave="startAutoPlay()">

    {{-- ══ SLIDER — flex-1, image fills this area only ══ --}}
    <div class="relative flex-1 overflow-hidden">

        {{-- Slide images --}}
        <template x-for="(s,i) in slides" :key="'img'+i">
            <img :src="s.image" :alt="s.alt"
                 class="absolute inset-0 h-full w-full object-cover transition-opacity duration-1000"
                 style="object-position:center center"
                 :class="activeSlide===i?'opacity-100':'opacity-0'"
                 :fetchpriority="i===0?'high':'auto'">
        </template>

        {{-- Gradient — left text readable, right image visible --}}
        <div class="absolute inset-0 pointer-events-none"
             style="background:linear-gradient(to right,rgba(0,0,0,.62) 0%,rgba(0,0,0,.28) 55%,rgba(0,0,0,.04) 100%)"></div>
        <div class="absolute inset-0 pointer-events-none"
             style="background:linear-gradient(to top,rgba(0,0,0,.45) 0%,transparent 38%)"></div>

        {{-- Slide text content --}}
        <div class="absolute inset-0 flex flex-col justify-end px-4 pb-8 sm:px-7 sm:pb-10 lg:px-12 lg:pb-12 xl:px-14">

            {{-- Slides text --}}
            <div>
                <template x-for="(s,i) in slides" :key="'txt'+i">
                    <div :class="activeSlide===i?'opacity-100 translate-y-0':'opacity-0 translate-y-4 pointer-events-none absolute'"
                         class="transition-all duration-700">
                        <h1 class="mb-3 font-display font-bold text-white leading-[1.2] max-w-xl"
                            style="font-size:clamp(1.6rem,3.5vw,2.9rem); text-shadow:0 2px 20px rgba(0,0,0,.5)"
                            x-html="s.title"></h1>
                        <p class="mb-6 text-white/70 leading-relaxed max-w-lg text-sm sm:text-[.92rem] line-clamp-2"
                           x-text="s.description"></p>
                        <div class="flex flex-wrap gap-3">
                            <template x-if="s.primaryBtnText && s.primaryBtnLink && s.primaryBtnLink!=='#'">
                                <a :href="s.primaryBtnLink"
                                   class="rounded-lg px-5 py-2.5 text-sm font-bold text-white transition hover:opacity-90"
                                   style="background:var(--site-brand)"
                                   x-text="s.primaryBtnText"></a>
                            </template>
                            <template x-if="s.secondaryBtnText && s.secondaryBtnLink && s.secondaryBtnLink!=='#'">
                                <a :href="s.secondaryBtnLink"
                                   class="rounded-lg border border-white/30 bg-white/10 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-white/20"
                                   x-text="s.secondaryBtnText"></a>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Controls --}}
            <div class="flex items-center gap-2.5 mt-5">
                <button @click="prev()" class="h-8 w-8 flex items-center justify-center rounded-full bg-white/10 border border-white/20 text-white hover:bg-white/20 transition" aria-label="Prev">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div class="flex gap-1.5">
                    <template x-for="(_,i) in slides" :key="'d'+i">
                        <button @click="goToSlide(i)"
                                :class="activeSlide===i?'w-6 bg-white':'w-2 bg-white/35 hover:bg-white/60'"
                                class="h-1.5 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
                <button @click="next()" class="h-8 w-8 flex items-center justify-center rounded-full bg-white/10 border border-white/20 text-white hover:bg-white/20 transition" aria-label="Next">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ══ MOBILE LATEST UPDATES — horizontal scroll strip, hidden on lg+ ══ --}}
    @if($panelItems->isNotEmpty())
    <div class="absolute bottom-0 left-0 right-0 z-10 lg:hidden"
         style="background:linear-gradient(to top, rgba(0,0,0,.82) 0%, rgba(0,0,0,.55) 100%)">
        <div class="flex items-center gap-0 overflow-x-auto px-3 py-2.5"
             style="scrollbar-width:none; -ms-overflow-style:none;">
            <span class="shrink-0 flex items-center gap-1.5 pr-3 mr-2 border-r border-white/15">
                <span class="relative flex h-1.5 w-1.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-red-500"></span>
                </span>
                <span class="text-[9px] font-bold uppercase tracking-[.14em] text-white/45 whitespace-nowrap">Updates</span>
            </span>
            <div class="flex gap-1.5">
                @foreach($panelItems as $item)
                <a href="{{ $item['type']==='event' ? route('events') : route('notices') }}"
                   class="shrink-0 flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-medium text-white/80 hover:text-white transition-colors whitespace-nowrap"
                   style="{{ $item['type']==='event' ? 'background:rgba(196,151,58,.22)' : 'background:rgba(107,45,57,.35)' }}">
                    @if($item['date'])<span class="text-white/35 text-[9px] mr-0.5">{{ $item['date'] }}</span>@endif
                    {{ \Illuminate\Support\Str::limit($item['title'], 32) }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ══ LATEST UPDATES PANEL — fixed width, solid dark ══ --}}
    <aside class="hidden lg:flex w-72 xl:w-80 shrink-0 flex-col"
           style="background:#111; border-left:1px solid rgba(255,255,255,.08);">

        {{-- Header --}}
        <div class="shrink-0 px-5 pt-5 pb-4">
            <div class="flex items-center gap-2 mb-1">
                <span class="relative flex h-2 w-2 shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background:#f87171"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-red-500"></span>
                </span>
                <span class="text-[11px] font-bold uppercase tracking-[.16em] text-white/50">Latest Updates</span>
            </div>
            <div class="h-px w-full mt-3" style="background:rgba(255,255,255,.07)"></div>
        </div>

        {{-- Items list --}}
        <div class="flex-1 overflow-y-auto px-3 space-y-1 pb-3"
             style="scrollbar-width:thin;scrollbar-color:#333 transparent;">
            @forelse($panelItems as $item)
            <a href="{{ $item['type']==='event'?route('events'):route('notices') }}"
               class="group flex gap-3 rounded-lg px-3 py-3 transition hover:bg-white/[.06] items-start">
                {{-- Icon --}}
                <div class="shrink-0 mt-0.5 h-7 w-7 rounded-md flex items-center justify-center"
                     style="{{ $item['type']==='event'?'background:rgba(196,151,58,.15)':'background:rgba(107,45,57,.25)' }}">
                    @if($item['type']==='event')
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--site-gold)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    @else
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--site-brand)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @endif
                </div>
                {{-- Text --}}
                <div class="min-w-0 flex-1">
                    <p class="text-[12px] font-medium leading-snug text-white/65 line-clamp-2 group-hover:text-white/90 transition">
                        {{ $item['title'] }}
                    </p>
                    @if($item['date'])
                    <span class="mt-1 block text-[10px] text-white/28">{{ $item['date'] }}</span>
                    @endif
                </div>
            </a>
            @empty
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <p class="text-xs text-white/20">No updates yet</p>
            </div>
            @endforelse
        </div>

        {{-- Apply button --}}
        <div class="shrink-0 p-4 border-t" style="border-color:rgba(255,255,255,.07)">
            <a href="{{ route('admissions') }}"
               class="flex w-full items-center justify-center gap-2 rounded-xl py-3 text-[12.5px] font-bold text-white transition hover:opacity-90"
               style="background:var(--site-brand)">
                Apply for Admission
            </a>
        </div>
    </aside>
</div>

<script>
document.addEventListener('alpine:init',()=>{
    Alpine.data('heroSlider',()=>({
        activeSlide:0, _t:null,
        slides:[
            @foreach($heroSlides as $slide)
            {
                image:"{{ str_starts_with($slide['image']??'','assets/') ? asset($slide['image']) : \Illuminate\Support\Facades\Storage::url($slide['image']??'') }}",
                alt:"{{ strip_tags($slide['title']??'JDCA') }}",
                title:"{!! addslashes($slide['title']??'') !!}",
                description:"{{ addslashes($slide['description']??'') }}",
                primaryBtnText:"{{ $slide['primary_btn_text']??'' }}",
                primaryBtnLink:"{{ !empty($slide['primary_btn_link'])&&\Illuminate\Support\Facades\Route::has($slide['primary_btn_link'])?route($slide['primary_btn_link']):'#' }}",
                secondaryBtnText:"{{ $slide['secondary_btn_text']??'' }}",
                secondaryBtnLink:"{{ !empty($slide['secondary_btn_link'])&&\Illuminate\Support\Facades\Route::has($slide['secondary_btn_link'])?route($slide['secondary_btn_link']):'#' }}"
            }@if(!$loop->last),@endif
            @endforeach
        ],
        startAutoPlay(){this.stopAutoPlay();this._t=setInterval(()=>this.next(),6000);},
        stopAutoPlay(){if(this._t){clearInterval(this._t);this._t=null;}},
        next(){this.activeSlide=(this.activeSlide+1)%this.slides.length;},
        prev(){this.activeSlide=(this.activeSlide-1+this.slides.length)%this.slides.length;},
        goToSlide(i){this.activeSlide=i;this.startAutoPlay();}
    }));
});
</script>
