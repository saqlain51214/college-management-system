<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\ManagesPageSections;
use App\Filament\Resources\ScholarshipResource;
use App\Models\Scholarship;
use App\Models\WebsitePage;
use Filament\Pages\Page;

class AdmissionSections extends Page
{
    use ManagesPageSections;

    protected static ?string $navigationGroup = 'Website Pages';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Admission';

    protected static ?string $title = 'Admission — Sections';

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.sections-manager';

    public string $intro = '"Download Fee Challan" isn\'t listed here — it\'s a working self-service tool with nothing to edit, not page content.';

    public function getSections(): array
    {
        return [
            $this->pageRow('admission-procedure', 'Admission Procedure'),
            $this->pageRow('admissions', 'Online Admission Form (intro)'),
            $this->pageRow('fee-structure', 'Fee Structure (info page)'),
            $this->pageRow('semester-rules', 'Semester Rules', ['detail' => $this->semesterRulesDetail()]),
            $this->collectionRow(
                'Scholarships',
                Scholarship::count(),
                ScholarshipResource::getUrl('index'),
                ['crossLink' => 'Managed under the existing Scholarships group — shown here for reference only.']
            ),
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
