<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;

class DownloadTeacherTemplateAction extends Action
{
    public static function make(?string $name = 'downloadTemplate'): static
    {
        return parent::make($name)
            ->label('Download Template')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->action(function () {
                $headers = [
                    'NAME',
                    'FATHER NAME',
                    'EMPLOYEE ID',
                    'CNIC',
                    'GENDER',
                    'PHONE',
                    'EMAIL',
                    'DEPARTMENT',
                    'DESIGNATION',
                    'QUALIFICATION',
                    'SPECIALIZATION',
                    'EMPLOYMENT TYPE',
                    'JOINING DATE',
                    'CITY',
                    'ADDRESS',
                    'REMARKS',
                ];

                // Columns: NAME, FATHER NAME, EMPLOYEE ID, CNIC, GENDER, PHONE, EMAIL,
                // DEPARTMENT, DESIGNATION, QUALIFICATION, SPECIALIZATION, EMPLOYMENT TYPE,
                // JOINING DATE, CITY, ADDRESS, REMARKS
                $examples = [
                    ['Ibrar Hussain', '', 'JDCA-T-001', '', 'male', '03215022568', 'Ibrar.astori@gmil.com', 'Education', 'Lecturer', 'M.Phil', 'Education', 'permanent', '', '', '', 'Final degree 2024; 9 yrs exp'],
                    ['Aurangzeb Ali', '', 'JDCA-T-002', '', 'male', '03555789646', 'Aliorangzaib64@gmail.com', 'Education', 'Lecturer', 'M.Phil', 'Education', 'permanent', '', '', '', 'Final degree 2025; 5 yrs exp'],
                    ['Ikram Hussain', '', 'JDCA-T-003', '', 'male', '03555009916', 'Ikramalisakhi121@gmail.com', 'Education', 'Lecturer', 'M.Phil', 'Education', 'permanent', '', '', '', 'Final degree 2025; 4 yrs exp'],
                    ['Mujeeb Ur Rehman', '', 'JDCA-T-004', '', 'male', '03555934923', 'Mujeebmirza404@gmail.com', '', 'Lecturer', 'M.Phil', 'Physics', 'permanent', '', '', '', 'Final degree 2024; 5 yrs exp'],
                    ['Tariq Hussain', '', 'JDCA-T-005', '', 'male', '03555410511', 'Tariqmalik7356@gmail.com', 'English', 'Lecturer', 'BS', 'English', 'permanent', '', '', '', 'Final degree 2020; 3 yrs exp'],
                    ['Fatima Niamat Khan', '', 'JDCA-T-006', '', 'female', '03109385353', 'Fatimaniamatullah6@gmail.com', '', 'Lecturer', 'BS', 'Biology', 'permanent', '', '', '', 'Final degree 2022; 3 yrs exp'],
                    ['Asima Juma', '', 'JDCA-T-007', '', 'female', '03129801799', 'asimaqau@gmai.com', '', 'Lecturer', 'BS (M.Phil continuing)', 'Chemistry, Biology', 'permanent', '', '', '', 'Final degree 2012; 10 yrs exp'],
                    ['Mehreen', '', 'JDCA-T-008', '', 'female', '03119719515', 'Mehreenbatool21@gmail.com', '', 'Lecturer', 'BS', 'Urdu', 'permanent', '', '', '', 'Final degree 2021; 3 yrs exp'],
                    ['Kaneez Fatima', '', 'JDCA-T-009', '', 'female', '03554358079', 'Kaneezz745@gmail.com', '', 'Lecturer', 'BS', 'Urdu', 'permanent', '', '', '', 'Final degree 2023; 3 yrs exp'],
                    ['Sumia Aziz', '', 'JDCA-T-010', '', 'female', '03554272243', 'Sumiaaziz112@gmail.com', '', 'Lecturer', 'BS', 'Zoology', 'permanent', '', '', '', 'Final degree 2024; 4 yrs exp'],
                    ['Habib Ur Rehman', '', 'JDCA-T-011', '', 'male', '03491908789', 'Habibhasrat1122@gmail.com', 'Education', 'Lecturer', 'BS', 'Education', 'permanent', '', '', '', 'Final degree 2024; 3 yrs exp'],
                    ['Tania Irum', '', 'JDCA-T-012', '', 'female', '03317443458', 'farmantanusalar@gmail.com', 'Physical Education', 'Lecturer', 'BS', 'Health & Physical', 'permanent', '', '', '', 'Final degree 2023; 2 yrs exp'],
                    ['Zakia Batool', '', 'JDCA-T-013', '', 'female', '03155999160', 'Zakiabatool70@gmail.com', 'English', 'Lecturer', 'BS', 'English', 'permanent', '', '', '', 'Final degree 2018; 6 yrs exp'],
                    ['Syeda Kishwar Batool', '', 'JDCA-T-014', '', 'female', '03125684175', 'Syyedakishwar76@gmail.com', '', 'Lecturer', 'BS', 'Mathematics', 'permanent', '', '', '', 'Final degree 2023; 2 yrs exp'],
                    ['Shahid Hussian', '', 'JDCA-T-015', '', 'male', '03115518080', 'Shahid343414@gmail.com', 'Computer Science', 'Lecturer', 'BS', 'Computer Science', 'permanent', '', '', '', 'Final degree 2020; 2 yrs exp'],
                    ['Arif Ali', '', 'JDCA-T-016', '', 'male', '03129776585', 'Arifaliraj@gmail.com', 'Computer Science', 'Lecturer', 'BS', 'Computer Science', 'permanent', '', '', '', 'Final degree 2016; 2 yrs exp'],
                    ['Habib Ur Rehman', '', 'JDCA-T-017', '', 'male', '03491908789', '', 'Sociology', 'Lecturer', 'BS', 'Social Study', 'permanent', '', '', '', 'Final degree 2023; 2 yrs exp'],
                    ['Muhammad Usama', '', 'JDCA-T-018', '', 'male', '03554459888', 'Musama6040@gmail.com', '', 'Lecturer', 'BS', 'Chemistry', 'permanent', '', '', '', 'Final degree 2022; 4 yrs exp'],
                    ['Taruf Hussain', '', 'JDCA-T-019', '', 'male', '03555792868', 'Tarufhussain111@gmail.com', '', 'Lecturer', 'BS', 'Mathematics', 'permanent', '', '', '', 'Final degree 2019; 4 yrs exp'],
                ];

                $output = fopen('php://temp', 'r+');
                fputcsv($output, $headers);
                foreach ($examples as $row) {
                    fputcsv($output, $row);
                }
                rewind($output);
                $csv = stream_get_contents($output);
                fclose($output);

                return response()->streamDownload(
                    fn() => print($csv),
                    'teachers-import-template.csv',
                    ['Content-Type' => 'text/csv']
                );
            });
    }
}
