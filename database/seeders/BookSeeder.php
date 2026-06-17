<?php

namespace Database\Seeders;

use App\Enums\BookStatusEnum;
use App\Models\Book;
use App\Models\Department;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $deptCS  = Department::where('slug', 'department-of-computer-science')->value('id');
        $deptEdu = Department::where('slug', 'department-of-education')->value('id');
        $deptEng = Department::where('slug', 'department-of-english')->value('id');
        $deptMath= Department::where('code', 'DEPT-PHY')->value('id');

        $avail   = BookStatusEnum::Available->value;
        $issued  = BookStatusEnum::Issued->value;
        $ref     = BookStatusEnum::Reserved->value;

        $books = [
            // ─── Computer Science ──────────────────────────────────────────────────
            ['acc' => 'LIB-2024-001', 'title' => 'Introduction to Computing',                      'author' => 'Peter Norton',                'pub' => 'McGraw-Hill',    'isbn' => '978-0-07-338420-3', 'yr' => 2019, 'ed' => '8th', 'cat' => 'Computer Science', 'dept' => $deptCS,  'pages' => 680, 'copies' => 5, 'price' => 3500, 'status' => $avail],
            ['acc' => 'LIB-2024-002', 'title' => 'C++ Programming: From Problem Analysis to Program Design', 'author' => 'D.S. Malik',     'pub' => 'Cengage Learning','isbn' => '978-1-305-26365-6', 'yr' => 2018, 'ed' => '8th', 'cat' => 'Computer Science', 'dept' => $deptCS,  'pages' => 1200, 'copies' => 4, 'price' => 5500, 'status' => $avail],
            ['acc' => 'LIB-2024-003', 'title' => 'Data Structures and Algorithms Made Easy',       'author' => 'Narasimha Karumanchi',        'pub' => 'CareerMonk',     'isbn' => '978-8-19-262784-5', 'yr' => 2020, 'ed' => '5th', 'cat' => 'Computer Science', 'dept' => $deptCS,  'pages' => 450, 'copies' => 3, 'price' => 2800, 'status' => $issued],
            ['acc' => 'LIB-2024-004', 'title' => 'Database System Concepts',                       'author' => 'Silberschatz, Korth, Sudarshan','pub' => 'McGraw-Hill',  'isbn' => '978-0-07-802215-9', 'yr' => 2019, 'ed' => '7th', 'cat' => 'Computer Science', 'dept' => $deptCS,  'pages' => 1376, 'copies' => 4, 'price' => 7500, 'status' => $avail],
            ['acc' => 'LIB-2024-005', 'title' => 'Artificial Intelligence: A Modern Approach',    'author' => 'Russell & Norvig',            'pub' => 'Pearson',        'isbn' => '978-0-13-461099-3', 'yr' => 2020, 'ed' => '4th', 'cat' => 'Computer Science', 'dept' => $deptCS,  'pages' => 1132, 'copies' => 2, 'price' => 9500, 'status' => $issued],
            ['acc' => 'LIB-2024-006', 'title' => 'Software Engineering',                          'author' => 'Ian Sommerville',             'pub' => 'Pearson',        'isbn' => '978-0-13-394303-0', 'yr' => 2016, 'ed' => '10th','cat' => 'Computer Science', 'dept' => $deptCS,  'pages' => 810, 'copies' => 3, 'price' => 6500, 'status' => $issued],
            ['acc' => 'LIB-2024-007', 'title' => 'Computer Networks',                             'author' => 'Andrew Tanenbaum',            'pub' => 'Pearson',        'isbn' => '978-0-13-212695-3', 'yr' => 2011, 'ed' => '5th', 'cat' => 'Computer Science', 'dept' => $deptCS,  'pages' => 960, 'copies' => 3, 'price' => 7200, 'status' => $issued],
            ['acc' => 'LIB-2024-008', 'title' => 'Object Oriented Programming in C++',            'author' => 'Robert Lafore',               'pub' => 'SAMS Publishing', 'isbn' => '978-0-67-232illicit', 'yr' => 2002, 'ed' => '4th', 'cat' => 'Computer Science', 'dept' => $deptCS, 'pages' => 1000, 'copies' => 4, 'price' => 4200, 'status' => $avail],
            ['acc' => 'LIB-2024-009', 'title' => 'Web Technologies: HTML, JavaScript, PHP, Java, JSP, XML, AJAX', 'author' => 'Uttam Kumar Roy', 'pub' => 'Oxford University Press', 'isbn' => '978-0-19-808756-6', 'yr' => 2012, 'ed' => '1st', 'cat' => 'Computer Science', 'dept' => $deptCS, 'pages' => 600, 'copies' => 3, 'price' => 3800, 'status' => $avail],
            ['acc' => 'LIB-2024-010', 'title' => 'Oxford Dictionary of Computing',                'author' => 'John Daintith',               'pub' => 'Oxford University Press','isbn' => '978-0-19-923400-4', 'yr' => 2019, 'ed' => '6th', 'cat' => 'Reference',       'dept' => $deptCS,  'pages' => 560, 'copies' => 2, 'price' => 2500, 'status' => $avail, 'ref_only' => true],
            ['acc' => 'LIB-2024-011', 'title' => 'Discrete Mathematics and Its Applications',    'author' => 'Kenneth H. Rosen',            'pub' => 'McGraw-Hill',    'isbn' => '978-0-07-338309-1', 'yr' => 2019, 'ed' => '8th', 'cat' => 'Mathematics',      'dept' => $deptMath,'pages' => 1072, 'copies' => 4, 'price' => 6800, 'status' => $avail],
            ['acc' => 'LIB-2024-012', 'title' => 'Introduction to Algorithms',                    'author' => 'Cormen, Leiserson, Rivest, Stein','pub' => 'MIT Press',   'isbn' => '978-0-26-204630-5', 'yr' => 2022, 'ed' => '4th', 'cat' => 'Computer Science', 'dept' => $deptCS,  'pages' => 1312, 'copies' => 2, 'price' => 8500, 'status' => $avail],
            ['acc' => 'LIB-2024-013', 'title' => 'Operating System Concepts',                     'author' => 'Silberschatz, Galvin, Gagne', 'pub' => 'Wiley',          'isbn' => '978-1-11-906333-0', 'yr' => 2018, 'ed' => '10th','cat' => 'Computer Science', 'dept' => $deptCS,  'pages' => 1040, 'copies' => 3, 'price' => 7000, 'status' => $avail],
            ['acc' => 'LIB-2024-014', 'title' => 'Computer Organization and Architecture',       'author' => 'William Stallings',           'pub' => 'Pearson',        'isbn' => '978-0-13-439555-0', 'yr' => 2019, 'ed' => '11th','cat' => 'Computer Science', 'dept' => $deptCS,  'pages' => 800, 'copies' => 2, 'price' => 6500, 'status' => $avail],
            ['acc' => 'LIB-2024-015', 'title' => 'Python Crash Course',                           'author' => 'Eric Matthes',                'pub' => 'No Starch Press','isbn' => '978-1-59-327928-0', 'yr' => 2019, 'ed' => '2nd', 'cat' => 'Computer Science', 'dept' => $deptCS,  'pages' => 560, 'copies' => 5, 'price' => 4200, 'status' => $avail],

            // ─── Education ─────────────────────────────────────────────────────────
            ['acc' => 'LIB-2024-016', 'title' => 'Foundations of Education',                      'author' => 'Allan Ornstein & Daniel Levine','pub' => 'Cengage Learning','isbn' => '978-1-28-573532-0', 'yr' => 2016, 'ed' => '13th','cat' => 'Education',       'dept' => $deptEdu, 'pages' => 576, 'copies' => 4, 'price' => 4500, 'status' => $avail],
            ['acc' => 'LIB-2024-017', 'title' => 'Educational Psychology: Theory and Practice',   'author' => 'Robert Slavin',               'pub' => 'Pearson',        'isbn' => '978-0-13-369673-4', 'yr' => 2018, 'ed' => '12th','cat' => 'Education',       'dept' => $deptEdu, 'pages' => 592, 'copies' => 4, 'price' => 5200, 'status' => $issued],
            ['acc' => 'LIB-2024-018', 'title' => 'Curriculum Development: Theory into Practice',  'author' => 'Daniel Tanner & Laurel Tanner','pub' => 'Pearson',       'isbn' => '978-0-13-239321-3', 'yr' => 2007, 'ed' => '4th', 'cat' => 'Education',       'dept' => $deptEdu, 'pages' => 528, 'copies' => 3, 'price' => 4800, 'status' => $avail],
            ['acc' => 'LIB-2024-019', 'title' => 'Classroom Management: Models, Applications and Cases', 'author' => 'M. Lee Manning & Katherine Bucher', 'pub' => 'Pearson', 'isbn' => '978-0-13-714451-0', 'yr' => 2013, 'ed' => '3rd', 'cat' => 'Education', 'dept' => $deptEdu, 'pages' => 384, 'copies' => 3, 'price' => 3900, 'status' => $avail],
            ['acc' => 'LIB-2024-020', 'title' => 'Teaching and Learning in Higher Education',     'author' => 'Linda Nilson',                'pub' => 'Stylus Publishing','isbn' => '978-1-62-036226-9', 'yr' => 2016, 'ed' => '4th', 'cat' => 'Education',       'dept' => $deptEdu, 'pages' => 448, 'copies' => 2, 'price' => 4200, 'status' => $avail],

            // ─── English ───────────────────────────────────────────────────────────
            ['acc' => 'LIB-2024-021', 'title' => 'English Grammar in Use',                        'author' => 'Raymond Murphy',              'pub' => 'Cambridge University Press','isbn' => '978-1-10-753104-2','yr' => 2019, 'ed' => '5th', 'cat' => 'English',         'dept' => $deptEng, 'pages' => 390, 'copies' => 6, 'price' => 2800, 'status' => $avail],
            ['acc' => 'LIB-2024-022', 'title' => 'The Elements of Style',                         'author' => 'Strunk & White',              'pub' => 'Pearson',        'isbn' => '978-0-20-530902-3', 'yr' => 1999, 'ed' => '4th', 'cat' => 'English',         'dept' => $deptEng, 'pages' => 105, 'copies' => 5, 'price' => 1500, 'status' => $avail],
            ['acc' => 'LIB-2024-023', 'title' => 'Academic Writing for Graduate Students',        'author' => 'Swales & Feak',               'pub' => 'University of Michigan Press','isbn' => '978-0-47-203000-9','yr' => 2012, 'ed' => '3rd', 'cat' => 'English', 'dept' => $deptEng, 'pages' => 368, 'copies' => 3, 'price' => 3200, 'status' => $avail],
            ['acc' => 'LIB-2024-024', 'title' => 'A History of the English Language',             'author' => 'Baugh & Cable',               'pub' => 'Routledge',      'isbn' => '978-0-41-535838-2', 'yr' => 2012, 'ed' => '6th', 'cat' => 'English',         'dept' => $deptEng, 'pages' => 448, 'copies' => 2, 'price' => 5500, 'status' => $avail],

            // ─── Mathematics / General ─────────────────────────────────────────────
            ['acc' => 'LIB-2024-025', 'title' => 'Calculus: Early Transcendentals',               'author' => 'James Stewart',               'pub' => 'Cengage Learning','isbn' => '978-1-28-557099-2', 'yr' => 2016, 'ed' => '8th', 'cat' => 'Mathematics',      'dept' => $deptMath,'pages' => 1368, 'copies' => 4, 'price' => 7200, 'status' => $avail],
        ];

        foreach ($books as $b) {
            Book::firstOrCreate(
                ['accession_number' => $b['acc']],
                [
                    'accession_number' => $b['acc'],
                    'title'            => $b['title'],
                    'author'           => $b['author'],
                    'publisher'        => $b['pub'],
                    'isbn'             => $b['isbn'],
                    'publication_year' => $b['yr'],
                    'edition'          => $b['ed'],
                    'category'         => $b['cat'],
                    'department_id'    => $b['dept'],
                    'total_copies'     => $b['copies'],
                    'available_copies' => $b['status'] === $avail ? $b['copies'] : max(0, $b['copies'] - 1),
                    'price'            => $b['price'],
                    'status'           => $b['status'],
                    'is_reference_only'=> $b['ref_only'] ?? false,
                    'is_active'        => true,
                ]
            );
        }
    }
}
