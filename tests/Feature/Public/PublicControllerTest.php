<?php

namespace Tests\Feature\Public;

use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\Department;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\HomeSection;
use App\Models\NewsArticle;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Timetable;
use App\Models\WebsiteEvent;
use App\Models\WebsitePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_limits_and_filters_public_content(): void
    {
        HomeSection::create([
            'key' => 'campus-life',
            'title' => 'Campus Life',
            'sort_order' => 2,
            'is_active' => false,
            'content' => [],
        ]);

        foreach (range(1, 7) as $index) {
            AcademicProgram::factory()->create([
                'name' => 'Program ' . $index,
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }

        AcademicProgram::factory()->create([
            'name' => 'Inactive Program',
            'is_active' => false,
            'sort_order' => 99,
        ]);

        NewsArticle::factory()->featured()->create([
            'title' => 'Featured Published News',
            'is_published' => true,
            'published_date' => now(),
        ]);
        NewsArticle::factory()->create([
            'title' => 'Draft News',
            'is_published' => false,
        ]);

        WebsiteEvent::create([
            'title' => 'Visible Event',
            'slug' => 'visible-event',
            'start_datetime' => now()->addDays(1),
            'is_published' => true,
        ]);
        WebsiteEvent::create([
            'title' => 'Hidden Event',
            'slug' => 'hidden-event',
            'start_datetime' => now()->addDays(2),
            'is_published' => false,
        ]);

        Announcement::factory()->create([
            'title' => 'Active Notice',
            'is_published' => true,
            'publish_date' => now(),
            'expiry_date' => null,
        ]);
        Announcement::factory()->expired()->create([
            'title' => 'Expired Notice',
            'is_published' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertViewHas('programs', fn (Collection $programs) => $programs->count() === 6
                && $programs->pluck('name')->contains('Program 1')
                && ! $programs->pluck('name')->contains('Inactive Program'))
            ->assertViewHas('news', fn (Collection $news) => $news->pluck('title')->contains('Featured Published News')
                && ! $news->pluck('title')->contains('Draft News'))
            ->assertViewHas('events', fn (Collection $events) => $events->pluck('title')->contains('Visible Event')
                && ! $events->pluck('title')->contains('Hidden Event'))
            ->assertViewHas('notices', fn ($notices) => $notices->pluck('title')->contains('Active Notice')
                && ! $notices->pluck('title')->contains('Expired Notice'))
            ->assertViewHas('homeSections', fn (array $sections) => $sections['campus-life']['is_active'] === false);
    }

    public function test_unpublished_static_page_returns_404(): void
    {
        WebsitePage::create([
            'title' => 'About Overview',
            'slug' => 'about',
            'is_published' => false,
        ]);

        $this->get(route('about'))->assertNotFound();
    }

    public function test_about_page_computes_stats_from_active_records(): void
    {
        Student::factory()->create(['status' => 'active', 'is_active' => true]);
        Student::factory()->inactive()->create();

        $this->createTeacher('Active Teacher', true);
        $this->createTeacher('Inactive Teacher', false);

        AcademicProgram::factory()->create(['is_active' => true]);
        AcademicProgram::factory()->create(['is_active' => false]);

        $response = $this->get(route('about'));

        $response->assertOk()
            ->assertViewHas('stats', fn (array $stats) => $stats['students'] === 1 && $stats['teachers'] === 1 && $stats['programs'] === 1);
    }

    public function test_history_and_mission_pages_load(): void
    {
        $this->get(route('about.history'))->assertOk();
        $this->get(route('about.mission'))->assertOk();
    }

    public function test_programs_page_only_shows_active_programs_in_order(): void
    {
        AcademicProgram::factory()->create(['name' => 'Gamma', 'is_active' => true, 'sort_order' => 3]);
        AcademicProgram::factory()->create(['name' => 'Alpha', 'is_active' => true, 'sort_order' => 1]);
        AcademicProgram::factory()->create(['name' => 'Hidden', 'is_active' => false, 'sort_order' => 2]);

        $response = $this->get(route('programs'));

        $response->assertOk()
            ->assertSeeInOrder(['Alpha', 'Gamma'])
            ->assertDontSee('Hidden');
    }

    public function test_faculty_page_only_shows_active_teachers(): void
    {
        $this->createTeacher('Dr Active', true);
        $this->createTeacher('Dr Inactive', false);

        $response = $this->get(route('faculty'));

        $response->assertOk()
            ->assertSee('Dr Active')
            ->assertDontSee('Dr Inactive');
    }

    public function test_admissions_page_splits_programs_by_admission_category(): void
    {
        $intermediate = AcademicProgram::factory()->create([
            'name' => 'ICS',
            'admission_category' => 'intermediate',
            'is_active' => true,
            'show_on_website' => true,
        ]);
        $undergraduate = AcademicProgram::factory()->create([
            'name' => 'BS Computer Science',
            'admission_category' => 'undergraduate',
            'is_active' => true,
            'show_on_website' => true,
        ]);
        AcademicProgram::factory()->create([
            'name' => 'Hidden Program',
            'admission_category' => 'intermediate',
            'is_active' => false,
            'show_on_website' => true,
        ]);

        $response = $this->get(route('admissions'));

        $response->assertOk()
            ->assertViewHas('intermediatePrograms', fn (Collection $programs) => $programs->pluck('id')->all() === [$intermediate->id])
            ->assertViewHas('undergraduatePrograms', fn (Collection $programs) => $programs->pluck('id')->all() === [$undergraduate->id])
            ->assertViewHas('admissionValidation', fn (array $config) => isset($config['patterns']['phone_pk'], $config['step_fields']));
    }

    public function test_contact_page_loads(): void
    {
        $this->get(route('contact'))->assertOk();
    }

    public function test_news_page_filters_by_category_and_uses_published_featured_article(): void
    {
        NewsArticle::factory()->featured()->create([
            'title' => 'Featured Event News',
            'category' => 'events',
            'is_published' => true,
            'published_date' => now(),
        ]);
        NewsArticle::factory()->create([
            'title' => 'Event Story',
            'category' => 'events',
            'is_published' => true,
            'published_date' => now()->subDay(),
        ]);
        NewsArticle::factory()->create([
            'title' => 'Campus News',
            'category' => 'news',
            'is_published' => true,
            'published_date' => now()->subDays(2),
        ]);

        $response = $this->get(route('news', ['category' => 'events']));

        $response->assertOk()
            ->assertSee('Event Story')
            ->assertDontSee('Campus News')
            ->assertViewHas('featured', fn (?NewsArticle $article) => $article?->title === 'Featured Event News');
    }

    public function test_news_detail_increments_views_and_shows_related_same_category(): void
    {
        $article = NewsArticle::factory()->create([
            'title' => 'Main Article',
            'slug' => 'main-article',
            'category' => 'news',
            'views' => 3,
            'is_published' => true,
            'published_date' => now(),
        ]);
        NewsArticle::factory()->create([
            'title' => 'Related News',
            'category' => 'news',
            'is_published' => true,
            'published_date' => now()->subDay(),
        ]);
        NewsArticle::factory()->create([
            'title' => 'Other Category',
            'category' => 'events',
            'is_published' => true,
            'published_date' => now()->subDays(2),
        ]);

        $response = $this->get(route('news.show', $article->slug));

        $response->assertOk()
            ->assertSee('Main Article')
            ->assertSee('Related News')
            ->assertDontSee('Other Category');

        $this->assertDatabaseHas('news_articles', [
            'id' => $article->id,
            'views' => 4,
        ]);
    }

    public function test_notices_page_only_shows_active_published_notices(): void
    {
        Announcement::factory()->create(['title' => 'Current Notice', 'is_published' => true, 'expiry_date' => now()->addDay()]);
        Announcement::factory()->expired()->create(['title' => 'Expired Notice', 'is_published' => true]);
        Announcement::factory()->unpublished()->create(['title' => 'Draft Notice']);

        $response = $this->get(route('notices'));

        $response->assertOk()
            ->assertSee('Current Notice')
            ->assertDontSee('Expired Notice')
            ->assertDontSee('Draft Notice');
    }

    public function test_results_page_only_shows_published_exam_results_for_found_student(): void
    {
        [$student] = $this->createStudentAcademicContext('Result Student');

        $publishedExam = $this->createExam($student->academic_program_id, resultsPublished: true, title: 'Midterm');
        $draftExam = $this->createExam($student->academic_program_id, resultsPublished: false, title: 'Final');

        ExamResult::create([
            'student_id' => $student->id,
            'exam_id' => $publishedExam->id,
            'marks_obtained' => 78,
            'grade' => 'B+',
            'grade_points' => 3.2,
            'is_absent' => false,
            'is_exempted' => false,
        ]);

        ExamResult::create([
            'student_id' => $student->id,
            'exam_id' => $draftExam->id,
            'marks_obtained' => 88,
            'grade' => 'A',
            'grade_points' => 3.8,
            'is_absent' => false,
            'is_exempted' => false,
        ]);

        $response = $this->get(route('results', ['roll_number' => $student->roll_number]));

        $response->assertOk()
            ->assertSee('Result Student')
            ->assertSee('Midterm')
            ->assertDontSee('Final');
    }

    public function test_results_page_shows_error_for_unknown_roll_number(): void
    {
        $this->get(route('results', ['roll_number' => 'UNKNOWN-1001']))
            ->assertOk()
            ->assertSee('No student found');
    }

    public function test_timetable_page_filters_slots_by_selected_program_and_semester(): void
    {
        $program = AcademicProgram::factory()->create(['name' => 'BSCS', 'sort_order' => 1]);
        $otherProgram = AcademicProgram::factory()->create(['name' => 'BSEng', 'sort_order' => 2]);
        $academicYear = AcademicYear::create([
            'name' => '2026-2027',
            'start_date' => '2026-08-01',
            'end_date' => '2027-06-30',
            'is_current' => true,
            'is_active' => true,
        ]);
        $teacher = $this->createTeacher('Timetable Teacher', true);
        $course = $this->createCourse($program->id, 'Programming Fundamentals', 'CS-101');
        $otherCourse = $this->createCourse($otherProgram->id, 'English Literature', 'ENG-101');

        Timetable::create([
            'academic_program_id' => $program->id,
            'academic_year_id' => $academicYear->id,
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
            'semester' => 1,
            'semester_number' => 1,
            'day_of_week' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'room' => 'Lab 1',
            'is_active' => true,
        ]);

        Timetable::create([
            'academic_program_id' => $otherProgram->id,
            'academic_year_id' => $academicYear->id,
            'course_id' => $otherCourse->id,
            'teacher_id' => $teacher->id,
            'semester' => 1,
            'semester_number' => 1,
            'day_of_week' => 'monday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'room' => 'R-2',
            'is_active' => true,
        ]);

        $response = $this->get(route('timetable', ['program_id' => $program->id, 'semester' => 1]));

        $response->assertOk()
            ->assertSee('Programming Fundamentals')
            ->assertDontSee('English Literature')
            ->assertViewHas('selectedProgram', fn (?AcademicProgram $selected) => $selected?->is($program))
            ->assertViewHas('selectedSemester', 1);
    }

    public function test_gallery_page_maps_configured_gallery_items(): void
    {
        WebsitePage::create([
            'title' => 'Gallery',
            'slug' => 'gallery',
            'is_published' => true,
            'content' => [
                'gallery_items' => [
                    [
                        'image' => 'assets/images/demo.jpg',
                        'title' => 'Campus Block',
                        'caption' => 'Main building view',
                        'category' => 'campus',
                    ],
                ],
            ],
        ]);

        $response = $this->get(route('gallery'));

        $response->assertOk()
            ->assertSee('Campus Block')
            ->assertSee('Main building view');
    }

    public function test_events_page_only_lists_published_events(): void
    {
        WebsiteEvent::create([
            'title' => 'Published Event',
            'slug' => 'published-event',
            'start_datetime' => now()->addDays(2),
            'is_published' => true,
        ]);
        WebsiteEvent::create([
            'title' => 'Hidden Event',
            'slug' => 'hidden-event',
            'start_datetime' => now()->addDays(3),
            'is_published' => false,
        ]);

        $response = $this->get(route('events'));

        $response->assertOk()
            ->assertSee('Published Event')
            ->assertDontSee('Hidden Event');
    }

    private function createTeacher(string $name, bool $active = true): Teacher
    {
        return Teacher::create([
            'employee_id' => 'EMP-' . Str::upper(Str::random(6)),
            'name' => $name,
            'designation' => 'Lecturer',
            'status' => $active ? 'active' : 'inactive',
            'is_active' => $active,
        ]);
    }

    private function createStudentAcademicContext(string $studentName): array
    {
        $department = Department::create([
            'name' => 'Computer Science',
            'slug' => 'computer-science',
            'code' => 'CS',
            'type' => 'academic',
            'is_active' => true,
            'show_on_website' => true,
        ]);

        $year = AcademicYear::create([
            'name' => '2026-2027',
            'start_date' => '2026-08-01',
            'end_date' => '2027-06-30',
            'is_current' => true,
            'is_active' => true,
        ]);

        $program = AcademicProgram::factory()->create([
            'department_id' => $department->id,
            'name' => 'BS Computer Science',
            'short_name' => 'BSCS',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $student = Student::factory()->create([
            'department_id' => $department->id,
            'academic_program_id' => $program->id,
            'academic_year_id' => $year->id,
            'name' => $studentName,
            'roll_number' => 'CS-2026-1001',
            'current_semester' => 1,
            'status' => 'active',
            'is_active' => true,
        ]);

        return [$student, $department, $year, $program];
    }

    private function createExam(int $programId, bool $resultsPublished, string $title): Exam
    {
        $program = AcademicProgram::findOrFail($programId);
        $course = $this->createCourse($programId, $title . ' Course', Str::upper(Str::substr($title, 0, 3)) . '-101', $program->department_id);

        return Exam::create([
            'course_id' => $course->id,
            'academic_program_id' => $program->id,
            'title' => $title,
            'exam_type' => 'midterm',
            'semester_number' => 1,
            'exam_date' => now()->toDateString(),
            'total_marks' => 100,
            'passing_marks' => 40,
            'results_published' => $resultsPublished,
            'is_published' => true,
        ]);
    }

    private function createCourse(int $programId, string $name, string $code, ?int $departmentId = null): Course
    {
        return Course::create([
            'department_id' => $departmentId,
            'academic_program_id' => $programId,
            'name' => $name,
            'code' => $code,
            'slug' => Str::slug($name . '-' . $code),
            'course_type' => 'core',
            'semester_number' => 1,
            'credit_hours' => 3,
            'is_active' => true,
            'show_on_website' => true,
            'sort_order' => 1,
        ]);
    }
}
