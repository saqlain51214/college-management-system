<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Generates all Filament Shield permissions and sets up the standard roles so
 * the super administrator can assign scoped access from the Roles screen
 * (/admin/shield/roles) without any code changes.
 *
 * Idempotent — safe to run on every deploy. Only permission RECORDS are
 * generated (no policy files are written), so it works on read-only hosts.
 */
class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        // Create permission records for every resource / page / widget in the panel.
        Artisan::call('shield:generate', [
            '--all'             => true,
            '--option'          => 'permissions',
            '--panel'           => 'admin',
            '--no-interaction'  => true,
            '--silent'          => true,
        ]);

        $permissions = Permission::query()->where('guard_name', 'web')->pluck('name');

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $admin      = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $panelUser  = Role::firstOrCreate(['name' => 'panel_user', 'guard_name' => 'web']);

        if ($permissions->isEmpty()) {
            return;
        }

        // Super admin: everything (also bypasses via Gate::before, this keeps it explicit).
        $superAdmin->syncPermissions($permissions);

        $widgetPermissions = $permissions
            ->filter(fn (string $p) => str_starts_with($p, 'widget_'))
            ->values();

        // Admin: day-to-day operations (no roles/users/settings, no destructive perms).
        $admin->syncPermissions($this->permissionsForResources(
            $permissions,
            resources: [
                'academic_program', 'academic_year', 'admission_inquiry', 'contact_message',
                'course', 'department', 'fee_payment', 'fee_structure', 'scholarship',
                'scholarship_award', 'list_item', 'news_article', 'announcement', 'student',
                'teacher', 'website_event', 'website_page', 'home_section', 'download',
                'fee::slip::template',
            ],
            prefixes: ['view', 'view_any', 'create', 'update', 'delete', 'replicate', 'reorder'],
            exactPermissions: $widgetPermissions->all(),
        ));

        // Panel user: website / CMS content + enquiry inboxes only.
        $panelUser->syncPermissions($this->permissionsForResources(
            $permissions,
            resources: [
                'admission_inquiry', 'contact_message', 'news_article', 'announcement',
                'website_event', 'website_page', 'home_section', 'download', 'list_item',
            ],
            prefixes: ['view', 'view_any', 'create', 'update'],
            exactPermissions: $widgetPermissions->all(),
        ));
    }

    private function permissionsForResources(Collection $permissions, array $resources, array $prefixes, array $exactPermissions = []): Collection
    {
        return $permissions
            ->filter(function (string $permission) use ($resources, $prefixes, $exactPermissions): bool {
                if (in_array($permission, $exactPermissions, true)) {
                    return true;
                }

                return $this->hasAllowedPrefix($permission, $prefixes)
                    && $this->matchesAnyResource($permission, $resources)
                    && ! $this->isSensitivePermission($permission)
                    && ! $this->isDestructivePermission($permission);
            })
            ->unique()
            ->values();
    }

    private function hasAllowedPrefix(string $permission, array $prefixes): bool
    {
        foreach ($prefixes as $prefix) {
            if (str_starts_with($permission, $prefix . '_')) {
                return true;
            }
        }

        return false;
    }

    private function matchesAnyResource(string $permission, array $resources): bool
    {
        foreach ($resources as $resource) {
            if (str_ends_with($permission, '_' . $resource)) {
                return true;
            }
        }

        return false;
    }

    private function isSensitivePermission(string $permission): bool
    {
        return str_contains($permission, '_role')
            || str_contains($permission, '_user')
            || str_contains($permission, 'shield')
            || str_contains($permission, 'setting')
            || str_starts_with($permission, 'page_');
    }

    private function isDestructivePermission(string $permission): bool
    {
        return str_starts_with($permission, 'delete_any_')
            || str_starts_with($permission, 'force_delete_')
            || str_starts_with($permission, 'force_delete_any_')
            || str_starts_with($permission, 'restore_any_');
    }
}
