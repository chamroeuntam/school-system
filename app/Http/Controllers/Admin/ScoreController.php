<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Score;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\Term;
use App\Models\SchoolClass;
use App\Models\AcademicYear;
use App\Models\SubjectRule;

class ScoreController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters with defaults
        $academicYearId = $request->academic_year_id ?? null;
        $classId = $request->class_id ?? null;
        $termId = $request->term_id ?? null;
        $subjectId = $request->subject_id ?? null;

        // Get filter options
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $classes = SchoolClass::with(['gradeLevel', 'stream'])->orderBy('name')->get();
        $terms = Term::orderBy('id', 'desc')->get();
        $subjects = Subject::orderBy('name')->get();

        // Build query - only get enrollments if filters are selected
        $enrollments = collect();
        if ($academicYearId && $classId) {
            $enrollments = Enrollment::with(['student', 'schoolClass', 'academicYear'])
                ->where('status', 'active')
                ->where('academic_year_id', $academicYearId)
                ->where('school_class_id', $classId)
                ->get();
        }

        // Get scores if filters are applied
        $scores = collect();
        if ($termId && $subjectId && $enrollments->isNotEmpty()) {
            $enrollmentIds = $enrollments->pluck('id');
            $scores = Score::whereIn('enrollment_id', $enrollmentIds)
                ->where('term_id', $termId)
                ->where('subject_id', $subjectId)
                ->get()
                ->keyBy(function ($score) {
                    return $score->enrollment_id . '_' . $score->exam_type;
                });
        }

        $maxScore = 100;
        if ($classId && $subjectId) {
            $class = SchoolClass::find($classId);
            $ruleMax = $this->getMaxScoreForClass($class, $subjectId);
            if ($ruleMax !== null) {
                $maxScore = $ruleMax;
            }
        }

        return view('admin.scores.index', compact(
            'enrollments',
            'scores',
            'academicYears',
            'classes',
            'terms',
            'subjects',
            'academicYearId',
            'classId',
            'termId',
            'subjectId',
            'maxScore'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'subject_id' => 'required|exists:subjects,id',
            'term_id' => 'required|exists:terms,id',
            'exam_type' => 'required|in:month_1,month_2,month_3,semester_exam',
            'score' => 'required|numeric|min:0',
            'grade_letter' => 'nullable|string|max:5',
            'remark' => 'nullable|string|max:255',
        ]);

        $maxScore = $this->getMaxScoreForEnrollment($validated['enrollment_id'], $validated['subject_id']);
        if ($maxScore === null) {
            return back()->with('error', 'គ្មានក្បួនពិន្ទុអតិបរមាសម្រាប់មុខវិជ្ជានេះ។');
        }
        if ($validated['score'] > $maxScore) {
            return back()->with('error', "ពិន្ទុត្រូវនៅចន្លោះ 0 និង {$maxScore}។");
        }

        $validated['recorded_by'] = auth()->id();

        Score::updateOrCreate(
            [
                'enrollment_id' => $validated['enrollment_id'],
                'subject_id' => $validated['subject_id'],
                'term_id' => $validated['term_id'],
                'exam_type' => $validated['exam_type'],
            ],
            $validated
        );

        return back()->with('success', 'ពិន្ទុត្រូវបានរក្សាទុក។');
    }

    public function update(Request $request, Score $score)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0',
            'grade_letter' => 'nullable|string|max:5',
            'remark' => 'nullable|string|max:255',
        ]);

        $maxScore = $this->getMaxScoreForEnrollment($score->enrollment_id, $score->subject_id);
        if ($maxScore === null) {
            return back()->with('error', 'គ្មានក្បួនពិន្ទុអតិបរមាសម្រាប់មុខវិជ្ជានេះ។');
        }
        if ($validated['score'] > $maxScore) {
            return back()->with('error', "ពិន្ទុត្រូវនៅចន្លោះ 0 និង {$maxScore}។");
        }

        $score->update($validated);

        return back()->with('success', 'ពិន្ទុត្រូវបានកែប្រែ។');
    }

    public function destroy(Score $score)
    {
        $score->delete();

        return back()->with('success', 'ពិន្ទុត្រូវបានលុប។');
    }

    public function batchStore(Request $request)
    {
        $validated = $request->validate([
            'term_id' => 'required|exists:terms,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_type' => 'required|in:month_1,month_2,month_3,semester_exam',
            'scores' => 'required|array',
            'scores.*.enrollment_id' => 'required|exists:enrollments,id',
            'scores.*.score' => 'nullable|numeric|min:0',
        ]);

        $validationErrors = [];
        $maxCache = [];
        foreach ($validated['scores'] as $index => $scoreData) {
            if (!isset($scoreData['score']) || $scoreData['score'] === '') {
                continue;
            }
            $maxScore = $this->getMaxScoreForEnrollment($scoreData['enrollment_id'], $validated['subject_id'], $maxCache);
            if ($maxScore === null) {
                $validationErrors[] = 'គ្មានក្បួនពិន្ទុអតិបរមាសម្រាប់មុខវិជ្ជានេះ។';
                break;
            }
            if ($scoreData['score'] > $maxScore) {
                $validationErrors[] = "ពិន្ទុខ្លះៗលើស {$maxScore}។";
                break;
            }
        }

        if (!empty($validationErrors)) {
            return back()->with('error', $validationErrors[0]);
        }

        foreach ($validated['scores'] as $scoreData) {
            if (isset($scoreData['score']) && $scoreData['score'] !== '') {
                Score::updateOrCreate(
                    [
                        'enrollment_id' => $scoreData['enrollment_id'],
                        'subject_id' => $validated['subject_id'],
                        'term_id' => $validated['term_id'],
                        'exam_type' => $validated['exam_type'],
                    ],
                    [
                        'score' => $scoreData['score'],
                        'recorded_by' => auth()->id(),
                    ]
                );
            }
        }

        return back()->with('success', 'ពិន្ទុទាំងអស់ត្រូវបានរក្សាទុក។');
    }

    private function getMaxScoreForEnrollment(int $enrollmentId, int $subjectId, array &$cache = []): ?float
    {
        $cacheKey = $enrollmentId . ':' . $subjectId;
        if (array_key_exists($cacheKey, $cache)) {
            return $cache[$cacheKey];
        }

        $enrollment = Enrollment::with('schoolClass')->find($enrollmentId);
        if (!$enrollment || !$enrollment->schoolClass) {
            $cache[$cacheKey] = null;
            return null;
        }

        $cache[$cacheKey] = $this->getMaxScoreForClass($enrollment->schoolClass, $subjectId);
        return $cache[$cacheKey];
    }

    private function getMaxScoreForClass(?SchoolClass $class, int $subjectId): ?float
    {
        if (!$class) {
            return null;
        }

        $rule = SubjectRule::where('grade_level_id', $class->grade_level_id)
            ->where('stream_id', $class->stream_id)
            ->where('subject_id', $subjectId)
            ->first();

        return $rule ? (float)$rule->max_score : null;
    }
}
