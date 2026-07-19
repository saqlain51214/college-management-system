@extends('layouts.public')
@section('title', 'Admission Procedure')

@section('content')
@php $customBody = !empty($cmsPage) ? $cmsPage->customBodyHtml() : null; @endphp
@if($customBody)
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1"><a href="{{ route('home') }}" class="hover:text-white">Home</a><span class="mx-1.5">›</span><span class="text-white/90">{{ $cmsPage->title }}</span></p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $cmsPage->title }}</h1>
        </div>
    </div>
</div>
<div class="mx-auto max-w-4xl px-4 sm:px-6 py-12"><div class="prose-content max-w-none">{!! $customBody !!}</div></div>
@else
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <a href="{{ route('admissions') }}" class="hover:text-white">Admission</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">Admission Procedure</span>
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Admission Procedure</h1>
            <p class="text-white/70 text-sm mt-1">Step-by-step guide to applying at JDCA</p>
        </div>
    </div>
</div>

<div class="mx-auto max-w-5xl px-4 sm:px-6 py-12">
    <div class="grid lg:grid-cols-3 gap-10">

        <div class="lg:col-span-2 space-y-10">
            {{-- Steps --}}
            <section>
                <h2 class="text-xl font-bold text-stone-800 pb-2 mb-6 border-b-2" style="border-color:var(--site-brand)">How to Apply</h2>
                <div class="space-y-6">
                    @foreach([
                        ['Check Eligibility','Review the eligibility criteria for your desired program. Ensure you meet the minimum qualification requirements before applying.'],
                        ['Obtain Admission Form','Download the admission form from our Downloads page or collect a printed copy from the college administration office.'],
                        ['Fill & Submit Form','Complete the form with accurate personal and academic information. Submit it along with all required documents to the admissions office.'],
                        ['Pay Admission Fee','Deposit the admission fee at the designated bank and attach the bank receipt with your application.'],
                        ['Document Verification','Our admissions team will verify your submitted documents. You may be called for an interview or additional verification if required.'],
                        ['Merit List & Confirmation','Admission is granted on merit. Check the merit list on the college notice board or website. Confirm your admission within the specified deadline.'],
                        ['Enrollment','Complete the enrollment process by paying the semester fee and obtaining your college ID and roll number.'],
                    ] as $i => [$title, $desc])
                    <div class="flex gap-4">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 text-white text-sm font-bold shadow" style="background:var(--site-brand)">{{ $i + 1 }}</div>
                        <div class="pt-1">
                            <h3 class="font-bold text-stone-800 text-base">{{ $title }}</h3>
                            <p class="text-sm text-stone-600 mt-1 leading-relaxed">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- Eligibility --}}
            <section>
                <h2 class="text-xl font-bold text-stone-800 pb-2 mb-4 border-b-2" style="border-color:var(--site-brand)">Eligibility Criteria</h2>
                <div class="overflow-x-auto rounded-xl border border-stone-200">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:var(--site-brand)" class="text-white">
                                <th class="px-4 py-3 text-left font-semibold">Program</th>
                                <th class="px-4 py-3 text-left font-semibold">Minimum Qualification</th>
                                <th class="px-4 py-3 text-left font-semibold">Min. Marks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            <tr class="bg-white hover:bg-stone-50">
                                <td class="px-4 py-3 font-medium text-stone-800">B.Ed 2.5 Year</td>
                                <td class="px-4 py-3 text-stone-600">HSSC / F.A / F.Sc or equivalent</td>
                                <td class="px-4 py-3 text-stone-600">45%</td>
                            </tr>
                            <tr class="bg-stone-50 hover:bg-stone-100">
                                <td class="px-4 py-3 font-medium text-stone-800">B.Ed 1.5 Year</td>
                                <td class="px-4 py-3 text-stone-600">Bachelor's Degree (14 years)</td>
                                <td class="px-4 py-3 text-stone-600">45%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <aside class="space-y-5">
            <div class="rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden">
                <div class="site-brand-gradient py-3 px-4">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wide">Required Documents</h3>
                </div>
                <ul class="divide-y divide-stone-100 text-sm">
                    @foreach(['SSC / O-Level DMC','HSSC / A-Level DMC','Passport Photograph (2)','CNIC / Form-B (copy)','Domicile Certificate','Migration Certificate','Bachelors Degree/DMC (for B.Ed 1.5)','MA/BS Certificate (for B.Ed 1.5)'] as $doc)
                    <li class="px-4 py-2.5 flex items-center gap-2 text-stone-700">
                        <svg class="w-3.5 h-3.5 shrink-0" style="color:var(--site-brand)" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ $doc }}
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="rounded-2xl p-5" style="background:var(--site-brand)">
                <h3 class="font-bold text-white mb-2">Apply Online Now</h3>
                <p class="text-white/80 text-sm mb-4">Fill out the online admission inquiry form and our team will contact you.</p>
                <a href="{{ route('admissions') }}" class="block text-center rounded-lg bg-white py-2 text-sm font-semibold transition hover:bg-stone-100" style="color:var(--site-brand)">
                    Online Admission Form →
                </a>
            </div>

            <div class="rounded-2xl border border-stone-200 bg-white p-5">
                <h3 class="font-bold text-stone-800 mb-3">Contact Admissions</h3>
                <div class="space-y-2 text-sm text-stone-600">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:+923129776585">+92 312 9776585</a>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:jinnahschooldegreecollege@gmail.com" class="break-all">jinnahschooldegreecollege@gmail.com</a>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endif
@endsection
