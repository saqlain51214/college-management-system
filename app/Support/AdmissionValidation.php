<?php

namespace App\Support;

use Illuminate\Validation\Rule;

class AdmissionValidation
{
    public static function normalizePhone(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value) ?? '';

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '92') && strlen($digits) === 12) {
            return '+' . $digits;
        }

        if (str_starts_with($digits, '0') && strlen($digits) === 11) {
            return '+92' . substr($digits, 1);
        }

        if (str_starts_with($digits, '3') && strlen($digits) === 10) {
            return '+92' . $digits;
        }

        return $value;
    }

    public static function config(): array
    {
        static $config = null;

        if ($config === null) {
            $path = resource_path('json/admission-validation.json');
            $config = is_file($path)
                ? json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR)
                : [];
        }

        return $config;
    }

    public static function frontendConfig(): array
    {
        return array_merge(self::config(), [
            'step_fields' => self::stepFields(),
        ]);
    }

    public static function rules(string $entryPath, ?int $step = null): array
    {
        $config = self::config();
        $patterns = $config['patterns'] ?? [];
        $limits = $config['limits'] ?? [];
        $yearRules = ['integer', 'min:' . ($limits['min_pass_year'] ?? 2018), 'max:' . ($limits['max_pass_year'] ?? 2035)];
        $phoneRule = 'regex:/'.$patterns['phone_pk'].'/';
        $cnicRule = 'regex:/'.$patterns['cnic'].'/';
        $marksRule = 'regex:/'.$patterns['marks_fraction'].'/';

        $rules = [
            'entry_path' => ['required', 'in:intermediate,undergraduate'],
            'program_id' => [
                'required',
                Rule::exists('academic_programs', 'id')->where(function ($query) use ($entryPath) {
                    $query->where('is_active', true)
                        ->where('show_on_website', true);

                    if (in_array($entryPath, ['intermediate', 'undergraduate'], true)) {
                        $query->where('admission_category', $entryPath);
                    }
                }),
            ],
            'gender' => ['required', 'in:female,male'],
            'campus' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:100'],
            'father_name' => ['required', 'string', 'max:100'],
            'cnic' => ['required', 'string', $cnicRule],
            'dob' => ['required', 'date', 'after_or_equal:' . ($limits['min_dob'] ?? '1990-01-01'), 'before_or_equal:' . ($limits['max_dob'] ?? '2015-12-31')],
            'phone' => ['required', 'string', $phoneRule],
            'student_phone' => ['nullable', 'string', $phoneRule],
            'email' => ['required', 'email', 'max:150'],
            'address' => ['required', 'string', 'max:1000'],
            'message' => ['nullable', 'string', 'max:1000'],
            'declare_true' => ['accepted'],
        ];

        if ($entryPath === 'intermediate') {
            $rules = array_merge($rules, [
                'academic.matric.qualification' => ['required', 'string', 'max:50'],
                'academic.matric.pass_year' => array_merge(['required'], $yearRules),
                'academic.matric.board' => ['required', 'string', 'max:150'],
                'academic.matric.marks' => ['required', 'string', $marksRule],
                'academic.matric.school' => ['required', 'string', 'max:150'],
            ]);
        }

        if ($entryPath === 'undergraduate') {
            $rules = array_merge($rules, [
                'academic.matric_summary.board' => ['required', 'string', 'max:150'],
                'academic.matric_summary.pass_year' => array_merge(['required'], $yearRules),
                'academic.matric_summary.marks' => ['required', 'string', $marksRule],
                'academic.hssc.qualification' => ['required', 'string', 'max:50'],
                'academic.hssc.pass_year' => array_merge(['required'], $yearRules),
                'academic.hssc.board' => ['required', 'string', 'max:150'],
                'academic.hssc.marks' => ['required', 'string', $marksRule],
                'academic.hssc.school' => ['required', 'string', 'max:150'],
            ]);
        }

        if ($step === null) {
            return $rules;
        }

        $allowedFields = array_flip(self::stepFields()[$step] ?? []);

        return array_intersect_key($rules, $allowedFields);
    }

    public static function messages(): array
    {
        return self::config()['messages'] ?? [];
    }

    public static function stepFields(): array
    {
        return [
            0 => ['entry_path', 'program_id', 'gender', 'campus', 'city'],
            1 => ['name', 'father_name', 'cnic', 'dob'],
            2 => ['phone', 'student_phone', 'email', 'address'],
            3 => [
                'academic.matric.qualification',
                'academic.matric.pass_year',
                'academic.matric.board',
                'academic.matric.marks',
                'academic.matric.school',
                'academic.matric_summary.board',
                'academic.matric_summary.pass_year',
                'academic.matric_summary.marks',
                'academic.hssc.qualification',
                'academic.hssc.pass_year',
                'academic.hssc.board',
                'academic.hssc.marks',
                'academic.hssc.school',
            ],
            4 => ['message', 'declare_true'],
        ];
    }
}
