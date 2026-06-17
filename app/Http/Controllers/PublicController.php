<?php

namespace App\Http\Controllers;

use App\Models\AcademicProgram;
use App\Models\AdmissionInquiry;
use App\Models\Announcement;
use App\Models\CollegeSetting;
use App\Models\ContactMessage;
use App\Models\ExamResult;
use App\Models\NewsArticle;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

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

    public function home()
    {
        $college  = $this->college();
        $programs = AcademicProgram::active()->limit(6)->get();
        $news     = NewsArticle::where('is_published', true)->orderByDesc('published_date')->limit(3)->get();
        $notices  = Announcement::where('is_published', true)
            ->where(fn($q) => $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()))
            ->orderByDesc('publish_date')->limit(5)->get();
        $stats = [
            'students' => Student::where('status', 'active')->count(),
            'teachers' => Teacher::where('is_active', true)->count(),
            'programs' => AcademicProgram::active()->count(),
            'years_of_excellence' => now()->year - 2010,
        ];
        return view('public.home', compact('college', 'programs', 'news', 'notices', 'stats'));
    }

    public function about()
    {
        $college = $this->college();
        $stats   = [
            'students' => Student::where('status', 'active')->count(),
            'teachers' => Teacher::where('is_active', true)->count(),
            'programs' => AcademicProgram::active()->count(),
        ];
        return view('public.about', compact('college', 'stats'));
    }

    public function programs()
    {
        $college  = $this->college();
        $programs = AcademicProgram::active()->orderBy('sort_order')->orderBy('name')->get();
        return view('public.programs', compact('college', 'programs'));
    }

    public function faculty()
    {
        $college  = $this->college();
        $teachers = Teacher::where('is_active', true)->orderBy('designation')->orderBy('name')->get();
        return view('public.faculty', compact('college', 'teachers'));
    }

    public function admissions()
    {
        $college  = $this->college();
        $programs = AcademicProgram::active()->orderBy('sort_order')->orderBy('name')->get();
        return view('public.admissions', compact('college', 'programs'));
    }

    public function contact()
    {
        $college = $this->college();
        return view('public.contact', compact('college'));
    }

    public function contactSend(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        ContactMessage::create($request->only(['name','email','subject','message']));

        return back()->with('success', 'Thank you! Your message has been received. We will get back to you shortly.');
    }

    public function admissionInquiry(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:20',
            'email'   => 'nullable|email',
            'program_id'    => 'nullable|exists:academic_programs,id',
            'qualification' => 'nullable|string|max:100',
            'message'       => 'nullable|string|max:1000',
        ]);

        AdmissionInquiry::create([
            'name'          => $request->name,
            'father_name'   => $request->father_name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'program_id'    => $request->program_id,
            'qualification' => $request->qualification,
            'message'       => $request->message,
            'status'        => 'new',
        ]);

        return back()->with('success', 'Your admission inquiry has been submitted! We will contact you within 1-2 business days.');
    }

    public function news(Request $request)
    {
        $college  = $this->college();
        $featured = NewsArticle::where('is_published', true)->where('is_featured', true)->latest('published_date')->first();
        $articles = NewsArticle::where('is_published', true)
            ->when($request->category, fn($q, $cat) => $q->where('category', $cat))
            ->orderByDesc('published_date')
            ->paginate(9);
        return view('public.news', compact('college', 'articles', 'featured'));
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
        $notices  = Announcement::where('is_published', true)
            ->where(fn($q) => $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()))
            ->orderByDesc('publish_date')
            ->paginate(15);
        return view('public.notices', compact('college', 'notices'));
    }

    public function results(Request $request)
    {
        $college = $this->college();
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

        return view('public.results', compact('college', 'results', 'student', 'error'));
    }

    public function timetable(Request $request)
    {
        $college  = $this->college();
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
            'college', 'programs', 'selectedProgram', 'selectedSemester', 'slots', 'days'
        ));
    }
}
