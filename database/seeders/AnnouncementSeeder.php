<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Department;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $deptCS  = Department::where('slug', 'department-of-computer-science')->value('id');
        $deptEdu = Department::where('slug', 'department-of-education')->value('id');

        $announcements = [
            // Urgent
            [
                'title'       => 'Examination Schedule — Fall 2024 Midterm Exams',
                'content'     => 'The midterm examinations for Fall 2024 will commence from 14th November 2024. Students are directed to check the detailed timetable on the notice board and college LMS portal. No re-scheduling requests will be entertained after 10th November.',
                'audience'    => 'students',
                'priority'    => 'urgent',
                'department'  => null,
                'published'   => true,
                'start'       => '2024-11-01',
                'end'         => '2024-11-30',
            ],
            [
                'title'       => 'Fee Submission Deadline — Semester 1 (2024-25)',
                'content'     => 'All students are reminded that the last date for fee submission for Semester 1 (Fall 2024) is 1st October 2024. A late fine of Rs. 100 per day will be charged after the deadline. Students with financial difficulties may contact the Accounts Office for installment plans.',
                'audience'    => 'students',
                'priority'    => 'urgent',
                'department'  => null,
                'published'   => true,
                'start'       => '2024-09-15',
                'end'         => '2024-10-10',
            ],

            // High Priority
            [
                'title'       => 'Faculty Meeting — Academic Calendar Review',
                'content'     => 'All faculty members are requested to attend a mandatory meeting on Saturday 19th October 2024 at 10:00 AM in the Conference Room. Agenda: (1) Review of academic calendar 2024-25 (2) Mid-semester assessment criteria (3) Any other business.',
                'audience'    => 'teachers',
                'priority'    => 'high',
                'department'  => null,
                'published'   => true,
                'start'       => '2024-10-14',
                'end'         => '2024-10-20',
            ],
            [
                'title'       => 'Anti-Plagiarism Policy — Assignment Submission',
                'content'     => 'Students are reminded that all written assignments must be original work. Turnitin will be used to check submissions. Any assignment with more than 20% similarity will receive zero marks and disciplinary action will follow for repeat offenders.',
                'audience'    => 'students',
                'priority'    => 'high',
                'department'  => $deptCS,
                'published'   => true,
                'start'       => '2024-09-01',
                'end'         => null,
            ],
            [
                'title'       => 'Guest Lecture — Machine Learning in Industry',
                'content'     => 'Department of Computer Science is organizing a guest lecture by Dr. Kashif Rashid (Senior Data Scientist, Systems Ltd.) on "Machine Learning Applications in Pakistani Tech Industry". Date: 25th October 2024, 2:00 PM. Venue: Seminar Hall. Attendance is mandatory for BS-CS Semester 5 students.',
                'audience'    => 'students',
                'priority'    => 'high',
                'department'  => $deptCS,
                'published'   => true,
                'start'       => '2024-10-18',
                'end'         => '2024-10-25',
            ],
            [
                'title'       => 'Teaching Practice Internship Placement — B.Ed 2024',
                'content'     => 'B.Ed students in their final semester are informed that teaching practice placement letters have been issued. Students must report to their assigned schools from 1st November 2024. Contact the Department of Education office for confirmation of your placement school.',
                'audience'    => 'students',
                'priority'    => 'high',
                'department'  => $deptEdu,
                'published'   => true,
                'start'       => '2024-10-20',
                'end'         => '2024-11-01',
            ],

            // Normal
            [
                'title'       => 'New Book Arrivals — College Library October 2024',
                'content'     => 'The College Library has acquired 50+ new books in Computer Science, Education, and English Literature. Students and faculty are encouraged to visit the library catalogue or check the LMS portal to browse new acquisitions. Issue period: 14 days for students, 28 days for faculty.',
                'audience'    => 'all',
                'priority'    => 'normal',
                'department'  => null,
                'published'   => true,
                'start'       => '2024-10-05',
                'end'         => null,
            ],
            [
                'title'       => 'Sports Trials — College Cricket & Football Teams 2024',
                'content'     => 'Applications are invited from talented male and female students for trials in College Cricket and Football teams. Trials will be held on Saturday 5th October 2024 at the College Sports Ground at 9:00 AM. Bring your college ID card and sports gear.',
                'audience'    => 'students',
                'priority'    => 'normal',
                'department'  => null,
                'published'   => true,
                'start'       => '2024-09-25',
                'end'         => '2024-10-05',
            ],
            [
                'title'       => 'ERP System Maintenance — Saturday 12th October',
                'content'     => 'The College Management System (CMS) and LMS portal will be unavailable for scheduled maintenance on Saturday 12th October 2024 from 10:00 PM to 6:00 AM Sunday. Please plan your work accordingly and download any materials before this window.',
                'audience'    => 'all',
                'priority'    => 'normal',
                'department'  => null,
                'published'   => true,
                'start'       => '2024-10-10',
                'end'         => '2024-10-13',
            ],
            [
                'title'       => 'Semester Registration Open — Spring 2025',
                'content'     => 'Online registration for Spring 2025 semester is now open. Students must complete their course registration by 31st December 2024. Students who fail to register will not be enrolled in any course. Login to the student portal to complete registration.',
                'audience'    => 'students',
                'priority'    => 'normal',
                'department'  => null,
                'published'   => true,
                'start'       => '2024-12-01',
                'end'         => '2024-12-31',
            ],
            [
                'title'       => 'Research Methodology Workshop — M.Phil/PhD Students',
                'content'     => 'A two-day workshop on "Quantitative Research Methods in Social Sciences" will be conducted on 8-9 November 2024 by Prof. Dr. Nasir Mehmood. Participation is strongly recommended for M.Phil and PhD students. Register via email by 4th November.',
                'audience'    => 'teachers',
                'priority'    => 'normal',
                'department'  => $deptEdu,
                'published'   => true,
                'start'       => '2024-11-01',
                'end'         => '2024-11-09',
            ],
            [
                'title'       => 'Scholarship Applications Open — PEEF 2024-25',
                'content'     => 'Punjab Education Endowment Fund (PEEF) scholarship applications for academic year 2024-25 are now open. Eligible students (Punjab domicile, family income below Rs. 30,000/month) are encouraged to apply by 15th November 2024. Visit the Accounts Office for forms.',
                'audience'    => 'students',
                'priority'    => 'normal',
                'department'  => null,
                'published'   => true,
                'start'       => '2024-10-15',
                'end'         => '2024-11-15',
            ],

            // Low Priority
            [
                'title'       => 'Cafeteria Renovation — Temporary Closure',
                'content'     => 'The college cafeteria will be closed for renovation from 7th to 14th October 2024. Alternative food arrangements have been made near the main gate. We apologize for the inconvenience.',
                'audience'    => 'all',
                'priority'    => 'low',
                'department'  => null,
                'published'   => true,
                'start'       => '2024-10-05',
                'end'         => '2024-10-14',
            ],
            [
                'title'       => 'Suggestion Box — Student Feedback Initiative',
                'content'     => 'A new digital suggestion box is now available on the college portal. Students are encouraged to anonymously submit feedback on teaching quality, facilities, and administrative services. All submissions are reviewed by the Principal\'s office monthly.',
                'audience'    => 'students',
                'priority'    => 'low',
                'department'  => null,
                'published'   => true,
                'start'       => '2024-10-01',
                'end'         => null,
            ],
            [
                'title'       => 'Annual Function 2024 — Preparations Underway',
                'content'     => 'The Cultural Society invites students from all departments to participate in the Annual Function 2024. Activities include: Naat competition, Debate, Drama, Musical evening, and Sports awards. Registration open till 20th November. Coordinate with your class representatives.',
                'audience'    => 'students',
                'priority'    => 'low',
                'department'  => null,
                'published'   => true,
                'start'       => '2024-11-10',
                'end'         => '2024-12-05',
            ],
        ];

        foreach ($announcements as $a) {
            Announcement::firstOrCreate(
                ['title' => $a['title']],
                [
                    'title'         => $a['title'],
                    'content'       => $a['content'],
                    'audience'      => $a['audience'],
                    'priority'      => $a['priority'],
                    'department_id' => $a['department'],
                    'is_published'  => $a['published'],
                    'publish_date'  => $a['start'],
                    'expiry_date'   => $a['end'],
                ]
            );
        }
    }
}
