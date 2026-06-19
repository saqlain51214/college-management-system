<?php

namespace App\Models;

use App\Enums\AdmissionCategoryEnum;
use App\Enums\DegreeTypeEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AcademicProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_id',
        'name',
        'short_name',
        'name_urdu',
        'slug',
        'code',
        'degree_type',
        'admission_category',
        'duration_years',
        'total_semesters',
        'total_credit_hours',
        'description',
        'eligibility',
        'scope',
        'banner_image',
        'is_active',
        'show_on_website',
        'sort_order',
    ];

    protected $casts = [
        'degree_type'        => DegreeTypeEnum::class,
        'admission_category' => AdmissionCategoryEnum::class,
        'is_active'          => 'boolean',
        'show_on_website'    => 'boolean',
        'sort_order'         => 'integer',
        'duration_years'     => 'integer',
        'total_semesters'    => 'integer',
        'total_credit_hours' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $program) {
            if (empty($program->slug)) {
                $program->slug = Str::slug($program->name);
            }

            if (empty($program->admission_category)) {
                $program->admission_category = self::inferAdmissionCategory(
                    $program->name,
                    $program->short_name,
                    $program->code,
                    $program->degree_type
                )->value;
            }
        });

        static::forceDeleting(function (self $program) {
            if ($program->banner_image) {
                Storage::disk('public')->delete($program->banner_image);
            }
        });
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_active', true)->where('show_on_website', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeForDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeForAdmissionCategory($query, AdmissionCategoryEnum|string $category)
    {
        $value = $category instanceof AdmissionCategoryEnum ? $category->value : $category;

        return $query->where('admission_category', $value);
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getStatusAttribute(): StatusEnum
    {
        return $this->is_active ? StatusEnum::Active : StatusEnum::Inactive;
    }

    public function getBannerImageUrlAttribute(): ?string
    {
        return $this->banner_image ? asset('storage/' . $this->banner_image) : null;
    }

    public function getAdmissionCategoryLabelAttribute(): string
    {
        return $this->admission_category?->label() ?? 'Other / Not for Online Admission';
    }

    public function getDurationLabelAttribute(): string
    {
        return "{$this->duration_years} Year" . ($this->duration_years > 1 ? 's' : '')
            . " / {$this->total_semesters} Semesters";
    }

    public static function inferAdmissionCategory(
        ?string $name,
        ?string $shortName,
        ?string $code,
        DegreeTypeEnum|string|null $degreeType
    ): AdmissionCategoryEnum {
        $haystack = Str::lower(trim(implode(' ', array_filter([$name, $shortName, $code]))));

        if ($haystack !== '' && preg_match('/\b(fa|f\.a|fsc|f\.sc|ics|i\.cs|i\.com|icom|intermediate|pre medical|pre-medical|pre engineering|pre-engineering)\b/', $haystack)) {
            return AdmissionCategoryEnum::Intermediate;
        }

        $degreeValue = $degreeType instanceof DegreeTypeEnum ? $degreeType->value : (string) $degreeType;

        return match ($degreeValue) {
            DegreeTypeEnum::BS->value, DegreeTypeEnum::BEd->value => AdmissionCategoryEnum::Undergraduate,
            default => AdmissionCategoryEnum::Other,
        };
    }
}
