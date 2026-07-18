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
        'home'                => ['title' => 'Home',                    'route_name' => 'home',                    'sort' =>  1, 'in_menu' => true],

        // ── About Us ──────────────────────────────────────────────────
        'about'               => ['title' => 'About JDCA',              'route_name' => 'about',                   'sort' =>  2, 'in_menu' => true],
        'about-history'       => ['title' => 'History & Geography',     'route_name' => 'about.history',           'sort' =>  3, 'in_menu' => false],
        'about-mission'       => ['title' => 'Mission & Vision',        'route_name' => 'about.mission',           'sort' =>  4, 'in_menu' => false],
        'about-message'       => ['title' => 'Message from Principal',  'route_name' => 'about.message',           'sort' =>  5, 'in_menu' => false],

        // ── Academics ─────────────────────────────────────────────────
        'programs'            => ['title' => 'Academic Programmes',     'route_name' => 'programs',                'sort' =>  6, 'in_menu' => true],
        'departments'         => ['title' => 'Departments',             'route_name' => 'departments',             'sort' =>  7, 'in_menu' => false],
        'faculty'             => ['title' => 'Faculty Profile',         'route_name' => 'faculty',                 'sort' =>  8, 'in_menu' => false],
        'campus-facilities'   => ['title' => 'Campus Facilities',       'route_name' => 'campus-facilities',       'sort' =>  9, 'in_menu' => false],
        'downloads'           => ['title' => 'Downloads',               'route_name' => 'downloads',               'sort' => 10, 'in_menu' => false],
        'gallery'             => ['title' => 'College Gallery',         'route_name' => 'gallery',                 'sort' => 11, 'in_menu' => false],

        // ── Admission ─────────────────────────────────────────────────
        'admissions'          => ['title' => 'Online Admission Form',   'route_name' => 'admissions',              'sort' => 12, 'in_menu' => true],
        'admission-procedure' => ['title' => 'Admission Procedure',     'route_name' => 'admissions.procedure',    'sort' => 13, 'in_menu' => false],
        'fee-structure'       => ['title' => 'Fee Structure',           'route_name' => 'admissions.fee-structure','sort' => 14, 'in_menu' => false],
        'semester-rules'      => ['title' => 'Semester Rules',          'route_name' => 'admissions.semester-rules','sort'=> 15, 'in_menu' => false],

        // ── Scholarships ──────────────────────────────────────────────
        'scholarships'        => ['title' => 'Scholarships',            'route_name' => 'scholarships',            'sort' => 16, 'in_menu' => true],

        // ── Other public pages ────────────────────────────────────────
        'alumni'              => ['title' => 'Alumni',                  'route_name' => 'alumni',                  'sort' => 17, 'in_menu' => true],
        'news'                => ['title' => 'News',                    'route_name' => 'news',                    'sort' => 18, 'in_menu' => false],
        'events'              => ['title' => 'Events',                  'route_name' => 'events',                  'sort' => 19, 'in_menu' => false],
        'notices'             => ['title' => 'Notices',                 'route_name' => 'notices',                 'sort' => 20, 'in_menu' => false],
        'contact'             => ['title' => 'Contact Us',              'route_name' => 'contact',                 'sort' => 21, 'in_menu' => true],
    ];

    protected $fillable = [
        'title', 'slug', 'content', 'meta_title', 'meta_description',
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

        return route($routeName, $preview ? ['preview' => 1] : []);
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
                'features' => [
                    ['title' => 'Board & entry focus', 'description' => 'Structured preparation for BISE papers, practicals, and university entry tests (MDCAT, ECAT, and others) with regular assessments and counselling.'],
                    ['title' => 'Qualified faculty', 'description' => 'Subject teachers with relevant degrees, remedial support before board exams, and parent-teacher meetings aligned with the college calendar.'],
                    ['title' => 'Campus life', 'description' => 'Societies, sports, and student welfare within college rules-building confidence for universities across Pakistan and abroad.'],
                ],
                'about' => [
                    'title' => 'Discover the Minds Shaping Future',
                    'description' => 'For over three decades, MySchool has been committed to providing exceptional education that prepares students for success in an ever-changing world. Our holistic approach combines rigorous academics with character development.',
                    'image' => 'assets/images/photo-1541339907198-e08756dedf3f.jpg',
                    'badge_title' => 'Excellence',
                    'badge_text' => 'A campus built for curiosity, collaboration, and growth.',
                    'button_text' => 'Learn More',
                    'button_link' => 'about',
                    'stats' => [
                        ['value' => number_format($studentCount) . '+', 'label' => 'Students'],
                        ['value' => '98%', 'label' => 'Success'],
                        ['value' => number_format($teacherCount) . '+', 'label' => 'Faculty'],
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
                'intro_title' => 'About Jinnah School & Degree College',
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
                'gallery_items' => [
                    ['image' => 'assets/images/photo-1562774053-701939374585.jpg', 'title' => 'Main Building', 'caption' => 'Historic college campus facade with trees', 'category' => 'campus'],
                    ['image' => 'assets/images/photo-1576495199011-eb94736d05d6.jpg', 'title' => 'Learning', 'caption' => 'Students in a lecture hall', 'category' => 'labs'],
                    ['image' => 'assets/images/photo-1519389950473-47ba0277781c.jpg', 'title' => 'Labs', 'caption' => 'Group study and digital projects', 'category' => 'labs'],
                    ['image' => 'assets/images/photo-1541339907198-e08756dedf3f.jpg', 'title' => 'Campus', 'caption' => 'Courtyard and walkways', 'category' => 'campus'],
                    ['image' => 'assets/images/photo-1588072432836-e10032774350.jpg', 'title' => 'Events', 'caption' => 'Seminar and guest sessions', 'category' => 'events'],
                    ['image' => 'assets/images/photo-1529156069898-49953e39b3ac.jpg', 'title' => 'Life', 'caption' => 'Student life and outdoor activities', 'category' => 'sports'],
                ],
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
            'about-message' => [
                'intro_title' => 'Message from the Principal',
                'intro_text' => 'A personal message from the Principal of Jinnah School & Degree College Astore.',
                'principal_name' => 'Arif Ali',
                'principal_title' => 'Principal, JDCA',
                'message_html' => '<p>On behalf of the faculty and staff of Jinnah School & Degree College Astore, I warmly welcome you to our institution. We are committed to providing quality education and nurturing every student to realise their full potential. Our aim is to create a supportive learning environment that prepares students for success in higher education and professional life.</p><p>We believe that education is not only about academic achievement, but also about character, service, and contribution to society. I invite you to explore our programmes and join the JDCA family.</p>',
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
                    ['title' => 'Computer Lab', 'description' => 'Modern computers with internet access for student research and coursework.', 'icon' => 'computer'],
                    ['title' => 'Science Labs', 'description' => 'Fully equipped physics, chemistry, and biology laboratories for practical sessions.', 'icon' => 'flask'],
                    ['title' => 'Library', 'description' => 'A comprehensive collection of academic texts, reference books, and digital resources.', 'icon' => 'book'],
                    ['title' => 'Sports Ground', 'description' => 'Outdoor sports facilities including cricket, football, and athletics.', 'icon' => 'sports'],
                    ['title' => 'Prayer Area', 'description' => 'Dedicated prayer spaces for students and staff.', 'icon' => 'mosque'],
                    ['title' => 'Canteen', 'description' => 'On-campus canteen providing meals and refreshments during college hours.', 'icon' => 'food'],
                ],
            ],
            'downloads' => [
                'intro_title' => 'Downloads',
                'intro_text' => 'Download official forms, notices, syllabi, and other academic documents from Jinnah School & Degree College Astore.',
                'body_html' => '<p>Use this section to provide guidance on available downloads.</p>',
            ],
            'admission-procedure' => [
                'intro_title' => 'Admission Procedure',
                'intro_text' => 'Step-by-step guide to applying for admission at JDCA for Intermediate and Degree programmes.',
                'body_html' => '<p>Update this section with the latest admission procedure and eligibility requirements.</p>',
                'steps' => [
                    ['step' => '01', 'title' => 'Eligibility Check', 'description' => 'Confirm you meet the minimum qualification requirements for your chosen programme (Matric for Intermediate, FA/FSc for Degree).'],
                    ['step' => '02', 'title' => 'Collect Form', 'description' => 'Collect the admission form from the college office or download it from this website.'],
                    ['step' => '03', 'title' => 'Submit Documents', 'description' => 'Submit the completed form along with attested copies of your academic certificates, CNIC/B-Form, and passport photos.'],
                    ['step' => '04', 'title' => 'Fee Deposit', 'description' => 'Deposit the admission fee at the designated bank and attach the original bank receipt.'],
                    ['step' => '05', 'title' => 'Verification', 'description' => 'Attend the college for document verification and interview if required.'],
                    ['step' => '06', 'title' => 'Enrolment', 'description' => 'Collect your student ID and timetable from the college office. You are now officially enrolled.'],
                ],
            ],
            'fee-structure' => [
                'intro_title' => 'Fee Structure',
                'intro_text' => 'Transparent fee information for all programmes offered at Jinnah School & Degree College Astore.',
                'body_html' => '<p>Fee details are updated each academic session. Contact the college office for the most current information.</p>',
            ],
            'semester-rules' => [
                'intro_title' => 'Semester Rules & Regulations',
                'intro_text' => 'Academic rules, attendance requirements, and examination policies for all students of JDCA.',
                'body_html' => '<p>Update this section with the current semester rules and regulations.</p>',
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
