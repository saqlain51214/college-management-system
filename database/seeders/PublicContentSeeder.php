<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\NewsArticle;
use App\Models\WebsiteEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Public-facing content so the website is not empty on launch:
 *   - Departments & academic programmes (via JdcaProgramsSeeder)
 *   - News articles          → home "Latest News" + /news
 *   - Website events         → home "Latest Updates" + /events
 *   - Announcements / notices → home "Latest Updates" + /notices
 *
 * Dates are relative to today so notices/events stay current and never
 * fall out of the "active" window. Everything is upserted (idempotent),
 * so re-running does not create duplicates.
 */
class PublicContentSeeder extends Seeder
{
    public function run(): void
    {
        // Departments + all JDCA programmes (both flagged show_on_website).
        $this->call(JdcaProgramsSeeder::class);

        $this->seedNews();
        $this->seedEvents();
        $this->seedAnnouncements();
    }

    private function seedNews(): void
    {
        $articles = [
            [
                'title'    => 'Admissions Open for the New Academic Session',
                'category' => 'admissions',
                'excerpt'  => 'Applications are now open for Intermediate, ADP and BS programmes. Apply online through the college website before the deadline.',
                'content'  => 'Jinnah School & Degree College Astore is pleased to announce that admissions are now open for the new academic session across all Intermediate, Associate Degree (ADP) and Bachelor (BS) programmes. Prospective students can submit their applications online through the official college website. Merit lists will be displayed on the notice board and shared with applicants via SMS and email.',
                'featured' => true,
                'days_ago' => 3,
            ],
            [
                'title'    => 'Annual Prize Distribution & Result Ceremony',
                'category' => 'event',
                'excerpt'  => 'The college celebrated the academic achievements of its top-performing students at the annual prize distribution ceremony.',
                'content'  => 'The annual prize distribution ceremony was held at the main campus to honour students who achieved outstanding results in the previous academic year. The Principal congratulated the position holders and encouraged all students to continue striving for excellence. Parents and faculty members attended the event in large numbers.',
                'featured' => true,
                'days_ago' => 12,
            ],
            [
                'title'    => 'New Computer Laboratory Inaugurated',
                'category' => 'news',
                'excerpt'  => 'A modern computer laboratory equipped with the latest hardware has been established to support IT and computer science students.',
                'content'  => 'To strengthen practical learning, the college has inaugurated a new, fully equipped computer laboratory. The facility supports the growing number of students enrolled in computer science and IT-related programmes, providing hands-on experience with modern software and development tools.',
                'featured' => false,
                'days_ago' => 25,
            ],
            [
                'title'    => 'Sports Gala & Inter-Class Competitions Held',
                'category' => 'news',
                'excerpt'  => 'Students participated enthusiastically in cricket, football, and athletics during the annual sports gala.',
                'content'  => 'The college organised its annual sports gala featuring inter-class competitions in cricket, football, volleyball and athletics. The event promoted teamwork, discipline and a healthy competitive spirit among students. Winning teams and individual champions were awarded medals and certificates.',
                'featured' => false,
                'days_ago' => 40,
            ],
        ];

        foreach ($articles as $a) {
            NewsArticle::updateOrCreate(
                ['slug' => Str::slug($a['title'])],
                [
                    'title'          => $a['title'],
                    'slug'           => Str::slug($a['title']),
                    'excerpt'        => $a['excerpt'],
                    'content'        => $a['content'],
                    'category'       => $a['category'],
                    'published_date' => now()->subDays($a['days_ago'])->toDateString(),
                    'is_published'   => true,
                    'is_featured'    => $a['featured'],
                ]
            );
        }
    }

    private function seedEvents(): void
    {
        $events = [
            [
                'title'       => 'Admission Test — New Session',
                'description' => 'Entry test for all applicants of Intermediate, ADP and BS programmes. Bring your CNIC/B-Form and application slip.',
                'venue'       => 'Main Campus Examination Hall, Astore',
                'in_days'     => 7,
                'duration'    => 3,
                'featured'    => true,
            ],
            [
                'title'       => 'Orientation Day for New Students',
                'description' => 'Welcome session introducing newly admitted students to the college, faculty, rules and facilities.',
                'venue'       => 'College Auditorium',
                'in_days'     => 21,
                'duration'    => 4,
                'featured'    => true,
            ],
            [
                'title'       => 'Parent–Teacher Meeting',
                'description' => 'Parents are invited to discuss the academic progress and attendance of their children with the faculty.',
                'venue'       => 'Respective Class Rooms',
                'in_days'     => 35,
                'duration'    => 3,
                'featured'    => false,
            ],
        ];

        foreach ($events as $e) {
            $start = now()->addDays($e['in_days'])->setTime(9, 0);
            WebsiteEvent::updateOrCreate(
                ['slug' => Str::slug($e['title'])],
                [
                    'title'          => $e['title'],
                    'slug'           => Str::slug($e['title']),
                    'description'    => $e['description'],
                    'venue'          => $e['venue'],
                    'start_datetime' => $start,
                    'end_datetime'   => (clone $start)->addHours($e['duration']),
                    'is_published'   => true,
                    'is_featured'    => $e['featured'],
                ]
            );
        }
    }

    private function seedAnnouncements(): void
    {
        $announcements = [
            [
                'title'    => 'Admissions Open — Apply Online Now',
                'content'  => 'Admissions for the new academic session are now open for all Intermediate, ADP and BS programmes. Submit your application online through the college website. Last date to apply will be announced shortly.',
                'priority' => 'high',
                'from'     => 2,
                'valid'    => 45,
            ],
            [
                'title'    => 'Fee Submission Schedule Announced',
                'content'  => 'All students are advised to submit their semester fees before the due date to avoid a late fine. Fee challans can be downloaded from the student portal.',
                'priority' => 'medium',
                'from'     => 5,
                'valid'    => 30,
            ],
            [
                'title'    => 'College Reopening After Break',
                'content'  => 'The college will resume regular classes as per the announced academic calendar. All students must attend classes regularly from the reopening date.',
                'priority' => 'medium',
                'from'     => 8,
                'valid'    => 25,
            ],
        ];

        foreach ($announcements as $a) {
            Announcement::updateOrCreate(
                ['title' => $a['title']],
                [
                    'title'        => $a['title'],
                    'content'      => $a['content'],
                    'audience'     => 'all',
                    'priority'     => $a['priority'],
                    'department_id' => null,
                    'is_published' => true,
                    'publish_date' => now()->subDays($a['from'])->toDateString(),
                    'expiry_date'  => now()->addDays($a['valid'])->toDateString(),
                ]
            );
        }
    }
}
