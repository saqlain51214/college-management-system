<?php

namespace Database\Seeders;

use App\Models\ListItem;
use Illuminate\Database\Seeder;

class ListItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [

            // ── Province / Region ─────────────────────────────────────────────
            ['category' => 'province', 'value' => 'GB',           'label' => 'Gilgit-Baltistan',           'sort_order' => 1],
            ['category' => 'province', 'value' => 'AJK',          'label' => 'Azad Jammu & Kashmir',       'sort_order' => 2],
            ['category' => 'province', 'value' => 'KPK',          'label' => 'Khyber Pakhtunkhwa',         'sort_order' => 3],
            ['category' => 'province', 'value' => 'Punjab',       'label' => 'Punjab',                     'sort_order' => 4],
            ['category' => 'province', 'value' => 'Sindh',        'label' => 'Sindh',                      'sort_order' => 5],
            ['category' => 'province', 'value' => 'Balochistan',  'label' => 'Balochistan',                'sort_order' => 6],
            ['category' => 'province', 'value' => 'ICT',          'label' => 'Islamabad Capital Territory','sort_order' => 7],
            ['category' => 'province', 'value' => 'FATA',         'label' => 'Tribal Areas (Ex-FATA)',     'sort_order' => 8],

            // ── Student Qualification Level ───────────────────────────────────
            ['category' => 'qualification_level', 'value' => 'Matric', 'label' => 'Matric (SSC)',                  'sort_order' => 1],
            ['category' => 'qualification_level', 'value' => 'FA',     'label' => 'FA / F.Sc',                     'sort_order' => 2],
            ['category' => 'qualification_level', 'value' => 'FSc',    'label' => 'F.Sc (Pre-Medical/Engineering)', 'sort_order' => 3],
            ['category' => 'qualification_level', 'value' => 'ICS',    'label' => 'ICS (Intermediate)',             'sort_order' => 4],
            ['category' => 'qualification_level', 'value' => 'D.Com',  'label' => 'D.Com (Diploma of Commerce)',   'sort_order' => 5],
            ['category' => 'qualification_level', 'value' => 'ADE',    'label' => 'ADE (Associate Degree)',        'sort_order' => 6],
            ['category' => 'qualification_level', 'value' => 'B.Com',  'label' => 'B.Com',                         'sort_order' => 7],
            ['category' => 'qualification_level', 'value' => 'BA',     'label' => 'BA (Bachelor of Arts)',         'sort_order' => 8],
            ['category' => 'qualification_level', 'value' => 'BSc',    'label' => 'B.Sc (Bachelor of Science)',    'sort_order' => 9],
            ['category' => 'qualification_level', 'value' => 'BS',     'label' => 'BS (4 Year)',                   'sort_order' => 10],
            ['category' => 'qualification_level', 'value' => 'BEd',    'label' => 'B.Ed',                         'sort_order' => 11],
            ['category' => 'qualification_level', 'value' => 'MA',     'label' => 'MA / M.Sc (16 Years)',         'sort_order' => 12],
            ['category' => 'qualification_level', 'value' => 'MS',     'label' => 'MS / M.Phil',                  'sort_order' => 13],
            ['category' => 'qualification_level', 'value' => 'MBA',    'label' => 'MBA',                          'sort_order' => 14],
            ['category' => 'qualification_level', 'value' => 'PhD',    'label' => 'PhD',                          'sort_order' => 15],
            ['category' => 'qualification_level', 'value' => 'Other',  'label' => 'Other',                        'sort_order' => 99],

            // ── Teacher Qualification ─────────────────────────────────────────
            ['category' => 'teacher_qualification', 'value' => 'BA/BSc',   'label' => 'BA / B.Sc (16 Years)', 'sort_order' => 1],
            ['category' => 'teacher_qualification', 'value' => 'BEd',      'label' => 'B.Ed',                  'sort_order' => 2],
            ['category' => 'teacher_qualification', 'value' => 'BS',       'label' => 'BS (4 Years)',           'sort_order' => 3],
            ['category' => 'teacher_qualification', 'value' => 'MA/MSc',   'label' => 'MA / M.Sc',             'sort_order' => 4],
            ['category' => 'teacher_qualification', 'value' => 'MEd',      'label' => 'M.Ed',                  'sort_order' => 5],
            ['category' => 'teacher_qualification', 'value' => 'MS/MPhil', 'label' => 'MS / M.Phil',           'sort_order' => 6],
            ['category' => 'teacher_qualification', 'value' => 'MBA',      'label' => 'MBA',                   'sort_order' => 7],
            ['category' => 'teacher_qualification', 'value' => 'LLB',      'label' => 'LLB',                   'sort_order' => 8],
            ['category' => 'teacher_qualification', 'value' => 'MBBS',     'label' => 'MBBS',                  'sort_order' => 9],
            ['category' => 'teacher_qualification', 'value' => 'PhD',      'label' => 'PhD',                   'sort_order' => 10],
            ['category' => 'teacher_qualification', 'value' => 'Post-Doc', 'label' => 'Post-Doctorate',        'sort_order' => 11],
            ['category' => 'teacher_qualification', 'value' => 'Other',    'label' => 'Other',                 'sort_order' => 99],

            // ── Teacher Designation ───────────────────────────────────────────
            ['category' => 'teacher_designation', 'value' => 'Lecturer',             'label' => 'Lecturer',             'sort_order' => 1],
            ['category' => 'teacher_designation', 'value' => 'Senior Lecturer',      'label' => 'Senior Lecturer',      'sort_order' => 2],
            ['category' => 'teacher_designation', 'value' => 'Assistant Professor',  'label' => 'Assistant Professor',  'sort_order' => 3],
            ['category' => 'teacher_designation', 'value' => 'Associate Professor',  'label' => 'Associate Professor',  'sort_order' => 4],
            ['category' => 'teacher_designation', 'value' => 'Professor',            'label' => 'Professor',            'sort_order' => 5],
            ['category' => 'teacher_designation', 'value' => 'Head of Department',   'label' => 'Head of Department',   'sort_order' => 6],
            ['category' => 'teacher_designation', 'value' => 'Vice Principal',       'label' => 'Vice Principal',       'sort_order' => 7],
            ['category' => 'teacher_designation', 'value' => 'Principal',            'label' => 'Principal',            'sort_order' => 8],
            ['category' => 'teacher_designation', 'value' => 'Lab Instructor',       'label' => 'Lab Instructor',       'sort_order' => 9],
            ['category' => 'teacher_designation', 'value' => 'Visiting Lecturer',    'label' => 'Visiting Lecturer',    'sort_order' => 10],
            ['category' => 'teacher_designation', 'value' => 'Research Associate',   'label' => 'Research Associate',   'sort_order' => 11],

            // ── Book Category ─────────────────────────────────────────────────
            ['category' => 'book_category', 'value' => 'Computer Science', 'label' => 'Computer Science',   'sort_order' => 1],
            ['category' => 'book_category', 'value' => 'Mathematics',      'label' => 'Mathematics',         'sort_order' => 2],
            ['category' => 'book_category', 'value' => 'Physics',          'label' => 'Physics',             'sort_order' => 3],
            ['category' => 'book_category', 'value' => 'Chemistry',        'label' => 'Chemistry',           'sort_order' => 4],
            ['category' => 'book_category', 'value' => 'Biology',          'label' => 'Biology',             'sort_order' => 5],
            ['category' => 'book_category', 'value' => 'English',          'label' => 'English Literature',  'sort_order' => 6],
            ['category' => 'book_category', 'value' => 'Education',        'label' => 'Education',           'sort_order' => 7],
            ['category' => 'book_category', 'value' => 'Social Sciences',  'label' => 'Social Sciences',     'sort_order' => 8],
            ['category' => 'book_category', 'value' => 'Islamic Studies',  'label' => 'Islamic Studies',     'sort_order' => 9],
            ['category' => 'book_category', 'value' => 'Urdu',             'label' => 'Urdu Literature',     'sort_order' => 10],
            ['category' => 'book_category', 'value' => 'Reference',        'label' => 'Reference',           'sort_order' => 11],
            ['category' => 'book_category', 'value' => 'Magazine',         'label' => 'Magazine / Journal',  'sort_order' => 12],
            ['category' => 'book_category', 'value' => 'Other',            'label' => 'Other',               'sort_order' => 99],

            // ── Book Language ─────────────────────────────────────────────────
            ['category' => 'book_language', 'value' => 'English', 'label' => 'English', 'sort_order' => 1],
            ['category' => 'book_language', 'value' => 'Urdu',    'label' => 'Urdu',    'sort_order' => 2],
            ['category' => 'book_language', 'value' => 'Arabic',  'label' => 'Arabic',  'sort_order' => 3],
            ['category' => 'book_language', 'value' => 'Pashto',  'label' => 'Pashto',  'sort_order' => 4],
            ['category' => 'book_language', 'value' => 'Persian', 'label' => 'Persian', 'sort_order' => 5],
            ['category' => 'book_language', 'value' => 'Other',   'label' => 'Other',   'sort_order' => 99],

            // ── Book Condition ────────────────────────────────────────────────
            ['category' => 'book_condition', 'value' => 'good',    'label' => 'Good',    'sort_order' => 1],
            ['category' => 'book_condition', 'value' => 'fair',    'label' => 'Fair',    'sort_order' => 2],
            ['category' => 'book_condition', 'value' => 'poor',    'label' => 'Poor',    'sort_order' => 3],
            ['category' => 'book_condition', 'value' => 'damaged', 'label' => 'Damaged', 'sort_order' => 4],
            ['category' => 'book_condition', 'value' => 'lost',    'label' => 'Lost',    'sort_order' => 5],

            // ── Fee Frequency ─────────────────────────────────────────────────
            ['category' => 'fee_frequency', 'value' => 'one_time',  'label' => 'One Time',     'sort_order' => 1],
            ['category' => 'fee_frequency', 'value' => 'semester',  'label' => 'Per Semester', 'sort_order' => 2],
            ['category' => 'fee_frequency', 'value' => 'annual',    'label' => 'Annual',       'sort_order' => 3],
            ['category' => 'fee_frequency', 'value' => 'monthly',   'label' => 'Monthly',      'sort_order' => 4],

            // ── LMS Material Type ─────────────────────────────────────────────
            ['category' => 'lms_material_type', 'value' => 'document', 'label' => 'Document (PDF/Word)', 'sort_order' => 1],
            ['category' => 'lms_material_type', 'value' => 'slides',   'label' => 'Lecture Slides',       'sort_order' => 2],
            ['category' => 'lms_material_type', 'value' => 'video',    'label' => 'Video Link',            'sort_order' => 3],
            ['category' => 'lms_material_type', 'value' => 'link',     'label' => 'External Link',         'sort_order' => 4],
            ['category' => 'lms_material_type', 'value' => 'audio',    'label' => 'Audio',                 'sort_order' => 5],
            ['category' => 'lms_material_type', 'value' => 'image',    'label' => 'Image',                 'sort_order' => 6],
            ['category' => 'lms_material_type', 'value' => 'other',    'label' => 'Other',                 'sort_order' => 99],

            // ── Assignment Submission Type ────────────────────────────────────
            ['category' => 'lms_submission_type', 'value' => 'file', 'label' => 'File Upload',      'sort_order' => 1],
            ['category' => 'lms_submission_type', 'value' => 'text', 'label' => 'Text Submission',  'sort_order' => 2],
            ['category' => 'lms_submission_type', 'value' => 'url',  'label' => 'Link / URL',        'sort_order' => 3],
            ['category' => 'lms_submission_type', 'value' => 'any',  'label' => 'Any',               'sort_order' => 4],

            // ── News Category ─────────────────────────────────────────────────
            ['category' => 'news_category', 'value' => 'news',        'label' => 'College News', 'sort_order' => 1],
            ['category' => 'news_category', 'value' => 'event',       'label' => 'Event',        'sort_order' => 2],
            ['category' => 'news_category', 'value' => 'achievement', 'label' => 'Achievement',  'sort_order' => 3],
            ['category' => 'news_category', 'value' => 'notice',      'label' => 'Notice',       'sort_order' => 4],
            ['category' => 'news_category', 'value' => 'circular',    'label' => 'Circular',     'sort_order' => 5],
            ['category' => 'news_category', 'value' => 'other',       'label' => 'Other',        'sort_order' => 99],

            // ── Priority Level ────────────────────────────────────────────────
            ['category' => 'priority_level', 'value' => 'low',    'label' => 'Low',    'sort_order' => 1],
            ['category' => 'priority_level', 'value' => 'normal', 'label' => 'Normal', 'sort_order' => 2],
            ['category' => 'priority_level', 'value' => 'high',   'label' => 'High',   'sort_order' => 3],
            ['category' => 'priority_level', 'value' => 'urgent', 'label' => 'Urgent', 'sort_order' => 4],
        ];

        foreach ($items as $item) {
            ListItem::updateOrCreate(
                ['category' => $item['category'], 'value' => $item['value']],
                $item
            );
        }
    }
}
