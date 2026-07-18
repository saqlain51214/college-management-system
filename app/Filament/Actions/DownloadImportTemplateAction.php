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
            [
                // Department of Education → B.Ed 2.5 Year (Fall 2024)
                'program'  => 'B.Ed 2.5 Year',
                'session'  => '2024',
                'semester' => '1',
                'students' => [
                    ['Sania', 'Sham ul Haq', '2024-KIU-2978'],
                    ['Araz Hafeez', 'Abdul Hafeez', '2024-KIU-2979'],
                    ['Noseen Jamal', 'Jamal Khan', '2024-KIU-2980'],
                    ['Amjeeda', 'Azam Khan', '2024-KIU-2981'],
                    ['Anjuman Shaheen', 'Abdul Zahir', '2024-KIU-2982'],
                    ['Fozia Batool', 'Sulaman', '2024-KIU-2983'],
                    ['Younan Aara', 'Sham ul Haq', '2024-KIU-2984'],
                    ['Wasila', 'Dilbar Khan', '2024-KIU-2985'],
                    ['Noor Ul Aim', 'Ashfaq Ahmed', '2024-KIU-2986'],
                    ['Batool', 'Muhammad Shafa', '2024-KIU-2987'],
                    ['Azeema Shaheen', 'Sadiq Ali', '2024-KIU-2988'],
                    ['Nina Batool', 'Baktawar Shah', '2024-KIU-2989'],
                    ['Quraish Begum', 'Ghulam Hussain', '2024-KIU-2990'],
                    ['Nadir Ali', 'Sag Ali', '2024-KIU-2991'],
                    ['Muhammad Usama', 'Azmat Khan', '2024-KIU-2992'],
                ],
            ],
            [
                // Department of Education → B.Ed 1.5 Year (Fall 2024)
                'program'  => 'B.Ed 1.5 Year',
                'session'  => '2024',
                'semester' => '1',
                'students' => [
                    ['Tariq Hussain', 'Juma Khan', '2024-KIU-2993'],
                    ['Rashid Minhas', 'Shah Zaman', '2024-KIU-2994'],
                    ['Wajahat Ali', 'Muhammad Ibrahim', '2024-KIU-2995'],
                    ['Mahnoor Saba', 'Altaf Hussain', '2024-KIU-2996'],
                    ['Mehrab Banu', 'Ali Muhammad', '2024-KIU-2997'],
                    ['Saira Akber', 'Akber Ali', '2024-KIU-2998'],
                    ['Nelofar Nisar', 'Nisar Ali', '2024-KIU-2999'],
                    ['Abira Murad', 'Murad Khan', '2024-KIU-3000'],
                    ['Younus Khan', 'Yar Muhammad', '2024-KIU-3001'],
                    ['Hira Iqbal', 'Khalid Iqbal', '2024-KIU-3002'],
                    ['Arif Ullah Khan', 'Juma Khan', '2024-KIU-3003'],
                    ['Muhammad Naveed', 'Muhammad Bashir', '2024-KIU-3004'],
                    ['Nusrat Fatima', 'Muhammad Yousaf', '2024-KIU-3005'],
                ],
            ],
            [
                // Department of Education → Associate Degree in Education (Spring 2025)
                'program'  => 'Associate Degree in Education',
                'session'  => '2025',
                'semester' => '1',
                'students' => [
                    ['Anjuman Tahir', 'Tahir Muhammad', '2025-KIU-ADP-3365'],
                    ['Saliqa', 'Liaqat Ali', '2025-KIU-ADP-3366'],
                    ['Ghazala', 'Dilawar Khan', '2025-KIU-ADP-3367'],
                    ['Muhammad Abid', 'Sher Alam', '2025-KIU-ADP-3368'],
                    ['Muhammad Sajid', 'Sher Alam', '2025-KIU-ADP-3369'],
                    ['Nusrart Alam', 'Naik Alam', '2025-KIU-ADP-3370'],
                    ['Nazia Batool', 'Rajab Ali', '2025-KIU-ADP-3371'],
                    ['Aliya Batool', 'Rajab Ali', '2025-KIU-ADP-3372'],
                    ['Salma Ramzan', 'Muhammad Ramzan', '2025-KIU-ADP-3373'],
                    ['Zahida Begum', 'Muhammad Yaqoob', '2025-KIU-ADP-3374'],
                    ['Soraj Zehra', 'Raja Zahid Ali', '2025-KIU-ADP-3375'],
                    ['Sugra', 'Abid Ali', '2025-KIU-ADP-3376'],
                    ['Mehreen', 'Ghulam Nabi', '2025-KIU-ADP-3377'],
                    ['Anee Begum', 'Abdul Quyyan', '2025-KIU-ADP-3378'],
                    ['Hasina', 'Muhammad Yousaf', '2025-KIU-ADP-3379'],
                    ['Salma', 'Noor Muhammad', '2025-KIU-ADP-3380'],
                    ['Uzma Parveen', 'Akbar Khan', '2025-KIU-ADP-3381'],
                    ['Nazeela', 'Malik Shah', '2025-KIU-ADP-3382'],
                    ['Suriya', 'M. Latif', '2025-KIU-ADP-3383'],
                    ['Anjum Ara', 'Ghulam Noor', '2025-KIU-ADP-3384'],
                    ['Hussain Wahid Lone', 'Abdul Wahid Lone', '2025-KIU-ADP-3385'],
                    ['Rukhsana', 'Faraz Khan', '2025-KIU-ADP-3386'],
                    ['Shah Hoor Batool', 'Muhammad Mussa', '2025-KIU-ADP-3387'],
                    ['Sadaf Fatima', 'Muhammad Murtaza', '2025-KIU-ADP-3388'],
                    ['Musrat Begum', 'Abdul Karim', '2025-KIU-ADP-3389'],
                ],
            ],
            [
                // Department of Education → B.Ed 2.5 Year (Spring 2025)
                'program'  => 'B.Ed 2.5 Year',
                'session'  => '2025',
                'semester' => '1',
                'students' => [
                    ['Nazakat', 'Raja Wajid Ali Shah', '2025-KIU-3510'],
                    ['Shafiqa Malik', 'Abdul Hakeem Malik', '2025-KIU-3511'],
                    ['Abdul Baqi', 'Abdul Raouf', '2025-KIU-3512'],
                    ['Maqsood Ali', 'Muhammad Ali', '2025-KIU-3513'],
                    ['Chinarah Gul', 'Gul Muhammad', '2025-KIU-3514'],
                    ['Safia Sher', 'Qari Muhammad Farooz', '2025-KIU-3515'],
                    ['Nabila', 'Muhammad Akbar', '2025-KIU-3516'],
                    ['Shamim', 'Abdul Manaf', '2025-KIU-3517'],
                    ['Shamim Ara', 'Muhammad Yousaf', '2025-KIU-3518'],
                    ['Marina', 'Akbar Khan', '2025-KIU-3519'],
                    ['Chaman Ara', 'Mohd Abdullah', '2025-KIU-3520'],
                    ['Nelofer', 'Bakhtawar', '2025-KIU-3521'],
                    ['Noor ul Haq', 'Muhammad Sher', '2025-KIU-3522'],
                    ['Shumaila Raziq', 'Abdul Raziq', '2025-KIU-3525'],
                ],
            ],
            [
                // Department of Education → B.Ed 1.5 Year (Spring 2025)
                'program'  => 'B.Ed 1.5 Year',
                'session'  => '2025',
                'semester' => '1',
                'students' => [
                    ['Sonia Bibi', 'Mirbaz Khan', '2025-KIU-3500'],
                    ['Mir Ghulam', 'Ghulam Rasool', '2025-KIU-3501'],
                    ['Sitara Batool', 'Murad Khan', '2025-KIU-3502'],
                    ['Abdul Razzaq', 'Azmat Khan', '2025-KIU-3503'],
                    ['Shahid Hussain', 'Muhammad Issa', '2025-KIU-3504'],
                    ['Adeel Ahmad', 'Samandar Khan', '2025-KIU-3505'],
                    ['Khalil Ahmad', 'Sher Ahmad', '2025-KIU-3506'],
                    ['Aqeel Turabi', 'Ibrahim', '2025-KIU-3507'],
                ],
            ],
            [
                // Department of Education → Associate Degree in Education (Fall 2025)
                'program'  => 'Associate Degree in Education',
                'session'  => '2025',
                'semester' => '1',
                'students' => [
                    ['Waseem', 'Ghulam Sarwar', '2025-ADP-4250'],
                    ['Shima Begum', 'Abdul Aziz', '2025-ADP-4251'],
                    ['Anila Jahan', 'Shah Jahan', '2025-ADP-4252'],
                    ['Erum Jahan', 'Shah Jahan', '2025-ADP-4253'],
                    ['Abdullah', 'Muhammad Wali', '2025-ADP-4254'],
                    ['Maria Raza', 'Muhammad Raza', '2025-ADP-4255'],
                    ['Zeeshan Ullah', 'Raheem Ullah', '2025-ADP-4256'],
                    ['Ali Abbas', 'Riaz Ahmed', '2025-ADP-4257'],
                    ['Nosheeda Kiran', 'Abdul Rasheed', '2025-ADP-4258'],
                    ['Bibi Sania', 'Behram Khan', '2025-ADP-4259'],
                    ['Qazafi', 'Shukoor Muhammad', '2025-ADP-4260'],
                    ['Shehzadi', 'Muzfar Khan', '2025-ADP-4261'],
                    ['Nosheen', 'Mirza Khan', '2025-ADP-4262'],
                    ['Iqrar-ul-Hassan', 'Muhammad Hussain', '2025-ADP-4263'],
                    ['Mehreen', 'Muhammad Hanif', '2025-ADP-4264'],
                    ['Huma', 'Ijaz Ali', '2025-ADP-4265'],
                    ['Waseem', 'Muhammad Sharif', '2025-ADP-4266'],
                    ['Kania Kosain', 'Iftikhar Ahmed', '2025-ADP-4267'],
                    ['Iqra', 'Ghulam Nabi', '2025-ADP-4268'],
                    ['Saba Nisar', 'Nisar Ali', '2025-ADP-4269'],
                    ['Alveena Alam', 'Naik Alam', '2025-ADP-4270'],
                    ['Afsheen Zaman', 'Muhammad Zaman', '2025-ADP-4271'],
                    ['Naheed Akhtar', 'Abdul Latif', '2025-ADP-4272'],
                    ['Roman Begum', 'Jamal Khan', '2025-ADP-4273'],
                    ['Musrat Batool', 'Shah Raies', '2025-ADP-4274'],
                    ['Fatima', 'Abdul Jamil', '2025-ADP-4275'],
                    ['Adnan Essa', 'Muhammad Essa', '2025-ADP-4276'],
                    ['Adeeba Issa', 'Muhammad Issa', '2025-ADP-4277'],
                    ['Hasbiullah', 'Abdul Jabbar', '2025-ADP-4278'],
                    ['Usman Malik', 'Muhammad Yousaf', '2025-ADP-4279'],
                    ['Iqra Batool', 'Amanullah', '2025-ADP-4280'],
                    ['Anjum Shareif', 'Muhammad Sharif', '2025-ADP-4281'],
                    ['Nadia Mushqat', 'Mushqat', '2025-ADP-4282'],
                    ['Muhammad Abid', 'Rozi Khan', '2025-ADP-4283'],
                    ['Zaidullah Khan', 'Jamal Khan', '2025-ADP-5209'],
                    ['Kiran', 'Muhammad Sharif', '2025-ADP-5372'],
                    ['Muneeba Manan', 'Abdul Manan', '2025-ADP-5373'],
                    ['Aizaz Ehsan', 'Abdul Rasheed', '2025-ADP-5374'],
                    ['Haseeba', 'Hafizullah Khan', '2025-ADP-5375'],
                    ['Faiza Batool', 'Yar Muhammad', '2025-ADP-5376'],
                    ['Mehak', 'Sham ul Haq', '2025-ADP-5377'],
                    ['Lubna Batool', 'Sherbaz Ali', '2025-ADP-5378'],
                ],
            ],
            [
                // Department of Physical Education → AD Health & Physical Education (Fall 2025)
                'program'  => 'Associate Degree in Health & Physical Education',
                'session'  => '2025',
                'semester' => '1',
                'students' => [
                    ['Mehwish Zehra', 'Shahid Ali', '2025-ADP-4220'],
                    ['Mustaeen Khan', 'Muhammad Sharif', '2025-ADP-4221'],
                    ['Saira Manzoor', 'Manzoor Ahmed', '2025-ADP-4222'],
                    ['Chand', 'Muhammad Khan', '2025-ADP-4223'],
                    ['Nazia Akbar', 'Akbar Khan', '2025-ADP-4224'],
                    ['Muhammad Gufran', 'Muhammad Anwar', '2025-ADP-4225'],
                    ['Sobia', 'Raji Rehmat', '2025-ADP-4226'],
                    ['Huzafa', 'Abdullah Jan', '2025-ADP-4227'],
                    ['Amir Khan', 'Abdullah Jan', '2025-ADP-4228'],
                    ['Mehreen Salma', 'Noshad Ali', '2025-ADP-4229'],
                    ['Bushra Noushad', 'Noushad Ali', '2025-ADP-4230'],
                    ['Mehak', 'Abdul Razzaq', '2025-ADP-4231'],
                    ['Sana', 'Muhammad Ibrahiem', '2025-ADP-4232'],
                    ['Bushra', 'Haider Ali', '2025-ADP-4233'],
                    ['Aska Raziq', 'Abdul Raziq', '2025-ADP-4234'],
                    ['Sidra Iqbal', 'Muhammad Iqbal', '2025-ADP-4235'],
                    ['Abdul Basit', 'Abdul Gaffar', '2025-ADP-4236'],
                    ['Rana Atif', 'Muhsin Khan', '2025-ADP-4237'],
                    ['Hadiya Aziz', 'Abdul Aziz', '2025-ADP-4238'],
                    ['Rana Zabiullah', 'Zamin Khan', '2025-ADP-4239'],
                    ['Zaheer Abbas', 'Mardan Ali', '2025-ADP-4240'],
                    ['Sabia Abdullah', 'Abdullah Jan', '2025-ADP-4241'],
                    ['Measum Abbas', 'Maroof Shah', '2025-ADP-4242'],
                    ['Mariam Bibi', 'Murtaza Khan', '2025-ADP-4243'],
                    ['Zehra Batool', 'Khush Din', '2025-ADP-4244'],
                    ['Kainat Bazigah', 'Abdul Aleem', '2025-ADP-4245'],
                    ['Kiran Shera', 'Muhammad Sher', '2025-ADP-4246'],
                    ['Zaheer Abbas', 'Abdullah', '2025-ADP-4247'],
                    ['Rizwana Abdullah', 'Abdullah Khan', '2025-ADP-4248'],
                    ['Nousheen Salma', 'Noushad Ali', '2025-ADP-4249'],
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
