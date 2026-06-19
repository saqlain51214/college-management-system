@php
    $heroSlides = $pageContent['hero']['slides'] ?? [];
@endphp

<section class="relative z-0 min-h-[min(78vh,640px)] sm:min-h-[min(82vh,680px)] overflow-hidden"
    aria-labelledby="hero-heading"
    x-data="heroSlider()"
    x-init="startAutoPlay()"
    @mouseenter="stopAutoPlay()"
    @mouseleave="startAutoPlay()">
    
    <!-- Background Slides -->
    <template x-for="(slide, index) in slides" :key="index">
        <div class="absolute inset-0 h-full w-full transition-opacity duration-1000 ease-in-out"
            x-show="activeSlide === index"
            x-transition:enter="transition-opacity ease-linear duration-1000"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-1000"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
            <img :src="slide.image"
                :alt="slide.alt"
                class="absolute inset-0 h-full w-full object-cover object-center" 
                width="1600" height="900" 
                :fetchpriority="index === 0 ? 'high' : 'auto'"
                :decoding="index === 0 ? 'sync' : 'async'" />

            <div class="absolute inset-0 bg-gradient-to-b from-ink/75 via-ink/45 to-ink/80"></div>
        </div>
    </template>

    <div class="relative z-10 mx-auto flex min-h-[min(78vh,640px)] max-w-6xl flex-col items-center justify-center px-4 pb-16 pt-32 text-center text-white sm:min-h-[min(82vh,680px)] sm:px-6 sm:pb-20 sm:pt-36">
        
        <!-- Text Content Slides -->
        <div class="relative w-full flex-1 flex flex-col items-center justify-center">
            <template x-for="(slide, index) in slides" :key="'content-'+index">
                <div x-show="activeSlide === index"
                     x-transition:enter="transition ease-out duration-700 delay-200"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-4"
                     class="flex flex-col items-center justify-center w-full"
                     :class="activeSlide === index ? 'relative' : 'absolute inset-0'">
                    
                    <h1 :id="index === 0 ? 'hero-heading' : ''"
                        class="mb-4 max-w-4xl font-display text-3xl font-semibold leading-[1.2] tracking-tight sm:mb-5 sm:text-4xl md:text-5xl lg:text-[3.25rem]"
                        x-html="slide.title"></h1>
                    
                    <p class="mb-8 max-w-2xl font-sans text-sm leading-relaxed text-white/90 sm:mb-8 sm:text-base md:max-w-xl"
                       x-text="slide.description"></p>

                    <div class="mb-8 flex w-full max-w-md flex-col gap-3 sm:max-w-none sm:w-auto sm:flex-row sm:gap-4 justify-center">
                        <template x-if="slide.primaryBtnText && slide.primaryBtnLink && slide.primaryBtnLink !== '#'">
                            <a :href="slide.primaryBtnLink" class="btn-primary sm:px-8 sm:text-base" x-text="slide.primaryBtnText"></a>
                        </template>
                        <template x-if="slide.secondaryBtnText && slide.secondaryBtnLink && slide.secondaryBtnLink !== '#'">
                            <a :href="slide.secondaryBtnLink" class="inline-flex items-center justify-center rounded-md border-2 border-white bg-transparent px-7 py-2.5 text-sm font-semibold text-white transition hover:bg-white hover:text-brand focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-ink sm:px-8 sm:text-base" x-text="slide.secondaryBtnText"></a>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <div class="mt-auto rounded-md border border-white/30 bg-white/10 px-4 py-2.5 text-xs font-medium backdrop-blur-sm sm:px-6 sm:text-sm">
            Fall {{ date('Y') }} admissions open · Entry test &amp; merit lists as per Punjab college calendar
        </div>

        <!-- Slider Navigation Dots -->
        <div class="absolute bottom-6 left-0 right-0 flex justify-center gap-2.5 z-20">
            <template x-for="(slide, index) in slides" :key="'nav-'+index">
                <button @click="goToSlide(index)"
                    class="w-2.5 h-2.5 rounded-full transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-ink"
                    :class="activeSlide === index ? 'bg-white scale-125' : 'bg-white/40 hover:bg-white/70'"
                    :aria-label="'Go to slide ' + (index + 1)"></button>
            </template>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('heroSlider', () => ({
                activeSlide: 0,
                autoPlayInterval: null,
                slides: [
                    @foreach($heroSlides as $slide)
                    {
                        image: "{{ str_starts_with($slide['image'] ?? '', 'assets/') ? asset($slide['image']) : \Illuminate\Support\Facades\Storage::url($slide['image'] ?? '') }}",
                        alt: "{{ strip_tags($slide['title'] ?? 'Hero slide') }}",
                        title: "{!! addslashes($slide['title'] ?? '') !!}",
                        description: "{{ addslashes($slide['description'] ?? '') }}",
                        primaryBtnText: "{{ $slide['primary_btn_text'] ?? '' }}",
                        primaryBtnLink: "{{ !empty($slide['primary_btn_link']) && \Illuminate\Support\Facades\Route::has($slide['primary_btn_link']) ? route($slide['primary_btn_link']) : '#' }}",
                        secondaryBtnText: "{{ $slide['secondary_btn_text'] ?? '' }}",
                        secondaryBtnLink: "{{ !empty($slide['secondary_btn_link']) && \Illuminate\Support\Facades\Route::has($slide['secondary_btn_link']) ? route($slide['secondary_btn_link']) : '#' }}"
                    }@if(! $loop->last),@endif
                    @endforeach
                ],
                startAutoPlay() {
                    this.stopAutoPlay();
                    this.autoPlayInterval = setInterval(() => {
                        this.nextSlide();
                    }, 3000);
                },
                stopAutoPlay() {
                    if (this.autoPlayInterval) {
                        clearInterval(this.autoPlayInterval);
                        this.autoPlayInterval = null;
                    }
                },
                nextSlide() {
                    this.activeSlide = (this.activeSlide === this.slides.length - 1) ? 0 : this.activeSlide + 1;
                },
                goToSlide(index) {
                    this.activeSlide = index;
                    this.startAutoPlay();
                }
            }));
        });
    </script>
</section>
