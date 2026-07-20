@php
    $principalName = \App\Models\CollegeSetting::get('college_principal', 'Principal');
    $collegeName   = $college->college_name ?? 'Jinnah Degree College Astore';

    // Message body: editable from Website Pages → "Message from Principal"; else a default.
    $msgPage = \App\Models\WebsitePage::where('slug', 'about-message')->first();
    $msgHtml = $msgPage && method_exists($msgPage, 'customBodyHtml') ? $msgPage->customBodyHtml() : null;
    $msgExcerpt = $msgHtml
        ? \Illuminate\Support\Str::limit(trim(strip_tags($msgHtml)), 380)
        : "It is my privilege to welcome you to {$collegeName}. Our mission is to nurture confident, responsible and well-educated young people ready to serve their community and nation. We combine dedicated teaching, discipline and modern facilities so every student can reach their full potential. I warmly invite you to join our family and begin a journey of learning and success.";

    $photo = \App\Models\CollegeSetting::get('principal_photo');
    $photoUrl = $photo ? asset('storage/' . $photo) : null;
    $initials = collect(explode(' ', trim($principalName)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('');
@endphp

<section class="py-12 sm:py-16 lg:py-24" style="background:var(--site-body-bg)">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        {{-- Section header (matches Programmes / News) --}}
        <div class="mb-8 sm:mb-12 text-center">
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em]" style="color:var(--site-gold)">Leadership</p>
            <h2 class="font-display text-2xl font-bold text-stone-900 sm:text-3xl lg:text-4xl">Message Desk</h2>
        </div>

        <div class="overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-black/5">
            <div class="grid lg:grid-cols-[320px_1fr]">
                {{-- Portrait panel --}}
                <div class="relative flex flex-col items-center justify-center gap-4 p-8 text-center site-brand-gradient">
                    <div class="h-40 w-40 overflow-hidden rounded-full ring-4 ring-white/25 shadow-lg flex items-center justify-center bg-white/10">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="{{ $principalName }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-4xl font-bold text-white/90 font-display">{{ $initials ?: 'JDCA' }}</span>
                        @endif
                    </div>
                    <div>
                        <p class="font-display text-lg font-bold text-white">{{ $principalName }}</p>
                        <p class="text-sm text-white/70">Principal · {{ $college->short_name ?? 'JDCA' }}</p>
                    </div>
                </div>

                {{-- Message --}}
                <div class="p-8 sm:p-10 lg:p-12">
                    <svg class="mb-4 h-9 w-9" style="color:var(--site-gold);opacity:.9" fill="currentColor" viewBox="0 0 24 24"><path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849h-3.983v-10h9.983z"/></svg>
                    <h3 class="font-display text-xl sm:text-2xl font-bold mb-4 text-stone-900">Message from the Principal</h3>
                    <p class="text-stone-600 leading-relaxed text-[15px]">{{ $msgExcerpt }}</p>
                    <a href="{{ route('about.message') }}"
                       class="mt-7 inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:opacity-90 site-brand-gradient">
                        Read Full Message
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
