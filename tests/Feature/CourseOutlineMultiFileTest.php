<?php

namespace Tests\Feature;

use App\Models\CourseOutline;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The public Department detail page used to link every "N PDFs" card to only
 * file_paths[0] (CourseOutline::getUrlAttribute()) — files #2+ were unreachable.
 * All uploaded files must now be individually linked when a course outline has more than one.
 */
class CourseOutlineMultiFileTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_files_of_a_multi_file_course_outline_are_linked_on_the_department_page(): void
    {
        $department = Department::create([
            'name' => 'Department of Education',
            'slug' => 'department-of-education',
            'is_active' => true,
            'show_on_website' => true,
        ]);

        CourseOutline::create([
            'department_id' => $department->id,
            'semester_number' => 1,
            'title' => 'Semester 1 Course Outline',
            'file_paths' => ['course-outlines/a.pdf', 'course-outlines/b.pdf', 'course-outlines/c.pdf'],
            'is_active' => true,
        ]);

        $response = $this->get(route('departments.show', 'department-of-education'));

        $response->assertOk();
        $response->assertSee('3 PDFs', false);
        $response->assertSee(asset('storage/course-outlines/a.pdf'), false);
        $response->assertSee(asset('storage/course-outlines/b.pdf'), false);
        $response->assertSee(asset('storage/course-outlines/c.pdf'), false);
    }

    public function test_a_single_file_course_outline_still_links_directly(): void
    {
        $department = Department::create([
            'name' => 'Department of Science',
            'slug' => 'department-of-science',
            'is_active' => true,
            'show_on_website' => true,
        ]);

        CourseOutline::create([
            'department_id' => $department->id,
            'semester_number' => 1,
            'title' => 'Semester 1 Course Outline',
            'file_paths' => ['course-outlines/only.pdf'],
            'is_active' => true,
        ]);

        $response = $this->get(route('departments.show', 'department-of-science'));

        $response->assertOk();
        $response->assertSee(asset('storage/course-outlines/only.pdf'), false);
    }
}
