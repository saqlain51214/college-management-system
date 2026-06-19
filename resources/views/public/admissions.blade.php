@extends('layouts.public')
@section('title', 'Online Admission Form — ' . ($college->college_name ?? 'JDCA'))

@section('content')

    @php
        $normalizePhoneInput = function (?string $value): string {
            $digits = preg_replace('/\D+/', '', (string) $value) ?? '';

            if ($digits === '') {
                return '';
            }

            if (str_starts_with($digits, '92') && strlen($digits) === 12) {
                return substr($digits, 2);
            }

            if (str_starts_with($digits, '0') && strlen($digits) === 11) {
                return substr($digits, 1);
            }

            return $digits;
        };

        $oldGuardianPhone = $normalizePhoneInput(old('phone'));
        $oldStudentPhone = $normalizePhoneInput(old('student_phone'));
    @endphp

    <main id="main" tabindex="-1" class="site-main outline-none [&_h1]:font-display [&_h2]:font-display [&_h3]:font-sans [&_h4]:font-sans [&_h1]:tracking-tight [&_h2]:tracking-tight [&_h3]:tracking-tight [&_h4]:tracking-tight">
        <section class="relative overflow-hidden bg-ink pt-28 pb-12 text-white sm:pt-32 sm:pb-14" aria-labelledby="page-title">
            <div class="absolute inset-0 bg-[url('{{ asset('assets/images/photo-1562774053-701939374585.jpg') }}')] bg-cover bg-center opacity-25"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-brand/90 via-ink/95 to-ink"></div>
            <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
                <nav class="mb-4 text-xs text-white/70" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
                    <span class="mx-2 text-white/40" aria-hidden="true">/</span>
                    <span class="text-white">Admission Form</span>
                </nav>
                <h1 id="page-title" class="font-display text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">{{ $pageContent['intro_title'] ?? 'Online admission form' }}</h1>
                <p class="mt-3 max-w-2xl text-sm text-white/90 sm:text-base">{{ $pageContent['intro_text'] ?? 'Multi-step application aligned with common Pakistani college portals.' }}</p>
            </div>
        </section>

        @if(!empty($pageContent['body_html']))
            <section class="border-b border-stone-200/80 bg-white py-10 md:py-12">
                <div class="mx-auto max-w-4xl px-4 sm:px-6">
                    <div class="prose prose-stone max-w-none">
                        {!! $pageContent['body_html'] !!}
                    </div>
                </div>
            </section>
        @endif

        <section class="border-b border-stone-200/80 bg-surface py-10 md:py-12" aria-labelledby="before-you-apply">
            <div class="mx-auto max-w-4xl px-4 sm:px-6">
                <p id="before-you-apply" class="font-display text-lg font-semibold text-ink">Before you apply</p>
                <p class="mt-2 text-sm leading-relaxed text-stone-600">Read admission procedure — academic records. Flow: programme level → personal → contact → <strong class="text-ink">last exam record</strong> (matric only for 1st-year intermediate; matric + HSSC/12th for BS/ADP) → declaration.</p>
                <div class="mt-6 rounded-xl border border-amber-200/80 bg-amber-50/90 p-4 text-sm text-stone-800">
                    <p class="font-semibold text-ink">What previous study we need (matches <a href="{{ route('programs') }}" class="text-brand underline">academics programmes</a>)</p>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-stone-700">
                        <li><strong class="text-ink">Intermediate (FSc, FA, ICS, I.Com):</strong> you have completed or are completing <strong>matric</strong> (or O Level + IBCC). Step 4 asks for matric/O Level marks, board, roll number, school.</li>
                        <li><strong class="text-ink">BS / ADP (undergraduate):</strong> you have passed <strong>intermediate (12th / HSSC Part II)</strong> or A-Level (IBCC). Step 4 asks for a short <strong>matric</strong> summary plus full <strong>HSSC</strong> record (board, group, marks, roll, college).</li>
                    </ul>
                </div>
            </div>
        </section>

        <section id="online-application" class="border-b border-stone-200/80 bg-white py-12 md:py-16">
            <div class="mx-auto max-w-3xl px-4 sm:px-6">
                @php($oldProgramId = old('program_id'))
                <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-display text-xl font-semibold text-ink sm:text-2xl">Application steps</h2>
                        <p class="mt-1 text-sm text-stone-600">Complete each step. You can go back to edit before submitting.</p>
                    </div>
                </div>

                <ol class="mb-8 flex flex-wrap gap-2" id="admProgress" aria-label="Form progress">
                    <li><span class="adm-step-pill inline-flex min-w-[2.5rem] items-center justify-center rounded-full border-2 border-brand bg-brand px-3 py-1 text-xs font-bold text-white" data-step-i="0">1</span></li>
                    <li><span class="adm-step-pill inline-flex min-w-[2.5rem] items-center justify-center rounded-full border-2 border-stone-200 bg-white px-3 py-1 text-xs font-bold text-stone-500" data-step-i="1">2</span></li>
                    <li><span class="adm-step-pill inline-flex min-w-[2.5rem] items-center justify-center rounded-full border-2 border-stone-200 bg-white px-3 py-1 text-xs font-bold text-stone-500" data-step-i="2">3</span></li>
                    <li><span class="adm-step-pill inline-flex min-w-[2.5rem] items-center justify-center rounded-full border-2 border-stone-200 bg-white px-3 py-1 text-xs font-bold text-stone-500" data-step-i="3">4</span></li>
                    <li><span class="adm-step-pill inline-flex min-w-[2.5rem] items-center justify-center rounded-full border-2 border-stone-200 bg-white px-3 py-1 text-xs font-bold text-stone-500" data-step-i="4">5</span></li>
                </ol>

                @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <p class="font-semibold">Please fix the highlighted admission form errors and submit again.</p>
                </div>
                @endif

                <form id="admissionWizard" class="rounded-2xl border border-stone-200/80 bg-surface/50 p-6 shadow-sm sm:p-8" novalidate action="{{ route('admissions.inquiry') }}" method="POST">
                    @csrf
                    <div class="adm-panel space-y-5" data-adm-step="0">
                        <h3 class="font-display text-lg font-semibold text-ink">Step 1 — Level, programme &amp; campus</h3>
                        <label class="block text-sm font-medium text-ink">You are applying for <span class="text-red-600">*</span>
                            <select name="entry_path" id="entryPath" required class="mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                                <option value="">Select</option>
                                <option value="intermediate" @selected(old('entry_path') === 'intermediate')>Intermediate — 1st year (after matric / O Level)</option>
                                <option value="undergraduate" @selected(old('entry_path') === 'undergraduate')>Undergraduate — BS / ADP 1st year (after intermediate / A-Level)</option>
                            </select>
                        </label>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block text-sm font-medium text-ink">Gender <span class="text-red-600">*</span>
                                <select name="gender" required class="mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                                    <option value="">Select</option>
                                    <option value="female" @selected(old('gender') === 'female')>Female</option>
                                    <option value="male" @selected(old('gender') === 'male')>Male</option>
                                </select>
                            </label>
                            <label class="block text-sm font-medium text-ink">Preferred campus <span class="text-red-600">*</span>
                                <select name="campus" required class="mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                                    <option value="">Select campus</option>
                                    <option value="main" @selected(old('campus') === 'main')>{{ $college->city ?? 'Astore' }} (main)</option>
                                    <option value="other" @selected(old('campus') === 'other')>Other / undecided</option>
                                </select>
                            </label>
                        </div>
                        <label class="block text-sm font-medium text-ink">City / district <span class="text-red-600">*</span>
                            <input type="text" name="city" required value="{{ old('city') }}" placeholder="e.g. Astore" autocomplete="address-level2" class="mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                        </label>
                        <div id="wrapProgInter" class="hidden space-y-2">
                            <label class="block text-sm font-medium text-ink">Intermediate programme <span class="text-red-600">*</span>
                                <select name="program_id" id="selProgInter" class="mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                                    <option value="">Select programme</option>
                                    @foreach($intermediatePrograms as $prog)
                                        <option value="{{ $prog->id }}" @selected((string) $oldProgramId === (string) $prog->id)>{{ $prog->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                            @if($intermediatePrograms->isEmpty())
                                <p class="text-xs text-amber-700">No intermediate programmes are configured yet. Please update `Academic Programs` admin and set admission category to `Intermediate`.</p>
                            @endif
                        </div>
                        <div id="wrapProgUnder" class="hidden space-y-2">
                            <label class="block text-sm font-medium text-ink">Undergraduate programme / major <span class="text-red-600">*</span>
                                <select name="program_id" id="selProgUnder" class="mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                                    <option value="">Select programme</option>
                                    @foreach($undergraduatePrograms as $prog)
                                        <option value="{{ $prog->id }}" @selected((string) $oldProgramId === (string) $prog->id)>{{ $prog->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                            @if($undergraduatePrograms->isEmpty())
                                <p class="text-xs text-amber-700">No undergraduate programmes are configured yet. Please update `Academic Programs` admin and set admission category to `Undergraduate`.</p>
                            @endif
                        </div>
                    </div>

                    <div class="adm-panel hidden space-y-5" data-adm-step="1" hidden>
                        <h3 class="font-display text-lg font-semibold text-ink">Step 2 — Personal information</h3>
                        <label class="block text-sm font-medium text-ink">Full name (student) <span class="text-red-600">*</span>
                            <input type="text" name="name" required value="{{ old('name') }}" autocomplete="name" class="mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                        </label>
                        <label class="block text-sm font-medium text-ink">Father’s / guardian’s name <span class="text-red-600">*</span>
                            <input type="text" name="father_name" required value="{{ old('father_name') }}" class="mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                        </label>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block text-sm font-medium text-ink">Student CNIC / B-Form <span class="text-red-600">*</span>
                            <input type="text" name="cnic" required value="{{ old('cnic') }}" inputmode="numeric" placeholder="xxxxx-xxxxxxx-x" class="mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2" data-validate-pattern="cnic">
                            </label>
                            <label class="block text-sm font-medium text-ink">Date of birth <span class="text-red-600">*</span>
                                <input type="date" name="dob" required value="{{ old('dob') }}" class="mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                            </label>
                        </div>
                    </div>

                    <div class="adm-panel hidden space-y-5" data-adm-step="2" hidden>
                        <h3 class="font-display text-lg font-semibold text-ink">Step 3 — Contact</h3>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block text-sm font-medium text-ink">Guardian mobile <span class="text-red-600">*</span>
                                <div class="mt-1.5 flex rounded-md border border-stone-200 bg-white ring-brand/20 focus-within:ring-2">
                                    <span class="inline-flex items-center border-r border-stone-200 bg-surface px-3 text-sm font-semibold text-ink">+92</span>
                                    <input type="tel" name="phone" required value="{{ $oldGuardianPhone }}" placeholder="312-3456789" autocomplete="tel" class="w-full rounded-r-md border-0 bg-transparent px-3 py-2 text-sm outline-none focus:ring-0" data-validate-pattern="phone_pk">
                                </div>
                            </label>
                            <label class="block text-sm font-medium text-ink">Student mobile
                                <div class="mt-1.5 flex rounded-md border border-stone-200 bg-white ring-brand/20 focus-within:ring-2">
                                    <span class="inline-flex items-center border-r border-stone-200 bg-surface px-3 text-sm font-semibold text-ink">+92</span>
                                    <input type="tel" name="student_phone" value="{{ $oldStudentPhone }}" placeholder="312-3456789" class="w-full rounded-r-md border-0 bg-transparent px-3 py-2 text-sm outline-none focus:ring-0" data-validate-pattern="phone_pk">
                                </div>
                            </label>
                        </div>
                        <label class="block text-sm font-medium text-ink">Email <span class="text-red-600">*</span>
                            <input type="email" name="email" required value="{{ old('email') }}" autocomplete="email" class="mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                        </label>
                        <label class="block text-sm font-medium text-ink">Complete mailing address <span class="text-red-600">*</span>
                            <textarea name="address" required rows="3" class="mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">{{ old('address') }}</textarea>
                        </label>
                    </div>

                    <div class="adm-panel hidden space-y-6" data-adm-step="3" hidden>
                        <h3 class="font-display text-lg font-semibold text-ink">Step 4 — Academic record</h3>
                        <fieldset id="admFsMatric" class="space-y-4 border-0 p-0">
                            <legend class="mb-2 font-semibold text-ink">For intermediate applicants — matric / O Level (last completed exam)</legend>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <label class="block text-sm font-medium text-ink">Degree <span class="text-red-600">*</span>
                                    <select name="academic[matric][qualification]" required class="adm-matric-req mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                                        <option value="">Select</option>
                                        <option value="matric" @selected(old('academic.matric.qualification') === 'matric')>Matriculation (10th)</option>
                                        <option value="olevel" @selected(old('academic.matric.qualification') === 'olevel')>O Level (IBCC equivalence required)</option>
                                        <option value="other" @selected(old('academic.matric.qualification') === 'other')>Other</option>
                                    </select>
                                </label>
                                <label class="block text-sm font-medium text-ink">Passing year <span class="text-red-600">*</span>
                                    <input type="number" name="academic[matric][pass_year]" required min="2018" max="2035" value="{{ old('academic.matric.pass_year') }}" class="adm-matric-req mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                                </label>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <label class="block text-sm font-medium text-ink">Board <span class="text-red-600">*</span>
                                    <input type="text" name="academic[matric][board]" value="{{ old('academic.matric.board') }}" placeholder="e.g. BISE Gilgit" required class="adm-matric-req mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                                </label>
                                <label class="block text-sm font-medium text-ink">Total marks / Obtained <span class="text-red-600">*</span>
                                    <input type="text" name="academic[matric][marks]" required value="{{ old('academic.matric.marks') }}" placeholder="e.g. 850/1100" class="adm-matric-req mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2" data-validate-pattern="marks_fraction">
                                </label>
                            </div>
                            <label class="block text-sm font-medium text-ink">School name (last attended) <span class="text-red-600">*</span>
                                <input type="text" name="academic[matric][school]" required value="{{ old('academic.matric.school') }}" class="adm-matric-req mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                            </label>
                        </fieldset>

                        <fieldset id="admFsHssc" class="hidden space-y-5 border-0 p-0" disabled>
                            <legend class="mb-2 font-semibold text-ink">For undergraduate applicants — matric summary + intermediate (12th / HSSC)</legend>
                            <p class="text-xs text-stone-500">You must have passed HSSC Part II (2nd year intermediate) or A-Level with IBCC.</p>
                            <div class="grid gap-4 sm:grid-cols-3">
                                <label class="block text-sm font-medium text-ink">Matric board summary <span class="text-red-600">*</span>
                                    <input type="text" name="academic[matric_summary][board]" required value="{{ old('academic.matric_summary.board') }}" placeholder="e.g. BISE Gilgit" class="adm-hssc-req mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                                </label>
                                <label class="block text-sm font-medium text-ink">Matric passing year <span class="text-red-600">*</span>
                                    <input type="number" name="academic[matric_summary][pass_year]" required min="2018" max="2035" value="{{ old('academic.matric_summary.pass_year') }}" class="adm-hssc-req mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                                </label>
                                <label class="block text-sm font-medium text-ink">Matric marks <span class="text-red-600">*</span>
                                    <input type="text" name="academic[matric_summary][marks]" required value="{{ old('academic.matric_summary.marks') }}" placeholder="e.g. 930/1100" class="adm-hssc-req mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2" data-validate-pattern="marks_fraction">
                                </label>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <label class="block text-sm font-medium text-ink">Qualification <span class="text-red-600">*</span>
                                    <select name="academic[hssc][qualification]" required class="adm-hssc-req mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                                        <option value="">Select</option>
                                        <option value="hssc" @selected(old('academic.hssc.qualification') === 'hssc')>HSSC / FA–FSc (2nd year passed)</option>
                                        <option value="alevel" @selected(old('academic.hssc.qualification') === 'alevel')>A-Level (IBCC equivalence)</option>
                                        <option value="dae" @selected(old('academic.hssc.qualification') === 'dae')>DAE / other</option>
                                    </select>
                                </label>
                                <label class="block text-sm font-medium text-ink">Passing year (Part II) <span class="text-red-600">*</span>
                                    <input type="number" name="academic[hssc][pass_year]" required min="2018" max="2035" value="{{ old('academic.hssc.pass_year') }}" class="adm-hssc-req mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                                </label>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <label class="block text-sm font-medium text-ink">Board <span class="text-red-600">*</span>
                                    <input type="text" name="academic[hssc][board]" value="{{ old('academic.hssc.board') }}" placeholder="e.g. BISE Gilgit" required class="adm-hssc-req mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                                </label>
                                <label class="block text-sm font-medium text-ink">Total marks / Obtained <span class="text-red-600">*</span>
                                    <input type="text" name="academic[hssc][marks]" required value="{{ old('academic.hssc.marks') }}" placeholder="e.g. 850/1100" class="adm-hssc-req mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2" data-validate-pattern="marks_fraction">
                                </label>
                            </div>
                            <label class="block text-sm font-medium text-ink">College attended (for intermediate) <span class="text-red-600">*</span>
                                <input type="text" name="academic[hssc][school]" required value="{{ old('academic.hssc.school') }}" class="adm-hssc-req mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">
                            </label>
                        </fieldset>
                    </div>

                    <div class="adm-panel hidden space-y-5" data-adm-step="4" hidden>
                        <h3 class="font-display text-lg font-semibold text-ink">Step 5 — Declaration</h3>
                        <label class="block text-sm font-medium text-ink">Message / Note (optional)
                            <textarea name="message" rows="3" class="mt-1.5 w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-sm outline-none ring-brand/20 focus:ring-2">{{ old('message') }}</textarea>
                        </label>
                        <label class="flex cursor-pointer gap-3 text-sm leading-relaxed text-stone-700">
                            <input type="checkbox" name="declare_true" required value="1" @checked(old('declare_true')) class="mt-1 h-4 w-4 shrink-0 rounded border-stone-300 text-brand focus:ring-brand">
                            <span>I confirm that the information provided is true. I agree to the college rules, fee policy, and use of my data for admission processing. <span class="text-red-600">*</span></span>
                        </label>
                    </div>

                    <p id="admFormError" class="mt-4 hidden text-sm font-medium text-red-700" role="alert"></p>

                    <div class="mt-8 flex flex-col-reverse gap-3 border-t border-stone-200/80 pt-6 sm:flex-row sm:justify-between" id="admNavButtons">
                        <button type="button" id="admBack" class="hidden rounded-md border border-stone-200 bg-white px-5 py-2.5 text-sm font-semibold text-ink transition hover:bg-surface">Back</button>
                        <div class="flex gap-3 sm:ml-auto">
                            <button type="button" id="admNext" class="rounded-md bg-brand px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2">Next step</button>
                            <button type="submit" id="admSubmit" class="hidden rounded-md bg-brand px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2">Submit application</button>
                        </div>
                    </div>
                </form>

                <div id="admSuccess" class="mt-8 rounded-2xl border border-green-200 bg-green-50/90 p-6 sm:p-8 {{ session('success') ? '' : 'hidden' }}" role="status" aria-live="polite">
                    <h3 class="font-display text-xl font-semibold text-ink">Application received</h3>
                    <p class="mt-2 text-sm text-stone-700" data-adm-success-message>{{ session('success') }}</p>
                    <ul class="mt-4 list-inside list-disc space-y-1 text-sm text-stone-600">
                        <li>Visit <strong class="text-ink">Admission Office, {{ $college->address ?? 'Astore' }}</strong> within the published dates.</li>
                        <li>Carry original certificates, CNIC/B-Form copies, and photographs.</li>
                    </ul>
                    <a href="{{ route('admissions') }}" class="mt-6 inline-block rounded-md border border-stone-200 bg-white px-4 py-2 text-sm font-semibold text-brand transition hover:bg-stone-50">Start new application</a>
                </div>
            </div>
        </section>

        <section class="bg-white py-12 md:py-16">
            <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6">
                <h2 class="font-display text-xl font-semibold text-ink">Offline / paper application</h2>
                <p class="text-sm leading-relaxed text-stone-600">You can also visit the admission office to get a <strong class="text-ink">printed form with prospectus</strong>.</p>
                <h2 class="font-display text-xl font-semibold text-ink">Office hours</h2>
                <p class="text-sm leading-relaxed text-stone-600">Monday–Saturday, 9:00 a.m. – 4:00 p.m. Phone: <a href="tel:{{ str_replace(' ', '', $college->phone ?? '') }}" class="font-semibold text-brand hover:underline">{{ $college->phone ?? '' }}</a>.</p>
            </div>
        </section>

    </main>

@endsection

@push('scripts')
    <script id="admissionValidationConfig" type="application/json">@json($admissionValidation)</script>
    <script id="admissionServerErrors" type="application/json">@json($errors->messages())</script>
    <script src="{{ asset('assets/js/admission-wizard.js') }}" defer></script>
@endpush
