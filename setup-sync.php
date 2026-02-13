<?php
// Quick setup script - run: php setup-sync.php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\GradeLevel;
use App\Models\SchoolClass;
use App\Models\Enrollment;

echo "\n=== SYNC SETUP CHECK ===\n";

// 1. Check/Create Current Academic Year
$year = AcademicYear::where('is_current', 1)->first();
if (!$year) {
    echo "✗ No current academic year. Creating one...\n";
    $year = AcademicYear::create([
        'name' => '2025-2026',
        'start_date' => '2025-09-01',
        'end_date' => '2026-08-31',
        'is_current' => 1,
    ]);
    echo "✓ Created academic year: {$year->name}\n";
} else {
    echo "✓ Current academic year: {$year->name} (ID: {$year->id})\n";
}

// 2. Check Student 00001
$student = Student::where('student_code', '00001')->first();
if (!$student) {
    echo "✗ Student 00001 not found\n";
} else {
    echo "✓ Student 00001 exists: {$student->name}\n";

    // Check enrollment
    $enrollment = $student->enrollments()->where('academic_year_id', $year->id)->first();
    if (!$enrollment) {
        echo "  ✗ No enrollment for {$year->name}. Creating...\n";
        // Get first class
        $class = SchoolClass::first();
        if (!$class) {
            echo "  ERROR: No school classes found!\n";
        } else {
            Enrollment::create([
                'student_id' => $student->id,
                'academic_year_id' => $year->id,
                'school_class_id' => $class->id,
                'status' => 'active',
            ]);
            echo "  ✓ Created enrollment in {$class->name}\n";
        }
    } else {
        echo "  ✓ Has enrollment in {$enrollment->schoolClass->name}\n";
    }
}

echo "\n=== READY TO SYNC ===\n";
