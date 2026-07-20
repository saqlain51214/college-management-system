@php
    $brand  = \App\Models\CollegeSetting::get('website_theme_brand', '#1A3A5F');
    $accent = \App\Models\CollegeSetting::get('website_theme_gold', '#C4973A');

    $tiles = [
        ['label' => 'Admissions',    'url' => route('admissions'),          'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0v6m0-6l6.16-3.42M6 12v5c0 1 2.7 2 6 2s6-1 6-2v-5'],
        ['label' => 'Scholarships',  'url' => route('scholarships'),        'icon' => 'M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0-6l2.09 4.26L19 7l-3 3.5.7 4.5L12 13l-4.7 2 .7-4.5L5 7l4.91-.74L12 2z'],
        ['label' => 'Programmes',    'url' => route('programs'),            'icon' => 'M12 14l9-5-9-5-9 5 9 5z M12 14v7'],
        ['label' => 'Notices',       'url' => route('notices'),             'icon' => 'M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0a3 3 0 11-6 0'],
        ['label' => 'Fee Challan',   'url' => route('fee-challan.download'),'icon' => 'M9 7h6m-6 4h6m-6 4h4M5 3h14a1 1 0 011 1v16l-3-2-2 2-2-2-2 2-2-2-3 2V4a1 1 0 011-1z'],
        ['label' => 'Downloads',     'url' => route('downloads'),           'icon' => 'M12 3v12m0 0l-4-4m4 4l4-4M4 19h16'],
        ['label' => 'Gallery',       'url' => route('gallery'),             'icon' => 'M4 5h16a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1zm3 5a2 2 0 100-4 2 2 0 000 4zm-3 7l5-5 3 3 4-4 5 5'],
        ['label' => 'Contact Us',    'url' => route('contact'),             'icon' => 'M3 5a2 2 0 012-2h3.3a1 1 0 01.95.68l1.5 4.5a1 1 0 01-.5 1.2L9 10.5a11 11 0 005 5l1.1-1.75a1 1 0 011.2-.5l4.5 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.6 21 3 14.4 3 6V5z'],
    ];
@endphp

<section class="px-4 sm:px-6 -mt-6 relative z-10">
    <div class="mx-auto max-w-6xl rounded-2xl p-4 sm:p-6 shadow-xl" style="background: {{ $brand }}">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            @foreach ($tiles as $t)
                <a href="{{ $t['url'] }}"
                   class="group flex items-center gap-3 rounded-xl bg-white/5 px-3 py-3 ring-1 ring-white/10 transition hover:bg-white/10">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg" style="background: {{ $accent }}">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $t['icon'] }}"/>
                        </svg>
                    </span>
                    <span class="text-sm font-semibold text-white">{{ $t['label'] }}</span>
                    <svg class="ml-auto h-4 w-4 text-white/40 transition group-hover:text-white/80 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endforeach
        </div>
    </div>
</section>
