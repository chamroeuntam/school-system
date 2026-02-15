<?php

namespace App\Services\Imports;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Subject;
use Carbon\Carbon;

class AttendanceSheetImporter
{
    public function import(
        array $rows,
        int $runByUserId,
        ?int $schoolClassId = null,
        ?string $subjectName = null,
        ?int $subjectIdFixed = null,
        ?string $sessionValue = null
    ): array
    {
        $rawHeader = $rows[0] ?? [];
        $header = array_map(fn($h) => $this->normalizeHeader($h), $rawHeader);
        $dataRows = array_slice($rows, 1);

        $year = AcademicYear::where('is_current', 1)->first();

        \Log::info('AttendanceSheetImporter started', [
            'rows_count' => count($dataRows),
            'has_current_year' => $year ? true : false,
            'year' => $year ? $year->name : 'NONE'
        ]);

        $total = 0; $ok = 0; $errors = [];

        if (!$year) {
            return [
                'total' => 0,
                'ok' => 0,
                'failed' => 1,
                'errors' => [[1, '', 'No current academic year (is_current=1)', ['header' => $rawHeader]]],
            ];
        }

        $studentCodeIdx = array_search('student_code', $header, true);
        if ($studentCodeIdx === false) {
            return [
                'total' => 0,
                'ok' => 0,
                'failed' => 1,
                'errors' => [[1, '', 'Missing student_code header', ['header' => $rawHeader]]],
            ];
        }

        $fullNameIdx = array_search('full_name', $header, true);
        $noteIdx = array_search('note', $header, true);

        $subjectId = $this->resolveSubjectId($subjectIdFixed, $subjectName);
        if (!$subjectId) {
            return [
                'total' => 0,
                'ok' => 0,
                'failed' => 1,
                'errors' => [[1, '', 'Subject not found for attendance sheet (check tab name or subject list)', ['header' => $rawHeader]]],
            ];
        }

        $session = $this->resolveSession($sessionValue, $subjectName);
        if (!$session) {
            return [
                'total' => 0,
                'ok' => 0,
                'failed' => 1,
                'errors' => [[1, '', 'Session not detected (include morning or evening in tab name)', ['header' => $rawHeader]]],
            ];
        }

        $dateColumns = [];
        $invalidDateHeaders = [];
        foreach ($rawHeader as $idx => $label) {
            $dateValue = $this->parseDateHeader($label, $invalidDateHeaders);
            if ($dateValue !== null) {
                $dateColumns[$idx] = $dateValue;
            }
        }

        if (!empty($invalidDateHeaders)) {
            return [
                'total' => 0,
                'ok' => 0,
                'failed' => 1,
                'errors' => [[1, '', 'Invalid date headers: ' . implode(', ', $invalidDateHeaders), ['header' => $rawHeader]]],
            ];
        }

        if (empty($dateColumns)) {
            return [
                'total' => 0,
                'ok' => 0,
                'failed' => 1,
                'errors' => [[1, '', 'No date columns found (use YYYY-MM-DD headers)', ['header' => $rawHeader]]],
            ];
        }

        foreach ($dataRows as $i => $row) {
            $rowNumber = $i + 2;
            $assoc = $this->combine($header, $row);

            $studentCode = trim((string)($row[$studentCodeIdx] ?? ''));
            if ($studentCode === '') {
                $errors[] = [$rowNumber, $studentCode, 'Missing student_code', $assoc];
                continue;
            }

            $student = Student::where('student_code', $studentCode)->first();
            if (!$student) {
                $errors[] = [$rowNumber, $studentCode, 'Student not found', $assoc];
                continue;
            }

            if ($fullNameIdx !== false) {
                $sheetName = trim((string)($row[$fullNameIdx] ?? ''));
                if ($sheetName !== '' && $this->normalizeName($sheetName) !== $this->normalizeName($student->full_name)) {
                    $errors[] = [$rowNumber, $studentCode, 'Full name does not match student record', $assoc];
                    continue;
                }
            }

            $enrollment = $student->enrollments()
                ->where('academic_year_id', $year->id)
                ->where('status', 'active')
                ->when($schoolClassId, fn($q) => $q->where('school_class_id', $schoolClassId))
                ->first();

            if (!$enrollment) {
                $errors[] = [$rowNumber, $studentCode, 'Enrollment not found for current year/class', $assoc];
                continue;
            }

            $note = $noteIdx !== false ? trim((string)($row[$noteIdx] ?? '')) : '';

            foreach ($dateColumns as $idx => $date) {
                $cellStr = trim((string)($row[$idx] ?? ''));
                if ($cellStr === '') {
                    continue;
                }

                $total++;
                $status = $this->statusFromCell($cellStr);
                if ($status === null) {
                    $headerLabel = trim((string)($rawHeader[$idx] ?? $date));
                    $errors[] = [$rowNumber, $studentCode, "Invalid status for {$headerLabel} (use 1, P, L, A)", $assoc];
                    continue;
                }

                Attendance::updateOrCreate(
                    ['enrollment_id' => $enrollment->id, 'subject_id' => $subjectId, 'attendance_date' => $date, 'session' => $session],
                    ['status' => $status, 'note' => $note ?: null, 'recorded_by' => $runByUserId]
                );

                $ok++;
            }
        }

        return ['total' => $total, 'ok' => $ok, 'failed' => count($errors), 'errors' => $errors];
    }

    private function normalizeHeader(mixed $value): string
    {
        return strtolower(trim((string)$value));
    }

    private function normalizeName(string $value): string
    {
        $collapsed = preg_replace('/\s+/', ' ', trim($value));
        return strtolower($collapsed ?? '');
    }

    private function parseDateHeader(mixed $value, array &$invalidHeaders): ?string
    {
        $label = trim((string)$value);
        if ($label === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $label)) {
            return null;
        }

        try {
            $date = Carbon::createFromFormat('Y-m-d', $label);
        } catch (\Throwable $e) {
            $invalidHeaders[] = $label;
            return null;
        }

        if ($date->format('Y-m-d') !== $label) {
            $invalidHeaders[] = $label;
            return null;
        }

        return $date->toDateString();
    }

    private function statusFromCell(string $value): ?string
    {
        $normalized = strtolower(trim($value));
        $map = [
            '1' => 'present',
            'present' => 'present',
            'p' => 'excused',
            'permission' => 'excused',
            'l' => 'late',
            'late' => 'late',
            'a' => 'absent',
            'absent' => 'absent',
            'apsent' => 'absent',
        ];

        return $map[$normalized] ?? null;
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

    private function resolveSubjectId(?int $subjectIdFixed, ?string $subjectName): ?int
    {
        if ($subjectIdFixed) {
            return $subjectIdFixed;
        }

        if ($subjectName === null || trim($subjectName) === '') {
            return null;
        }

        $subject = Subject::where('name', trim($subjectName))->first();
        return $subject?->id;
    }

    private function resolveSession(?string $sessionValue, ?string $tabName): ?string
    {
        if ($sessionValue && in_array(strtolower($sessionValue), ['morning', 'evening'])) {
            return strtolower($sessionValue);
        }

        if ($tabName === null) {
            return null;
        }

        $lower = strtolower($tabName);
        if (str_contains($lower, 'morning') || str_contains($lower, 'ព្រឹក')) {
            return 'morning';
        }
        if (str_contains($lower, 'evening') || str_contains($lower, 'afternoon') || str_contains($lower, 'ល្ងាច') || str_contains($lower, 'រសៀល')) {
            return 'evening';
        }

        return null;
    }
}
