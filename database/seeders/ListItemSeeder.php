<?php

namespace Database\Seeders;

use App\Models\ListItem;
use Illuminate\Database\Seeder;

class ListItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [];

        $groups = [
            'province' => [
                'gilgit_baltistan' => 'Gilgit-Baltistan',
                'ajk' => 'Azad Jammu & Kashmir',
                'khyber_pakhtunkhwa' => 'Khyber Pakhtunkhwa',
                'punjab' => 'Punjab',
                'sindh' => 'Sindh',
                'balochistan' => 'Balochistan',
                'islamabad_capital_territory' => 'Islamabad Capital Territory',
            ],
            'qualification_level' => [
                'matric_science' => 'Matric (Science)',
                'matric_arts' => 'Matric (Arts)',
                'fa' => 'FA',
                'fsc_pre_medical' => 'FSc Pre-Medical',
                'fsc_pre_engineering' => 'FSc Pre-Engineering',
                'ics' => 'ICS',
                'icom' => 'I.Com',
                'dae' => 'DAE',
                'ba' => 'BA',
                'bsc' => 'BSc',
                'bs' => 'BS / Bachelor (4 Years)',
                'bed' => 'B.Ed',
                'ma_msc' => 'MA / MSc',
                'ms_mphil' => 'MS / M.Phil',
                'phd' => 'PhD',
                'other' => 'Other',
            ],
            'teacher_qualification' => [
                'bs' => 'BS (4 Years)',
                'ma_msc' => 'MA / MSc',
                'bed' => 'B.Ed',
                'med' => 'M.Ed',
                'ms_mphil' => 'MS / M.Phil',
                'phd' => 'PhD',
                'other' => 'Other',
            ],
            'teacher_designation' => [
                'lecturer' => 'Lecturer',
                'senior_lecturer' => 'Senior Lecturer',
                'assistant_professor' => 'Assistant Professor',
                'associate_professor' => 'Associate Professor',
                'professor' => 'Professor',
                'head_of_department' => 'Head of Department',
                'principal' => 'Principal',
                'visiting_lecturer' => 'Visiting Lecturer',
            ],
            'student_group' => [
                'pre_medical' => 'Pre-Medical',
                'pre_engineering' => 'Pre-Engineering',
                'computer_science' => 'Computer Science',
                'arts_humanities' => 'Arts / Humanities',
                'commerce' => 'Commerce',
            ],
            'education_board' => [
                'bise_gilgit' => 'BISE Gilgit',
                'bise_lahore' => 'BISE Lahore',
                'bise_rawalpindi' => 'BISE Rawalpindi',
                'bise_karachi' => 'BISE Karachi',
                'bise_peshawar' => 'BISE Peshawar',
                'bise_quetta' => 'BISE Quetta',
                'federal_board' => 'FBISE Islamabad',
                'akueb' => 'Aga Khan University Examination Board',
            ],
            'campus_location' => [
                'main_campus' => 'Main Campus',
                'city_campus' => 'City Campus',
                'girls_campus' => 'Girls Campus',
            ],
            'book_category' => [
                'computer_science' => 'Computer Science',
                'mathematics' => 'Mathematics',
                'physics' => 'Physics',
                'chemistry' => 'Chemistry',
                'biology' => 'Biology',
                'english' => 'English',
                'education' => 'Education',
                'islamic_studies' => 'Islamic Studies',
                'reference' => 'Reference',
                'other' => 'Other',
            ],
            'book_language' => [
                'english' => 'English',
                'urdu' => 'Urdu',
                'arabic' => 'Arabic',
                'other' => 'Other',
            ],
            'book_condition' => [
                'good' => 'Good',
                'fair' => 'Fair',
                'poor' => 'Poor',
                'damaged' => 'Damaged',
                'lost' => 'Lost',
            ],
            'fee_frequency' => [
                'one_time' => 'One Time',
                'monthly' => 'Monthly',
                'quarterly' => 'Quarterly',
                'semester' => 'Per Semester',
                'annual' => 'Annual',
            ],
            'lms_material_type' => [
                'document' => 'Document (PDF / Word)',
                'slides' => 'Lecture Slides',
                'video' => 'Video Link',
                'link' => 'External Link',
                'audio' => 'Audio',
                'image' => 'Image',
                'other' => 'Other',
            ],
            'lms_submission_type' => [
                'file' => 'File Upload',
                'text' => 'Text Submission',
                'url' => 'Link / URL',
                'any' => 'Any',
            ],
            'news_category' => [
                'news' => 'College News',
                'event' => 'Event',
                'achievement' => 'Achievement',
                'notice' => 'Notice',
                'circular' => 'Circular',
                'other' => 'Other',
            ],
            'priority_level' => [
                'low' => 'Low',
                'normal' => 'Normal',
                'high' => 'High',
                'urgent' => 'Urgent',
            ],
        ];

        foreach ($groups as $category => $options) {
            $sortOrder = 1;

            foreach ($options as $value => $label) {
                $items[] = [
                    'category' => $category,
                    'value' => $value,
                    'label' => $label,
                    'sort_order' => $sortOrder++,
                ];
            }
        }

        foreach ($items as $item) {
            ListItem::updateOrCreate(
                ['category' => $item['category'], 'value' => $item['value']],
                $item + ['is_active' => true]
            );
        }
    }
}
