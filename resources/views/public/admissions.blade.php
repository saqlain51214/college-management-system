@extends('layouts.public')
@section('title', 'Online Admission Form — ' . ($college->college_name ?? 'JDCA'))

@section('content')

<main id="main" tabindex="-1" class="site-main outline-none [&_h1]:font-display [&_h2]:font-display [&_h3]:font-sans [&_h4]:font-sans [&_h1]:tracking-tight [&_h2]:tracking-tight [&_h3]:tracking-tight [&_h4]:tracking-tight">

    {{-- Page hero --}}
    <section class="relative overflow-hidden bg-ink pt-28 pb-12 text-white sm:pt-32 sm:pb-14" aria-labelledby="page-title">
        <div class="absolute inset-0 bg-gradient-to-br from-brand/90 via-ink/95 to-ink"></div>
        <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
            <nav class="mb-4 text-xs text-white/70" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
                <span class="mx-2 text-white/40" aria-hidden="true">/</span>
                <span class="text-white">Admission Form</span>
            </nav>
            <h1 id="page-title" class="font-display text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">Online Admission Form</h1>
            <p class="mt-3 max-w-2xl text-sm text-white/90 sm:text-base">Fall Semester 2026 — {{ $college->college_name ?? 'Jinnah School & Degree College Astore' }}</p>
        </div>
    </section>

    {{-- Documents required notice --}}
    <section class="border-b border-stone-200/80 bg-amber-50/70 py-5">
        <div class="mx-auto max-w-4xl px-4 sm:px-6">
            <p class="mb-2 text-sm font-semibold text-stone-800">Documents required at Admission Office after online submission:</p>
            <ol class="list-inside list-decimal space-y-0.5 text-sm text-stone-700">
                <li>Attested academic credentials &amp; Detailed Marks Certificates (2 copies each)</li>
                <li>Attested photocopy of CNIC or Form 'B'</li>
                <li>Departmental NOC (Government Employees only)</li>
                <li>05 recent passport-size photographs</li>
                <li>Domicile Certificate</li>
                <li>Migration Certificate (if board other than KIU)</li>
            </ol>
            <p class="mt-3 text-xs text-stone-600">Submit form with Rs. 500/- draft / pay order to the Admission Office before the deadline. Incomplete forms will not be entertained.</p>
        </div>
    </section>

    {{-- Main form --}}
    <section id="online-application" class="border-b border-stone-200/80 bg-white py-10 md:py-14">
        <div class="mx-auto max-w-4xl px-4 sm:px-6">

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <p class="mb-2 font-semibold">Please fix the following errors and try again:</p>
                    <ul class="list-inside list-disc space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div id="admSuccess" class="rounded-2xl border border-green-200 bg-green-50/90 p-6 sm:p-8" role="status" aria-live="polite">
                    <h3 class="font-display text-xl font-semibold text-ink">Application received</h3>
                    <p class="mt-2 text-sm text-stone-700">{{ session('success') }}</p>
                    <ul class="mt-4 list-inside list-disc space-y-1 text-sm text-stone-600">
                        <li>Visit <strong class="text-ink">Admission Office, {{ $college->address ?? 'Eidgah Astore' }}</strong> within the published dates.</li>
                        <li>Carry original certificates, CNIC/B-Form copies, and photographs.</li>
                    </ul>
                    <a href="{{ route('admissions') }}" class="mt-6 inline-block rounded-md border border-stone-200 bg-white px-4 py-2 text-sm font-semibold text-brand transition hover:bg-stone-50">Start new application</a>
                </div>
            @else

            <form action="{{ route('admissions.inquiry') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- ── Header: Semester + Program type ── --}}
                <div class="rounded-xl border border-stone-200 bg-stone-50/60 px-5 py-5 sm:px-6">
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <p class="mb-2 text-sm font-semibold text-ink">Semester <span class="text-red-600">*</span></p>
                            <div class="flex gap-6">
                                <label class="flex cursor-pointer items-center gap-2 text-sm text-stone-700">
                                    <input type="radio" name="semester" value="spring" @checked(old('semester') === 'spring')
                                           class="h-4 w-4 border-stone-300 text-brand focus:ring-brand">
                                    Spring
                                </label>
                                <label class="flex cursor-pointer items-center gap-2 text-sm text-stone-700">
                                    <input type="radio" name="semester" value="fall" @checked(old('semester', 'fall') === 'fall')
                                           class="h-4 w-4 border-stone-300 text-brand focus:ring-brand">
                                    Fall
                                </label>
                            </div>
                            @error('semester')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <p class="mb-2 text-sm font-semibold text-ink">Program</p>
                            <div class="flex gap-6">
                                <label class="flex cursor-not-allowed items-center gap-2 text-sm text-stone-400">
                                    <input type="radio" name="program_type" value="bs" disabled
                                           class="h-4 w-4 border-stone-300 text-stone-300 opacity-50">
                                    BS <span class="text-[10px] text-stone-400">(coming soon)</span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-2 text-sm text-stone-700">
                                    <input type="radio" name="program_type" value="adp" @checked(old('program_type') === 'adp')
                                           class="h-4 w-4 border-stone-300 text-brand focus:ring-brand">
                                    ADP
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-ink">
                            Application for admission in Program of <span class="text-red-600">*</span>
                            <select name="program_name" id="programSelect" required
                                    class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2.5 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2 @error('program_name') border-red-400 @enderror">
                                <option value="">— Select department &amp; programme —</option>

                                <optgroup label="Department of Education">
                                    <option value="Associate Degree in Education"
                                        @selected(old('program_name') === 'Associate Degree in Education')>Associate Degree in Education</option>
                                    <option value="B.Ed 1.5 Year"
                                        @selected(old('program_name') === 'B.Ed 1.5 Year')>B.Ed 1.5 Year</option>
                                    <option value="B.Ed 2.5 Year"
                                        @selected(old('program_name') === 'B.Ed 2.5 Year')>B.Ed 2.5 Year</option>
                                </optgroup>

                                <optgroup label="Department of Physical Education">
                                    <option value="Associate Degree in Health &amp; Physical Education"
                                        @selected(old('program_name') === 'Associate Degree in Health & Physical Education')>Associate Degree in Health &amp; Physical Education</option>
                                </optgroup>

                                <optgroup label="Department of Sociology">
                                    <option value="Associate Degree in Sociology"
                                        @selected(old('program_name') === 'Associate Degree in Sociology')>Associate Degree in Sociology</option>
                                </optgroup>

                                <optgroup label="Department of Computer Science">
                                    <option value="Associate Degree in Computer Science"
                                        @selected(old('program_name') === 'Associate Degree in Computer Science')>Associate Degree in Computer Science</option>
                                </optgroup>

                                <optgroup label="Department of English">
                                    <option value="Associate Degree in English"
                                        @selected(old('program_name') === 'Associate Degree in English')>Associate Degree in English</option>
                                </optgroup>

                                <optgroup label="Department of Continuous Education">
                                    <option value="Associate Degree in Continuous Education"
                                        @selected(old('program_name') === 'Associate Degree in Continuous Education')>Associate Degree in Continuous Education</option>
                                </optgroup>

                            </select>
                        </label>
                        @error('program_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- ── Section 2: Personal Record ── --}}
                <div class="rounded-xl border border-stone-200">
                    <div class="rounded-t-xl border-b border-stone-200 bg-brand px-5 py-3">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-white">2. Applicant's Personal Record</h2>
                    </div>
                    <div class="space-y-4 px-5 py-5 sm:px-6">

                        <div>
                            <label class="block text-sm font-medium text-ink">
                                Name <span class="text-xs text-stone-500">(in block letters)</span> <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="name" required value="{{ old('name') }}"
                                   autocomplete="name"
                                   class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm uppercase outline-none ring-brand/20 focus:border-brand focus:ring-2 @error('name') border-red-400 @enderror">
                            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-ink">CNIC / B-Form <span class="text-red-600">*</span></label>
                                <input type="text" name="cnic" required value="{{ old('cnic') }}"
                                       placeholder="xxxxx-xxxxxxx-x" inputmode="numeric"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2 @error('cnic') border-red-400 @enderror">
                                @error('cnic')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-ink">Gender <span class="text-red-600">*</span></label>
                                <select name="gender" required
                                        class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2.5 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2 @error('gender') border-red-400 @enderror">
                                    <option value="">Select</option>
                                    <option value="male"   @selected(old('gender') === 'male')>Male</option>
                                    <option value="female" @selected(old('gender') === 'female')>Female</option>
                                </select>
                                @error('gender')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-ink">Date of Birth <span class="text-red-600">*</span></label>
                                <input type="date" name="dob" required value="{{ old('dob') }}"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2 @error('dob') border-red-400 @enderror">
                                @error('dob')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-ink">Cell No. (Student) <span class="text-red-600">*</span></label>
                                <input type="tel" name="student_phone" required value="{{ old('student_phone') }}"
                                       placeholder="03xx-xxxxxxx" autocomplete="tel"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2 @error('student_phone') border-red-400 @enderror">
                                @error('student_phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-ink">Father's Name <span class="text-red-600">*</span></label>
                            <input type="text" name="father_name" required value="{{ old('father_name') }}"
                                   class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2 @error('father_name') border-red-400 @enderror">
                            @error('father_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-ink">Father's Occupation</label>
                                <input type="text" name="father_occupation" value="{{ old('father_occupation') }}"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-ink">Father's Cell No.</label>
                                <input type="tel" name="father_phone" value="{{ old('father_phone') }}"
                                       placeholder="03xx-xxxxxxx"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2">
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-ink">Guardian's Name</label>
                                <input type="text" name="guardian_name" value="{{ old('guardian_name') }}"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-ink">Guardian's Cell No.</label>
                                <input type="tel" name="guardian_phone" value="{{ old('guardian_phone') }}"
                                       placeholder="03xx-xxxxxxx"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-ink">Postal Address <span class="text-red-600">*</span></label>
                            <input type="text" name="address" required value="{{ old('address') }}"
                                   class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2 @error('address') border-red-400 @enderror">
                            @error('address')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <p class="mb-2 text-sm font-medium text-ink">Permanent Address <span class="text-red-600">*</span></p>
                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-stone-500">District <span class="text-red-600">*</span></label>
                                    <input type="text" name="district" required value="{{ old('district') }}"
                                           placeholder="e.g. Astore"
                                           class="w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2 @error('district') border-red-400 @enderror">
                                    @error('district')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-stone-500">Tehsil</label>
                                    <input type="text" name="tehsil" value="{{ old('tehsil') }}"
                                           class="w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-stone-500">Village</label>
                                    <input type="text" name="village" value="{{ old('village') }}"
                                           class="w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-stone-500">Post Office</label>
                                    <input type="text" name="post_office" value="{{ old('post_office') }}"
                                           class="w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-ink">Applicant's Email <span class="text-red-600">*</span></label>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                   autocomplete="email"
                                   class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2 @error('email') border-red-400 @enderror">
                            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>

                {{-- ── Section 3: Academic Record ── --}}
                <div class="rounded-xl border border-stone-200">
                    <div class="rounded-t-xl border-b border-stone-200 bg-brand px-5 py-3">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-white">3. Applicant's Academic Record</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[860px] text-sm">
                            <thead>
                                <tr class="border-b border-stone-200 bg-stone-50 text-xs font-semibold text-stone-600">
                                    <th class="px-4 py-3 text-left w-44">Examination</th>
                                    <th class="px-3 py-3 text-left w-24">Year</th>
                                    <th class="px-3 py-3 text-left w-28">Division</th>
                                    <th class="px-3 py-3 text-left w-32">Marks Obtained</th>
                                    <th class="px-3 py-3 text-left w-24">Total</th>
                                    <th class="px-3 py-3 text-left">Major Subjects</th>
                                    <th class="px-3 py-3 text-left w-44">Board / University</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                @php
                                    $examRows = [
                                        ['key' => 'ssc',       'label' => 'SSC / O Level'],
                                        ['key' => 'hssc',      'label' => 'HSSC / A Level or Equivalence'],
                                        ['key' => 'bachelors', 'label' => 'Bachelors'],
                                        ['key' => 'mabs',      'label' => 'MA / BS'],
                                    ];
                                    $inp = fn($key, $sub) => old("academic.{$key}.{$sub}", '');
                                    $ic = 'w-full rounded border border-stone-200 bg-white px-3 py-2 text-sm outline-none focus:border-brand focus:ring-1 focus:ring-brand/20';
                                @endphp
                                @foreach($examRows as $row)
                                <tr id="academic-row-{{ $row['key'] }}" class="@if(in_array($row['key'], ['ssc', 'hssc'])) bg-amber-50/40 @endif">
                                    <td class="px-4 py-3 text-xs font-semibold text-stone-700">
                                        {{ $row['label'] }}
                                        @if(in_array($row['key'], ['ssc', 'hssc']))
                                            <span class="block text-[10px] font-normal text-stone-400">(Required)</span>
                                        @endif
                                        @if(in_array($row['key'], ['bachelors', 'mabs']))
                                            <span id="badge-{{ $row['key'] }}" class="hidden block text-[10px] font-normal text-stone-400">(Required)</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <input type="number" name="academic[{{ $row['key'] }}][year]"
                                               value="{{ $inp($row['key'], 'year') }}"
                                               placeholder="2024" min="1990" max="2035"
                                               @if(in_array($row['key'], ['ssc', 'hssc'])) required @endif
                                               class="{{ $ic }}">
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <select name="academic[{{ $row['key'] }}][division]" class="{{ $ic }}">
                                            <option value="">—</option>
                                            <option value="1st" @selected($inp($row['key'],'division')==='1st')>1st</option>
                                            <option value="2nd" @selected($inp($row['key'],'division')==='2nd')>2nd</option>
                                            <option value="3rd" @selected($inp($row['key'],'division')==='3rd')>3rd</option>
                                            <option value="pass" @selected($inp($row['key'],'division')==='pass')>Pass</option>
                                        </select>
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <input type="number" name="academic[{{ $row['key'] }}][obtained]"
                                               value="{{ $inp($row['key'], 'obtained') }}"
                                               placeholder="850" min="0"
                                               @if(in_array($row['key'], ['ssc', 'hssc'])) required @endif
                                               class="{{ $ic }}">
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <input type="number" name="academic[{{ $row['key'] }}][total]"
                                               value="{{ $inp($row['key'], 'total') }}"
                                               placeholder="1100" min="0"
                                               @if(in_array($row['key'], ['ssc', 'hssc'])) required @endif
                                               class="{{ $ic }}">
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <input type="text" name="academic[{{ $row['key'] }}][major]"
                                               value="{{ $inp($row['key'], 'major') }}"
                                               placeholder="e.g. Biology"
                                               @if(in_array($row['key'], ['ssc', 'hssc'])) required @endif
                                               class="{{ $ic }}">
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <input type="text" name="academic[{{ $row['key'] }}][board]"
                                               value="{{ $inp($row['key'], 'board') }}"
                                               placeholder="e.g. BISE GB"
                                               @if(in_array($row['key'], ['ssc', 'hssc'])) required @endif
                                               class="{{ $ic }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ── Section 4: Documents to be Attached ── --}}
                <div class="rounded-xl border border-stone-200">
                    <div class="rounded-t-xl border-b border-stone-200 bg-brand px-5 py-3">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-white">4. Documents to be Attached</h2>
                    </div>
                    <div class="px-5 py-5 sm:px-6">
                        <p class="mb-4 text-xs text-stone-500">Upload scanned copies or clear photos. Accepted: PDF, JPG, PNG — max 4 MB each.</p>
                        <div class="grid gap-5 sm:grid-cols-2">

                            {{-- SSC DMC --}}
                            <div>
                                <label class="block text-sm font-medium text-ink">
                                    SSC / O Level DMC <span class="text-red-600">*</span>
                                    <span class="block text-xs font-normal text-stone-400">Attested Detailed Marks Certificate</span>
                                </label>
                                <input type="file" name="doc_ssc" required accept=".pdf,.jpg,.jpeg,.png"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm text-stone-600 outline-none file:mr-3 file:rounded file:border-0 file:bg-brand/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-brand hover:file:bg-brand/20 @error('doc_ssc') border-red-400 @enderror">
                                @error('doc_ssc')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- HSSC DMC --}}
                            <div>
                                <label class="block text-sm font-medium text-ink">
                                    HSSC / A Level DMC <span class="text-red-600">*</span>
                                    <span class="block text-xs font-normal text-stone-400">Attested Detailed Marks Certificate</span>
                                </label>
                                <input type="file" name="doc_hssc" required accept=".pdf,.jpg,.jpeg,.png"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm text-stone-600 outline-none file:mr-3 file:rounded file:border-0 file:bg-brand/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-brand hover:file:bg-brand/20 @error('doc_hssc') border-red-400 @enderror">
                                @error('doc_hssc')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                             {{-- Bachelors DMC (shown & required only for B.Ed 1.5 Year) --}}
                            <div id="bed-doc-bachelors" class="hidden">
                                <label class="block text-sm font-medium text-ink">
                                    Bachelors Degree / DMC <span class="text-red-600">*</span>
                                    <span class="block text-xs font-normal text-stone-400">Attested Detailed Marks Certificate</span>
                                </label>
                                <input type="file" name="doc_bachelors" id="doc_bachelors" accept=".pdf,.jpg,.jpeg,.png"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm text-stone-600 outline-none file:mr-3 file:rounded file:border-0 file:bg-brand/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-brand hover:file:bg-brand/20 @error('doc_bachelors') border-red-400 @enderror">
                                @error('doc_bachelors')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- MA/BS Certificate (shown & required only for B.Ed 1.5 Year) --}}
                            <div id="bed-doc-mabs" class="hidden">
                                <label class="block text-sm font-medium text-ink">
                                    MA / BS Certificate / DMC <span class="text-red-600">*</span>
                                    <span class="block text-xs font-normal text-stone-400">Attested Detailed Marks Certificate</span>
                                </label>
                                <input type="file" name="doc_mabs" id="doc_mabs" accept=".pdf,.jpg,.jpeg,.png"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm text-stone-600 outline-none file:mr-3 file:rounded file:border-0 file:bg-brand/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-brand hover:file:bg-brand/20 @error('doc_mabs') border-red-400 @enderror">
                                @error('doc_mabs')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Photograph --}}
                            <div>
                                <label class="block text-sm font-medium text-ink">
                                    Passport Size Photograph <span class="text-red-600">*</span>
                                    <span class="block text-xs font-normal text-stone-400">Recent photo — clear background</span>
                                </label>
                                <input type="file" name="doc_photo" required accept=".jpg,.jpeg,.png"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm text-stone-600 outline-none file:mr-3 file:rounded file:border-0 file:bg-brand/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-brand hover:file:bg-brand/20 @error('doc_photo') border-red-400 @enderror">
                                @error('doc_photo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- CNIC / Form B --}}
                            <div>
                                <label class="block text-sm font-medium text-ink">
                                    CNIC or Form 'B' <span class="text-red-600">*</span>
                                    <span class="block text-xs font-normal text-stone-400">Attested photocopy</span>
                                </label>
                                <input type="file" name="doc_cnic" required accept=".pdf,.jpg,.jpeg,.png"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm text-stone-600 outline-none file:mr-3 file:rounded file:border-0 file:bg-brand/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-brand hover:file:bg-brand/20 @error('doc_cnic') border-red-400 @enderror">
                                @error('doc_cnic')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Domicile Certificate (optional) --}}
                            <div>
                                <label class="block text-sm font-medium text-ink">
                                    Domicile Certificate
                                    <span class="ml-1 rounded bg-stone-100 px-1.5 py-0.5 text-[10px] font-medium text-stone-500">Optional</span>
                                    <span class="block text-xs font-normal text-stone-400">Attested copy</span>
                                </label>
                                <input type="file" name="doc_domicile" accept=".pdf,.jpg,.jpeg,.png"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm text-stone-600 outline-none file:mr-3 file:rounded file:border-0 file:bg-brand/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-brand hover:file:bg-brand/20">
                            </div>

                            {{-- Migration Certificate (optional) --}}
                            <div>
                                <label class="block text-sm font-medium text-ink">
                                    Migration Certificate
                                    <span class="ml-1 rounded bg-stone-100 px-1.5 py-0.5 text-[10px] font-medium text-stone-500">Optional</span>
                                    <span class="block text-xs font-normal text-stone-400">Required if board other than KIU</span>
                                </label>
                                <input type="file" name="doc_migration" accept=".pdf,.jpg,.jpeg,.png"
                                       class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm text-stone-600 outline-none file:mr-3 file:rounded file:border-0 file:bg-brand/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-brand hover:file:bg-brand/20">
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── Message (optional) ── --}}
                <div>
                    <label class="block text-sm font-medium text-ink">
                        Additional message / note <span class="text-xs text-stone-400">(optional)</span>
                    </label>
                    <textarea name="message" rows="3"
                              class="mt-1.5 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:border-brand focus:ring-2">{{ old('message') }}</textarea>
                </div>

                {{-- ── Declaration ── --}}
                <div class="rounded-xl border border-stone-200 bg-stone-50/60 px-5 py-5 sm:px-6">
                    <p class="mb-3 text-sm font-semibold text-ink">Declaration</p>
                    <p class="mb-4 text-xs leading-relaxed text-stone-600">
                        I declare that the information provided in this form is true and correct. I agree to abide by the rules and regulations of {{ $college->college_name ?? 'Jinnah School & Degree College Astore' }}. I undertake to maintain minimum 75% attendance, pay all dues on time, and accept that the Principal is authorised to take disciplinary action, including cancellation of admission, for violation of college rules or submission of forged documents.
                    </p>
                    <label class="flex cursor-pointer items-start gap-3 text-sm leading-relaxed text-stone-700">
                        <input type="checkbox" name="declare_true" required value="1" @checked(old('declare_true'))
                               class="mt-0.5 h-4 w-4 shrink-0 rounded border-stone-300 text-brand focus:ring-brand">
                        <span>I have read and agree to the declaration above. All information provided is accurate. <span class="text-red-600">*</span></span>
                    </label>
                    @error('declare_true')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- ── Submit ── --}}
                <div class="flex items-center justify-between border-t border-stone-200/80 pt-4">
                    <p class="text-xs text-stone-500">Fields marked <span class="text-red-600 font-bold">*</span> are required.</p>
                    <button type="submit"
                            class="site-brand-gradient rounded-lg px-8 py-3 text-sm font-semibold text-white shadow-md transition hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2">
                        Submit Application
                    </button>
                </div>
            </form>

            @endif

        </div>
    </section>

    {{-- Office info --}}
    <section class="bg-white py-10 md:py-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6">
            <div class="grid gap-8 sm:grid-cols-2">
                <div>
                    <h2 class="font-display text-lg font-semibold text-ink">Admission Office</h2>
                    <address class="mt-3 space-y-1 text-sm not-italic text-stone-600">
                        <p>{{ $college->college_name ?? 'Jinnah School & Degree College Astore' }}</p>
                        <p>Near Ali Murtaza Chowk, Eidgah Astore</p>
                        <p>Gilgit-Baltistan, Pakistan</p>
                    </address>
                </div>
                <div>
                    <h2 class="font-display text-lg font-semibold text-ink">Contact & Hours</h2>
                    <div class="mt-3 space-y-1 text-sm text-stone-600">
                        <p><a href="tel:03556025437" class="font-semibold text-brand hover:underline">03556025437</a></p>
                        <p><a href="tel:03554459888" class="font-semibold text-brand hover:underline">03554459888</a></p>
                        <p class="mt-2">Monday – Saturday, 9:00 am – 4:00 pm</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

@push('scripts')
<script>
(function () {
    // Academic rows that become required per program
    // key => required when the listed programs are selected
    var ACADEMIC_RULES = {
        bachelors: ['B.Ed 1.5 Year', 'B.Ed 2.5 Year'],
        mabs:      ['B.Ed 1.5 Year'],
    };

    // Document upload fields that appear per program
    var DOC_RULES = {
        bachelors: ['B.Ed 1.5 Year', 'B.Ed 2.5 Year'],
        mabs:      ['B.Ed 1.5 Year'],
    };

    function needs(rules, key, val) {
        return rules[key] && rules[key].indexOf(val) !== -1;
    }

    function updateFields() {
        var sel = document.getElementById('programSelect');
        if (!sel) return;
        var val = sel.value;

        // Academic rows
        Object.keys(ACADEMIC_RULES).forEach(function (key) {
            var required = needs(ACADEMIC_RULES, key, val);
            var row      = document.getElementById('academic-row-' + key);
            var badge    = document.getElementById('badge-' + key);
            if (!row) return;

            row.classList.toggle('bg-amber-50/40', required);
            if (badge) badge.classList.toggle('hidden', !required);

            row.querySelectorAll('input, select').forEach(function (el) {
                if (required) {
                    el.setAttribute('required', '');
                } else {
                    el.removeAttribute('required');
                }
            });
        });

        // Document upload fields
        Object.keys(DOC_RULES).forEach(function (key) {
            var required = needs(DOC_RULES, key, val);
            var wrap     = document.getElementById('bed-doc-' + key);
            var input    = document.getElementById('doc_' + key);
            if (!wrap) return;
            wrap.classList.toggle('hidden', !required);
            if (input) input.required = required;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var sel = document.getElementById('programSelect');
        if (sel) {
            sel.addEventListener('change', updateFields);
            updateFields(); // restore state for old() values after validation error
        }
    });
})();
</script>
@endpush

@endsection
