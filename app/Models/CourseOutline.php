<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseOutline extends Model
{
    protected $fillable = [
        'department_id', 'academic_program_id', 'semester_number',
        'title', 'file_paths', 'external_url', 'description', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'semester_number' => 'integer',
        'sort_order'      => 'integer',
        'file_paths'      => 'array',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function academicProgram(): BelongsTo
    {
        return $this->belongsTo(AcademicProgram::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** One entry per uploaded PDF: ['name' => 'Syllabus.pdf', 'url' => '...']. */
    public function getFilesAttribute(): array
    {
        return collect($this->file_paths ?? [])
            ->values()
            ->map(fn ($path, $i) => [
                'name' => $this->displayNameFor($path, $i + 1),
                'url'  => asset('storage/' . $path),
            ])
            ->all();
    }

    /**
     * Files uploaded before ->preserveFilenames() was enabled were saved under a
     * random storage name (e.g. "01KYPNV8NE...pdf") — show a friendly ordinal
     * label instead of that random string. Filenames preserved from here on
     * (real names, no spaces-to-underscore-only pattern) are shown as-is.
     */
    protected function displayNameFor(string $path, int $position): string
    {
        $name = basename($path);

        if (preg_match('/^[0-9A-Z]{25,26}\.\w+$/i', $name)) {
            $ext = pathinfo($name, PATHINFO_EXTENSION);

            return 'PDF ' . $position . ($ext ? '.' . $ext : '');
        }

        return $name;
    }

    /** Public URL to download/open the outline — first uploaded file, or the external link. */
    public function getUrlAttribute(): ?string
    {
        if (filled($this->file_paths)) {
            return asset('storage/' . $this->file_paths[0]);
        }

        return $this->external_url ?: null;
    }
}
