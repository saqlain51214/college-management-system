@php
    $tiles = [
        ['label' => 'Admissions',   'sub' => 'Apply online',      'url' => route('admissions'),          'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0v6m-6-3.5V17c0 1.1 2.7 2 6 2s6-.9 6-2v-2.5'],
        ['label' => 'Scholarships', 'sub' => 'Financial aid',     'url' => route('scholarships'),        'icon' => 'M12 2l2.4 4.9 5.4.8-3.9 3.8.9 5.4L12 14.8 7.2 17l.9-5.4L4.2 7.7l5.4-.8L12 2z'],
        ['label' => 'Programmes',   'sub' => 'What we offer',     'url' => route('programs'),            'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0v7'],
        ['label' => 'Fee Challan',  'sub' => 'Download / pay',    'url' => route('fee-challan.download'), 'icon' => 'M9 7h6M9 11h6M9 15h4M6 3h12a1 1 0 011 1v17l-3.5-2-2 2-2-2-2 2L6 21V4a1 1 0 011-1z'],
        ['label' => 'Notices',      'sub' => 'Announcements',     'url' => route('notices'),             'icon' => 'M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0a3 3 0 11-6 0'],
        ['label' => 'Downloads',    'sub' => 'Forms & files',     'url' => route('downloads'),           'icon' => 'M12 3v12m0 0l-4-4m4 4l4-4M5 19h14'],
        ['label' => 'Gallery',      'sub' => 'Campus photos',     'url' => route('gallery'),             'icon' => 'M4 5h16a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1zm3 5a2 2 0 100-4 2 2 0 000 4zm-3 8l6-6 3 3 3-3 5 5'],
        ['label' => 'Contact Us',   'sub' => 'Get in touch',      'url' => route('contact'),             'icon' => 'M3 5a2 2 0 012-2h3.3a1 1 0 01.95.68l1.5 4.5a1 1 0 01-.5 1.2L9 10.5a11 11 0 005 5l1.1-1.75a1 1 0 011.2-.5l4.5 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.6 21 3 14.4 3 6V5z'],
    ];
@endphp

<section class="relative z-20 px-4 sm:px-6" style="margin-top:-3rem">
    <div class="mx-auto max-w-6xl overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5">
        <div class="grid grid-cols-2 sm:grid-cols-4">
            @foreach ($tiles as $i => $t)
                <a href="{{ $t['url'] }}"
                   class="group relative flex items-center gap-3 p-4 sm:p-5 transition
                          border-stone-100 {{ $i % 4 !== 3 ? 'sm:border-r' : '' }} {{ $i < count($tiles) - 2 ? 'border-b' : '' }} {{ $i % 2 === 0 ? 'border-r sm:border-r' : '' }}
                          hover:bg-stone-50">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-white shadow-sm transition group-hover:scale-105 site-gold-gradient">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $t['icon'] }}"/>
                        </svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-bold leading-tight truncate" style="color:var(--site-brand)">{{ $t['label'] }}</span>
                        <span class="block text-[11px] text-stone-400 truncate">{{ $t['sub'] }}</span>
                    </span>
                    <svg class="ml-auto hidden sm:block h-4 w-4 shrink-0 text-stone-300 transition group-hover:translate-x-0.5" style="color:var(--site-gold)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endforeach
        </div>
    </div>
</section>
