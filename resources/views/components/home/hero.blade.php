@php
    $heroSlides = $pageContent['hero']['slides'] ?? [];
    // Fallback: if no slides are configured, show a bundled default slide so the
    // hero is never empty (also survives Railway's ephemeral uploads).
    if (empty($heroSlides)) {
        $collegeName = $college->college_name ?? 'Jinnah Degree College Astore';
        $heroSlides = [
            ['image' => 'assets/images/default/slider-1.jpeg', 'title' => $collegeName, 'description' => 'Empowering students in Astore, Gilgit-Baltistan.', 'primary_btn_text' => 'Apply for Admission', 'primary_btn_link' => 'admissions', 'secondary_btn_text' => 'Explore Programs', 'secondary_btn_link' => 'programs'],
            ['image' => 'assets/images/default/slider-2.jpeg', 'title' => 'Quality Education, Bright Futures', 'description' => 'Affiliated with Karakoram International University (KIU).', 'primary_btn_text' => 'Our Programs', 'primary_btn_link' => 'programs', 'secondary_btn_text' => 'Departments', 'secondary_btn_link' => 'departments'],
            ['image' => 'assets/images/default/slider-3.jpeg', 'title' => 'Join Our Community', 'description' => 'Admissions open for Intermediate, ADP and BS programmes.', 'primary_btn_text' => 'Apply Now', 'primary_btn_link' => 'admissions', 'secondary_btn_text' => 'Contact Us', 'secondary_btn_link' => 'contact'],
        ];
    }
    $panelItems = collect();
    foreach(($notices ?? []) as $n) {
        $panelItems->push(['type'=>'notice','title'=>$n->title,'date'=>$n->publish_date?\Carbon\Carbon::parse($n->publish_date)->format('d M'):'']);
    }
    foreach(($events ?? []) as $e) {
        $panelItems->push(['type'=>'event','title'=>$e->title,'date'=>optional($e->start_datetime)->format('d M')??'']);
    }
    $panelItems = $panelItems->take(9);
@endphp

<style>
@keyframes jdca-mq-up   { from { transform: translateY(0); } to { transform: translateY(-50%); } }
@keyframes jdca-mq-left { from { transform: translateX(0); } to { transform: translateX(-50%); } }
.jdca-vtrack { animation: jdca-mq-up var(--vdur, 30s) linear infinite; }
.jdca-htrack { animation: jdca-mq-left var(--hdur, 40s) linear infinite; will-change: transform; }
.jdca-mq:hover .jdca-vtrack, .jdca-mq:hover .jdca-htrack { animation-play-state: paused; }
@media (prefers-reduced-motion: reduce) { .jdca-vtrack, .jdca-htrack { animation: none; } }
</style>

<div class="flex w-full overflow-hidden
            h-[58dvh] min-h-[400px] max-h-[540px]
            lg:h-[calc(100dvh_-_var(--site-header-offset,6rem))] lg:min-h-[520px] lg:max-h-[880px]"
     x-data="heroSlider()"
     x-init="startAutoPlay()"
     @mouseenter="stopAutoPlay()"
     @mouseleave="startAutoPlay()"
     @touchstart.passive="onTouchStart($event)"
     @touchend="onTouchEnd($event)">

    {{-- ══ SLIDER — flex-1, image fills this area only ══ --}}
    <div class="relative flex-1 overflow-hidden">

        {{-- Slide images --}}
        <template x-for="(s,i) in slides" :key="'img'+i">
            <img :src="s.image" :alt="s.alt"
                 class="absolute inset-0 h-full w-full object-cover transition-opacity duration-1000"
                 style="object-position:center center"
                 :class="activeSlide===i?'opacity-100':'opacity-0'"
                 :fetchpriority="i===0?'high':'auto'"
                 onerror="this.onerror=null;this.src='{{ asset('assets/images/default/slider-1.jpeg') }}'">
        </template>

        {{-- Gradient — left text readable, right image visible --}}
        <div class="absolute inset-0 pointer-events-none"
             style="background:linear-gradient(to right,rgba(0,0,0,.62) 0%,rgba(0,0,0,.28) 55%,rgba(0,0,0,.04) 100%)"></div>
        <div class="absolute inset-0 pointer-events-none"
             style="background:linear-gradient(to top,rgba(0,0,0,.45) 0%,transparent 38%)"></div>

        {{-- Slide text content --}}
        <div class="absolute inset-0 flex flex-col justify-end px-4 pb-20 sm:px-7 lg:px-12 lg:pb-12 xl:px-14">

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

            {{-- Controls (larger tap targets on mobile) --}}
            <div class="flex items-center gap-2.5 mt-5">
                <button @click="prev()" class="h-11 w-11 sm:h-9 sm:w-9 flex items-center justify-center rounded-full bg-white/10 border border-white/20 text-white hover:bg-white/20 transition" aria-label="Previous slide">
                    <svg class="h-4 w-4 sm:h-3.5 sm:w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div class="flex items-center">
                    <template x-for="(_,i) in slides" :key="'d'+i">
                        <button @click="goToSlide(i)" class="flex items-center justify-center px-1.5 py-3 -my-1" :aria-label="'Go to slide '+(i+1)">
                            <span :class="activeSlide===i?'w-6 bg-white':'w-2 bg-white/35 hover:bg-white/60'"
                                  class="block h-1.5 rounded-full transition-all duration-300"></span>
                        </button>
                    </template>
                </div>
                <button @click="next()" class="h-11 w-11 sm:h-9 sm:w-9 flex items-center justify-center rounded-full bg-white/10 border border-white/20 text-white hover:bg-white/20 transition" aria-label="Next slide">
                    <svg class="h-4 w-4 sm:h-3.5 sm:w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ══ MOBILE BOTTOM BAR — persistent Apply CTA (updates shown in ticker below), hidden on lg+ ══ --}}
    <div class="absolute bottom-0 left-0 right-0 z-10 lg:hidden"
         style="background:linear-gradient(to top, rgba(0,0,0,.82) 0%, rgba(0,0,0,.55) 100%)">
        <div class="flex items-center gap-2 px-3 py-2.5">
            <span class="flex-1 min-w-0 truncate text-[11px] font-semibold text-white/75">Admissions are open — apply now</span>
            <a href="{{ route('admissions') }}"
               class="shrink-0 rounded-full px-4 py-2 text-[11px] font-bold text-white transition hover:opacity-90"
               style="background:var(--site-brand)">
                Apply Now
            </a>
        </div>
    </div>

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

        {{-- Items list — auto-scrolling vertical ticker (pauses on hover) --}}
        <div class="jdca-mq flex-1 overflow-hidden px-3 pb-3">
            @if($panelItems->isNotEmpty())
            <div class="jdca-vtrack space-y-1" style="--vdur: {{ max(14, $panelItems->count() * 4) }}s;">
                @foreach($panelItems->concat($panelItems) as $item)
                <a href="{{ $item['type']==='event'?route('events'):route('notices') }}"
                   class="group flex gap-3 rounded-lg px-3 py-3 transition hover:bg-white/[.06] items-start">
                    <div class="shrink-0 mt-0.5 h-7 w-7 rounded-md flex items-center justify-center"
                         style="{{ $item['type']==='event'?'background:rgba(196,151,58,.15)':'background:rgba(255,255,255,.08)' }}">
                        @if($item['type']==='event')
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--site-gold)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @else
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#fff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[12px] font-medium leading-snug text-white/65 line-clamp-2 group-hover:text-white/90 transition">{{ $item['title'] }}</p>
                        @if($item['date'])<span class="mt-1 block text-[10px] text-white/28">{{ $item['date'] }}</span>@endif
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <p class="text-xs text-white/20">No updates yet</p>
            </div>
            @endif
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

{{-- ══ HORIZONTAL UPDATES TICKER — below the slider, mobile/tablet only (desktop uses the side panel) ══ --}}
@if($panelItems->isNotEmpty())
<div class="w-full overflow-hidden lg:hidden" style="background:var(--site-brand-dark);border-top:1px solid rgba(255,255,255,.1);border-bottom:1px solid rgba(255,255,255,.1)">
    <div class="flex items-stretch">
        <span class="shrink-0 z-10 flex items-center gap-1.5 px-4 text-[11px] font-bold uppercase tracking-wider text-white" style="background:var(--site-brand)">
            <span class="relative flex h-1.5 w-1.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span><span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-white"></span></span>
            Updates
        </span>
        <div class="jdca-mq flex-1 overflow-hidden">
            <div class="jdca-htrack flex items-center gap-10 whitespace-nowrap py-2.5 pl-8" style="--hdur: {{ max(24, $panelItems->count() * 7) }}s;">
                @foreach($panelItems->concat($panelItems) as $item)
                <a href="{{ $item['type']==='event'?route('events'):route('notices') }}" class="flex items-center gap-2 text-[12.5px] text-white/85 hover:text-white transition-colors">
                    <span class="inline-block h-1.5 w-1.5 rounded-full" style="background:{{ $item['type']==='event'?'var(--site-gold)':'#fff' }}"></span>
                    @if($item['date'])<span class="text-white/45">{{ $item['date'] }}</span>@endif
                    {{ $item['title'] }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('alpine:init',()=>{
    Alpine.data('heroSlider',()=>({
        activeSlide:0, _t:null, _tx:null,
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
        startAutoPlay(){
            this.stopAutoPlay();
            if(this.slides.length<=1)return;
            if(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)return;
            this._t=setInterval(()=>this.next(),6000);
        },
        stopAutoPlay(){if(this._t){clearInterval(this._t);this._t=null;}},
        next(){if(!this.slides.length)return;this.activeSlide=(this.activeSlide+1)%this.slides.length;},
        prev(){if(!this.slides.length)return;this.activeSlide=(this.activeSlide-1+this.slides.length)%this.slides.length;},
        goToSlide(i){this.activeSlide=i;this.startAutoPlay();},
        onTouchStart(e){this._tx=e.changedTouches[0].screenX;this.stopAutoPlay();},
        onTouchEnd(e){
            const dx=e.changedTouches[0].screenX-(this._tx??0);
            if(Math.abs(dx)>40){dx<0?this.next():this.prev();}
            this.startAutoPlay();
        }
    }));
});
</script>
