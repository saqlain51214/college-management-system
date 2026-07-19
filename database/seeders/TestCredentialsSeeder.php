<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TestCredentialsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = Permission::query()
            ->where('guard_name', 'web')
            ->pluck('name');

        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $developerRole = Role::firstOrCreate(['name' => 'Developer', 'guard_name' => 'web']);
        $panelRole = Role::firstOrCreate(['name' => 'panel_user', 'guard_name' => 'web']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        if ($permissions->isNotEmpty()) {
            $widgetPermissions = $permissions
                ->filter(fn (string $permission) => str_starts_with($permission, 'widget_'))
                ->values();

            $superAdminRole->syncPermissions($permissions);

            $developerPermissions = $permissions
                ->reject(fn (string $permission) => $this->isSensitivePermission($permission) || $this->isDestructivePermission($permission))
                ->merge($widgetPermissions)
                ->unique()
                ->values();

            $adminPermissions = $this->permissionsForResources(
                permissions: $permissions,
                resources: [
                    'academic_program',
                    'academic_year',
                    'admission_inquiry',
                    'contact_message',
                    'course',
                    'department',
                    'fee_payment',
                    'fee_structure',
                    'list_item',
                    'news_article',
                    'student',
                    'teacher',
                    'website_event',
                    'website_page',
                    'home_section',
                ],
                prefixes: ['view', 'view_any', 'create', 'update', 'delete', 'replicate', 'reorder'],
                exactPermissions: $widgetPermissions->all(),
            );

            $panelPermissions = $this->permissionsForResources(
                permissions: $permissions,
                resources: [
                    'admission_inquiry',
                    'contact_message',
                    'news_article',
                    'website_event',
                    'website_page',
                    'home_section',
                    'list_item',
                ],
                prefixes: ['view', 'view_any', 'create', 'update'],
                exactPermissions: $widgetPermissions->all(),
            );

            $teacherPermissions = $this->permissionsForResources(
                permissions: $permissions,
                resources: [
                    'announcement',
                    'course',
                    'teacher',
                ],
                prefixes: ['view', 'view_any', 'update'],
            );

            $adminRole->syncPermissions($adminPermissions);
            $developerRole->syncPermissions($developerPermissions);
            $panelRole->syncPermissions($panelPermissions);
            $teacherRole->syncPermissions($teacherPermissions);
            $studentRole->syncPermissions([]);
        }

        $superAdmin = User::updateOrCreate(
            ['email' => 'jinnahschooldegreecollege@gmail.com'],
            [
                'name' => 'Arif Ali',
                'phone' => '+923129776585',
                'password' => 'admin@1234',
            ]
        );
        $superAdmin->syncRoles([$superAdminRole->name]);

        $admin = User::updateOrCreate(
            ['email' => 'admin@jdca.edu.pk'],
            [
                'name' => 'College Admin',
                'phone' => '0311-0000001',
                'password' => 'admin@1234',
            ]
        );
        $admin->syncRoles([$adminRole->name]);

        $developer = User::updateOrCreate(
            ['email' => 'developer@jdca.edu.pk'],
            [
                'name' => 'System Developer',
                'phone' => '0311-0000003',
                'password' => 'dev@1234',
            ]
        );
        $developer->syncRoles([$developerRole->name]);

        $staff = User::updateOrCreate(
            ['email' => 'staff@jdca.edu.pk'],
            [
                'name' => 'Office Staff',
                'phone' => '0311-0000002',
                'password' => 'staff@1234',
            ]
        );
        $staff->syncRoles([$panelRole->name]);

        $teacherUser = User::updateOrCreate(
            ['email' => 'teacher@jdca.edu.pk'],
            [
                'name' => 'Muhammad Bilal',
                'phone' => '0311-2345678',
                'password' => 'teacher@1234',
            ]
        );
        $teacherUser->syncRoles([$teacherRole->name]);

        $studentUser = User::updateOrCreate(
            ['email' => 'student@jdca.edu.pk'],
            [
                'name' => 'Ali Raza',
                'phone' => '0321-1234567',
                'password' => 'student@1234',
            ]
        );
        $studentUser->syncRoles([$studentRole->name]);

        if ($teacher = Teacher::where('employee_id', 'JDCA-T-001')->first()) {
            $teacher->user_id = $teacherUser->id;
            $teacher->portal_password = 'teacher@1234';
            $teacher->save();
        }

        if ($student = Student::where('roll_number', 'JDCA-2026-0001')->first()) {
            $student->user_id = $studentUser->id;
            $student->portal_password = 'student@1234';
            $student->save();
        }

        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════════════════════════╗');
        $this->command->info('║              TEST LOGIN CREDENTIALS                          ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  ADMIN PANEL  →  http://127.0.0.1:8000/admin                ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  Role: super_admin (Full Access)                             ║');
        $this->command->info('║  Email:    jinnahschooldegreecollege@gmail.com               ║');
        $this->command->info('║  Password: admin@1234                                        ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  Role: admin (Operational Access)                            ║');
        $this->command->info('║  Email:    admin@jdca.edu.pk                                 ║');
        $this->command->info('║  Password: admin@1234                                        ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  Role: Developer (Technical Access)                          ║');
        $this->command->info('║  Email:    developer@jdca.edu.pk                             ║');
        $this->command->info('║  Password: dev@1234                                          ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  Role: panel_user (Limited access)                           ║');
        $this->command->info('║  Email:    staff@jdca.edu.pk                                 ║');
        $this->command->info('║  Password: staff@1234                                        ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  Role: teacher                                                ║');
        $this->command->info('║  Email:    teacher@jdca.edu.pk                               ║');
        $this->command->info('║  Password: teacher@1234                                      ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  Role: student                                                ║');
        $this->command->info('║  Email:    student@jdca.edu.pk                               ║');
        $this->command->info('║  Password: student@1234                                      ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  STUDENT PORTAL  →  http://127.0.0.1:8000/portal/login      ║');
        $this->command->info('║  TEACHER PORTAL  →  http://127.0.0.1:8000/teacher/login     ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  Student: Ali Raza                                           ║');
        $this->command->info('║  Roll No: JDCA-2026-0001   Password: student@1234           ║');
        $this->command->info('╚══════════════════════════════════════════════════════════════╝');
        $this->command->info('');
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
