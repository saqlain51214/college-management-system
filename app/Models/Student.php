<?php

namespace App\Models;

use App\Enums\AdmissionTypeEnum;
use App\Enums\BloodGroupEnum;
use App\Enums\GenderEnum;
use App\Enums\StudentStatusEnum;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Student extends Model implements AuthenticatableContract
{
    use HasFactory, SoftDeletes, Authenticatable, Notifiable;

    protected $fillable = [
        'user_id', 'department_id', 'academic_program_id', 'academic_year_id',
        'roll_number', 'registration_number', 'name', 'name_urdu',
        'father_name', 'father_name_urdu', 'mother_name', 'date_of_birth',
        'gender', 'blood_group', 'religion', 'nationality', 'domicile',
        'cnic', 'father_cnic', 'email', 'phone', 'father_phone',
        'guardian_name', 'guardian_phone', 'guardian_relation',
        'address', 'city', 'district', 'province', 'permanent_address',
        'photo', 'batch_year', 'current_semester', 'section',
        'admission_date', 'admission_type', 'previous_qualification',
        'previous_marks', 'previous_board', 'previous_year',
        'status', 'disability', 'is_hosteler', 'is_active', 'remarks',
        'portal_password',
    ];

    protected $casts = [
        'date_of_birth'  => 'date',
        'admission_date' => 'date',
        'status'         => StudentStatusEnum::class,
        'gender'         => GenderEnum::class,
        'blood_group'    => BloodGroupEnum::class,
        'admission_type' => AdmissionTypeEnum::class,
        'is_hosteler'    => 'boolean',
        'is_active'      => 'boolean',
        'previous_marks' => 'decimal:2',
    ];

    protected $hidden = ['portal_password', 'remember_token'];

    public function routeNotificationForMail(): string
    {
        return $this->email;
    }

    public function getAuthIdentifierName(): string  { return 'roll_number'; }
    public function getAuthIdentifier(): mixed        { return $this->roll_number; }
    public function getAuthPassword(): string         { return $this->portal_password ?? ''; }
    public function getRememberTokenName(): string    { return 'remember_token'; }

    // ─── Relations ────────────────────────────────────────────────────────
    public function user(): BelongsTo           { return $this->belongsTo(User::class); }
    public function department(): BelongsTo     { return $this->belongsTo(Department::class); }
    public function academicProgram(): BelongsTo{ return $this->belongsTo(AcademicProgram::class); }
    public function academicYear(): BelongsTo   { return $this->belongsTo(AcademicYear::class); }
    public function feePayments()               { return $this->hasMany(FeePayment::class); }

    // ─── Accessors & Mutators ─────────────────────────────────────────────
    protected function fullName(): Attribute
    {
        return Attribute::get(fn() => $this->name . ' ' . $this->father_name);
    }

    protected function age(): Attribute
    {
        return Attribute::get(
            fn() => $this->date_of_birth ? $this->date_of_birth->age : null
        );
    }

    protected function portalPassword(): Attribute
    {
        return Attribute::make(
            set: fn(?string $value) => filled($value) ? \Illuminate\Support\Facades\Hash::make($value) : null
        );
    }

    /** Give every new student the initial portal password "123456" if none is set. */
    protected static function booted(): void
    {
        static::creating(function (Student $student) {
            if (empty($student->getAttributes()['portal_password'] ?? null)) {
                $student->portal_password = '123456';
            }
        });
    }

    // ─── Scopes ───────────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', StudentStatusEnum::Active->value);
    }

    public function scopeForDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeForProgram($query, int $programId)
    {
        return $query->where('academic_program_id', $programId);
    }

    public function scopeForBatch($query, int $year)
    {
        return $query->where('batch_year', $year);
    }
}
