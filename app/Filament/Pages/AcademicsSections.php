<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\ManagesPageSections;
use App\Filament\Resources\AcademicProgramResource;
use App\Filament\Resources\CourseOutlineResource;
use App\Filament\Resources\DepartmentResource;
use App\Filament\Resources\TeacherResource;
use App\Models\AcademicProgram;
use App\Models\CourseOutline;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\WebsitePage;
use Filament\Pages\Page;

class AcademicsSections extends Page
{
    use ManagesPageSections;

    protected static ?string $navigationGroup = 'Website Pages';

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
            $this->pageRow('faculty', 'Faculty & Leadership'),
            $this->pageRow('semester-rules', 'Semester Rules', ['detail' => $this->semesterRulesDetail()]),
            $this->collectionRow('Course Outlines', CourseOutline::count(), CourseOutlineResource::getUrl('index')),
        ];
    }

    protected function semesterRulesDetail(): string
    {
        $page = WebsitePage::where('slug', 'semester-rules')->first();
        $sections = $page?->content['rule_sections'] ?? WebsitePage::defaultContentFor('semester-rules')['rule_sections'];
        $ruleCount = collect($sections)->sum(fn ($section) => count($section['rules'] ?? []));

        return count($sections) . ' section' . (count($sections) === 1 ? '' : 's') . ' · ' . $ruleCount . ' rule' . ($ruleCount === 1 ? '' : 's') . ' — add/edit/delete inside Edit';
    }
}
