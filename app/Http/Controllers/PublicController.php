<?php

namespace App\Http\Controllers;

use App\Mail\AdmissionInquiryAcknowledgementMail;
use App\Mail\AdmissionInquiryOfficeNotificationMail;
use App\Mail\ContactMessageAcknowledgementMail;
use App\Mail\ContactMessageOfficeNotificationMail;
use App\Models\AcademicProgram;
use App\Models\AdmissionInquiry;
use App\Models\Department;
use App\Models\Announcement;
use App\Models\CollegeSetting;
use App\Models\ContactMessage;
use App\Models\HomeSection;
use App\Models\NewsArticle;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\WebsitePage;
use App\Models\WebsiteEvent;
use App\Support\AdmissionValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{
    private function college(): object
    {
        $name        = CollegeSetting::get('college_name',        'Jinnah Degree College Astore');
        $short       = CollegeSetting::get('college_short_name',  'JDCA');
        $address     = CollegeSetting::get('college_address',     'Distt. Astore Village Eidgah, Near Ali Murtaza Chowk, Astore, GB');
        $phone       = CollegeSetting::get('college_phone',       '+923129776585');
        $email       = CollegeSetting::get('college_email',       'jinnahschooldegreecollege@gmail.com');
        $principal   = CollegeSetting::get('college_principal',   'Arif Ali');
        $affiliation = CollegeSetting::get('college_affiliation', 'Karakoram International University');

        $logoPath = CollegeSetting::get('college_logo');
        $logoUrl  = $logoPath ? asset('storage/' . $logoPath) : null;

        return (object) [
            // canonical names
            'name'             => $name,
            'short_name'       => $short,
            'tagline'          => 'Empowering Minds, Building Futures in Gilgit-Baltistan',
            'address'          => $address,
            'city'             => CollegeSetting::get('college_city', 'Astore, Gilgit Baltistan 14300'),
            'phone'            => $phone,
            'email'            => $email,
            'website'          => CollegeSetting::get('college_website', 'https://jdca.edu.pk'),
            'principal'        => $principal,
            'affiliation'      => $affiliation,
            'logo_url'         => $logoUrl,
            // aliases used by views
            'college_name'     => $name,
            'college_short'    => $short,
            'principal_name'   => $principal,
            'established_year' => (int) CollegeSetting::get('college_established', 2010),
        ];
    }

    private function cmsPage(string $slug): WebsitePage
    {
        $page = WebsitePage::query()->where('slug', $slug)->first();

        // Admins (logged into the panel) may preview unpublished pages via ?preview=1,
        // so content can be reviewed privately before it is published to visitors.
        $isAdminPreview = request()->boolean('preview') && auth()->guard('web')->check();

        if ($page && $slug !== 'home' && ! $page->is_published && ! $isAdminPreview) {
            abort(404);
        }

        if (! $page) {
            $definition = WebsitePage::staticPageDefinitions()[$slug] ?? ['title' => 'Website Page', 'sort' => 0, 'in_menu' => false];
            $page = new WebsitePage([
                'title' => $definition['title'],
                'slug' => $slug,
                'sort_order' => $definition['sort'],
                'in_menu' => $definition['in_menu'],
                'is_published' => true,
                'content' => [],
            ]);
        }

        $page->content = array_replace_recursive(
            WebsitePage::defaultContentFor($slug),
            is_array($page->content) ? $page->content : []
        );

        return $page;
    }

    private function homeSections(): array
    {
        $records = HomeSection::query()->ordered()->get()->keyBy('key');
        $sections = [];

        foreach (HomeSection::definitions() as $key => $definition) {
            $record = $records->get($key);
            $sections[$key] = [
                'is_active' => $record?->is_active ?? true,
                'title' => $record?->title ?? $definition['title'],
                'content' => array_replace_recursive(
                    HomeSection::defaultContentFor($key),
                    is_array($record?->content) ? $record->content : []
                ),
            ];
        }

        return $sections;
    }

    public function home()
    {
        $college  = $this->college();
        $cmsPage  = $this->cmsPage('home');
        $pageContent = $cmsPage->content;
        $homeSections = $this->homeSections();
        $programs = AcademicProgram::active()->limit(6)->get();
        $news     = NewsArticle::where('is_published', true)->orderByDesc('published_date')->limit(3)->get();
        $events   = WebsiteEvent::published()->orderBy('start_datetime')->limit(4)->get();
        $notices  = Announcement::where('is_published', true)
            ->where(fn($q) => $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()))
            ->orderByDesc('publish_date')->limit(5)->get();
        $establishedYear = (int) CollegeSetting::get('college_established', 2010);
        $stats = [
            'students' => Student::where('status', 'active')->count(),
            'teachers' => Teacher::where('is_active', true)->count(),
            'programs' => AcademicProgram::active()->count(),
            'departments' => Department::where('is_active', true)->where('show_on_website', true)->count(),
            'years_of_excellence' => max(0, now()->year - $establishedYear),
        ];
        $pageContent['programs']['stats'] = [
            ['value' => number_format($stats['students']) . '+', 'label' => 'Active Students'],
            ['value' => $pageContent['programs']['stats'][1]['value'] ?? '98%', 'label' => $pageContent['programs']['stats'][1]['label'] ?? 'Graduate Rate'],
            ['value' => number_format($stats['programs']) . '+', 'label' => 'Programs Offered'],
        ];
        $pageContent['about']['stats'] = [
            ['value' => number_format($stats['students']) . '+', 'label' => 'Students'],
            ['value' => $pageContent['about']['stats'][1]['value'] ?? '98%', 'label' => $pageContent['about']['stats'][1]['label'] ?? 'Success'],
            ['value' => number_format($stats['teachers']) . '+', 'label' => 'Faculty'],
        ];
        return view('public.home', compact('college', 'cmsPage', 'pageContent', 'homeSections', 'programs', 'news', 'events', 'notices', 'stats'));
    }

    public function about()
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('about');
        $pageContent = $cmsPage->content;
        $stats   = [
            'students' => Student::where('status', 'active')->count(),
            'teachers' => Teacher::where('is_active', true)->count(),
            'programs' => AcademicProgram::active()->count(),
        ];
        if (isset($pageContent['stats'])) {
            $pageContent['stats'][0]['value'] = number_format($stats['students']) . '+';
            $pageContent['stats'][2]['value'] = number_format($stats['teachers']) . '+';
        }
        return view('public.about', compact('college', 'cmsPage', 'pageContent', 'stats'));
    }

    public function programs()
    {
        $college  = $this->college();
        $cmsPage  = $this->cmsPage('programs');
        $pageContent = $cmsPage->content;
        $programs = AcademicProgram::active()->orderBy('sort_order')->orderBy('name')->get();
        return view('public.programs', compact('college', 'cmsPage', 'pageContent', 'programs'));
    }

    public function faculty()
    {
        $college  = $this->college();
        $cmsPage  = $this->cmsPage('faculty');
        $pageContent = $cmsPage->content;
        $teachers = Teacher::where('is_active', true)->orderBy('designation')->orderBy('name')->get();
        return view('public.faculty', compact('college', 'cmsPage', 'pageContent', 'teachers'));
    }

    public function admissions()
    {
        $college     = $this->college();
        $cmsPage     = $this->cmsPage('admissions');
        $pageContent = $cmsPage->content;
        $allPrograms = collect();

        // Same DB source as the navbar so the form and menu never drift apart.
        $departments = Department::visible()->ordered()
            ->with(['academicPrograms' => fn ($q) => $q->where('is_active', true)->where('show_on_website', true)->orderBy('sort_order')->orderBy('name')])
            ->get();

        return view('public.admissions', compact(
            'college',
            'cmsPage',
            'pageContent',
            'allPrograms',
            'departments'
        ));
    }

    public function contact()
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('contact');
        $pageContent = $cmsPage->content;
        return view('public.contact', compact('college', 'cmsPage', 'pageContent'));
    }

    public function contactSend(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        $contactMessage = ContactMessage::create($request->only(['name', 'email', 'subject', 'message']));

        Mail::to($contactMessage->email)->queue(new ContactMessageAcknowledgementMail($contactMessage));

        $contactRecipient = config('platform.notifications.contact_recipient');

        if (filled($contactRecipient)) {
            Mail::to($contactRecipient)->queue(new ContactMessageOfficeNotificationMail($contactMessage));
        }

        return back()->with('success', 'Thank you! Your message has been received. We will get back to you shortly.');
    }

    public function admissionInquiry(Request $request)
    {
        $validated = $request->validate([
            'semester'          => 'required|string',
            'program_type'      => 'nullable|string',
            'program_name'      => 'required|string',
            'name'              => 'required|string',
            'cnic'              => 'required|string',
            'gender'            => 'required|string',
            'dob'               => 'required|string',
            'student_phone'     => 'required|string',
            'father_name'       => 'required|string',
            'father_occupation' => 'nullable|string',
            'father_phone'      => 'nullable|string',
            'guardian_name'     => 'nullable|string',
            'guardian_phone'    => 'nullable|string',
            'address'           => 'required|string',
            'district'          => 'required|string',
            'tehsil'            => 'nullable|string',
            'village'           => 'nullable|string',
            'post_office'       => 'nullable|string',
            'email'             => 'required|string',
            'academic'          => 'nullable|array',
            'declare_true'      => 'required',
            'message'           => 'nullable|string',
            'doc_ssc'           => 'required|file|max:4096',
            'doc_hssc'          => 'required|file|max:4096',
            'doc_photo'         => 'required|file|max:2048',
            'doc_cnic'          => 'required|file|max:4096',
            'doc_domicile'      => 'nullable|file|max:4096',
            'doc_migration'     => 'nullable|file|max:4096',
            'doc_bachelors'     => 'nullable|file|max:4096',
            'doc_mabs'          => 'nullable|file|max:4096',
        ]);

        $academicDetails = $validated['academic'] ?? [];
        $hsscData = $academicDetails['hssc'] ?? [];
        $primaryQualification = filled($hsscData['board'] ?? null) ? 'hssc' : 'ssc';

        $docSlots = [
            'doc_ssc'       => 'SSC / O Level DMC',
            'doc_hssc'      => 'HSSC / A Level DMC',
            'doc_photo'     => 'Passport Photograph',
            'doc_cnic'      => 'CNIC / Form B',
            'doc_domicile'  => 'Domicile Certificate',
            'doc_migration' => 'Migration Certificate',
            'doc_bachelors' => 'Bachelors Degree / DMC',
            'doc_mabs'      => 'MA / BS Certificate / DMC',
        ];
        $storedDocs = [];
        foreach ($docSlots as $field => $label) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('admissions/docs', 'public');
                $storedDocs[$field] = ['label' => $label, 'path' => $path];
            }
        }

        $admissionInquiry = AdmissionInquiry::create([
            'reference_no'      => 'JDCA-' . now()->format('Y') . '-' . strtoupper(Str::random(8)),
            'name'              => $validated['name'],
            'father_name'       => $validated['father_name'],
            'father_occupation' => $validated['father_occupation'] ?? null,
            'father_phone'      => AdmissionValidation::normalizePhone($validated['father_phone'] ?? null),
            'guardian_name'     => $validated['guardian_name'] ?? null,
            'guardian_phone'    => AdmissionValidation::normalizePhone($validated['guardian_phone'] ?? null),
            'email'             => $validated['email'],
            'phone'             => AdmissionValidation::normalizePhone($validated['guardian_phone'] ?? null),
            'student_phone'     => AdmissionValidation::normalizePhone($validated['student_phone']),
            'cnic'              => $validated['cnic'],
            'dob'               => $validated['dob'],
            'entry_path'        => 'undergraduate',
            'semester'          => $validated['semester'],
            'program_type'      => $validated['program_type'] ?? null,
            'gender'            => $validated['gender'],
            'campus'            => 'main',
            'city'              => $validated['district'],
            'district'          => $validated['district'],
            'tehsil'            => $validated['tehsil'] ?? null,
            'village'           => $validated['village'] ?? null,
            'post_office'       => $validated['post_office'] ?? null,
            'address'           => $validated['address'],
            'program_id'        => null,
            'program_name'      => $validated['program_name'],
            'qualification'     => $primaryQualification,
            'academic_details'  => $academicDetails,
            'documents'         => $storedDocs ?: null,
            'declare_true'      => true,
            'message'           => $validated['message'] ?? null,
            'status'            => 'new',
        ]);

        if (filled($admissionInquiry->email)) {
            Mail::to($admissionInquiry->email)->queue(new AdmissionInquiryAcknowledgementMail($admissionInquiry));
        }

        $admissionsRecipient = config('platform.notifications.admissions_recipient');

        if (filled($admissionsRecipient)) {
            Mail::to($admissionsRecipient)->queue(new AdmissionInquiryOfficeNotificationMail($admissionInquiry));
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Your admission application has been submitted successfully. Please wait for admission office review and keep your documents ready.',
            ], 201);
        }

        return back()->with('success', 'Your admission application has been submitted successfully. Please wait for admission office review and keep your documents ready.');
    }

    public function news(Request $request)
    {
        $college  = $this->college();
        $cmsPage  = $this->cmsPage('news');
        $pageContent = $cmsPage->content;
        $featured = NewsArticle::where('is_published', true)->where('is_featured', true)->latest('published_date')->first();
        $articles = NewsArticle::where('is_published', true)
            ->when($request->category, fn($q, $cat) => $q->where('category', $cat))
            ->orderByDesc('published_date')
            ->paginate(9);
        return view('public.news', compact('college', 'cmsPage', 'pageContent', 'articles', 'featured'));
    }

    public function newsDetail(string $slug)
    {
        $college = $this->college();
        $article = NewsArticle::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $article->increment('views');
        $related = NewsArticle::where('is_published', true)
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->latest('published_date')->limit(3)->get();
        return view('public.news-detail', compact('college', 'article', 'related'));
    }

    public function notices()
    {
        $college  = $this->college();
        $cmsPage  = $this->cmsPage('notices');
        $pageContent = $cmsPage->content;
        $notices  = Announcement::where('is_published', true)
            ->where(fn($q) => $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()))
            ->orderByDesc('publish_date')
            ->paginate(15);
        return view('public.notices', compact('college', 'cmsPage', 'pageContent', 'notices'));
    }

    public function gallery()
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('gallery');
        $pageContent = $cmsPage->content;
        $galleryImages = collect($pageContent['gallery_items'] ?? [])->map(function (array $item) {
            $image = $item['image'] ?? '';

            return [
                'src' => str_starts_with($image, 'assets/')
                    ? asset($image)
                    : \Illuminate\Support\Facades\Storage::url($image),
                'title' => $item['title'] ?? 'Gallery Item',
                'desc' => $item['caption'] ?? '',
                'category' => $item['category'] ?? 'campus',
            ];
        })->values()->all();
        return view('public.gallery', compact('college', 'cmsPage', 'pageContent', 'galleryImages'));
    }

    public function events()
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('events');
        $pageContent = $cmsPage->content;
        $events = WebsiteEvent::published()
            ->orderBy('start_datetime')
            ->paginate(12);
        return view('public.events', compact('college', 'cmsPage', 'pageContent', 'events'));
    }

    // ─── About sub-pages ────────────────────────────────────────────────────

    public function aboutHistory()
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('about-history');
        return view('public.about-history', compact('college', 'cmsPage'));
    }

    public function aboutMission()
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('about-mission');
        return view('public.about-mission', compact('college', 'cmsPage'));
    }

    public function aboutMessage()
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('about-message');
        return view('public.about-message', compact('college', 'cmsPage'));
    }

    public function aboutDirector()
    {
        $college = $this->college();
        return view('public.about-director', compact('college'));
    }

    public function aboutPrincipal()
    {
        $college = $this->college();
        return view('public.about-principal', compact('college'));
    }

    // ─── Academics sub-pages ────────────────────────────────────────────────

    public function departments()
    {
        $college     = $this->college();
        $departments = Department::visible()->ordered()->get();
        return view('public.departments', compact('college', 'departments'));
    }

    public function departmentDetail(string $slug)
    {
        $college    = $this->college();
        $department = Department::visible()->where('slug', $slug)->firstOrFail();
        $teachers   = $department->teachers()->get();
        $programs   = $department->academicPrograms()->get();
        $outlines   = \Illuminate\Support\Facades\Schema::hasTable('course_outlines')
            ? $department->courseOutlines()->get()->groupBy('semester_number')
            : collect();
        return view('public.department-detail', compact('college', 'department', 'teachers', 'programs', 'outlines'));
    }

    public function courseOutlines()
    {
        $college = $this->college();

        if (! \Illuminate\Support\Facades\Schema::hasTable('course_outlines')) {
            $departments = collect();
            return view('public.course-outlines', compact('college', 'departments'));
        }

        $departments = Department::visible()->ordered()
            ->with(['courseOutlines' => fn ($q) => $q->orderBy('semester_number')->orderBy('sort_order')])
            ->get()
            ->filter(fn ($d) => $d->courseOutlines->isNotEmpty());
        return view('public.course-outlines', compact('college', 'departments'));
    }

    public function campusFacilities()
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('campus-facilities');
        return view('public.campus-facilities', compact('college', 'cmsPage'));
    }

    public function downloads()
    {
        $college = $this->college();
        $downloads = [];
        if (class_exists(\App\Models\Download::class)) {
            $downloads = \App\Models\Download::where('is_active', true)
                ->orderBy('category')->orderBy('sort_order')->orderBy('title')
                ->get()->groupBy('category');
        }
        return view('public.downloads', compact('college', 'downloads'));
    }

    public function search(Request $request)
    {
        $college = $this->college();
        $q = trim($request->get('q', ''));
        $results = collect();

        if (strlen($q) >= 2) {
            $news = NewsArticle::where('is_published', true)
                ->where(fn($query) => $query->where('title', 'like', "%{$q}%")->orWhere('content', 'like', "%{$q}%"))
                ->select('id', 'title', 'slug', 'excerpt', 'published_date')
                ->latest('published_date')->take(5)->get()
                ->map(fn($r) => ['type' => 'News', 'title' => $r->title, 'excerpt' => Str::limit($r->excerpt ?? strip_tags($r->content ?? ''), 100), 'url' => route('news.show', $r->slug), 'date' => optional($r->published_date)->format('d M Y')]);

            $notices = Announcement::where('is_published', true)
                ->where('title', 'like', "%{$q}%")
                ->select('id', 'title', 'publish_date')->latest('publish_date')->take(5)->get()
                ->map(fn($r) => ['type' => 'Notice', 'title' => $r->title, 'excerpt' => '', 'url' => route('notices'), 'date' => optional(\Carbon\Carbon::parse($r->publish_date))->format('d M Y')]);

            $events = WebsiteEvent::where('title', 'like', "%{$q}%")
                ->select('id', 'title', 'start_datetime')->latest('start_datetime')->take(5)->get()
                ->map(fn($r) => ['type' => 'Event', 'title' => $r->title, 'excerpt' => '', 'url' => route('events'), 'date' => optional($r->start_datetime)->format('d M Y')]);

            $depts = Department::where('show_on_website', true)->where('is_active', true)
                ->where(fn($query) => $query->where('name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%"))
                ->select('id', 'name', 'slug')->take(4)->get()
                ->map(fn($r) => ['type' => 'Department', 'title' => $r->name, 'excerpt' => '', 'url' => route('departments.show', $r->slug), 'date' => null]);

            $programs = AcademicProgram::where('is_active', true)->where('show_on_website', true)
                ->where(fn($query) => $query->where('name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%"))
                ->select('id', 'name', 'short_name', 'description')->take(4)->get()
                ->map(fn($r) => ['type' => 'Program', 'title' => $r->name, 'excerpt' => Str::limit($r->description ?? '', 80), 'url' => route('programs'), 'date' => null]);

            $results = $news->concat($notices)->concat($events)->concat($depts)->concat($programs);
        }

        return view('public.search', compact('college', 'q', 'results'));
    }

    // ─── Admission sub-pages ────────────────────────────────────────────────

    public function admissionProcedure()
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('admission-procedure');
        return view('public.admission-procedure', compact('college', 'cmsPage'));
    }

    public function feeStructurePublic()
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('fee-structure');
        return view('public.fee-structure-public', compact('college', 'cmsPage'));
    }

    public function semesterRules()
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('semester-rules');
        return view('public.semester-rules', compact('college', 'cmsPage'));
    }

    public function scholarships()
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('scholarships');
        return view('public.scholarships', compact('college', 'cmsPage'));
    }

    public function scholarshipDetail(string $type)
    {
        $college = $this->college();
        $types   = ['merit', 'need', 'orphan', 'special'];
        if (! in_array($type, $types)) {
            abort(404);
        }
        return view('public.scholarship-detail', compact('college', 'type'));
    }

    // ─── Fee Challan self-service download ────────────────────────────────────

    public function feeChallanDownload()
    {
        $college = $this->college();

        return view('public.fee-challan-download', compact('college'));
    }

    public function feeChallanLookup(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string', 'max:60'],
        ]);

        $college = $this->college();
        $identifier = trim($request->input('identifier'));

        $student = Student::where('roll_number', $identifier)
            ->orWhere('registration_number', $identifier)
            ->first();

        $result = null;
        if ($student) {
            $unpaid = \App\Models\FeePayment::where('student_id', $student->id)
                ->where('payment_status', '!=', 'paid')
                ->orderByRaw('due_date is null, due_date asc')
                ->get();

            $paidThisMonth = \App\Models\FeePayment::where('student_id', $student->id)
                ->where('payment_status', 'paid')
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->orderByDesc('payment_date')
                ->get();

            $result = compact('student', 'unpaid', 'paidThisMonth');
        }

        return view('public.fee-challan-download', [
            'college'    => $college,
            'result'     => $result,
            'searched'   => true,
            'identifier' => $identifier,
            'notFound'   => $student === null,
        ]);
    }

    public function feeChallanPdf(Request $request, \App\Models\FeePayment $payment)
    {
        // Light guard: the roll/registration number must be supplied and match,
        // so challan PDFs cannot be enumerated by guessing IDs.
        $ref = trim((string) $request->query('ref', ''));
        $student = $payment->student;

        if (! $student || ($ref !== $student->roll_number && $ref !== $student->registration_number)) {
            abort(403);
        }

        return app(PdfController::class)->feeChallan($payment);
    }

    // ─── Jobs ────────────────────────────────────────────────────────────────

    public function jobs()
    {
        $college = $this->college();
        return view('public.jobs', compact('college'));
    }

    public function jobApply(Request $request)
    {
        $data = $request->validate([
            'position'    => ['required', 'string', 'max:120'],
            'name'        => ['required', 'string', 'max:100'],
            'email'       => ['required', 'email', 'max:120'],
            'phone'       => ['required', 'string', 'max:30'],
            'education'   => ['required', 'string', 'max:200'],
            'experience'  => ['nullable', 'string', 'max:200'],
            'message'     => ['required', 'string', 'max:1000'],
        ]);

        $college      = $this->college();
        $officeEmail  = $college->email ?? CollegeSetting::get('college_email', 'jinnahschooldegreecollege@gmail.com');

        try {
            Mail::raw(
                "New Job Application Received\n\n"
                . "Position Applied For: {$data['position']}\n"
                . "Name: {$data['name']}\n"
                . "Email: {$data['email']}\n"
                . "Phone: {$data['phone']}\n"
                . "Education: {$data['education']}\n"
                . "Experience: " . ($data['experience'] ?? 'Not specified') . "\n"
                . "Cover Letter:\n{$data['message']}\n\n"
                . "Submitted at: " . now()->format('d M Y, h:i A'),
                fn ($m) => $m->to($officeEmail)
                             ->replyTo($data['email'], $data['name'])
                             ->subject("Job Application: {$data['position']} — {$data['name']}")
            );
        } catch (\Throwable) {
            // Mail failure silently — application still acknowledged
        }

        return back()->with('job_applied', $data['name']);
    }
}
