@extends('layouts.public')
@section('title', 'Semester Rules')

@section('content')
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <a href="{{ route('admissions') }}" class="hover:text-white">Admission</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">Semester Rules</span>
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Semester Rules &amp; Regulations</h1>
        </div>
    </div>
</div>

<div class="mx-auto max-w-4xl px-4 sm:px-6 py-12 space-y-8">

    @foreach([
        ['Attendance Policy',[
            'Students must maintain a minimum of 75% attendance in each subject per semester.',
            'Students with less than 75% attendance will not be allowed to appear in final examinations.',
            'Medical leave is considered upon submission of a valid medical certificate.',
            'Attendance is marked at the start of each class by the respective subject teacher.',
        ]],
        ['Examination Rules',[
            'Students must appear in all mid-term and final examinations as scheduled.',
            'No make-up exam will be conducted without prior approval from the Principal.',
            'Use of mobile phones or any electronic device during exams is strictly prohibited.',
            'Students found cheating will be debarred from the examination and may face disciplinary action.',
            'Results will be declared within 30 days of the final examination.',
        ]],
        ['Academic Standing',[
            'A student must pass all subjects in a semester to be promoted to the next semester.',
            'A student who fails in one or two subjects may be awarded a "Repeat" grade and must re-appear in the supplementary examination.',
            'A student failing in more than two subjects will be required to repeat the entire semester.',
            'The Grade Point Average (GPA) is calculated on a 4.0 scale.',
        ]],
        ['Code of Conduct',[
            'Students are required to maintain discipline and respect inside and outside the classroom.',
            'Proper college dress code must be observed at all times on campus.',
            'Any form of harassment or bullying will result in immediate disciplinary action.',
            'Students must carry their college ID cards at all times on campus.',
            'Use of social media to defame the college or any individual is a punishable offense.',
        ]],
        ['Fee & Registration',[
            'Semester fee must be paid within the first two weeks of the semester.',
            'Students who fail to pay fees on time may be de-registered from the current semester.',
            'Course registration must be completed before the start of lectures.',
            'Add/drop of subjects is only allowed within the first week of the semester.',
        ]],
    ] as [$section, $rules])
    <section class="rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden">
        <div class="site-brand-gradient px-6 py-3">
            <h2 class="font-bold text-white">{{ $section }}</h2>
        </div>
        <ul class="divide-y divide-stone-100">
            @foreach($rules as $i => $rule)
            <li class="px-6 py-3 flex items-start gap-3 text-sm text-stone-700">
                <span class="flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold text-white mt-0.5" style="background:var(--site-brand)">{{ $i + 1 }}</span>
                {{ $rule }}
            </li>
            @endforeach
        </ul>
    </section>
    @endforeach

    <div class="rounded-2xl p-5 border border-stone-200 bg-stone-50 flex items-start gap-4 text-sm text-stone-700">
        <svg class="w-5 h-5 shrink-0 mt-0.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <p>These rules are aligned with KIU regulations. Students are bound by both JDCA's and KIU's academic policies. For complete regulations, refer to the official KIU prospectus or contact the administration office.</p>
    </div>
</div>
@endsection
