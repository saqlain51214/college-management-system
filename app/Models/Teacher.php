<?php

namespace App\Models;

use App\Enums\BloodGroupEnum;
use App\Enums\EmploymentTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\TeacherStatusEnum;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model implements AuthenticatableContract
{
    use SoftDeletes, Authenticatable;

    protected $fillable = [
        'user_id',
        'department_id',
        'employee_id',
        'name',
        'name_urdu',
        'father_name',
        'date_of_birth',
        'gender',
        'blood_group',
        'religion',
        'nationality',
        'cnic',
        'email',
        'phone',
        'alternative_phone',
        'address',
        'city',
        'province',
        'photo',
        'highest_qualification',
        'specialization',
        'qualification_institution',
        'qualification_year',
        'designation',
        'employment_type',
        'experience_years',
        'joining_date',
        'leaving_date',
        'salary_grade',
        'basic_salary',
        'status',
        'is_active',
        'remarks',
        'portal_password',
    ];

    protected $casts = [
        'date_of_birth'  => 'date',
        'joining_date'   => 'date',
        'leaving_date'   => 'date',
        'status'          => TeacherStatusEnum::class,
        'gender'          => GenderEnum::class,
        'blood_group'     => BloodGroupEnum::class,
        'employment_type' => EmploymentTypeEnum::class,
        'is_active'       => 'boolean',
        'basic_salary'    => 'decimal:2',
    ];

    protected $hidden = ['portal_password', 'remember_token'];

    public function getAuthIdentifierName(): string  { return 'employee_id'; }
    public function getAuthIdentifier(): mixed        { return $this->employee_id; }
    public function getAuthPassword(): string         { return $this->portal_password ?? ''; }
    public function getRememberTokenName(): string    { return 'remember_token'; }

    // ─── Relations ────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }

    public function attendanceSessions(): HasMany
    {
        return $this->hasMany(AttendanceSession::class);
    }

    public function lmsMaterials(): HasMany
    {
        return $this->hasMany(LmsMaterial::class);
    }

    public function lmsAssignments(): HasMany
    {
        return $this->hasMany(LmsAssignment::class);
    }

    public function bookIssues(): HasMany
    {
        return $this->hasMany(BookIssue::class);
    }

    protected function portalPassword(): Attribute
    {
        return Attribute::make(
            set: fn(?string $value) => filled($value) ? \Illuminate\Support\Facades\Hash::make($value) : null
        );
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', TeacherStatusEnum::Active->value);
    }

    public function scopeForDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('employment_type', $type);
    }
}
