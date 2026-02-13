<?php

namespace App\Services\Imports;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;

class AttendanceSheetImporter
{
    public function import(array $rows, int $runByUserId, ?int $schoolClassId = null): array
    {
        $header = array_map(fn($h) => strtolower(trim((string)$h)), $rows[0] ?? []);
        $dataRows = array_slice($rows, 1);

        $year = AcademicYear::where('is_current', 1)->first();

        \Log::info('AttendanceSheetImporter started', [
            'rows_count' => count($dataRows),
            'has_current_year' => $year ? true : false,
            'year' => $year ? $year->name : 'NONE'
        ]);

        $total = 0; $ok = 0; $errors = [];

        foreach ($dataRows as $i => $row) {
            $total++;
            $rowNumber = $i + 2;

            $assoc = $this->combine($header, $row);

            $studentCode = trim((string)($assoc['student_code'] ?? ''));
            $dateStr     = trim((string)($assoc['date'] ?? ''));
            $statusRaw   = strtolower(trim((string)($assoc['status'] ?? '')));
            $note        = trim((string)($assoc['note'] ?? ''));

            if ($studentCode === '' || $dateStr === '' || $statusRaw === '') {
                $errors[] = [$rowNumber, $studentCode, 'Missing required fields (date, student_code, status)', $assoc];
                continue;
            }

            // Convert shorthand to full status
            $statusMap = [
                'p' => 'present',
                'present' => 'present',
                'a' => 'absent',
                'absent' => 'absent',
                'l' => 'late',
                'late' => 'late',
                'e' => 'excused',
                'excused' => 'excused',
            ];

            if (!isset($statusMap[$statusRaw])) {
                $errors[] = [$rowNumber, $studentCode, 'Invalid status (use: p/present, a/absent, l/late, e/excused)', $assoc];
                continue;
            }

            $status = $statusMap[$statusRaw];

            try {
                $date = Carbon::parse($dateStr)->toDateString();
            } catch (\Throwable $e) {
                $errors[] = [$rowNumber, $studentCode, 'Invalid date format (use YYYY-MM-DD)', $assoc];
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

            $enrollment = $student->enrollments()
                ->where('academic_year_id', $year->id)
                ->where('status', 'active')
                ->when($schoolClassId, fn($q) => $q->where('school_class_id', $schoolClassId))
                ->first();

            if (!$enrollment) {
                $errors[] = [$rowNumber, $studentCode, 'Enrollment not found for current year/class', $assoc];
                continue;
            }

            Attendance::updateOrCreate(
                ['enrollment_id' => $enrollment->id, 'attendance_date' => $date],
                ['status' => $status, 'note' => $note ?: null, 'recorded_by' => $runByUserId]
            );

            $ok++;
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
}
