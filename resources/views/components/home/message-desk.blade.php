@php
    $brand   = \App\Models\CollegeSetting::get('website_theme_brand', '#1A3A5F');
    $accent  = \App\Models\CollegeSetting::get('website_theme_gold', '#C4973A');
    $principalName = \App\Models\CollegeSetting::get('college_principal', 'Principal');
    $collegeName   = $college->college_name ?? 'Jinnah Degree College Astore';

    // Message body: editable from Website Pages → "Message from Principal"; else a default.
    $msgPage = \App\Models\WebsitePage::where('slug', 'about-message')->first();
    $msgHtml = $msgPage && method_exists($msgPage, 'customBodyHtml') ? $msgPage->customBodyHtml() : null;
    $msgExcerpt = $msgHtml
        ? \Illuminate\Support\Str::limit(trim(strip_tags($msgHtml)), 420)
        : "It is my privilege to welcome you to {$collegeName}. Our mission is to nurture confident, responsible and well-educated young people who are ready to serve their community and the nation. We combine dedicated teaching, discipline and modern facilities to help every student reach their full potential. I invite you to join our family and begin a journey of learning and success.";

    $photo = \App\Models\CollegeSetting::get('principal_photo');
    $photoUrl = $photo ? asset('storage/' . $photo) : asset('assets/images/default/cologe-logo-web.png');
    $initials = collect(explode(' ', trim($principalName)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('');
@endphp

<section class="py-16 px-4 sm:px-6" style="background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);">
    <div class="mx-auto max-w-6xl">
        <div class="text-center mb-10">
            <span class="inline-block text-xs font-semibold uppercase tracking-[0.2em] mb-2" style="color: {{ $accent }}">Leadership</span>
            <h2 class="font-display text-2xl sm:text-3xl lg:text-4xl font-bold" style="color: {{ $brand }}">Message Desk</h2>
            <div class="mx-auto mt-3 h-1 w-20 rounded-full" style="background: {{ $accent }}"></div>
        </div>

        <div class="grid gap-8 lg:grid-cols-[300px_1fr] lg:gap-12 items-center rounded-3xl bg-white p-6 sm:p-10 shadow-xl ring-1 ring-black/5">
            {{-- Photo --}}
            <div class="mx-auto lg:mx-0">
                <div class="relative">
                    <div class="absolute -inset-2 rounded-3xl opacity-20" style="background: {{ $brand }}"></div>
                    <div class="relative h-56 w-56 sm:h-64 sm:w-64 overflow-hidden rounded-3xl ring-4 ring-white shadow-lg flex items-center justify-center" style="background: {{ $brand }}">
                        <img src="{{ $photoUrl }}" alt="{{ $principalName }}"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                             class="h-full w-full object-cover">
                        <span class="hidden h-full w-full items-center justify-center text-5xl font-bold text-white/90">{{ $initials ?: 'JDCA' }}</span>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <p class="font-display text-lg font-bold" style="color: {{ $brand }}">{{ $principalName }}</p>
                    <p class="text-sm text-slate-500">Principal, {{ $college->short_name ?? 'JDCA' }}</p>
                </div>
            </div>

            {{-- Message --}}
            <div>
                <svg class="mb-3 h-10 w-10 opacity-20" style="color: {{ $brand }}" fill="currentColor" viewBox="0 0 24 24"><path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849h-3.983v-10h9.983z"/></svg>
                <h3 class="font-display text-xl sm:text-2xl font-bold mb-4" style="color: {{ $brand }}">Message from the Principal</h3>
                <p class="text-slate-600 leading-relaxed text-[15px]">{{ $msgExcerpt }}</p>
                <div class="mt-6">
                    <a href="{{ route('about.message') }}"
                       class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:opacity-90"
                       style="background: {{ $brand }}">
                        Read Full Message
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
