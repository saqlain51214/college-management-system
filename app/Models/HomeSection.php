<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    public const SECTIONS = [
        'elevate-learning' => ['title' => 'Elevate Your Learning', 'sort' => 1],
        'campus-life' => ['title' => 'Campus Life', 'sort' => 2],
        'testimonials' => ['title' => 'Testimonials', 'sort' => 3],
    ];

    protected $fillable = [
        'key',
        'title',
        'content',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function definitions(): array
    {
        return self::SECTIONS;
    }

    public static function defaultContentFor(string $key): array
    {
        return match ($key) {
            'elevate-learning' => [
                'section_title' => 'Elevate your learning',
                'section_text' => 'Discover your potential through programs designed by educators and industry leaders.',
                'badge_text' => 'Premium education',
                'heading' => 'World-class programs for every learner',
                'description' => 'Flexible formats, expert faculty, and resources that fit your goals.',
                'primary_button_text' => 'Explore programs',
                'primary_button_link' => 'programs',
                'secondary_button_text' => 'Contact admissions',
                'secondary_button_link' => 'contact',
                'stats' => [
                    ['value' => '7500+', 'label' => 'Active learners'],
                    ['value' => '60+', 'label' => 'Expert courses'],
                ],
                'feature_cards' => [
                    ['icon' => '🏆', 'title' => 'Certified excellence', 'text' => 'Industry-recognized certificates'],
                    ['icon' => '👥', 'title' => 'Global community', 'text' => 'Connect worldwide'],
                    ['icon' => '⏱', 'title' => 'Learn at your pace', 'text' => '24/7 resource access'],
                ],
                'main_image' => 'assets/images/photo-1588072432836-e10032774350.jpg',
            ],
            'campus-life' => [
                'section_title' => 'Campus life',
                'section_text' => 'Experience the vibrant community and opportunities on campus.',
                'intro_label' => 'Student Life',
                'heading' => 'Everything you need for a better education, on one campus',
                'description' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.',
                'link_text' => 'Explore student life',
                'link_route' => 'gallery',
                'stats' => [
                    ['value' => '85+', 'label' => 'Student Organizations'],
                    ['value' => '150+', 'label' => 'Annual Events'],
                ],
                'hero_image' => 'assets/images/photo-1588072432836-e10032774350.jpg',
                'hero_image_alt' => 'Campus courtyard with students',
                'hero_badge' => 'Campus community',
                'support_images' => [
                    ['image' => 'assets/images/photo-1584697964190-a8e6cb8a0b79.jpg', 'alt' => 'Campus life activity'],
                    ['image' => 'assets/images/photo-1596495577886-d920f1fb7238.jpg', 'alt' => 'Students on campus'],
                ],
                'cards' => [
                    [
                        'image' => 'assets/images/photo-1500530855697-b586d89ba3ee.jpg',
                        'title' => 'Leadership Development',
                        'description' => 'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.',
                    ],
                    [
                        'image' => 'assets/images/photo-1581091870622-1e7d4c5e9d5d.jpg',
                        'title' => 'Cultural Diversity',
                        'description' => 'Ut enim ad minima veniam quis nostrum exercitationem ullam corporis suscipit.',
                    ],
                    [
                        'image' => 'assets/images/photo-1599058917765-a780eda07a3e.jpg',
                        'title' => 'Innovation Hub',
                        'description' => 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam.',
                    ],
                ],
            ],
            'testimonials' => [
                'section_title' => 'Testimonials',
                'section_text' => 'Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit.',
                'items' => [
                    [
                        'portrait' => 'assets/images/photo-1573496359142-b8d87734a5a2.jpg',
                        'portrait_alt' => 'Student portrait',
                        'quote' => 'MySchool transformed my path - the rigor, mentorship, and community gave me confidence to lead.',
                        'avatar' => 'assets/images/portrait-women-45.jpg',
                        'name' => 'Emma Mitchell',
                        'role' => 'Alumni, Business Administration',
                    ],
                    [
                        'portrait' => 'assets/images/photo-1560250097-0b93528c311a.jpg',
                        'portrait_alt' => 'Graduate portrait',
                        'quote' => 'Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit. Sed ut perspiciatis unde omnis iste natus error.',
                        'avatar' => 'assets/images/portrait-men-32.jpg',
                        'name' => 'James Porter',
                        'role' => 'Graduate, Engineering',
                    ],
                    [
                        'portrait' => 'assets/images/photo-1544717305-278376deb5da.jpg',
                        'portrait_alt' => 'Student on campus',
                        'quote' => 'The faculty know you by name. Internships and research labs opened doors I never expected before graduation.',
                        'avatar' => 'assets/images/portrait-women-68.jpg',
                        'name' => 'Priya Sharma',
                        'role' => 'Current student, Sciences',
                    ],
                    [
                        'portrait' => 'assets/images/photo-1519085360753-af0119f7cbe7.jpg',
                        'portrait_alt' => 'Alumni portrait',
                        'quote' => 'Campus life balanced academics with clubs and sports - I found mentors and friends for life.',
                        'avatar' => 'assets/images/portrait-men-52.jpg',
                        'name' => 'Marcus Rodriguez',
                        'role' => 'Alumni, 2022',
                    ],
                ],
            ],
            default => [],
        };
    }
}
