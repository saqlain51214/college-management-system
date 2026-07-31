<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class WebsitePage extends Model
{
    use SoftDeletes;

    public const STATIC_PAGES = [
        // ── Home ──────────────────────────────────────────────────────
        'home'                => ['title' => 'Home',                    'route_name' => 'home',                    'sort' =>  1, 'in_menu' => true,  'section' => 'Home'],

        // ── About Us ──────────────────────────────────────────────────
        'about'               => ['title' => 'About JDCA',              'route_name' => 'about',                   'sort' =>  2, 'in_menu' => true,  'section' => 'About Us'],
        'about-history'       => ['title' => 'History & Geography',     'route_name' => 'about.history',           'sort' =>  3, 'in_menu' => false, 'section' => 'About Us'],
        'about-mission'       => ['title' => 'Mission & Vision',        'route_name' => 'about.mission',           'sort' =>  4, 'in_menu' => false, 'section' => 'About Us'],
        'campus-facilities'   => ['title' => 'Campus Facilities',       'route_name' => 'campus-facilities',       'sort' =>  5, 'in_menu' => false, 'section' => 'About Us'],
        'gallery'             => ['title' => 'College Gallery',         'route_name' => 'gallery',                 'sort' =>  6, 'in_menu' => true,  'section' => 'About Us'],

        // ── Academics ─────────────────────────────────────────────────
        'programs'            => ['title' => 'Academic Programmes',     'route_name' => 'programs',                'sort' =>  7, 'in_menu' => true,  'section' => 'Academics'],
        'departments'         => ['title' => 'Departments',             'route_name' => 'departments',             'sort' =>  8, 'in_menu' => false, 'section' => 'Academics'],
        'faculty'             => ['title' => 'Faculty Profile',         'route_name' => 'faculty',                 'sort' =>  9, 'in_menu' => false, 'section' => 'Academics'],
        'semester-rules'      => ['title' => 'Semester Rules',          'route_name' => 'admissions.semester-rules','sort'=> 10, 'in_menu' => false, 'section' => 'Academics'],

        // ── Admission ─────────────────────────────────────────────────
        'admissions'          => ['title' => 'Online Admission Form',   'route_name' => 'admissions',              'sort' => 11, 'in_menu' => true,  'section' => 'Admission'],
        'admission-procedure' => ['title' => 'Admission Procedure',     'route_name' => 'admissions.procedure',    'sort' => 12, 'in_menu' => false, 'section' => 'Admission'],
        'fee-structure'       => ['title' => 'Fee Structure',           'route_name' => 'admissions.fee-structure','sort' => 13, 'in_menu' => false, 'section' => 'Admission'],
        'scholarships'        => ['title' => 'Scholarships',            'route_name' => 'scholarships',            'sort' => 14, 'in_menu' => true,  'section' => 'Admission'],

        // ── Other public pages ────────────────────────────────────────
        'downloads'           => ['title' => 'Downloads',               'route_name' => 'downloads',               'sort' => 15, 'in_menu' => false, 'section' => 'Other'],
        'news'                => ['title' => 'News',                    'route_name' => 'news',                    'sort' => 16, 'in_menu' => false, 'section' => 'Other'],
        'events'              => ['title' => 'Events',                  'route_name' => 'events',                  'sort' => 17, 'in_menu' => false, 'section' => 'Other'],
        'notices'             => ['title' => 'Notices',                 'route_name' => 'notices',                 'sort' => 18, 'in_menu' => false, 'section' => 'Other'],
        'contact'             => ['title' => 'Contact Us',              'route_name' => 'contact',                 'sort' => 19, 'in_menu' => true,  'section' => 'Other'],
    ];

    protected $fillable = [
        'title', 'menu_label', 'slug', 'section', 'content', 'meta_title', 'meta_description',
        'featured_image', 'sort_order', 'in_menu', 'is_published',
    ];

    protected $casts = [
        'content'      => 'array',
        'in_menu'      => 'boolean',
        'is_published' => 'boolean',
    ];

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeInMenu($q)    { return $q->where('in_menu', true)->orderBy('sort_order'); }
    public function scopeStaticPages($q) { return $q->whereIn('slug', array_keys(self::STATIC_PAGES)); }

    /** What the nav actually shows — an admin-set nickname if given, otherwise the page Title. */
    public function getMenuLabelDisplayAttribute(): string
    {
        return filled($this->menu_label) ? $this->menu_label : $this->title;
    }

    /** Menu label for a given static-page slug, straight from the DB, falling back to the seed default. */
    public static function menuLabelFor(string $slug): string
    {
        $page = static::where('slug', $slug)->first();

        if ($page) {
            return $page->menu_label_display;
        }

        return self::STATIC_PAGES[$slug]['title'] ?? $slug;
    }

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    public static function staticPageDefinitions(): array
    {
        return self::STATIC_PAGES;
    }

    public function previewUrl(bool $preview = false): string
    {
        $routeName = self::STATIC_PAGES[$this->slug]['route_name'] ?? 'home';

        // Guard: if a page's route isn't defined, fall back to home instead of 500.
        if (! \Illuminate\Support\Facades\Route::has($routeName)) {
            $routeName = 'home';
        }

        return route($routeName, $preview ? ['preview' => 1] : []);
    }

    /**
     * Returns the admin-authored page body ONLY when it has actually been
     * customised (i.e. it differs from the seeded placeholder default and is
     * not empty). Lets a blade fall back to its built-in design until the
     * administrator writes real content in the Website Pages editor.
     */
    public function customBodyHtml(): ?string
    {
        $body = $this->content['body_html'] ?? '';

        if (trim(strip_tags($body)) === '') {
            return null;
        }

        $default = self::defaultContentFor($this->slug)['body_html'] ?? '';
        if (trim(strip_tags($body)) === trim(strip_tags($default))) {
            return null; // still the untouched placeholder
        }

        return $body;
    }

    public static function defaultContentFor(string $slug): array
    {
        $studentCount = class_exists(Student::class) ? Student::where('status', 'active')->count() : 2500;
        $teacherCount = class_exists(Teacher::class) ? Teacher::where('is_active', true)->count() : 250;
        $programCount = class_exists(AcademicProgram::class) ? AcademicProgram::active()->count() : 50;

        return match ($slug) {
            'home' => [
                'hero' => [
                    'slides' => [
                        [
                            'title' => "Shaping Minds for<br>Tomorrow's World",
                            'description' => "Strong intermediate and college programmes in the heart of Astore—aligned with national standards, modern labs, and pathways to universities across Pakistan.",
                            'image' => 'assets/images/photo-1562774053-701939374585.jpg',
                            'primary_btn_text' => 'Apply online',
                            'primary_btn_link' => 'admissions',
                            'secondary_btn_text' => 'Schedule Tour',
                            'secondary_btn_link' => 'contact',
                        ],
                        [
                            'title' => "Excellence in<br>Academic Achievement",
                            'description' => 'Join a community dedicated to intellectual growth, critical thinking, and outstanding results. Our experienced faculty is committed to nurturing the next generation of leaders.',
                            'image' => 'assets/images/photo-1523050854058-8df90110c9f1.jpg',
                            'primary_btn_text' => 'View Programs',
                            'primary_btn_link' => 'programs',
                            'secondary_btn_text' => 'Admissions',
                            'secondary_btn_link' => 'admissions',
                        ],
                        [
                            'title' => "Vibrant Campus<br>& Student Life",
                            'description' => 'Experience a dynamic college life with diverse societies, sports facilities, and events that build character, teamwork, and lifelong friendships.',
                            'image' => 'assets/images/photo-1541339907198-e08756dedf3f.jpg',
                            'primary_btn_text' => 'Student Life',
                            'primary_btn_link' => 'news',
                            'secondary_btn_text' => 'Campus Gallery',
                            'secondary_btn_link' => 'gallery',
                        ],
                    ],
                ],
                'programs' => [
                    'section_title' => 'Featured Programs',
                    'section_text' => 'Discover our comprehensive range of academic programs designed to prepare you for success.',
                    'intro_label' => 'Programs',
                    'intro_title' => 'Discover Excellence in Education',
                    'intro_text' => 'Explore intermediate and degree programmes designed to build strong academic foundations and career-ready skills.',
                    'stats' => [
                        ['value' => number_format($studentCount) . '+', 'label' => 'Active Students'],
                        ['value' => '98%', 'label' => 'Graduate Rate'],
                        ['value' => number_format($programCount) . '+', 'label' => 'Programs Offered'],
                    ],
                ],
                'news' => [
                    'section_title' => 'Latest news',
                    'section_text' => 'Updates from campus, academics, and student life.',
                ],
                'events' => [
                    'section_title' => 'Events',
                    'section_text' => 'Upcoming dates for academics, sports, arts, and community.',
                    'button_text' => 'View all events',
                ],
            ],
            'about' => [
                'intro_title' => 'About Jinnah Degree College',
                'intro_text' => 'A premier college combining board-focused intermediate programmes with modern labs, digital learning, and pathways into Pakistan top universities and professions.',
                'body_html' => '<p>Use this section to update the introduction, story, or any important overview for the About page.</p>',
            ],
            'about-history' => [
                'intro_title' => 'History & Location',
                'intro_text' => "Institutional history, campus location in Astore, and how we connect with the region's education landscape.",
                'body_html' => '<p>Use this section to update the history and location content shown near the top of the page.</p>',
            ],
            'about-mission' => [
                'intro_title' => 'Mission & Vision',
                'intro_text' => 'Formal mission, vision, and graduate attributes aligned with national goals and educational quality themes.',
                'body_html' => '<p>Use this section to update the mission and vision introduction shown on the page.</p>',
            ],
            'programs' => [
                'intro_title' => 'Academics',
                'intro_text' => 'Programmes, pathways, and how we support board and university entry.',
                'body_html' => '<p>Update the academic overview or introductory note for programmes here.</p>',
            ],
            'faculty' => [
                'intro_title' => 'Faculty & Leadership',
                'intro_text' => 'Meet the educators and academic leaders guiding our students.',
                'body_html' => '<p>Update the page introduction or faculty note here.</p>',
            ],
            'admissions' => [
                'intro_title' => 'Online admission form',
                'intro_text' => 'Multi-step application aligned with common Pakistani college portals.',
                'body_html' => '<p>Use this section for admission updates, instructions, or deadlines.</p>',
            ],
            'gallery' => [
                'intro_title' => 'College gallery',
                'intro_text' => 'Campus, labs, student life, and events-explore our college through photography.',
                'body_html' => '<p>Use this section for a short gallery introduction or photography note.</p>',
                // Intentionally empty — add real campus photos from the admin panel
                // (Website Pages → Gallery → Gallery Items). Previously seeded with
                // stock placeholder photos, which is what actually showed on the
                // live site until an admin replaced every item by hand.
                'gallery_items' => [],
            ],
            'news' => [
                'intro_title' => 'News',
                'intro_text' => 'Latest updates, announcements, and campus highlights.',
                'body_html' => '<p>Use this section for a news intro, editor note, or archive description.</p>',
            ],
            'events' => [
                'intro_title' => 'Events',
                'intro_text' => 'Upcoming academic, co-curricular, and campus events.',
                'body_html' => '<p>Use this section for an events intro or registration note.</p>',
            ],
            'notices' => [
                'intro_title' => 'Notices',
                'intro_text' => 'Important circulars, updates, and official notices.',
                'body_html' => '<p>Use this section for notice-related guidance or an archive intro.</p>',
            ],
            'results' => [
                'intro_title' => 'Results',
                'intro_text' => 'Check published student results and examination outcomes.',
                'body_html' => '<p>Use this section for result instructions or published result notes.</p>',
            ],
            'timetable' => [
                'intro_title' => 'Timetable',
                'intro_text' => 'Browse class schedules by programme and semester.',
                'body_html' => '<p>Use this section for timetable guidance or scheduling notes.</p>',
            ],
            'contact' => [
                'intro_title' => 'Contact Us',
                'intro_text' => "Reach out to the JDCA team — we're happy to answer your questions about admissions, programmes, or anything else.",
                'body_html' => '<p>Use this section for contact instructions, reception details, or visitor notes.</p>',
            ],
            'departments' => [
                'intro_title' => 'Our Departments',
                'intro_text' => 'Jinnah Degree College Astore offers a range of academic departments covering arts, sciences, computer science, and professional education.',
                'body_html' => '<p>Each department is staffed by qualified subject specialists committed to academic excellence and student development.</p>',
            ],
            'campus-facilities' => [
                'intro_title' => 'Campus Facilities',
                'intro_text' => 'Modern facilities supporting academic, co-curricular, and student welfare activities at JDCA.',
                'body_html' => '<p>Update this section with the latest campus facilities information.</p>',
                'facilities' => [
                    ['title' => 'Classrooms', 'description' => 'Modern, well-ventilated classrooms equipped for effective teaching with proper seating and boards.', 'icon' => 'classrooms'],
                    ['title' => 'Library', 'description' => 'A growing collection of academic books, journals, and reference materials for students and faculty.', 'icon' => 'library'],
                    ['title' => 'Computer Lab', 'description' => 'ICT-equipped computer laboratory to support digital learning and technology education.', 'icon' => 'computer'],
                    ['title' => 'Administrative Block', 'description' => 'Dedicated administrative offices for student services, registration, and college management.', 'icon' => 'admin'],
                    ['title' => 'Sports Area', 'description' => 'Open sports grounds supporting physical education and extracurricular activities.', 'icon' => 'sports'],
                    ['title' => 'Prayer Area', 'description' => 'A dedicated space for daily prayers, supporting the spiritual well-being of our community.', 'icon' => 'prayer'],
                    ['title' => 'Canteen', 'description' => 'Student canteen providing affordable meals and refreshments during college hours.', 'icon' => 'canteen'],
                    ['title' => 'Safe Environment', 'description' => 'A safe, inclusive campus environment with security measures for student welfare.', 'icon' => 'security'],
                    ['title' => 'Wi-Fi Campus', 'description' => 'Internet connectivity available on campus to support research and online learning.', 'icon' => 'wifi'],
                ],
            ],
            'downloads' => [
                'intro_title' => 'Downloads',
                'intro_text' => 'Download official forms, notices, syllabi, and other academic documents from Jinnah Degree College Astore.',
                'body_html' => '<p>Use this section to provide guidance on available downloads.</p>',
            ],
            'admission-procedure' => [
                'intro_title' => 'Admission Procedure',
                'intro_text' => 'Step-by-step guide to applying for admission at JDCA for Intermediate and Degree programmes.',
                'body_html' => '<p>Update this section with the latest admission procedure and eligibility requirements.</p>',
                'steps' => [
                    ['title' => 'Check Eligibility', 'description' => 'Review the eligibility criteria for your desired program. Ensure you meet the minimum qualification requirements before applying.'],
                    ['title' => 'Obtain Admission Form', 'description' => 'Download the admission form from our Downloads page or collect a printed copy from the college administration office.'],
                    ['title' => 'Fill & Submit Form', 'description' => 'Complete the form with accurate personal and academic information. Submit it along with all required documents to the admissions office.'],
                    ['title' => 'Pay Admission Fee', 'description' => 'Deposit the admission fee at the designated bank and attach the bank receipt with your application.'],
                    ['title' => 'Document Verification', 'description' => 'Our admissions team will verify your submitted documents. You may be called for an interview or additional verification if required.'],
                    ['title' => 'Merit List & Confirmation', 'description' => 'Admission is granted on merit. Check the merit list on the college notice board or website. Confirm your admission within the specified deadline.'],
                    ['title' => 'Enrollment', 'description' => 'Complete the enrollment process by paying the semester fee and obtaining your college ID and roll number.'],
                ],
            ],
            'fee-structure' => [
                'intro_title' => 'Fee Structure',
                'intro_text' => 'Transparent fee information for all programmes offered at Jinnah Degree College Astore.',
                'body_html' => '<p>Fee details are updated each academic session. Contact the college office for the most current information.</p>',
            ],
            'semester-rules' => [
                'intro_title' => 'Semester Rules & Regulations',
                'intro_text' => 'Academic rules, attendance requirements, and examination policies for all students of JDCA.',
                'body_html' => '<p>Update this section with the current semester rules and regulations.</p>',
                'rule_sections' => [
                    ['title' => 'Attendance Policy', 'rules' => [
                        'Students must maintain a minimum of 75% attendance in each subject per semester.',
                        'Students with less than 75% attendance will not be allowed to appear in final examinations.',
                        'Medical leave is considered upon submission of a valid medical certificate.',
                        'Attendance is marked at the start of each class by the respective subject teacher.',
                    ]],
                    ['title' => 'Examination Rules', 'rules' => [
                        'Students must appear in all mid-term and final examinations as scheduled.',
                        'No make-up exam will be conducted without prior approval from the Principal.',
                        'Use of mobile phones or any electronic device during exams is strictly prohibited.',
                        'Students found cheating will be debarred from the examination and may face disciplinary action.',
                        'Results will be declared within 30 days of the final examination.',
                    ]],
                    ['title' => 'Academic Standing', 'rules' => [
                        'A student must pass all subjects in a semester to be promoted to the next semester.',
                        'A student who fails in one or two subjects may be awarded a "Repeat" grade and must re-appear in the supplementary examination.',
                        'A student failing in more than two subjects will be required to repeat the entire semester.',
                        'The Grade Point Average (GPA) is calculated on a 4.0 scale.',
                    ]],
                    ['title' => 'Code of Conduct', 'rules' => [
                        'Students are required to maintain discipline and respect inside and outside the classroom.',
                        'Proper college dress code must be observed at all times on campus.',
                        'Any form of harassment or bullying will result in immediate disciplinary action.',
                        'Students must carry their college ID cards at all times on campus.',
                        'Use of social media to defame the college or any individual is a punishable offense.',
                    ]],
                    ['title' => 'Fee & Registration', 'rules' => [
                        'Semester fee must be paid within the first two weeks of the semester.',
                        'Students who fail to pay fees on time may be de-registered from the current semester.',
                        'Course registration must be completed before the start of lectures.',
                        'Add/drop of subjects is only allowed within the first week of the semester.',
                    ]],
                ],
            ],
            'scholarships' => [
                'intro_title' => 'Scholarships',
                'intro_text' => 'JDCA offers scholarships to support deserving students — merit-based, need-based, and special category awards.',
                'body_html' => '<p>Update this section with scholarship eligibility, application process, and deadlines.</p>',
            ],
            'alumni' => [
                'intro_title' => 'JDCA Alumni',
                'intro_text' => 'Join our growing community of JDCA graduates who are making a difference in Astore, Gilgit-Baltistan, and beyond.',
                'body_html' => '<p>Update this section with alumni stories, achievements, and how to register as an alumnus.</p>',
            ],
            default => [
                'intro_title' => self::STATIC_PAGES[$slug]['title'] ?? 'Website Page',
                'intro_text' => '',
                'body_html' => '<p>Update this page content from the admin panel.</p>',
            ],
        };
    }
}
