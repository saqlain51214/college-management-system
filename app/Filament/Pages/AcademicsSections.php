<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\WebsitePagesCluster;
use App\Filament\Concerns\ManagesPageSections;
use App\Filament\Resources\AcademicProgramResource;
use App\Filament\Resources\CourseOutlineResource;
use App\Filament\Resources\DepartmentResource;
use App\Filament\Resources\TeacherResource;
use App\Models\AcademicProgram;
use App\Models\CourseOutline;
use App\Models\Department;
use App\Models\Teacher;
use Filament\Pages\Page;

class AcademicsSections extends Page
{
    use ManagesPageSections;

    protected static ?string $cluster = WebsitePagesCluster::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Academics';

    protected static ?string $title = 'Academics — Sections';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.sections-manager';

    public function getSections(): array
    {
        return [
            $this->collectionRow('Departments', Department::count(), DepartmentResource::getUrl('index')),
            $this->collectionRow('Academic Programs', AcademicProgram::count(), AcademicProgramResource::getUrl('index')),
            $this->collectionRow(
                'Faculty Profile',
                Teacher::count(),
                TeacherResource::getUrl('index'),
                ['crossLink' => 'Managed under Faculty & Staff — shown here for reference only.']
            ),
            $this->pageRow('semester-rules', 'Semester Rules'),
            $this->collectionRow('Course Outlines', CourseOutline::count(), CourseOutlineResource::getUrl('index')),
        ];
    }
}
