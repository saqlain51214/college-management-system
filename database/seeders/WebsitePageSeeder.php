<?php

namespace Database\Seeders;

use App\Models\WebsitePage;
use Illuminate\Database\Seeder;

class WebsitePageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (WebsitePage::staticPageDefinitions() as $slug => $definition) {
            WebsitePage::updateOrCreate(
                ['slug' => $slug],
                [
                    'title'        => $definition['title'],
                    'content'      => WebsitePage::defaultContentFor($slug),
                    'sort_order'   => $definition['sort'],
                    'in_menu'      => $definition['in_menu'],
                    'is_published' => true,
                ]
            );
        }
    }
}
