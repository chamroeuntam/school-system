<?php

namespace App\Services\Imports;

use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectRule;
use App\Models\Term;

class ScoreSheetImporter
{
    public function import(
        array $rows,
        int $runByUserId,
        ?int $schoolClassId = null,
        ?int $subjectIdFixed = null,
        ?int $termIdFixed = null
    ): array
    {
        $rawHeader = $rows[0] ?? [];
        $header = array_map(fn($h) => $this->normalizeHeader($h), $rawHeader);
        $dataRows = array_slice($rows, 1);

        $year = AcademicYear::where('is_current', 1)->first();

        $total = 0; $ok = 0; $errors = [];

        $hasScoreColumn = in_array('score', $header, true);

        if ($subjectIdFixed && !$hasScoreColumn) {
            return [
                'total' => 0,
                'ok' => 0,
                'failed' => 1,
                'errors' => [[1, '', 'Sheet has no score column for fixed subject import', ['header' => $rawHeader]]],
            ];
        }

        $subjectMap = $this->subjectNameMap();
        $ignored = $this->ignoredHeaders();
        $subjectColumns = [];
        $unknownHeaders = [];

        if (!$hasScoreColumn && !$subjectIdFixed) {
            foreach ($header as $idx => $key) {
                if ($key === '' || isset($ignored[$key])) {
                    continue;
                }

                if (isset($subjectMap[$key])) {
                    $subjectColumns[$idx] = $subjectMap[$key];
                } else {
                    $unknownHeaders[] = $rawHeader[$idx] ?? $key;
                }
            }

            if (!empty($unknownHeaders)) {
                return [
                    'total' => 0,
                    'ok' => 0,
                    'failed' => 1,
                    'errors' => [[1, '', 'Unknown subject columns: ' . implode(', ', $unknownHeaders), ['header' => $rawHeader]]],
                ];
            }

            if (empty($subjectColumns)) {
                return [
                    'total' => 0,
                    'ok' => 0,
                    'failed' => 1,
                    'errors' => [[1, '', 'No subject columns found in header', ['header' => $rawHeader]]],
                ];
            }
        }

        foreach ($dataRows as $i => $row) {
            $total++;
            $rowNumber = $i + 2;

            $assoc = $this->combine($header, $row);

            $studentCode = trim((string)($assoc['student_code'] ?? ''));
            $subjectName = trim((string)($assoc['subject'] ?? ''));
            $scoreStr    = trim((string)($assoc['score'] ?? ''));
            $termName    = trim((string)($assoc['term'] ?? ''));   // optional
            $remark      = trim((string)($assoc['remark'] ?? ''));

            if ($studentCode === '') {
                $errors[] = [$rowNumber, $studentCode, 'Missing required fields (student_code)', $assoc];
                continue;
            }

            if (!$year) {
                $errors[] = [$rowNumber, $studentCode, 'No current academic year (is_current=1)', $assoc];
                continue;
            }

            $student = Student::where('student_code', $studentCode)->first();
            if (!$student) {
                $errors[] = [$rowNumber, $studentCode, 'Student not found', $assoc];
                continue;
            }

            /** @var Enrollment|null $enrollment */
            $enrollment = $student->enrollments()
                ->where('academic_year_id', $year->id)
                ->where('status', 'active')
                ->when($schoolClassId, fn($q) => $q->where('school_class_id', $schoolClassId))
                ->with(['schoolClass.gradeLevel','schoolClass.stream'])
                ->first();

            if (!$enrollment) {
                $errors[] = [$rowNumber, $studentCode, 'Enrollment not found for current year/class', $assoc];
                continue;
            }

            $termId = $termIdFixed;
            if (!$termId && $termName !== '') {
                $term = Term::where('academic_year_id', $year->id)->where('name', $termName)->first();
                if (!$term) {
                    $errors[] = [$rowNumber, $studentCode, "Term not found: {$termName}", $assoc];
                    continue;
                }
                $termId = $term->id;
            }

            $gradeId  = optional($enrollment->schoolClass->gradeLevel)->id;
            $streamId = optional($enrollment->schoolClass->stream)->id;

            if (!$gradeId || !$streamId) {
                $errors[] = [$rowNumber, $studentCode, 'Class grade_level or stream missing (check school_classes)', $assoc];
                continue;
            }

            if ($hasScoreColumn || $subjectIdFixed) {
                if ($scoreStr === '') {
                    $errors[] = [$rowNumber, $studentCode, 'Missing required fields (score)', $assoc];
                    continue;
                }

                if (!is_numeric($scoreStr)) {
                    $errors[] = [$rowNumber, $studentCode, 'Score must be numeric', $assoc];
                    continue;
                }

                $scoreVal = (float)$scoreStr;

                $subjectId = $subjectIdFixed;
                if (!$subjectId) {
                    if ($subjectName === '') {
                        $errors[] = [$rowNumber, $studentCode, 'Missing subject (or set subject_id in sheet_sources)', $assoc];
                        continue;
                    }
                    $subjectKey = $this->normalizeHeader($subjectName);
                    $subjectId = $subjectMap[$subjectKey] ?? null;
                    if (!$subjectId) {
                        $errors[] = [$rowNumber, $studentCode, "Subject not found: {$subjectName}", $assoc];
                        continue;
                    }
                }

                $result = $this->saveScore(
                    $studentCode,
                    $enrollment->id,
                    $subjectId,
                    $termId,
                    $scoreVal,
                    $remark,
                    $runByUserId,
                    $gradeId,
                    $streamId
                );

                if ($result !== true) {
                    $errors[] = [$rowNumber, $studentCode, $result, $assoc];
                    continue;
                }

                $ok++;
                continue;
            }

            $rowOk = false;
            foreach ($subjectColumns as $idx => $subjectId) {
                $cellValue = $row[$idx] ?? null;
                $cellStr = trim((string)$cellValue);
                if ($cellStr === '') {
                    continue;
                }

                if (!is_numeric($cellStr)) {
                    $subjectNameRaw = trim((string)($rawHeader[$idx] ?? ''));
                    $errors[] = [$rowNumber, $studentCode, "Score must be numeric for {$subjectNameRaw}", $assoc];
                    continue;
                }

                $scoreVal = (float)$cellStr;
                $result = $this->saveScore(
                    $studentCode,
                    $enrollment->id,
                    $subjectId,
                    $termId,
                    $scoreVal,
                    null,
                    $runByUserId,
                    $gradeId,
                    $streamId
                );

                if ($result !== true) {
                    $subjectNameRaw = trim((string)($rawHeader[$idx] ?? ''));
                    $errors[] = [$rowNumber, $studentCode, "{$subjectNameRaw}: {$result}", $assoc];
                    continue;
                }

                $rowOk = true;
            }

            if ($rowOk) {
                $ok++;
            } else {
                $errors[] = [$rowNumber, $studentCode, 'No scores found in subject columns', $assoc];
            }
        }

        return ['total' => $total, 'ok' => $ok, 'failed' => count($errors), 'errors' => $errors];
    }

    private function combine(array $header, array $row): array
    {
        $assoc = [];
        foreach ($header as $idx => $key) {
            if ($key === '') continue;
            $assoc[$key] = $row[$idx] ?? null;
        }
        return $assoc;
    }

    private function saveScore(
        string $studentCode,
        int $enrollmentId,
        int $subjectId,
        ?int $termId,
        float $scoreVal,
        ?string $remark,
        int $runByUserId,
        int $gradeId,
        int $streamId
    ) {
        $rule = SubjectRule::where('grade_level_id', $gradeId)
            ->where('stream_id', $streamId)
            ->where('subject_id', $subjectId)
            ->first();

        if (!$rule) {
            return 'No subject rule (max_score) for grade/stream/subject';
        }

        if ($scoreVal < 0 || $scoreVal > $rule->max_score) {
            return "Score out of range (0 - {$rule->max_score})";
        }

        Score::updateOrCreate(
            ['enrollment_id' => $enrollmentId, 'subject_id' => $subjectId, 'term_id' => $termId],
            ['score' => $scoreVal, 'remark' => $remark ?: null, 'recorded_by' => $runByUserId]
        );

        return true;
    }

    private function subjectNameMap(): array
    {
        $map = [];
        foreach (Subject::all(['id', 'name']) as $subject) {
            $key = $this->normalizeHeader($subject->name);
            if ($key !== '') {
                $map[$key] = $subject->id;
            }
        }
        return $map;
    }

    private function ignoredHeaders(): array
    {
        $ignore = [
            'no',
            'no.',
            'no#',
            'ល.រ',
            'student_code',
            'student_name',
            'gender',
            'dob',
            'date',
            'subject',
            'score',
            'term',
            'remark',
            'note',
            'total',
            'average',
            'rank',
            'grade',
            'other',
            'ពិន្ទុសរុប',
            'មធ្យមភាគ',
            'ចំណាត់ថ្នាក់',
            'និទ្ទេស',
            'ផ្សេងៗ',
        ];

        $normalized = [];
        foreach ($ignore as $value) {
            $normalized[$this->normalizeHeader($value)] = true;
        }
        return $normalized;
    }

    private function normalizeHeader($value): string
    {
        $text = trim((string)$value);
        if ($text === '') {
            return '';
        }
        return function_exists('mb_strtolower') ? mb_strtolower($text) : strtolower($text);
    }
}
