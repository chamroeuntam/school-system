# System Architecture Diagram

## High-Level Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                          TEACHER DASHBOARD                       │
│                   /teacher/sheet-sources (Blade)                 │
│                                                                  │
│  [Register]  [List]  [Edit]  [Delete]  [Sync]                   │
└────────────────────────────┬────────────────────────────────────┘
                             │
             ┌───────────────┼───────────────┐
             ▼               ▼               ▼
        Handler          Validation       Policy
    SheetSourceCtlr    (server-side)  SheetSourcePolicy
             │               │               │
             └───────────────┼───────────────┘
                             ▼
                    Database Update
                  sheet_sources table
                             │
                ┌────────────┴────────────┐
                ▼                         ▼
        When Sync Clicked        Next Time View Opens
            │                              │
            ▼                              ▼
     SheetSyncController           UI Updates
            │                     (Last synced at)
            ├─ Fetch SheetSource config
            ├─ Read Google Sheet (API call)
            ├─ Pass to ScoreSheetImporter
            │
            ▼
    ScoreSheetImporter
            │
            ├─ Parse headers
            ├─ Auto-detect subjects
            ├─ Validate against DB
            │  ├─ Find students
            │  ├─ Find enrollments
            │  ├─ Check subject rules
            │  └─ Validate scores
            │
            ├─ Database Transactions
            │  ├─ Save Score records
            │  ├─ Create ImportJob
            │  └─ Log ImportJobErrors
            │
            └─ Return Results (ok/failed count)
                             │
                             ▼
                    Show Results on Dashboard
                      ✅ X imported, ❌ Y failed
```

## Component Interactions

```
┌──────────────────────┐
│   Browser (Teacher)  │
│  /teacher/sheet-    │
│   sources           │
└──────────┬───────────┘
           │ Request
           ▼
┌────────────────────────────────────────┐
│     Laravel Routes (web.php)            │
│  GET /teacher/sheet-sources             │
│  GET /teacher/sheet-sources/create      │
│  POST /teacher/sheet-sources            │
│  GET /teacher/sheet-sources/{id}/edit   │
│  PUT /teacher/sheet-sources/{id}        │
│  DELETE /teacher/sheet-sources/{id}     │
│  POST /teacher/sheet-sources/{id}/sync  │
└────────────┬───────────────────────────┘
             │ Route to Controller
             ▼
┌────────────────────────────────────────┐
│  SheetSourceController                  │
│  ├─ index()      → List sheets           │
│  ├─ create()     → Form view             │
│  ├─ store()      → Validate & save       │
│  ├─ edit()       → Edit form             │
│  ├─ update()     → Validate & update     │
│  └─ destroy()    → Delete                │
└────────┬──────────────────────┬─────────┘
         │                      │
         ▼                      ▼
    Check Policy          Validate Input
    (SheetSource         (Server-Side)
     Policy)                 │
         │                   ▼
         ├──────────────────→ Store SheetSource
                             in Database
                                 │
                                 ▼
                    ┌────────────────────────┐
                    │   SYNC Handler         │
                    │ SheetSyncController    │
                    └────────┬───────────────┘
                             │
                             ▼
                    ┌────────────────────────┐
                    │  GoogleSheetsClient    │
                    │  readTab()             │
                    └────────┬───────────────┘
                             │
                    Google Sheets API Call
                             │
                             ▼
                    ┌────────────────────────┐
                    │  Google Sheets         │
                    │  (External)            │
                    └────────┬───────────────┘
                             │
                    Sheet Data (JSON)
                             │
                             ▼
                    ┌────────────────────────┐
                    │ ScoreSheetImporter     │
                    │ import($rows)          │
                    └────────┬───────────────┘
                             │
        ┌────────────────────┼────────────────────┐
        │                    │                    │
        ▼                    ▼                    ▼
    Parse           Detect Subjects      Validate Data
    Headers         (From Column Names)  (From Database)
        │                    │                    │
        └────────────────────┼────────────────────┘
                             │
                    Process Each Row
                             │
        ┌────────────────────┼────────────────────┐
        │                    │                    │
        ▼                    ▼                    ▼
    Find Student    Find Enrollment    Check Subject
    by code         in class/year       Rules (max_score)
        │                    │                    │
        └────────────────────┼────────────────────┘
                             │
                    For Each Subject Column
                             │
        ┌────────────────────┼────────────────────┐
        │                    │                    │
        ▼                    ▼                    ▼
    Extract Score   Validate Range    Save to Database
    From Cell       (0 to max_score)   Score Table
        │                    │                    │
        └────────────────────┼────────────────────┘
                             │
                    Log Errors (if any)
                             │
                    ImportJobError Table
                             │
                             ▼
                    Return Results
                    [ok:30, failed:2]
                             │
                             ▼
                    Update Dashboard UI
                    (Last synced time)
                             │
                             ▼
                    Show Toast Message
                    "Sync done: 30 ok, 2 failed"
```

## Database Relationships

```
┌─────────────────┐
│ users           │
├─────────────────┤
│ id (PK)         │
│ name            │
│ email           │ 1──┐
└─────────────────┘    │
                       │ created_by (FK)
                       │
┌──────────────────────────┐
│ sheet_sources            │
├──────────────────────────┤
│ id (PK)                  │
│ created_by (FK) ◄────────┘
│ type (score|attendance)  │
│ sheet_id                 │
│ tab_name                 │
│ school_class_id (FK) ────┐
│ subject_id (FK, null) ────┤─────┐
│ term_id (FK, null) ───────┤─┐   │
│ is_active                │   │ │
│ last_synced_at          │   │ │
└──────────────────────────┘   │ │
                               │ │
                ┌──────────────┤ │
                │              │ │
        ┌───────────────┐      │ │
        │ school_classes│ ◄─────┘ │
        ├───────────────┤         │
        │ id (PK)       │         │
        │ name          │         │
        └───────────────┘         │
                                  │
                      ┌───────────────┐
                      │ subjects      │
                      ├───────────────┤
                      │ id (PK)       │
                      │ name          │ ◄────┘
                      └───────────────┘
                                  │
                      ┌───────────────┐
                      │ terms         │
                      ├───────────────┤
                      │ id (PK)       │
                      │ name          │
                      └───────────────┘
```

## Authorization Flow

```
Teacher A logs in
       │
       ▼
Visit /teacher/sheet-sources
       │
       ├─ List Query: WHERE created_by = 5
       │  (shows only Teacher A's sheets)
       │
Teacher clicks Edit on sheet_sources.id=10
       │
       ├─ Controller calls:
       │  $this->authorize('update', $sheetSource)
       │
       ├─ SheetSourcePolicy::update($user, $sheetSource) runs
       │  └─ Check: $user->id === $sheetSource->created_by
       │     (5 === 5? YES → Allow)
       │
       ▼
    Edit Form Loaded ✅

Teacher A tries to edit Teacher B's sheet (sheet_sources.id=11, created_by=6)
       │
       ├─ Controller calls:
       │  $this->authorize('update', $sheetSource)
       │
       ├─ SheetSourcePolicy::update($user, $sheetSource) runs
       │  └─ Check: $user->id === $sheetSource->created_by
       │     (5 === 6? NO → Deny)
       │
       ▼
    403 Forbidden ❌
```

## File Structure

```
app/
├── Http/
│   └── Controllers/
│       ├── Teacher/
│       │   ├── SheetSourceController.php ← CRUD operations
│       │   └── SheetSyncController.php (already exists)
│       └── Controller.php (updated with traits)
├── Policies/
│   └── SheetSourcePolicy.php ← Authorization
├── Providers/
│   └── AppServiceProvider.php (updated)
├── Services/
│   ├── GoogleSheetsClient.php (updated)
│   └── Imports/
│       └── ScoreSheetImporter.php (updated)
└── Models/
    └── SheetSource.php (already exists)

resources/
└── views/
    └── teacher/
        └── sheet-sources/
            ├── index.blade.php ← List sheets
            ├── create.blade.php ← Register sheet
            └── edit.blade.php ← Edit sheet

routes/
└── web.php (updated with teacher routes)

storage/
└── app/
    └── google-sheets.json ← Service account credentials
```

## Data Flow Example: Wide Format Import

```
Google Sheet:
┌──────────────┬──────────┬──────┬─────────┐
│student_code  │Khmer     │Math  │Science  │
├──────────────┼──────────┼──────┼─────────┤
│STU00001      │85        │90    │88       │
│STU00002      │78        │92    │85       │
└──────────────┴──────────┴──────┴─────────┘
        │
        │ Fetch via API
        ▼
Importer Receives:
[
  ['student_code', 'Khmer', 'Math', 'Science'],
  ['STU00001', 85, 90, 88],
  ['STU00002', 78, 92, 85],
]
        │
        │ Parse headers → [student_code, khmer, math, science]
        │ Detect subjects: {2: khmer_id, 3: math_id, 4: science_id}
        │
        ▼
For each data row:
  Find student: STU00001
  Find enrollment: 12A-SCI / 2026
  For column 2 (Khmer, value 85):
    Check rule: max_score=100 ✓
    Save: Score{enrollment_id, subject_id=khmer_id, score=85}
  For column 3 (Math, value 90):
    Check rule: max_score=100 ✓
    Save: Score{enrollment_id, subject_id=math_id, score=90}
  For column 4 (Science, value 88):
    Check rule: max_score=100 ✓
    Save: Score{enrollment_id, subject_id=science_id, score=88}
        │
        ▼
Database Results:
Scores table now has 6 rows:
  STU00001 → Khmer: 85, Math: 90, Science: 88
  STU00002 → Khmer: 78, Math: 92, Science: 85
```

---

**Visual Reference Complete** ✅

Use these diagrams to understand:
- Component interactions
- Database relationships  
- Authorization flow
- Data flow through the system
