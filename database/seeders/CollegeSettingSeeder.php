<?php

namespace Database\Seeders;

use App\Models\CollegeSetting;
use Illuminate\Database\Seeder;

class CollegeSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // College Info
            ['key' => 'college_name',          'value' => 'Jinnah School & Degree College Astore', 'group' => 'college'],
            ['key' => 'college_name_urdu',      'value' => 'جناح اسکول اینڈ ڈگری کالج آستور',       'group' => 'college'],
            ['key' => 'college_short_name',     'value' => 'JDCA',                                  'group' => 'college'],
            ['key' => 'college_address',        'value' => 'Distt.Astore Village Eidgah Near Ali Murtaza Chowk Astore', 'group' => 'college'],
            ['key' => 'college_city',           'value' => 'Astore, Gilgit Baltistan 14300',        'group' => 'college'],
            ['key' => 'college_phone',          'value' => '+923129776585',                         'group' => 'college'],
            ['key' => 'college_email',          'value' => 'jinnahschooldegreecollege@gmail.com',   'group' => 'college'],
            ['key' => 'college_website',        'value' => 'https://JDCA.edu.pk',                   'group' => 'college'],
            ['key' => 'college_principal',      'value' => 'Arif Ali',                              'group' => 'college'],
            ['key' => 'college_established',    'value' => '2000',                                  'group' => 'college'],
            ['key' => 'college_affiliation',    'value' => 'Karakoram International University',    'group' => 'college'],
            ['key' => 'college_accreditation',  'value' => 'HEC Recognized',                        'group' => 'college'],
            ['key' => 'college_logo',           'value' => null,                                    'group' => 'college'],

            // Academic Settings
            ['key' => 'current_academic_year',  'value' => '2024-2025',   'group' => 'academic'],
            ['key' => 'current_semester',       'value' => 'Fall 2024',   'group' => 'academic'],
            ['key' => 'attendance_min_percent', 'value' => '75',          'group' => 'academic'],
            ['key' => 'passing_marks_percent',  'value' => '40',          'group' => 'academic'],
            ['key' => 'grading_system',         'value' => 'percentage',  'group' => 'academic'],
            ['key' => 'max_exam_marks',         'value' => '100',         'group' => 'academic'],
            ['key' => 'working_days_per_week',  'value' => '5',           'group' => 'academic'],

            // Fee Settings
            ['key' => 'fee_late_fine_per_day',  'value' => '100',           'group' => 'fee'],
            ['key' => 'fee_grace_days',         'value' => '7',             'group' => 'fee'],
            ['key' => 'fee_bank_name',          'value' => 'HBL',           'group' => 'fee'],
            ['key' => 'fee_bank_account',       'value' => '0001234567890', 'group' => 'fee'],
            ['key' => 'fee_bank_branch',        'value' => 'Main Branch, Lahore', 'group' => 'fee'],

            // Library Settings
            ['key' => 'library_issue_days_student', 'value' => '14', 'group' => 'library'],
            ['key' => 'library_issue_days_teacher', 'value' => '28', 'group' => 'library'],
            ['key' => 'library_fine_per_day',       'value' => '10', 'group' => 'library'],
            ['key' => 'library_max_books_student',  'value' => '3',  'group' => 'library'],
            ['key' => 'library_max_books_teacher',  'value' => '5',  'group' => 'library'],

            // System Settings
            ['key' => 'system_timezone',        'value' => 'Asia/Karachi',  'group' => 'system'],
            ['key' => 'system_date_format',     'value' => 'd/m/Y',         'group' => 'system'],
            ['key' => 'system_currency',        'value' => 'PKR',           'group' => 'system'],
            ['key' => 'system_language',        'value' => 'en',            'group' => 'system'],

            // Website Appearance
            ['key' => 'website_footer_about',      'value' => 'Intermediate and college programmes in Astore, Gilgit-Baltistan, aligned with national standards, student welfare, and pathways to universities across Pakistan.', 'group' => 'website'],
            ['key' => 'website_footer_copyright',  'value' => 'Jinnah School & Degree College Astore. All rights reserved.', 'group' => 'website'],
            ['key' => 'website_theme_brand',       'value' => '#6B2D39',    'group' => 'website'],
            ['key' => 'website_theme_brand_dark',  'value' => '#5A2430',    'group' => 'website'],
            ['key' => 'website_theme_gold',        'value' => '#C4973A',    'group' => 'website'],
            ['key' => 'website_theme_footer_bg',   'value' => '#1A1A1A',    'group' => 'website'],
            ['key' => 'website_theme_body_bg',     'value' => '#F8FAFC',    'group' => 'website'],
            ['key' => 'website_theme_surface',     'value' => '#F1F5F9',    'group' => 'website'],
            ['key' => 'website_font_sans',         'value' => 'open-sans',  'group' => 'website'],
            ['key' => 'website_font_display',      'value' => 'playfair-display', 'group' => 'website'],
        ];

        foreach ($settings as $s) {
            CollegeSetting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}
