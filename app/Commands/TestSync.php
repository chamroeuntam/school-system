<?php

namespace App\Commands;

use App\Models\AcademicYear;
use App\Models\SheetSource;
use App\Models\Student;
use Illuminate\Console\Command;

class TestSync extends Command
{
    protected $signature = 'test:sync';
    protected $description = 'Test sync prerequisites';

    public function handle()
    {
        $this->info('=== CHECKING PREREQUISITES ===');

        // Check academic year
        $year = AcademicYear::where('is_current', 1)->first();
        if ($year) {
            $this->line("✓ Current academic year found: {$year->name} (ID: {$year->id})");
        } else {
            $this->error("✗ NO current academic year (is_current=1)");
            $this->line("Available years: " . AcademicYear::pluck('name')->join(', '));
        }

        // Check student 00001
        $student = Student::where('student_code', '00001')->first();
        if ($student) {
            $this->line("✓ Student 00001 found: {$student->name} (ID: {$student->id})");

            if ($year) {
                $enrollment = $student->enrollments()
                    ->where('academic_year_id', $year->id)
                    ->where('status', 'active')
                    ->first();

                if ($enrollment) {
                    $this->line("✓ Student has active enrollment in {$year->name} - Class: {$enrollment->schoolClass->name ?? 'N/A'}");
                } else {
                    $this->error("✗ Student 00001 has NO active enrollment for {$year->name}");
                }
            }
        } else {
            $this->error("✗ Student 00001 NOT FOUND");
            $this->line("Total students in DB: " . Student::count());
        }

        // Check sheet source
        $sheet = SheetSource::find(2);
        if ($sheet) {
            $this->line("✓ Sheet source ID 2 found: type={$sheet->type}, is_active={$sheet->is_active}");
        } else {
            $this->error("✗ Sheet source ID 2 not found");
        }

        $this->info('=== END CHECK ===');
    }
}
