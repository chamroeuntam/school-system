<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Score extends Model
{
    protected $fillable = [
        'enrollment_id','subject_id','term_id','exam_type',
        'score','grade_letter','remark','recorded_by'
    ];

    // Exam type constants
    const EXAM_MONTH_1 = 'month_1';
    const EXAM_MONTH_2 = 'month_2';
    const EXAM_MONTH_3 = 'month_3';
    const EXAM_SEMESTER = 'semester_exam';

    public static function examTypes(): array
    {
        return [
            self::EXAM_MONTH_1 => 'ខែទី១',
            self::EXAM_MONTH_2 => 'ខែទី២',
            self::EXAM_MONTH_3 => 'ខែទី៣',
            self::EXAM_SEMESTER => 'ប្រឡងឆមាស',
        ];
    }

    // Calculate semester total (Month1 + Month2 + Month3 + Semester Exam)
    public static function calculateSemesterTotal($enrollmentId, $subjectId, $termId)
    {
        $scores = self::where('enrollment_id', $enrollmentId)
            ->where('subject_id', $subjectId)
            ->where('term_id', $termId)
            ->get();

        $total = 0;
        foreach ($scores as $score) {
            $total += $score->score ?? 0;
        }

        return $total;
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
