<?php

namespace Database\Seeders;

use App\Models\WebsitePage;
use Illuminate\Database\Seeder;

class WebsitePageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['title' => 'Home', 'slug' => 'home', 'sort' => 1, 'menu' => true,
             'content' => '<h2>Welcome to Government Degree College</h2><p>Established with a vision to provide quality higher education in the region, our college has been a center of academic excellence for decades. We offer programs in Computer Science, Education, English, and more. Join us and become part of a community committed to knowledge, character, and service.</p>'],

            ['title' => 'About Us', 'slug' => 'about-us', 'sort' => 2, 'menu' => true,
             'content' => '<h2>About Our College</h2><p>Government Degree College is a premier educational institution dedicated to the holistic development of students. Our mission is to provide affordable, quality education while fostering critical thinking, creativity, and moral values.</p><h3>Our Vision</h3><p>To be recognized as a center of excellence in higher education in Punjab.</p><h3>Our Mission</h3><p>To nurture intellectually capable, morally upright, and professionally skilled graduates who contribute positively to society.</p>'],

            ['title' => 'Academics', 'slug' => 'academics', 'sort' => 3, 'menu' => true,
             'content' => '<h2>Academic Programs</h2><p>We offer the following degree programs under the University of Punjab affiliation:</p><ul><li><strong>BS Computer Science</strong> — 4-year degree (8 semesters)</li><li><strong>Bachelor of Education (B.Ed)</strong> — 1.5-year and 4-year programs</li><li><strong>BS English Literature</strong> — 4-year degree</li><li><strong>BS Islamic Studies</strong> — 4-year degree</li></ul><h3>Faculty</h3><p>Our faculty includes PhDs, MS/MPhil degree holders with extensive industry and research experience.</p>'],

            ['title' => 'Admissions', 'slug' => 'admissions', 'sort' => 4, 'menu' => true,
             'content' => '<h2>Admissions 2024-25</h2><p>Admissions are merit-based for all programs. We welcome students with a passion for learning and a commitment to excellence.</p><h3>Eligibility</h3><ul><li><strong>BS-CS:</strong> FSc (Pre-Engineering/Pre-Medical/ICS) with minimum 60% marks</li><li><strong>B.Ed (1.5-yr):</strong> BA/BSc with minimum 45% marks</li><li><strong>B.Ed (4-yr):</strong> FSc/FA/ICS with minimum 50% marks</li></ul><h3>Required Documents</h3><ul><li>Matric Certificate (original + attested copies)</li><li>FSc/FA/ICS Certificate (original + attested copies)</li><li>CNIC/Form-B Copy</li><li>Domicile Certificate</li><li>4 Passport Size Photographs</li></ul><h3>Scholarships Available</h3><p>HEC Need-Based, Merit, PEEF, and Special Persons scholarships are available for eligible students.</p>'],

            ['title' => 'Departments', 'slug' => 'departments', 'sort' => 5, 'menu' => true,
             'content' => '<h2>Our Departments</h2><h3>Department of Computer Science</h3><p>The DCS offers a comprehensive BS Computer Science program covering programming, databases, AI, networking, and software engineering. Equipped with 3 modern computer labs with 150 workstations.</p><h3>Department of Education</h3><p>The Department of Education offers B.Ed programs with focus on modern pedagogical methods, educational psychology, and teaching practice in partner schools.</p><h3>Department of English</h3><p>Offering BS English Literature with emphasis on literary theory, linguistics, creative writing, and communication skills.</p><h3>Department of Islamic Studies</h3><p>Provides both as a standalone program and as a core course in all degree programs, covering Quran, Hadith, Islamic law, and history.</p>'],

            ['title' => 'Library', 'slug' => 'library', 'sort' => 6, 'menu' => false,
             'content' => '<h2>College Library</h2><p>The college library houses over 5,000 books, journals, and digital resources. Open from Monday to Saturday, 8:00 AM to 6:00 PM.</p><h3>Services</h3><ul><li>Book Issue: 14 days for students, 28 days for faculty</li><li>Reference Section: In-library use only</li><li>Digital Resources: Access to HEC Digital Library</li><li>Photocopying and printing services</li></ul><h3>Rules</h3><ul><li>Maintain silence in the library</li><li>Return books on time to avoid fines</li><li>Bring your college ID card for issuing books</li></ul>'],

            ['title' => 'Fee Structure', 'slug' => 'fee-structure', 'sort' => 7, 'menu' => false,
             'content' => '<h2>Fee Structure 2024-25</h2><table><tr><th>Program</th><th>Tuition Fee (Per Semester)</th><th>Admission Fee (One Time)</th></tr><tr><td>BS Computer Science</td><td>Rs. 25,000</td><td>Rs. 15,000</td></tr><tr><td>B.Ed (1.5-year)</td><td>Rs. 20,000</td><td>Rs. 10,000</td></tr></table><p>Additional fees: Library Rs. 1,000/year | Sports Rs. 500/year | Examination Rs. 3,000/semester</p><p><em>Note: Fee concessions available for eligible students. Contact Accounts Office for details.</em></p>'],

            ['title' => 'Contact Us', 'slug' => 'contact-us', 'sort' => 8, 'menu' => true,
             'content' => '<h2>Contact Information</h2><p><strong>Address:</strong> Main Campus Road, Lahore, Punjab, Pakistan</p><p><strong>Phone:</strong> +92-42-3550-0000</p><p><strong>Email:</strong> info@gdcollege.edu.pk</p><p><strong>Principal Office:</strong> principal@gdcollege.edu.pk</p><p><strong>Admissions:</strong> admissions@gdcollege.edu.pk</p><h3>Office Hours</h3><p>Monday – Friday: 8:00 AM – 4:00 PM<br>Saturday: 9:00 AM – 1:00 PM</p><h3>Location</h3><p>Located 2 km from main city center. Accessible by public transport Routes 5, 12, and 18.</p>'],
        ];

        foreach ($pages as $p) {
            WebsitePage::firstOrCreate(
                ['slug' => $p['slug']],
                [
                    'title'        => $p['title'],
                    'content'      => $p['content'],
                    'sort_order'   => $p['sort'],
                    'in_menu'      => $p['menu'],
                    'is_published' => true,
                ]
            );
        }
    }
}
