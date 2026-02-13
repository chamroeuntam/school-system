# Google Sheets Score Import Setup Guide

## Overview

This system supports **two Google Sheet formats** for importing student scores:

1. **Wide Format** (Multiple subjects per row) — Recommended for this screenshot
2. **Narrow Format** (One score per row) — For simpler imports

---

## Step 1: Prepare Subjects in Database

First, seed your subjects table with Khmer subject names. Run this seeder or add via admin panel:

```php
// database/seeders/SubjectsSeeder.php
<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectsSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            'អក្សរសាស្រខ្មែរ',        // Khmer
            'គណិតវិទ្យា',           // Mathematics
            'រូបវិទ្យា',             // Physics
            'គីមីវិទ្យា',           // Chemistry
            'ជីវៈវិទ្យា',           // Biology
            'ប្រវត្តិវិទ្យា',       // History
            'ភូមិវិទ្យា',           // Geography
            'ផែនដីវិទ្យា',         // Earth Sciences
            'សីលធម៌-ពលរដ្ឋ',       // Morality & Citizenship
            'សេដ្ឋកិច្ចវិទ្យា',     // Economics
            'ICT',                      // Information & Communication Technology
            'កីឡា/អប់រំកាយ',        // Sports/Physical Education
        ];

        foreach ($subjects as $name) {
            Subject::customOrCreate(['name' => $name]);
        }
    }
}
```

Run:
```bash
php artisan db:seed SubjectsSeeder
```

---

## Step 2: Set Up Grade/Stream Subject Rules

Each **Grade + Stream** combo must have max_score rules for each subject.

```php
// database/seeders/SubjectRulesSeeder.php (example)
$gradeLevel = GradeLevel::where('level', '10')->first();
$stream = Stream::where('name', 'SCI')->first();

foreach (Subject::all() as $subject) {
    SubjectRule::firstOrCreate(
        [
            'grade_level_id' => $gradeLevel->id,
            'stream_id' => $stream->id,
            'subject_id' => $subject->id,
        ],
        ['max_score' => 100] // 0-100 range
    );
}
```

---

## Step 3: Create Google Sheet

### Column Headers (as shown in screenshot)

| Column | Header              | Type    | Notes                           |
|--------|---------------------|---------|--------------------------------|
| A      | ល.រ                 | Number  | Row number (optional)           |
| B      | student_code        | Text    | Student ID (required)           |
| C      | student_name        | Text    | Student name (optional)         |
| D      | gender              | Text    | Male/Female (optional)          |
| E      | dob                 | Date    | Date of birth (optional)        |
| F-Q    | [Subject names]     | Number  | **Score columns** (0-100)       |
| R      | ពិន្ទុសរុប            | Number  | Total score (auto-calculated)   |
| S      | មធ្យមភាគ             | Number  | Average (auto-calculated)       |
| T      | ចំណាត់ថ្នាក់         | Text    | Rank (auto-calculated)          |
| U      | និទ្ទេស              | Text    | Remarks (auto-calculated)       |
| V      | ផ្សេងៗ               | Text    | Other (optional)                |

### Example Sheet Headers:

```
ល.រ | student_code | student_name | gender | dob | អក្សរសាស្រខ្មែរ | គណិតវិទ្យា | រូបវិទ្យា | គីមីវិទ្យា | ... | ពិន្ទុសរុប | មធ្យមភាគ | ចំណាត់ថ្នាក់ | និទ្ទេស
```

### Sample Data:

```
1 | STU00001 | ឈុន ដាលា    | M | 2009-09-16 | 85 | 77 | 88 | 75 | ... | 1000 | 83.3 | 1 | ល្អ
2 | STU00002 | សោក ស្នេហ៍   | F | 2009-10-14 | 92 | 81 | 90 | 88 | ... | 1050 | 87.5 | 1 | ល្អ
```

### Auto-Calculate Columns:

Add formulas in sheet for totals/averages (optional, system validates anyway):

- **Total (ពិន្ទុសរុប):**  `=F2+G2+H2+I2+...+Q2`
- **Average (មធ្យមភាគ):** `=R2/11` (divide by num of subjects)

---

## Step 4: Register Sheet Source in Database

Add the Google Sheet connection via admin panel or artisan command:

### Option A: Via Artisan Command (if available)

```bash
php artisan sheet:register \
  --sheet-id="YOUR_GOOGLE_SHEET_ID" \
  --tab-name="Score12A-SCI(January)" \
  --type="score" \
  --class-id=1 \
  --term-id=1
```

### Option B: Direct Database Insert

```php
// Via tinker or admin panel
SheetSource::create([
    'created_by'      => auth()->id(),              // Current teacher/admin
    'type'            => 'score',                   // import type
    'sheet_id'        => 'SHEET_ID_FROM_URL',      // Google Sheet ID
    'tab_name'        => 'Score12A-SCI(January)',  // Tab/sheet name
    'school_class_id' => 1,                        // Class ID
    'subject_id'      => null,                     // null for wide format
    'term_id'         => 1,                        // Term ID
    'is_active'       => true,
    'column_map'      => null,                     // Auto-detect headers
]);
```

### How to Find Google Sheet ID:

```
https://docs.google.com/spreadsheets/d/1A2B3C4D5E6F7G8H9I0J/edit

                             ↓↓↓↓↓↓↓↓↓↓↓↓↓
Sheet ID = 1A2B3C4D5E6F7G8H9I0J
```

---

## Step 5: Update Sheet Source Permissions

The sheet **must be shared** with the service account email:

From [storage/app/google-sheets.json](storage/app/google-sheets.json):

```json
{
  "client_email": "google-sheets-api@...",
  ...
}
```

**Share the Google Sheet with this email:**
1. Open Facebook Sheet → Share
2. Paste the `client_email`
3. Grant **Editor** access
4. Click Share

---

## Step 6: Sync/Import Scores

### Via Web UI

Navigate to the teacher dashboard → **Sync Data** → Select sheet source → Click **Sync**

### Via Artisan Command

```bash
php artisan sheet:sync {sheetSourceId}
```

### What Happens:

1. ✅ Sheet fetched from Google Drive
2. ✅ Header parsed (student_code in col B, subjects in cols F-Q)
3. ✅ Each row:
   - Finds student by `student_code`
   - Finds enrollment in current year/class
   - For each subject column: saves score (0-100, validated against `subject_rules`)
   - Logs any errors (missing student, invalid score, etc.)
4. ✅ Database updated with scores
5. ✅ Report generated (X rows imported, Y failed)

---

## Format Validation Rules

| Rule                          | Behavior                                      |
|-------------------------------|-----------------------------------------------|
| Empty cell in subject column  | Skipped (treated as no score)                 |
| Non-numeric (e.g., "A+")      | ❌ Error logged, row skipped                  |
| Score out of range (>100)     | ❌ Error logged for that subject               |
| Missing `student_code`        | ❌ Row skipped, error logged                  |
| Student not found             | ❌ Row skipped, error logged                  |
| No enrollment (current year)  | ❌ Row skipped, error logged                  |
| No subject rule               | ❌ Subject skipped, logged                    |

---

## Error Handling & Logging

### View Import Errors

```php
// Via web:
Admin Dashboard → Import History → Click job → View Errors

// Via database:
ImportJobError::where('import_job_id', $jobId)->get();

// Fields:
- row_number: Sheet row (1-indexed)
- student_code: Student ID attempted
- error: Reason for failure
- raw_row: Original sheet data
```

### Common Errors & Fixes

| Error                                 | Fix                                              |
|---------------------------------------|--------------------------------------------------|
| "Sheet has no data rows"             | Add data rows starting row 2                    |
| "No subject columns found"           | Add subject names as headers (exact match)       |
| "Unknown subject columns: XYZ"       | Subject "XYZ" not in `subjects` table           |
| "Student not found"                  | Check student_code spelling                     |
| "Enrollment not found"               | Enroll student in this class/year first         |
| "No subject rule"                    | Add grade/stream/subject combination to rules   |
| "Score out of range (0-100)"        | Ensure score ≤ max_score in subject_rules      |

---

## Advanced: Column Mapping (for custom headers)

If you want custom headers (not standard), use `column_map`:

```php
SheetSource::find(1)->update([
    'column_map' => [
        'A' => 'student_code',    // Map custom headers
        'B' => 'student_name',
        'C' => 'gender',
        // ... etc
    ],
]);
```

---

## Two Supported Formats

### Format 1: Wide (Recommended) ✅

Multiple subjects per row:

```
student_code | Khmer | Math | Science
STU00001     |  85   |  77  |   88
STU00002     |  92   |  81  |   90
```

✅ Syncs all subjects in one row  
✅ Efficient for viewing/editing  
✅ Supported by updated importer

### Format 2: Narrow

One score per row:

```
student_code | subject   | score
STU00001     | Khmer     | 85
STU00001     | Math      | 77
STU00001     | Science   | 88
STU00002     | Khmer     | 92
```

✅ More rows, but simpler structure  
✅ Requires `subject_id` fixed in sheet_sources  
✅ Also supported

---

## Testing Import

### Create Test Data

```bash
php artisan tinker

# Create student
$student = Student::first();
$year = AcademicYear::where('is_current', 1)->first();
$class = SchoolClass::first();
$enrollment = Enrollment::create([
    'student_id' => $student->id,
    'academic_year_id' => $year->id,
    'school_class_id' => $class->id,
    'status' => 'active',
]);

# Test import
$importer = app(\App\Services\Imports\ScoreSheetImporter::class);
$rows = [
    ['student_code', 'អក្សរសាស្រខ្មែរ', 'គណិតវិទ្យា'],
    [$student->student_code, 85, 77],
];
$result = $importer->import($rows, auth()->id(), $class->id);
dd($result);
```

---

## Environment Setup

Ensure in `.env`:

```env
GOOGLE_SERVICE_ACCOUNT_JSON=storage/app/google-sheets.json
```

And service account JSON is in [storage/app/google-sheets.json](storage/app/google-sheets.json)

---

## Next Steps

1. ✅ Add subjects to database
2. ✅ Create subject rules for grades/streams
3. ✅ Create Google Sheet with headers
4. ✅ Register sheet source in system
5. ✅ Share sheet with service account
6. ✅ Run sync (test or production)
7. ✅ Check import history for errors
8. ✅ Verify scores in database

---

## Support

For issues:

1. Check [storage/logs/laravel.log](storage/logs/laravel.log) for sync errors
2. Review `import_job_errors` table
3. Verify Google account permissions
4. Ensure service account has Editor access

