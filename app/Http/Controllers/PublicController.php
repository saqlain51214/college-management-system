<?php

namespace App\Http\Controllers;

use App\Mail\AdmissionInquiryAcknowledgementMail;
use App\Mail\AdmissionInquiryOfficeNotificationMail;
use App\Mail\ContactMessageAcknowledgementMail;
use App\Mail\ContactMessageOfficeNotificationMail;
use App\Models\AcademicProgram;
use App\Models\AdmissionInquiry;
use App\Models\Announcement;
use App\Models\CollegeSetting;
use App\Models\ContactMessage;
use App\Models\ExamResult;
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
        $name        = CollegeSetting::get('college_name',        'Jinnah School & Degree College Astore');
        $short       = CollegeSetting::get('college_short_name',  'JDCA');
        $address     = CollegeSetting::get('college_address',     'Distt. Astore Village Eidgah, Near Ali Murtaza Chowk, Astore, GB');
        $phone       = CollegeSetting::get('college_phone',       '+923129776585');
        $email       = CollegeSetting::get('college_email',       'jinnahschooldegreecollege@gmail.com');
        $principal   = CollegeSetting::get('college_principal',   'Arif Ali');
        $affiliation = CollegeSetting::get('college_affiliation', 'Karakoram International University');

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
            // aliases used by views
            'college_name'     => $name,
            'college_short'    => $short,
            'principal_name'   => $principal,
            'established_year' => 2010,
        ];
    }

    private function cmsPage(string $slug): WebsitePage
    {
        $page = WebsitePage::query()->where('slug', $slug)->first();

        if ($page && $slug !== 'home' && ! $page->is_published) {
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
        $stats = [
            'students' => Student::where('status', 'active')->count(),
            'teachers' => Teacher::where('is_active', true)->count(),
            'programs' => AcademicProgram::active()->count(),
            'years_of_excellence' => now()->year - 2010,
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

    public function history()
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('about-history');
        $pageContent = $cmsPage->content;
        return view('public.history', compact('college', 'cmsPage', 'pageContent'));
    }

    public function mission()
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('about-mission');
        $pageContent = $cmsPage->content;
        return view('public.mission', compact('college', 'cmsPage', 'pageContent'));
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
        $college  = $this->college();
        $cmsPage  = $this->cmsPage('admissions');
        $pageContent = $cmsPage->content;
        $intermediatePrograms = AcademicProgram::visible()
            ->forAdmissionCategory('intermediate')
            ->ordered()
            ->get();
        $undergraduatePrograms = AcademicProgram::visible()
            ->forAdmissionCategory('undergraduate')
            ->ordered()
            ->get();
        $admissionValidation = AdmissionValidation::frontendConfig();

        return view('public.admissions', compact(
            'college',
            'cmsPage',
            'pageContent',
            'intermediatePrograms',
            'undergraduatePrograms',
            'admissionValidation'
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
        $payload = $request->all();
        $payload['phone'] = AdmissionValidation::normalizePhone($payload['phone'] ?? null);
        $payload['student_phone'] = AdmissionValidation::normalizePhone($payload['student_phone'] ?? null);

        $entryPath = (string) $request->input('entry_path');
        $step = $request->filled('current_step') ? (int) $request->input('current_step') : null;
        $validationMode = (string) $request->input('validation_mode');
        $rules = AdmissionValidation::rules($entryPath, $validationMode === 'step' ? $step : null);

        $validated = Validator::make(
            $payload,
            $rules,
            AdmissionValidation::messages()
        )->validate();

        if ($validationMode === 'step') {
            return response()->json([
                'message' => 'Step validation passed.',
                'step' => $step,
            ]);
        }

        $program = AcademicProgram::findOrFail($validated['program_id']);
        $academicDetails = $validated['academic'] ?? [];
        $primaryQualification = $entryPath === 'undergraduate'
            ? ($academicDetails['hssc']['qualification'] ?? 'hssc')
            : ($academicDetails['matric']['qualification'] ?? 'matric');

        $admissionInquiry = AdmissionInquiry::create([
            'reference_no'   => 'JDCA-' . now()->format('Y') . '-' . strtoupper(Str::random(8)),
            'name'           => $validated['name'],
            'father_name'    => $validated['father_name'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'],
            'student_phone'  => $validated['student_phone'] ?? null,
            'cnic'           => $validated['cnic'],
            'dob'            => $validated['dob'],
            'entry_path'     => $entryPath,
            'gender'         => $validated['gender'],
            'campus'         => $validated['campus'],
            'city'           => $validated['city'],
            'address'        => $validated['address'],
            'program_id'     => $program->id,
            'qualification'  => $primaryQualification,
            'academic_details' => $academicDetails,
            'declare_true'   => (bool) ($validated['declare_true'] ?? false),
            'message'        => $validated['message'] ?? null,
            'status'         => 'new',
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

    public function results(Request $request)
    {
        $college = $this->college();
        $cmsPage = $this->cmsPage('results');
        $pageContent = $cmsPage->content;
        $results = null;
        $student = null;
        $error   = null;

        if ($request->filled('roll_number')) {
            $student = Student::where('roll_number', trim($request->roll_number))->first();
            if ($student) {
                $results = ExamResult::with(['exam.course', 'exam.academicProgram'])
                    ->where('student_id', $student->id)
                    ->whereHas('exam', fn($q) => $q->where('results_published', true))
                    ->orderByDesc('created_at')
                    ->get();
            } else {
                $error = 'No student found with roll number "' . e($request->roll_number) . '". Please check and try again.';
            }
        }

        return view('public.results', compact('college', 'cmsPage', 'pageContent', 'results', 'student', 'error'));
    }

    public function timetable(Request $request)
    {
        $college  = $this->college();
        $cmsPage  = $this->cmsPage('timetable');
        $pageContent = $cmsPage->content;
        $programs = AcademicProgram::active()->orderBy('sort_order')->orderBy('name')->get();

        $selectedProgram  = null;
        $selectedSemester = null;
        $slots            = collect();
        $days             = ['monday','tuesday','wednesday','thursday','friday','saturday'];

        if ($request->filled('program_id')) {
            $selectedProgram  = AcademicProgram::find($request->program_id);
            $selectedSemester = (int) $request->get('semester', 1);

            if ($selectedProgram) {
                $slots = \App\Models\Timetable::with(['course','teacher'])
                    ->forProgram($selectedProgram->id)
                    ->forSemester($selectedSemester)
                    ->active()
                    ->orderByRaw("CASE day_of_week WHEN 'monday' THEN 1 WHEN 'tuesday' THEN 2 WHEN 'wednesday' THEN 3 WHEN 'thursday' THEN 4 WHEN 'friday' THEN 5 WHEN 'saturday' THEN 6 ELSE 7 END")
                    ->orderBy('start_time')
                    ->get()
                    ->groupBy('day_of_week');
            }
        }

        return view('public.timetable', compact(
            'college', 'cmsPage', 'pageContent', 'programs', 'selectedProgram', 'selectedSemester', 'slots', 'days'
        ));
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
}
