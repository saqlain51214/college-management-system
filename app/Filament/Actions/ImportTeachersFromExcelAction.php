<?php

namespace App\Filament\Actions;

use App\Models\Department;
use App\Models\Teacher;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;

class ImportTeachersFromExcelAction extends Action
{
    public static function make(?string $name = 'importFromExcel'): static
    {
        return parent::make($name)
            ->label('Import Excel / CSV')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('success')
            ->modalHeading('Import Teachers')
            ->modalWidth('5xl')
            ->modalSubmitActionLabel('Import')
            ->steps([

                // ── Step 1: Upload ──────────────────────────────────────────
                Forms\Components\Wizard\Step::make('Upload File')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->schema([
                        Forms\Components\FileUpload::make('file')
                            ->label('Select Excel or CSV file')
                            ->required()
                            ->disk('local')
                            ->directory('imports/teachers')
                            ->acceptedFileTypes([
                                'text/csv',
                                'text/plain',
                                'application/csv',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/octet-stream',
                            ])
                            ->maxSize(10240)
                            ->helperText('Accepted: .xlsx, .xls, .csv — max 10 MB')
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if (empty($state)) {
                                    $set('_preview_key', '');
                                    return;
                                }

                                $file = is_array($state) ? ($state[0] ?? null) : $state;

                                if ($file === null) {
                                    $set('_preview_key', '');
                                    return;
                                }

                                // Case 1: Livewire TemporaryUploadedFile object — use getRealPath()
                                if (is_object($file) && method_exists($file, 'getRealPath')) {
                                    $path = $file->getRealPath();
                                } else {
                                    // Case 2: string identifier — files are in public disk / livewire-tmp/
                                    $name = basename((string) $file);
                                    $path = storage_path('app/public/livewire-tmp/' . $name);

                                    if (!file_exists($path)) {
                                        $path = \Illuminate\Support\Facades\Storage::disk('public')->path((string) $file);
                                    }
                                }

                                if (!$path || !file_exists($path)) {
                                    $set('_preview_key', 'notfound');
                                    return;
                                }

                                try {
                                    $parsedRows = static::parseRows($path);
                                    $previewHtml = static::buildPreviewHtml($path, $parsedRows);
                                    $cacheKey = 'teacher-import-' . auth()->id() . '-' . uniqid('', true);
                                    cache()->put($cacheKey, [
                                        'html' => $previewHtml,
                                        'rows' => $parsedRows,
                                    ], now()->addMinutes(60));
                                    $set('_preview_key', $cacheKey);
                                } catch (\Throwable $e) {
                                    $set('_preview_key', 'error:' . $e->getMessage());
                                }
                            }),

                        Forms\Components\Hidden::make('_preview_key'),

                        Forms\Components\Placeholder::make('format_hint')
                            ->label('')
                            ->content(new HtmlString(
                                '<div class="rounded-xl p-3 text-xs" style="background:#f0f9ff;border:1px solid #bae6fd;color:#0369a1;">'
                                . '<strong>First row must be the header row.</strong> Recognised columns: '
                                . 'NAME, FATHER NAME, EMPLOYEE ID, CNIC, GENDER, PHONE, EMAIL, DEPARTMENT, DESIGNATION, QUALIFICATION, SPECIALIZATION, EMPLOYMENT TYPE, JOINING DATE, CITY, ADDRESS, REMARKS'
                                . '</div>'
                            )),
                    ]),

                // ── Step 2: Preview & Confirm ───────────────────────────────
                Forms\Components\Wizard\Step::make('Preview & Confirm')
                    ->icon('heroicon-o-eye')
                    ->schema([
                        Forms\Components\Placeholder::make('preview_table')
                            ->label('')
                            ->content(function (Forms\Get $get) {
                                $key = trim((string) $get('_preview_key'));

                                if ($key === '') {
                                    return new HtmlString(
                                        '<div class="rounded-xl p-4 text-sm text-center" style="background:#f9fafb;border:1px dashed #d1d5db;color:#9ca3af;">'
                                        . 'Go back and upload a file — the preview will load automatically.'
                                        . '</div>'
                                    );
                                }

                                if (str_starts_with($key, 'error:')) {
                                    return new HtmlString('<p class="text-sm text-red-500">Error reading file: ' . e(substr($key, 6)) . '</p>');
                                }

                                if (str_starts_with($key, 'notfound')) {
                                    return new HtmlString('<p class="text-sm text-red-500">Uploaded file not accessible. Please go back and upload again.</p>');
                                }

                                $cached = cache()->get($key);
                                if (!$cached) {
                                    return new HtmlString('<p class="text-sm text-amber-600">Preview expired. Go back and re-upload the file.</p>');
                                }

                                return new HtmlString($cached['html']);
                            }),
                    ]),
            ])
            ->action(function (array $data) {
                $key    = trim((string) ($data['_preview_key'] ?? ''));
                $cached = $key ? cache()->get($key) : null;

                if (!$cached || empty($cached['rows'])) {
                    Notification::make()->title('No import data found. Please re-upload the file.')->danger()->send();
                    return;
                }

                [$created, $updated, $failed, $lastError] = static::saveRows($cached['rows']);

                cache()->forget($key);

                $title = "Import complete: {$created} created, {$updated} updated" . ($failed ? ", {$failed} failed" : '');
                $notification = Notification::make()->title($title);

                if ($failed > 0 && $lastError) {
                    $notification->body('Last error: ' . $lastError)->warning();
                } else {
                    $notification->success();
                }

                $notification->send();
            });
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private static function readRows(string $path): array
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (in_array($ext, ['xlsx', 'xls'], true)) {
            $allSheets = Excel::toArray(new class {}, $path, null, \Maatwebsite\Excel\Excel::XLSX);
            $sheetRows = $allSheets[0] ?? [];
        } else {
            // Robust CSV read: strip BOM and auto-detect the delimiter (comma,
            // semicolon or tab) so files saved by Excel in any locale still parse.
            $sheetRows = static::readCsvRows($path);
        }

        // Auto-detect header row: first row with a recognised column name
        $knownTerms = [
            'name', 'teacher name', 'father name', 'employee id', 'emp id',
            'cnic', 'gender', 'phone', 'email', 'department', 'designation',
            'qualification', 'specialization', 'employment type',
        ];
        $headerIdx = 0;
        foreach ($sheetRows as $i => $row) {
            $lower = array_map(fn($v) => strtolower(trim((string) $v)), $row);
            foreach ($knownTerms as $term) {
                if (in_array($term, $lower, true)) {
                    $headerIdx = $i;
                    break 2;
                }
            }
        }

        return array_slice($sheetRows, $headerIdx);
    }

    /**
     * Read a CSV/TXT file into rows, tolerating a UTF-8 BOM and comma,
     * semicolon or tab delimiters (Excel exports vary by locale).
     *
     * @return array<int,array<int,string>>
     */
    private static function readCsvRows(string $path): array
    {
        $content = (string) file_get_contents($path);
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content); // strip BOM

        $lines = preg_split('/\r\n|\r|\n/', $content) ?: [];
        $lines = array_values(array_filter($lines, fn ($l) => trim($l) !== ''));
        if ($lines === []) {
            return [];
        }

        $header = $lines[0];
        $counts = [
            ','  => substr_count($header, ','),
            ';'  => substr_count($header, ';'),
            "\t" => substr_count($header, "\t"),
        ];
        arsort($counts);
        $delimiter = array_key_first($counts) ?: ',';
        if ($counts[$delimiter] === 0) {
            $delimiter = ',';
        }

        return array_map(fn ($line) => str_getcsv($line, $delimiter), $lines);
    }

    private static function buildPreviewHtml(string $path, array $parsed = []): string
    {
        if (empty($parsed)) {
            try {
                $parsed = static::parseRows($path);
            } catch (\Throwable $e) {
                return '<p class="text-sm text-red-500">Could not read file: ' . e($e->getMessage()) . '</p>';
            }
        }

        if (empty($parsed)) {
            return '<p class="text-sm text-amber-600">No valid teacher rows found. Make sure the NAME column is present and not empty.</p>';
        }

        // Rebuild header info for mapping chips
        try {
            $rows       = static::readRows($path);
            $rawHeaders = count($rows) ? array_map(fn($h) => strtolower(trim((string) $h)), $rows[0]) : [];
            $fieldMap   = static::buildFieldMap($rawHeaders);
            $headerRow  = $rows[0] ?? [];
        } catch (\Throwable) {
            $fieldMap  = [];
            $headerRow = [];
        }

        $newCount    = count(array_filter($parsed, fn($r) => $r['_status'] === 'new'));
        $updateCount = count(array_filter($parsed, fn($r) => $r['_status'] === 'update'));
        $total       = count($parsed);

        // Column mapping chips
        $fieldLabels = [
            'name' => 'Name', 'father_name' => "Father's Name",
            'employee_id' => 'Employee ID', 'cnic' => 'CNIC',
            'gender' => 'Gender', 'phone' => 'Phone', 'email' => 'Email',
            '_department_name' => 'Department', 'designation' => 'Designation',
            'highest_qualification' => 'Qualification', 'specialization' => 'Specialization',
            'employment_type' => 'Employment Type', 'joining_date' => 'Joining Date',
            'city' => 'City', 'address' => 'Address', 'remarks' => 'Remarks',
        ];
        $chips = [];
        foreach ($fieldMap as $idx => $field) {
            $orig    = $headerRow[$idx] ?? "Col $idx";
            $chips[] = '<span style="display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:500;background:#f3f4f6;color:#374151;">'
                . e((string) $orig) . ' <span style="color:#9ca3af;">→</span> ' . e($fieldLabels[$field] ?? $field)
                . '</span>';
        }

        $html  = '<div style="display:flex;flex-direction:column;gap:12px;">';

        // Mapping
        if ($chips) {
            $html .= '<div style="border-radius:10px;padding:10px 12px;background:#f0f9ff;border:1px solid #bae6fd;">';
            $html .= '<p style="font-size:11px;font-weight:600;color:#0369a1;margin:0 0 6px;">Detected Column Mapping</p>';
            $html .= '<div style="display:flex;flex-wrap:wrap;gap:6px;">' . implode('', $chips) . '</div>';
            $html .= '</div>';
        }

        // Summary
        $html .= '<div style="display:flex;gap:10px;flex-wrap:wrap;">';
        $html .= '<span style="padding:4px 14px;border-radius:999px;font-size:12px;font-weight:700;background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;">+ ' . $newCount . ' New</span>';
        $html .= '<span style="padding:4px 14px;border-radius:999px;font-size:12px;font-weight:700;background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;">↻ ' . $updateCount . ' Update</span>';
        $html .= '<span style="padding:4px 14px;border-radius:999px;font-size:12px;font-weight:700;background:#f9fafb;color:#6b7280;border:1px solid #e5e7eb;">' . $total . ' Total</span>';
        $html .= '</div>';

        // Table
        $html .= '<div style="overflow-x:auto;border-radius:10px;border:1px solid #e5e7eb;max-height:360px;overflow-y:auto;">';
        $html .= '<table style="width:100%;border-collapse:collapse;font-size:12px;">';
        $html .= '<thead><tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">';
        foreach (['#', 'Status', 'Name', "Father's Name", 'Employee ID', 'Department', 'Designation', 'Gender', 'Phone'] as $col) {
            $html .= '<th style="padding:8px 12px;text-align:left;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;white-space:nowrap;">' . e($col) . '</th>';
        }
        $html .= '</tr></thead><tbody>';

        foreach ($parsed as $i => $row) {
            $isNew = $row['_status'] === 'new';
            $bg    = $i % 2 === 0 ? '#ffffff' : '#fafafa';
            $badge = $isNew
                ? '<span style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;padding:2px 8px;border-radius:999px;font-size:10px;font-weight:700;">New</span>'
                : '<span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;padding:2px 8px;border-radius:999px;font-size:10px;font-weight:700;">Update</span>';

            $html .= '<tr style="background:' . $bg . ';border-bottom:1px solid #f3f4f6;">';
            $html .= '<td style="padding:6px 12px;color:#9ca3af;font-family:monospace;">' . ($i + 1) . '</td>';
            $html .= '<td style="padding:6px 12px;">' . $badge . '</td>';
            $html .= '<td style="padding:6px 12px;font-weight:500;color:#111827;">' . e($row['name'] ?? '—') . '</td>';
            $html .= '<td style="padding:6px 12px;color:#6b7280;">' . e($row['father_name'] ?? '—') . '</td>';
            $html .= '<td style="padding:6px 12px;font-family:monospace;font-size:11px;color:#6b7280;">' . e($row['employee_id'] ?? '—') . '</td>';
            $html .= '<td style="padding:6px 12px;color:#6b7280;font-size:11px;">' . e($row['_department_name'] ?? '—') . '</td>';
            $html .= '<td style="padding:6px 12px;color:#6b7280;font-size:11px;">' . e($row['designation'] ?? '—') . '</td>';
            $html .= '<td style="padding:6px 12px;color:#6b7280;font-size:11px;">' . e($row['gender'] ?? '—') . '</td>';
            $html .= '<td style="padding:6px 12px;font-family:monospace;font-size:11px;color:#6b7280;">' . e($row['phone'] ?? '—') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></div>';
        $html .= '<p style="font-size:11px;color:#9ca3af;">Review the rows above, then click <strong>Import</strong> to save all ' . $total . ' records.</p>';
        $html .= '</div>';

        return $html;
    }

    private static function parseRows(string $path): array
    {
        $rows = static::readRows($path);
        if (count($rows) < 2) return [];

        $rawHeaders = array_map(fn($h) => strtolower(trim((string) $h)), $rows[0]);
        $fieldMap   = static::buildFieldMap($rawHeaders);
        $dataRows   = array_slice($rows, 1);

        $parsed = [];
        foreach ($dataRows as $row) {
            $mapped = static::mapRow($row, $fieldMap);
            if (empty(trim((string) ($mapped['name'] ?? '')))) continue;

            $status = 'new';
            if (!empty($mapped['employee_id']) && Teacher::where('employee_id', $mapped['employee_id'])->exists()) {
                $status = 'update';
            } elseif (!empty($mapped['cnic']) && Teacher::where('cnic', $mapped['cnic'])->exists()) {
                $status = 'update';
            }
            $parsed[] = array_merge($mapped, ['_status' => $status]);
        }
        return $parsed;
    }

    private static function saveRows(array $parsed): array
    {
        $created = $updated = $failed = 0;
        $lastError = null;

        foreach ($parsed as $mapped) {
            try {
                unset($mapped['_status']);

                // Resolve department by name / code / slug fragment
                if (!empty($mapped['_department_name'])) {
                    $term = $mapped['_department_name'];
                    $dept = Department::where('name', 'like', '%' . $term . '%')
                        ->orWhere('code', 'like', '%' . $term . '%')
                        ->orWhere('slug', 'like', '%' . \Illuminate\Support\Str::slug($term) . '%')
                        ->first();
                    if ($dept) $mapped['department_id'] = $dept->id;
                    unset($mapped['_department_name']);
                }

                if (empty($mapped['employee_id'])) {
                    $mapped['employee_id'] = 'JDCA-T-' . str_pad(Teacher::withTrashed()->count() + 1, 3, '0', STR_PAD_LEFT);
                }

                // Normalise enum fields
                if (!empty($mapped['gender'])) {
                    $mapped['gender'] = strtolower(trim($mapped['gender']));
                }
                if (!empty($mapped['employment_type'])) {
                    $mapped['employment_type'] = strtolower(trim($mapped['employment_type']));
                }

                $fillable = (new Teacher)->getFillable();
                $attrs    = array_intersect_key($mapped, array_flip($fillable));

                // Blank cells must become NULL — an empty string breaks date/enum columns.
                $attrs = array_map(fn ($v) => (is_string($v) && trim($v) === '') ? null : $v, $attrs);

                // Find existing record by any unique key
                $teacher = null;
                if (!empty($mapped['employee_id'])) {
                    $teacher = Teacher::where('employee_id', $mapped['employee_id'])->first();
                }
                if (!$teacher && !empty($mapped['cnic'])) {
                    $teacher = Teacher::where('cnic', $mapped['cnic'])->first();
                }
                if (!$teacher && !empty($mapped['email'])) {
                    $teacher = Teacher::where('email', $mapped['email'])->first();
                }

                if ($teacher) {
                    $teacher->update($attrs);
                    $updated++;
                } else {
                    // Strip unique fields that might conflict with soft-deleted records
                    $cleanAttrs = $attrs;
                    foreach (['cnic', 'email'] as $unique) {
                        if (!empty($cleanAttrs[$unique])) {
                            $exists = Teacher::withTrashed()->where($unique, $cleanAttrs[$unique])->exists();
                            if ($exists) unset($cleanAttrs[$unique]);
                        }
                    }
                    Teacher::create(array_merge(['is_active' => true, 'status' => 'active'], $cleanAttrs));
                    $created++;
                }

            } catch (\Throwable $e) {
                $failed++;
                $lastError = $e->getMessage();
            }
        }

        return [$created, $updated, $failed, $lastError];
    }

    public static function buildFieldMap(array $headers): array
    {
        $map = [];
        $patterns = [
            'name'                  => ['name', 'teacher name', 'full name', 'teacher_name'],
            'father_name'           => ['father name', 'father_name', "father's name", 'fathers name'],
            'employee_id'           => ['employee id', 'employee_id', 'emp id', 'emp_id', 'employee no'],
            'cnic'                  => ['cnic', 'nic', 'national id'],
            'gender'                => ['gender', 'sex'],
            'phone'                 => ['phone', 'mobile', 'contact', 'phone no', 'cell'],
            'email'                 => ['email', 'e-mail', 'email address'],
            '_department_name'      => ['department', 'dept', 'department name'],
            'designation'           => ['designation', 'post', 'title', 'role'],
            'highest_qualification' => ['qualification', 'highest qualification', 'degree', 'education'],
            'specialization'        => ['specialization', 'specialisation', 'subject', 'expertise'],
            'employment_type'       => ['employment type', 'employment_type', 'emp type', 'type'],
            'joining_date'          => ['joining date', 'joining_date', 'date of joining', 'doj'],
            'city'                  => ['city', 'district', 'area'],
            'address'               => ['address', 'home address'],
            'remarks'               => ['remarks', 'notes', 'comment'],
        ];

        foreach ($headers as $idx => $header) {
            foreach ($patterns as $field => $aliases) {
                if (in_array($header, $aliases, true)) {
                    $map[$idx] = $field;
                    break;
                }
            }
        }

        return $map;
    }

    public static function mapRow(array $row, array $fieldMap): array
    {
        $result = [];
        foreach ($row as $idx => $value) {
            if (isset($fieldMap[$idx])) {
                $result[$fieldMap[$idx]] = is_string($value) ? trim($value) : (string) ($value ?? '');
            }
        }
        return $result;
    }
}
