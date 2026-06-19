<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use Illuminate\Database\Seeder;

class HomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (HomeSection::definitions() as $key => $definition) {
            HomeSection::updateOrCreate(
                ['key' => $key],
                [
                    'title' => $definition['title'],
                    'content' => HomeSection::defaultContentFor($key),
                    'sort_order' => $definition['sort'],
                    'is_active' => true,
                ]
            );
        }
    }
}
