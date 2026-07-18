<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;

class DownloadImportTemplateAction extends Action
{
    /**
     * Real student data grouped by programme (department-wise). Each group's
     * students share the same DEG. PROGRAM / SESSION / SEMESTER. Add more
     * groups here as new department lists arrive.
     *
     * Student rows are [Name, Father Name, Registration No].
     *
     * @return array<int,array<string,mixed>>
     */
    public static function studentGroups(): array
    {
        return [
            [
                // Department of Education → resolves to this programme + its department on import
                'program'  => 'Associate Degree in Education',
                'session'  => '2024',
                'semester' => '1',
                'students' => [
                    ['Shafeeta Begum', 'Mustaq Hussain', '2024-KIU-ADP-3048'],
                    ['Sheraz Ali', 'Sadiq Ali', '2024-KIU-ADP-3049'],
                    ['Muddasira Raziq', 'Abdul Raziq', '2024-KIU-ADP-3050'],
                    ['Nimra Munir', 'Munir Ahmad', '2024-KIU-ADP-3051'],
                    ['Eshal Arifi', 'Abdul Qayyum', '2024-KIU-ADP-3052'],
                    ['Shahzadi', 'Muhammad Ibrahim', '2024-KIU-ADP-3053'],
                    ['Usman Khan', 'Nasir Shah', '2024-KIU-ADP-3054'],
                    ['Imtiaz Ahmad', 'Talib Khan', '2024-KIU-ADP-3055'],
                    ['Humaira Irshad', 'Irshad Ali', '2024-KIU-ADP-3056'],
                    ['Bushra Qayoom', 'Abdul Qayoom', '2024-KIU-ADP-3057'],
                    ['Laiba Begum', 'Fazal Din', '2024-KIU-ADP-3058'],
                    ['Saiqa Afiyat', 'Afiyat Khan', '2024-KIU-ADP-3059'],
                    ['Raza ur Rehman', 'Nimat Khan', '2024-KIU-ADP-3060'],
                    ['Asiya Begum', 'Juma Ali', '2024-KIU-ADP-3061'],
                    ['Kazim Ali', 'Farid Khan', '2024-KIU-ADP-3062'],
                    ['Hassan Kamran', 'Muhammad Ibrahim', '2024-KIU-ADP-3063'],
                    ['Bushra Jamsheed', 'Jamsheed Khan', '2024-KIU-ADP-3064'],
                    ['Adnan Zia', 'Zia Ul Haq', '2024-KIU-ADP-3065'],
                    ['Saba Shaheen', 'Muhammad Saleem', '2024-KIU-ADP-3066'],
                    ['Tamseel Fatima', 'Amir Haider', '2024-KIU-ADP-3067'],
                    ['Sajid Wali', 'Muhammad Wali', '2024-KIU-ADP-3068'],
                    ['Muneera Begum', 'Ghulam Muhammad', '2024-KIU-ADP-3069'],
                    ['Rubi Zehra', 'Zakir Hussain', '2024-KIU-ADP-3070'],
                    ['Ayesha', 'Shakoor Khan', '2024-KIU-ADP-3071'],
                    ['Huma Kabir', 'Kabir Baig', '2024-KIU-ADP-3072'],
                    ['Aqsa Latif', 'Abdul Latif', '2024-KIU-ADP-3073'],
                    ['Salma', 'Abdul Hakeem', '2024-KIU-ADP-3074'],
                    ['Aziza Shah', 'Qalandar Shah', '2024-KIU-ADP-3075'],
                    ['Rukhsana', 'Muhammad Mussa', '2024-KIU-ADP-3076'],
                    ['Mehreen Zehra', 'Ghulam Mustafa', '2024-KIU-ADP-3077'],
                    ['Laiba Kiran', 'Abdul Shakoor', '2024-KIU-ADP-3078'],
                    ['Ishrat Bibi', 'Mohamad Essa', '2024-KIU-ADP-3079'],
                    ['Nosheen Altaf', 'Altaf Hussain', '2024-KIU-ADP-3080'],
                    ['Ibadat Ali', 'Mastan Ali', '2024-KIU-ADP-3081'],
                    ['Laiba', 'Sherbaz', '2024-KIU-ADP-3082'],
                    ['Maqaddas Ali', 'Ali Muhammad', '2024-KIU-ADP-3083'],
                    ['Saima', 'Muhammad Essa', '2024-KIU-ADP-3084'],
                    ['Urooj Zehra', 'Ghulam Ali', '2024-KIU-ADP-3085'],
                    ['Nelum Batool', 'Noor Khan', '2024-KIU-ADP-3086'],
                    ['Azra Begum', 'Muhammad Raza', '2024-KIU-ADP-3087'],
                    ['Iqra Azmat', 'Azmat Khan', '2024-KIU-ADP-3088'],
                    ['Saima Raziq', 'Abdul Raziq', '2024-KIU-ADP-3089'],
                    ['Katrina', 'Ibrahim', '2024-KIU-ADP-3090'],
                    ['Momeda', 'Noor Shah', '2024-KIU-ADP-3091'],
                    ['Wasila Batool', 'Juma Khan', '2024-KIU-ADP-3092'],
                    ['Yasmeen Akhter', 'Juma Khan', '2024-KIU-ADP-3093'],
                    ['Ghulam Abbas', 'Ghulam Muhammad', '2024-KIU-ADP-3094'],
                    ['Saqib Zaman', 'Shah Zaman', '2024-KIU-ADP-3095'],
                    ['Zulfiqar Ali', 'Baker Ali', '2024-KIU-ADP-3096'],
                    ['Mudasir Ali', 'Akber Hussain', '2024-KIU-ADP-3097'],
                    ['Muneeba Mannan', 'Abdul Mannan', '2024-KIU-ADP-3098'],
                    ['Rafida', 'Sheikh Fareed', '2024-KIU-ADP-3099'],
                    ['Shaheen Begum', 'Johar', '2024-KIU-ADP-3100'],
                    ['Khuban Chaman', 'Saidan Shah', '2024-KIU-ADP-3101'],
                    ['Dil Ruba', 'Azim Khan', '2024-KIU-ADP-3102'],
                    ['Sabiha Bano', 'Muhammad Hussain', '2024-KIU-ADP-3103'],
                    ['Kiran Alam', 'Jan Alam', '2024-KIU-ADP-3104'],
                    ['Gul Samaria', 'Jamal Khan', '2024-KIU-ADP-3105'],
                    ['Dina Batool', 'Ibrahim', '2024-KIU-ADP-3106'],
                    ['Kaveeta Begum', 'Khush Khan', '2024-KIU-ADP-3107'],
                ],
            ],
        ];
    }

    public static function make(?string $name = 'downloadTemplate'): static
    {
        return parent::make($name)
            ->label('Download Template')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->action(function () {
                $headers = [
                    'NAME', 'FATHER NAME', 'REGISTRATION NUMBER', 'ROLL NUMBER',
                    'DEG. PROGRAM', 'SESSION', 'GENDER', 'PHONE', 'CNIC',
                    'CITY', 'ADDRESS', 'SEMESTER', 'REMARKS',
                ];

                $output = fopen('php://temp', 'r+');
                fputcsv($output, $headers);

                foreach (static::studentGroups() as $group) {
                    foreach ($group['students'] as [$studentName, $fatherName, $registration]) {
                        fputcsv($output, [
                            $studentName,          // NAME
                            $fatherName,           // FATHER NAME
                            $registration,         // REGISTRATION NUMBER
                            '',                    // ROLL NUMBER (auto-generated on import)
                            $group['program'],     // DEG. PROGRAM (resolves to programme + department)
                            $group['session'],     // SESSION (batch year)
                            '',                    // GENDER
                            '',                    // PHONE
                            '',                    // CNIC
                            '',                    // CITY
                            '',                    // ADDRESS
                            $group['semester'],    // SEMESTER
                            '',                    // REMARKS
                        ]);
                    }
                }

                rewind($output);
                $csv = stream_get_contents($output);
                fclose($output);

                return response()->streamDownload(
                    fn() => print($csv),
                    'students-import-template.csv',
                    ['Content-Type' => 'text/csv']
                );
            });
    }
}
