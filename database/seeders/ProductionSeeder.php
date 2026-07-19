<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Clean starting point for a live deployment.
 *
 * Seeds ONLY:
 *   - Reference/lookup data (dropdown options used by admin forms)
 *   - College settings & appearance
 *   - Public website pages and homepage sections (public-facing content)
 *   - Fee slip and notification templates (useful presets)
 *   - A single super_admin account
 *
 * It intentionally creates NO students, teachers, departments, programs,
 * fees, exams, or other operational records. The super admin adds those.
 */
class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Lookup data and appearance/settings.
            ListItemSeeder::class,
            CollegeSettingSeeder::class,

            // Public website content.
            WebsitePageSeeder::class,
            HomeSectionSeeder::class,

            // Public-facing data: departments, programmes, news, events, notices.
            PublicContentSeeder::class,

            // Useful presets.
            FeeSlipTemplateSeeder::class,
            NotificationTemplateSeeder::class,

            // Permissions + standard roles (super_admin / admin / panel_user).
            ShieldSeeder::class,
        ]);

        // The only user in the system: full-access super administrator.
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'phone' => '+923129776585',
                'password' => 'admin123',
            ]
        );
        $superAdmin->syncRoles([$superAdminRole->name]);

        $this->command->info('');
        $this->command->info('╔════════════════════════════════════════════════════╗');
        $this->command->info('║              SUPER ADMIN LOGIN                     ║');
        $this->command->info('╠════════════════════════════════════════════════════╣');
        $this->command->info('║  Admin panel →  /admin                             ║');
        $this->command->info('║  Email:    admin@admin.com                         ║');
        $this->command->info('║  Password: admin123                                ║');
        $this->command->info('╚════════════════════════════════════════════════════╝');
        $this->command->info('');
    }
}
