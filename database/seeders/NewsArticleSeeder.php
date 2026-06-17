<?php

namespace Database\Seeders;

use App\Models\NewsArticle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title'    => 'College Students Win First Place at National Programming Competition',
                'category' => 'achievement',
                'excerpt'  => 'BS-CS students Fatima Zahra and Hamza Raza secured first place at the National ICPC Programming Contest 2024 held in Islamabad, defeating teams from 45 universities.',
                'content'  => 'In a proud moment for our college, students Fatima Zahra (BS-CS, Batch 2024) and Hamza Raza (BS-CS, Batch 2023) won first place at the National ICPC Programming Contest 2024 held at COMSATS University Islamabad on 20th October 2024. The team solved 9 out of 12 problems, defeating teams from 45 universities across Pakistan. The Principal congratulated both students and their faculty supervisor Dr. Muhammad Asif Iqbal on this outstanding achievement.',
                'author'   => 'Admin',
                'date'     => '2024-10-22',
                'status'   => 'published',
                'featured' => true,
                'tags'     => 'programming,achievement,ICPC,competition',
            ],
            [
                'title'    => 'Annual Science and Technology Exhibition 2024',
                'category' => 'event',
                'excerpt'  => 'The annual science and technology exhibition was held with participation from over 200 students showcasing innovative projects in AI, IoT, and web development.',
                'content'  => 'The Annual Science and Technology Exhibition 2024 was held on 15th October 2024 at the college auditorium. Over 200 students from the Department of Computer Science presented 65 projects in categories including Artificial Intelligence, Internet of Things, Web Development, and Mobile Applications. The best project award was given to the team that developed an "AI-based Sign Language Interpreter" that converts Pakistani Sign Language into text in real-time.',
                'author'   => 'Admin',
                'date'     => '2024-10-16',
                'status'   => 'published',
                'featured' => true,
                'tags'     => 'exhibition,AI,IoT,innovation',
            ],
            [
                'title'    => 'New Computer Lab with 50 Latest Workstations Inaugurated',
                'category' => 'news',
                'excerpt'  => 'The college inaugurated a state-of-the-art computer lab equipped with 50 high-performance workstations funded by the Punjab Information Technology Board.',
                'content'  => 'The Principal inaugurated a new state-of-the-art Computer Lab (CS Lab 3) on 1st October 2024. The lab is equipped with 50 high-performance workstations featuring Intel Core i7 processors, 16GB RAM, and 512GB SSD storage. The lab was funded through a grant from the Punjab Information Technology Board (PITB) as part of the Digital Skills Pakistan initiative. This brings the total number of computer workstations in the college to 150.',
                'author'   => 'Admin',
                'date'     => '2024-10-02',
                'status'   => 'published',
                'featured' => false,
                'tags'     => 'infrastructure,lab,PITB,technology',
            ],
            [
                'title'    => 'Admissions Open for Spring 2025 — BS CS and B.Ed Programs',
                'category' => 'notice',
                'excerpt'  => 'Applications are now open for Spring 2025 semester for BS Computer Science and B.Ed programs. Merit-based and need-based scholarships available.',
                'content'  => 'The college is pleased to announce that admissions are open for the Spring 2025 semester in the following programs: BS Computer Science (4-year), B.Ed (1.5-year and 4-year). Merit criteria: FSc/ICS with minimum 60% marks for BS-CS; BA/BSc with 45% marks for B.Ed. Applications are accepted online through the college website. Scholarship opportunities are available including HEC Need-Based scholarships and college merit awards. Last date: 31st December 2024.',
                'author'   => 'Admin',
                'date'     => '2024-11-01',
                'status'   => 'published',
                'featured' => true,
                'tags'     => 'admissions,spring2025,scholarships',
            ],
            [
                'title'    => 'College Delegation Visits Beijing University for Academic Exchange',
                'category' => 'achievement',
                'excerpt'  => 'A delegation of faculty members visited Beijing Language and Culture University under the China-Pakistan Higher Education Collaboration initiative.',
                'content'  => 'A four-member faculty delegation from our college visited Beijing Language and Culture University (BLCU) in China from 5th to 12th October 2024 under the China-Pakistan Higher Education Collaboration initiative. The visit included academic meetings, curriculum exchange discussions, and exploration of joint research opportunities. An MOU for faculty and student exchange has been signed with BLCU, opening pathways for our students to study in China.',
                'author'   => 'Admin',
                'date'     => '2024-10-14',
                'status'   => 'published',
                'featured' => false,
                'tags'     => 'international,exchange,China,MOU',
            ],
            [
                'title'    => 'B.Ed Students Conclude Teaching Practice — Outstanding Feedback',
                'category' => 'news',
                'excerpt'  => 'B.Ed final semester students successfully completed their 8-week teaching practice internship, receiving excellent feedback from 12 partner schools.',
                'content'  => 'B.Ed students of Batch 2023 completed their 8-week teaching practice internship from 1st November to 31st December 2024. The students were placed at 12 partner schools across Lahore and Sheikhupura. Supervisory reports indicate that 92% of students received "Excellent" or "Good" ratings from school mentors. Dr. Rukhsana Bibi (Head of Education Department) commended the students for their professional conduct and teaching skills.',
                'author'   => 'Admin',
                'date'     => '2025-01-05',
                'status'   => 'published',
                'featured' => false,
                'tags'     => 'B.Ed,teaching-practice,internship',
            ],
            [
                'title'    => 'HEC Grants Approved for Research Projects — Faculty Update',
                'category' => 'achievement',
                'excerpt'  => 'Two faculty members secured HEC NRPU research grants worth PKR 4.2 million for AI and educational technology research projects.',
                'content'  => 'The college is proud to announce that two faculty members have been awarded HEC National Research Programme for Universities (NRPU) grants for 2024-2026. Dr. Muhammad Asif Iqbal (Computer Science) received PKR 2.8 million for a project on "AI-based Early Warning Systems for Student Academic Performance". Dr. Nasir Mehmood (Education) received PKR 1.4 million for "Digital Pedagogy Models for Underprivileged Schools in Punjab".',
                'author'   => 'Admin',
                'date'     => '2024-09-20',
                'status'   => 'published',
                'featured' => true,
                'tags'     => 'research,HEC,grants,faculty',
            ],
            [
                'title'    => 'Iqbal Day Celebrations — College Literary Festival 2024',
                'category' => 'event',
                'excerpt'  => 'The college celebrated Iqbal Day with a literary festival featuring Urdu poetry competitions, speeches, and a special exhibition on Allama Iqbal\'s life and work.',
                'content'  => 'The college observed Iqbal Day on 9th November 2024 with a grand literary festival. Events included: Naatia Mushaira, Hamd & Naat competitions, debate on "Iqbal\'s Vision and Modern Pakistan", and an exhibition on Allama Iqbal\'s philosophy and poetry. Over 300 students participated. The event was presided over by the Principal who delivered an inspiring speech on integrating Iqbal\'s philosophy in modern education.',
                'author'   => 'Admin',
                'date'     => '2024-11-10',
                'status'   => 'published',
                'featured' => false,
                'tags'     => 'Iqbal-Day,literary-festival,culture',
            ],
            [
                'title'    => 'Digital Skills Certificate Program Launched — Free for Students',
                'category' => 'notice',
                'excerpt'  => 'In collaboration with DigiSkills Pakistan, the college launches a free 3-month digital skills certificate program covering freelancing, graphic design, and web development.',
                'content'  => 'The college has partnered with DigiSkills Pakistan to offer free 3-month certificate courses in: (1) Freelancing Fundamentals (2) Graphic Design using Canva and Adobe (3) WordPress and Web Design. Classes will be held on Saturdays from 10:00 AM to 1:00 PM starting January 2025. Enrollment is open to all currently enrolled students. Register online at the college portal. Certificates from DigiSkills will be provided upon successful completion.',
                'author'   => 'Admin',
                'date'     => '2024-12-01',
                'status'   => 'published',
                'featured' => false,
                'tags'     => 'DigiSkills,certificate,free-course,digital',
            ],
            [
                'title'    => 'Fee Concession Policy 2024-25 — Revised Categories',
                'category' => 'circular',
                'excerpt'  => 'The Board of Governors has approved revised fee concession categories for 2024-25 including new provisions for deserving students from merged districts.',
                'content'  => 'The Board of Governors in its meeting held on 15th September 2024 approved a revised fee concession policy for academic year 2024-25. Key changes include: (1) Students from merged districts (formerly FATA) are eligible for 50% tuition fee concession. (2) Orphan students with NADRA-verified certificates receive 100% fee waiver. (3) Students with below 3.0 CGPA will lose merit scholarships but can apply for need-based alternatives. Applications must be submitted to the Accounts Office by 15th October 2024.',
                'author'   => 'Admin',
                'date'     => '2024-09-25',
                'status'   => 'published',
                'featured' => false,
                'tags'     => 'fee-concession,policy,BOG,circular',
            ],
            [
                'title'    => 'College Achieves ISO 9001 Quality Management Certification',
                'category' => 'achievement',
                'excerpt'  => 'The college has successfully obtained ISO 9001:2015 Quality Management System certification, becoming one of the few degree colleges in Punjab to achieve this distinction.',
                'content'  => 'We are delighted to announce that our college has been awarded ISO 9001:2015 Quality Management System certification following a rigorous three-day audit by Pakistan National Accreditation Council (PNAC) in August 2024. The certification recognizes the college\'s commitment to quality in teaching, examination processes, student support services, and administrative systems. This makes us one of only seven degree colleges in Punjab to hold this certification.',
                'author'   => 'Admin',
                'date'     => '2024-08-30',
                'status'   => 'published',
                'featured' => true,
                'tags'     => 'ISO,quality,certification,achievement',
            ],
            [
                'title'    => 'Alumni Meet 2024 — Register Now',
                'category' => 'event',
                'excerpt'  => 'The college is organizing its annual Alumni Meet on 15th December 2024. Alumni from all batches are invited to reconnect, share experiences, and contribute to college development.',
                'content'  => 'The college will host its Annual Alumni Meet on 15th December 2024 at the college auditorium from 5:00 PM. All graduates are cordially invited to attend. The event will include: Alumni achievement awards, Panel discussion on "Career Growth in the Digital Age", Networking dinner, and Launch of the Alumni Scholarship Fund. Alumni who wish to contribute to the scholarship fund or sponsor college activities are encouraged to contact the Alumni Committee.',
                'author'   => 'Admin',
                'date'     => '2024-11-20',
                'status'   => 'published',
                'featured' => false,
                'tags'     => 'alumni,networking,event,December',
            ],
        ];

        foreach ($articles as $a) {
            NewsArticle::firstOrCreate(
                ['title' => $a['title']],
                [
                    'title'          => $a['title'],
                    'slug'           => Str::slug($a['title']),
                    'excerpt'        => $a['excerpt'],
                    'content'        => $a['content'],
                    'category'       => $a['category'],
                    'published_date' => $a['date'],
                    'is_published'   => $a['status'] === 'published',
                    'is_featured'    => $a['featured'],
                ]
            );
        }
    }
}
