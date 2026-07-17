<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionInquiry extends Model
{
    protected $fillable = [
        'reference_no','name','father_name','father_occupation','father_phone',
        'guardian_name','guardian_phone',
        'email','phone','student_phone',
        'entry_path','semester','program_type','gender','campus','city',
        'district','tehsil','village','post_office',
        'cnic','dob','address','program_id','program_name',
        'qualification','academic_details','documents','declare_true','message','status','admin_notes',
    ];

    protected $casts = [
        'dob' => 'date',
        'academic_details' => 'array',
        'documents'        => 'array',
        'declare_true' => 'boolean',
    ];

    public function program(): BelongsTo { return $this->belongsTo(AcademicProgram::class, 'program_id'); }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'new'       => 'info',
            'contacted' => 'warning',
            'enrolled'  => 'success',
            'rejected'  => 'danger',
            default     => 'gray',
        };
    }
}
