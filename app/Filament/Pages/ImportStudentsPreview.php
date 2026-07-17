<?php

namespace App\Filament\Pages;

use App\Filament\Actions\ImportStudentsFromExcelAction;
use App\Models\AcademicProgram;
use App\Models\Student;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;

class ImportStudentsPreview extends Page
{
    protected static ?string $navigationIcon  = null;
    protected static bool    $shouldRegisterNavigation = false;
    protected static string  $view = 'filament.pages.import-students-preview';

    public string $cacheKey  = '';
    public array  $rows      = [];
    public int    $newCount  = 0;
    public int    $updateCount = 0;
    public int    $totalCount  = 0;

    public function mount(): void
    {
        $this->cacheKey = request()->get('key', '');

        if (!$this->cacheKey) {
            $this->redirect(route('filament.admin.resources.students.index'));
            return;
        }

        $cached = cache()->get($this->cacheKey);

        if (empty($cached)) {
            Notification::make()->title('Preview session expired or not found.')->danger()->send();
            $this->redirect(route('filament.admin.resources.students.index'));
            return;
        }

        $this->rows        = $cached;
        $this->totalCount  = count($this->rows);
        $this->newCount    = count(array_filter($this->rows, fn($r) => $r['_status'] === 'new'));
        $this->updateCount = count(array_filter($this->rows, fn($r) => $r['_status'] === 'update'));
    }

    public function getTitle(): string
    {
        return 'Import Preview — ' . $this->totalCount . ' students';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('confirmImport')
                ->label('Confirm & Import ' . $this->totalCount . ' Students')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Confirm Import')
                ->modalDescription("This will save {$this->newCount} new and {$this->updateCount} updated student records to the database. This cannot be undone.")
                ->action(function () {
                    $created = $updated = $failed = 0;

                    foreach ($this->rows as $mapped) {
                        try {
                            $status = $mapped['_status'] ?? 'new';
                            unset($mapped['_status'], $mapped['_row']);

                            // Resolve program name → ID
                            if (!empty($mapped['_program_name'])) {
                                $prog = AcademicProgram::where('name', 'like', '%' . $mapped['_program_name'] . '%')
                                    ->orWhere('short_name', 'like', '%' . $mapped['_program_name'] . '%')
                                    ->first();
                                if ($prog) $mapped['academic_program_id'] = $prog->id;
                                unset($mapped['_program_name']);
                            }

                            if (empty($mapped['roll_number'])) {
                                $mapped['roll_number'] = 'JDCA-' . date('Y') . '-' . str_pad(Student::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);
                            }

                            $fillable = (new Student)->getFillable();
                            $attrs    = array_intersect_key($mapped, array_flip($fillable));

                            if ($status === 'update') {
                                $student = null;
                                if (!empty($mapped['registration_number'])) {
                                    $student = Student::where('registration_number', $mapped['registration_number'])->first();
                                }
                                if (!$student && !empty($mapped['roll_number'])) {
                                    $student = Student::where('roll_number', $mapped['roll_number'])->first();
                                }
                                if ($student) {
                                    $student->update($attrs);
                                    $updated++;
                                } else {
                                    Student::create(array_merge(['is_active' => true], $attrs));
                                    $created++;
                                }
                            } else {
                                Student::create(array_merge(['is_active' => true], $attrs));
                                $created++;
                            }
                        } catch (\Throwable $e) {
                            $failed++;
                        }
                    }

                    cache()->forget($this->cacheKey);

                    Notification::make()
                        ->title("Import complete: {$created} created, {$updated} updated" . ($failed ? ", {$failed} failed" : ''))
                        ->success()
                        ->send();

                    $this->redirect(route('filament.admin.resources.students.index'));
                }),

            Action::make('cancel')
                ->label('Cancel')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->outlined()
                ->action(function () {
                    cache()->forget($this->cacheKey);
                    $this->redirect(route('filament.admin.resources.students.index'));
                }),
        ];
    }
}
