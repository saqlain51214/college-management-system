<?php

namespace App\Filament\Concerns;

use App\Models\WebsitePage;
use Filament\Notifications\Notification;

/**
 * Shared behaviour for the 4 "Sections" pages (Home Page / About Us /
 * Academics / Admission) — each one lists every section that composes that
 * public page, mixing single `WebsitePage` rows with summaries of
 * collection-type content (Leadership Messages, Departments, Scholarships,
 * ...). Renaming/toggling only makes sense for the single-page rows;
 * collection rows only ever get a "Manage" link into their own resource.
 */
trait ManagesPageSections
{
    /** @return array<int, array<string, mixed>> */
    abstract public function getSections(): array;

    /**
     * A single-content section backed by one `WebsitePage` row. `$slug` must
     * match a `WebsitePage::STATIC_PAGES` key.
     */
    protected function pageRow(string $slug, string $fallbackName, array $extra = []): array
    {
        $page = WebsitePage::where('slug', $slug)->first();

        return array_merge([
            'kind' => 'page',
            'slug' => $slug,
            'name' => $page?->menu_label_display ?? $fallbackName,
            'status' => (bool) $page?->is_published,
            'previewUrl' => $page?->previewUrl(true) ?? '#',
            'editUrl' => $page ? \App\Filament\Resources\WebsitePageResource::getUrl('edit', ['record' => $page]) : null,
        ], $extra);
    }

    /**
     * A section representing many records at once (Leadership Messages,
     * Departments, ...) — no single toggle makes sense, so this just shows a
     * count and a "Manage" link into the resource that already handles the
     * full add/edit/reorder/hide-per-record flow.
     */
    protected function collectionRow(string $name, int $count, ?string $manageUrl, array $extra = []): array
    {
        return array_merge([
            'kind' => 'collection',
            'name' => $name,
            'count' => $count,
            'manageUrl' => $manageUrl,
        ], $extra);
    }

    public function renameSection(string $slug, string $value): void
    {
        $page = WebsitePage::where('slug', $slug)->first();

        if (! $page) {
            return;
        }

        $page->update(['menu_label' => trim($value) ?: null]);

        Notification::make()
            ->title('Renamed')
            ->body('The menu now shows "' . $page->menu_label_display . '" — the page\'s web address did not change.')
            ->success()
            ->send();
    }

    public function toggleSection(string $slug): void
    {
        $page = WebsitePage::where('slug', $slug)->first();

        if (! $page) {
            return;
        }

        $page->update(['is_published' => ! $page->is_published]);

        Notification::make()
            ->title($page->is_published ? 'Section set to Live' : 'Section Hidden from the live site')
            ->success()
            ->send();
    }
}
