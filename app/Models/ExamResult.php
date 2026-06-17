<?php

namespace App\Models;

use App\Helpers\CollegeHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    protected $fillable = [
        'exam_id', 'student_id', 'marks_obtained', 'grade',
        'grade_points', 'is_absent', 'is_exempted', 'remarks', 'entered_by',
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'grade_points'   => 'decimal:2',
        'is_absent'      => 'boolean',
        'is_exempted'    => 'boolean',
    ];

    public function exam(): BelongsTo    { return $this->belongsTo(Exam::class); }
    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function enterer(): BelongsTo { return $this->belongsTo(User::class, 'entered_by'); }

    public function calculateGrade(): void
    {
        if ($this->is_absent || $this->is_exempted || is_null($this->marks_obtained)) {
            return;
        }

        $totalMarks = $this->exam->total_marks;
        if ($totalMarks <= 0) {
            return;
        }

        $percentage  = ($this->marks_obtained / $totalMarks) * 100;
        $gradeInfo   = CollegeHelper::gradeFromPercentage($percentage);

        $this->grade       = $gradeInfo['grade'];
        $this->grade_points = $gradeInfo['points'];
    }
}
