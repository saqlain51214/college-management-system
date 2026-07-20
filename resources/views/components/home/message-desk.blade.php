@php
    use App\Models\CollegeSetting;
    use App\Models\WebsitePage;
    use Illuminate\Support\Str;

    $collegeName = $college->college_name ?? 'Jinnah Degree College Astore';
    $shortName   = $college->short_name ?? 'JDCA';

    // Pull the Principal's message from the editable "Message from Principal" page.
    $pPage   = WebsitePage::where('slug', 'about-message')->first();
    $pCustom = $pPage && method_exists($pPage, 'customBodyHtml') ? $pPage->customBodyHtml() : null;
    $pMsg    = $pCustom ?: ($pPage->content['message_html'] ?? null)
        ?: "On behalf of the faculty and staff of {$collegeName}, I warmly welcome you. We are committed to quality education, discipline and nurturing every student to realise their full potential — preparing them for success in higher education and life.";

    $leaders = [
        [
            'name'  => 'Directorate of Colleges',
            'title' => 'Director of Colleges',
            'org'   => 'Government of Gilgit-Baltistan',
            'url'   => route('about.director'),
            'photo' => CollegeSetting::get('director_photo'),
            'msg'   => "The Government of Gilgit-Baltistan is deeply committed to expanding access to quality higher education across all districts, including the valleys of Astore. {$collegeName} represents our vision of bringing world-class education to the doorstep of every deserving student in the region.",
        ],
        [
            'name'  => CollegeSetting::get('college_principal', 'Arif Ali'),
            'title' => 'Principal',
            'org'   => $collegeName,
            'url'   => route('about.message'),
            'photo' => CollegeSetting::get('principal_photo'),
            'msg'   => strip_tags($pMsg),
        ],
    ];
@endphp

<section class="relative overflow-hidden py-14 sm:py-20 site-brand-gradient">
    <div class="pointer-events-none absolute -right-20 -top-20 h-72 w-72 rounded-full opacity-[0.08]" style="background:var(--site-gold)"></div>
    <div class="pointer-events-none absolute -left-24 -bottom-24 h-80 w-80 rounded-full opacity-[0.06] bg-white"></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
        <div class="mb-10 sm:mb-12 text-center">
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.22em]" style="color:var(--site-gold)">Leadership</p>
            <h2 class="font-display text-2xl font-bold text-white sm:text-3xl lg:text-4xl">Message Desk</h2>
            <div class="mx-auto mt-3 h-1 w-16 rounded-full" style="background:var(--site-gold)"></div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
            @foreach ($leaders as $L)
                @php
                    $initials = collect(explode(' ', trim($L['name'])))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('');
                    $photoUrl = $L['photo'] ? asset('storage/' . $L['photo']) : null;
                @endphp
                <div class="group flex flex-col overflow-hidden rounded-3xl bg-white/[0.07] ring-1 ring-white/12 backdrop-blur-sm transition duration-300 hover:bg-white/[0.11] hover:-translate-y-1">
                    <div class="flex items-center gap-4 border-b border-white/10 p-5 sm:p-6">
                        <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl ring-2 ring-white/25 flex items-center justify-center site-gold-gradient">
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}" alt="{{ $L['name'] }}" class="h-full w-full object-cover">
                            @else
                                <span class="font-display text-xl font-bold text-white">{{ $initials ?: 'JD' }}</span>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="font-display text-lg font-bold text-white leading-tight">{{ $L['name'] }}</p>
                            <p class="text-sm font-medium" style="color:var(--site-gold)">{{ $L['title'] }}</p>
                            <p class="text-xs text-white/55 truncate">{{ $L['org'] }}</p>
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col p-5 sm:p-6">
                        <svg class="mb-2 h-7 w-7 opacity-40" style="color:var(--site-gold)" fill="currentColor" viewBox="0 0 24 24"><path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849h-3.983v-10h9.983z"/></svg>
                        <p class="text-[14.5px] leading-relaxed text-white/75">{{ Str::limit($L['msg'], 300) }}</p>
                        <a href="{{ $L['url'] }}"
                           class="mt-5 inline-flex w-fit items-center gap-2 text-sm font-semibold text-white transition hover:gap-3"
                           style="color:var(--site-gold)">
                            Read Full Message
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
