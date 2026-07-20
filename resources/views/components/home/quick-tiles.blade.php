@php
    $tiles = [
        ['label' => 'Admissions',   'sub' => 'Apply online',   'url' => route('admissions'),          'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0v6m-6-3.5V17c0 1.1 2.7 2 6 2s6-.9 6-2v-2.5'],
        ['label' => 'Scholarships', 'sub' => 'Financial aid',  'url' => route('scholarships'),        'icon' => 'M12 2l2.4 4.9 5.4.8-3.9 3.8.9 5.4L12 14.8 7.2 17l.9-5.4L4.2 7.7l5.4-.8L12 2z'],
        ['label' => 'Programmes',   'sub' => 'What we offer',  'url' => route('programs'),            'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0v7'],
        ['label' => 'Fee Challan',  'sub' => 'Download / pay', 'url' => route('fee-challan.download'), 'icon' => 'M9 7h6M9 11h6M9 15h4M6 3h12a1 1 0 011 1v17l-3.5-2-2 2-2-2-2 2L6 21V4a1 1 0 011-1z'],
        ['label' => 'Notices',      'sub' => 'Announcements',  'url' => route('notices'),             'icon' => 'M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0a3 3 0 11-6 0'],
        ['label' => 'Downloads',    'sub' => 'Forms & files',  'url' => route('downloads'),           'icon' => 'M12 3v12m0 0l-4-4m4 4l4-4M5 19h14'],
        ['label' => 'Gallery',      'sub' => 'Campus photos',  'url' => route('gallery'),             'icon' => 'M4 5h16a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1zm3 5a2 2 0 100-4 2 2 0 000 4zm-3 8l6-6 3 3 3-3 5 5'],
        ['label' => 'Contact Us',   'sub' => 'Get in touch',   'url' => route('contact'),             'icon' => 'M3 5a2 2 0 012-2h3.3a1 1 0 01.95.68l1.5 4.5a1 1 0 01-.5 1.2L9 10.5a11 11 0 005 5l1.1-1.75a1 1 0 011.2-.5l4.5 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.6 21 3 14.4 3 6V5z'],
    ];
@endphp

<section class="py-8 sm:py-10" style="background:var(--site-body-bg)">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <div class="mb-8 sm:mb-10 text-center" data-reveal>
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.2em]" style="color:var(--site-gold)">Explore</p>
            <h2 class="font-display text-2xl font-bold text-stone-900 sm:text-3xl">Quick Access</h2>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-5" data-reveal data-reveal-delay="1">
            @foreach ($tiles as $t)
                <a href="{{ $t['url'] }}"
                   class="qt-card group relative flex flex-col gap-3 overflow-hidden rounded-2xl bg-white p-5 ring-1 ring-stone-100 shadow-sm transition duration-300 hover:-translate-y-1.5 hover:shadow-xl">
                    <span class="qt-bar absolute inset-x-0 top-0 h-1 origin-left scale-x-0 transition-transform duration-300 group-hover:scale-x-100 site-gold-gradient"></span>
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl text-white shadow-sm transition duration-300 group-hover:scale-110 site-gold-gradient">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $t['icon'] }}"/>
                        </svg>
                    </span>
                    <span>
                        <span class="block text-[15px] font-bold leading-tight" style="color:var(--site-brand)">{{ $t['label'] }}</span>
                        <span class="mt-0.5 block text-xs text-stone-400">{{ $t['sub'] }}</span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
