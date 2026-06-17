<?php

namespace Database\Seeders;

use App\Models\WebsiteEvent;
use Illuminate\Database\Seeder;

class WebsiteEventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title'       => 'Fall 2024 Midterm Examinations',
                'description' => 'Midterm examinations for all programs for Fall 2024 semester. Check individual course schedules on the timetable notice board.',
                'venue'       => 'Examination Halls — Block A & B',
                'start'       => '2024-11-14 09:00:00',
                'end'         => '2024-11-25 16:00:00',
                'type'        => 'exam',
                'pub'         => true,
                'featured'    => true,
            ],
            [
                'title'       => 'Annual Science & Technology Exhibition 2024',
                'description' => 'Students showcase innovative projects in AI, IoT, Robotics, and Web Development. Open to all students, faculty, and public. Judges from industry and academia.',
                'venue'       => 'College Auditorium & Main Hall',
                'start'       => '2024-10-15 09:00:00',
                'end'         => '2024-10-15 17:00:00',
                'type'        => 'exhibition',
                'pub'         => true,
                'featured'    => true,
            ],
            [
                'title'       => 'Guest Lecture: Machine Learning in Industry',
                'description' => 'Guest lecture by Dr. Kashif Rashid, Senior Data Scientist at Systems Ltd. on "Machine Learning Applications in Pakistani Tech Industry". Mandatory for BS-CS Sem 5 students.',
                'venue'       => 'Seminar Hall, Block B',
                'start'       => '2024-10-25 14:00:00',
                'end'         => '2024-10-25 16:00:00',
                'type'        => 'lecture',
                'pub'         => true,
                'featured'    => false,
            ],
            [
                'title'       => 'Iqbal Day Literary Festival 2024',
                'description' => 'Celebrating Iqbal Day with Naatia Mushaira, Hamd & Naat competitions, debate on Iqbal\'s philosophy, and a special exhibition on Allama Iqbal\'s life and works.',
                'venue'       => 'College Auditorium',
                'start'       => '2024-11-09 09:00:00',
                'end'         => '2024-11-09 17:00:00',
                'type'        => 'cultural',
                'pub'         => true,
                'featured'    => false,
            ],
            [
                'title'       => 'Admission Open Day — Spring 2025',
                'description' => 'Prospective students and parents are invited for campus tours, program information sessions, and meetings with faculty. Free counseling for program selection available.',
                'venue'       => 'College Grounds & Departments',
                'start'       => '2024-11-15 10:00:00',
                'end'         => '2024-11-15 15:00:00',
                'type'        => 'admission',
                'pub'         => true,
                'featured'    => true,
            ],
            [
                'title'       => 'Sports Gala 2024 — Inter-Departmental Competition',
                'description' => 'Annual sports gala featuring cricket, football, badminton, table tennis, and athletics. Open to all enrolled students. Prizes for top performers in each category.',
                'venue'       => 'College Sports Ground',
                'start'       => '2024-12-06 08:00:00',
                'end'         => '2024-12-08 17:00:00',
                'type'        => 'sports',
                'pub'         => true,
                'featured'    => false,
            ],
            [
                'title'       => 'Alumni Meet 2024 — Networking Evening',
                'description' => 'Annual alumni gathering with achievement awards, panel discussion on careers in the digital age, and launch of the Alumni Scholarship Fund. Dinner included.',
                'venue'       => 'College Auditorium',
                'start'       => '2024-12-15 17:00:00',
                'end'         => '2024-12-15 22:00:00',
                'type'        => 'cultural',
                'pub'         => true,
                'featured'    => false,
            ],
            [
                'title'       => 'Final Year Project Presentations — BS-CS 2024',
                'description' => 'Final year BS-CS students present their capstone projects to a panel of industry experts and faculty. Best projects will be nominated for HEC Innovation Award.',
                'venue'       => 'Seminar Hall & CS Labs',
                'start'       => '2025-01-15 09:00:00',
                'end'         => '2025-01-17 17:00:00',
                'type'        => 'academic',
                'pub'         => true,
                'featured'    => true,
            ],
            [
                'title'       => 'Final Examinations — Fall 2024',
                'description' => 'Final examinations for all programs for the Fall 2024 semester. Students must carry their college ID card and admission slips. No calculators except where explicitly permitted.',
                'venue'       => 'Examination Halls — All Blocks',
                'start'       => '2025-01-20 09:00:00',
                'end'         => '2025-02-01 16:00:00',
                'type'        => 'exam',
                'pub'         => true,
                'featured'    => true,
            ],
            [
                'title'       => 'Two-Day Research Methodology Workshop',
                'description' => 'Workshop on Quantitative Research Methods in Social Sciences by Prof. Dr. Nasir Mehmood. Recommended for M.Phil/PhD students and junior faculty.',
                'venue'       => 'Conference Room, Admin Block',
                'start'       => '2024-11-08 09:00:00',
                'end'         => '2024-11-09 17:00:00',
                'type'        => 'workshop',
                'pub'         => true,
                'featured'    => false,
            ],
        ];

        foreach ($events as $e) {
            WebsiteEvent::firstOrCreate(
                ['title' => $e['title']],
                [
                    'title'          => $e['title'],
                    'slug'           => \Illuminate\Support\Str::slug($e['title']),
                    'description'    => $e['description'],
                    'venue'          => $e['venue'],
                    'start_datetime' => $e['start'],
                    'end_datetime'   => $e['end'],
                    'is_published'   => $e['pub'],
                    'is_featured'    => $e['featured'],
                ]
            );
        }
    }
}
