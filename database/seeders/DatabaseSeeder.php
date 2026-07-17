<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            // Static production-style lookup and appearance settings.
            ListItemSeeder::class,
            CollegeSettingSeeder::class,

            // Minimal operational sample data for each core table.
            MinimalCoreDataSeeder::class,

            // Website CMS defaults after reference data exists.
            WebsitePageSeeder::class,
            HomeSectionSeeder::class,

            // Login accounts and role-based access testing.
            TestCredentialsSeeder::class,

            // Fee slip template designer presets.
            FeeSlipTemplateSeeder::class,
        ]);
    }
}
