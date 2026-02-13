# Google Sheet Source Dashboard Implementation

Complete dashboard for teachers to register and manage Google Sheets for score/attendance imports.

## 📁 Files Created

### Backend

1. **[app/Http/Controllers/Teacher/SheetSourceController.php](app/Http/Controllers/Teacher/SheetSourceController.php)**
   - REST API controller for CRUD operations
   - Methods: `index()`, `create()`, `store()`, `edit()`, `update()`, `destroy()`
   - Authorization via policy (only creator can edit/delete)

2. **[app/Policies/SheetSourcePolicy.php](app/Policies/SheetSourcePolicy.php)**
   - Ensures teachers can only manage their own sheet sources
   - Methods: `update()`, `delete()`

3. **[app/Providers/AppServiceProvider.php](app/Providers/AppServiceProvider.php)** (Updated)
   - Registered `SheetSourcePolicy` for authorization

### Routes

**[routes/web.php](routes/web.php)** (Updated)

Teacher routes group (requires `role:teacher` middleware):

```php
Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function () {
    // Sheet Sources CRUD
    Route::resource('sheet-sources', SheetSourceController::class)->except(['show']);
    
    // Sync endpoint (already existed)
    Route::post('/sheet-sources/{sheetSource}/sync', [SheetSyncController::class, 'sync'])->name('sheet.sync');
    
    // Other routes...
});
```

**Route Names:**
| Route | Name | Method |
|-------|------|--------|
| `/admin/sheet-sources` | `admin.sheet-sources.index` | GET |
| `/admin/sheet-sources/create` | `admin.sheet-sources.create` | GET |
| `/admin/sheet-sources` | `admin.sheet-sources.store` | POST |
| `/admin/sheet-sources/{id}/edit` | `admin.sheet-sources.edit` | GET |
| `/admin/sheet-sources/{id}` | `admin.sheet-sources.update` | PUT |
| `/admin/sheet-sources/{id}` | `admin.sheet-sources.destroy` | DELETE |
| `/admin/sheet-sources/{id}/sync` | `admin.sheet.sync` | POST |

### Views

1. **[resources/views/teacher/sheet-sources/index.blade.php](resources/views/teacher/sheet-sources/index.blade.php)**
   - Lists all sheet sources created by current teacher
   - Shows: Sheet ID, Tab Name, Type, Class, Subject/Term, Status, Last Sync
   - Actions: Sync, Edit, Delete
   - Help section with quick setup guide

2. **[resources/views/teacher/sheet-sources/create.blade.php](resources/views/teacher/sheet-sources/create.blade.php)**
   - Form to register new sheet source
   - Fields:
     - Import Type (Score / Attendance)
     - Sheet ID (from Google Sheet URL)
     - Tab Name (exact sheet tab name)
     - Class (required)
     - Subject (optional, for fixed subject imports)
     - Term (optional)
   - Input validation with error messages
   - Instructions for setup

3. **[resources/views/teacher/sheet-sources/edit.blade.php](resources/views/teacher/sheet-sources/edit.blade.php)**
   - Edit existing sheet source
   - Same fields as create
   - Additional: Active/Inactive toggle
   - Shows: Creator, Created date, Last sync date

## 🚀 Features

### Dashboard Features

✅ **List Sheet Sources**
- Shows all sheets registered by current teacher
- Displays sync status and history
- Copy button for Sheet ID

✅ **Register New Sheet**
- Simple form to connect Google Sheet
- Validates all required fields
- Auto-detects subjects from headers (wide format)
- Supports fixed subject imports (narrow format)

✅ **Edit Sheet Source**
- Update sheet ID, tab name, class mapping
- Toggle active/inactive status
- Shows creation and sync metadata

✅ **Delete Sheet Source**
- Remove sheet source from system
- Prevents accidental sync on deleted sources

✅ **Sync Action**
- One-click sync button from dashboard
- Confirmation before sync
- Imports scores/attendance to database

### Form Validation

All fields validated server-side:

```php
'type' => ['required', Rule::in('score', 'attendance')],
'sheet_id' => ['required', 'string', 'max:255'],
'tab_name' => ['required', 'string', 'max:255'],
'school_class_id' => ['required', 'exists:school_classes,id'],
'subject_id' => ['nullable', 'exists:subjects,id'],
'term_id' => ['nullable', 'exists:terms,id'],
'is_active' => ['nullable', 'boolean'],
```

### Authorization

Teachers can only:
- View their own sheet sources
- Edit their own sheet sources
- Delete their own sheet sources

Policy enforced via Laravel's authorization system.

## 🔧 How It Works

### Registration Flow

1. **Teacher clicks** "Register New Sheet"
2. **Fills form** with:
   - Google Sheet ID (from URL)
   - Sheet tab name
   - Class to import to
   - Optional subject/term filters
3. **Submits form** → Server validates → Store in database
4. **System shows** confirmation with instructions to share Sheet with service account
5. **Teacher shares** Sheet with service account email
6. **Dashboard shows** new sheet source, ready to sync

### Sync Flow

1. **Teacher clicks** "Sync" button on sheet source
2. **System fetches** data from Google Sheet (via GoogleSheetsClient)
3. **Importer processes** rows:
   - Finds student by code
   - Matches to class enrollment
   - Validates scores
   - Saves to database
4. **System logs** results:
   - ✅ Imported count
   - ❌ Failed count
   - 📋 Detailed errors for each failed row
5. **Dashboard updates** "Last Synced" timestamp
6. **Teacher sees** success/error message

## 📊 Database Relations

### SheetSource Model

```php
protected $fillable = [
    'created_by',      // User who created
    'type',            // 'score' or 'attendance'
    'sheet_id',        // Google Sheet ID
    'tab_name',        // Sheet tab name
    'school_class_id', // Class to import to
    'subject_id',      // Optional: Fixed subject for narrow format
    'term_id',         // Optional: Specific term
    'column_map',      // Reserved for custom column mapping
    'is_active',       // Enable/disable syncing
    'last_synced_at',  // Timestamp of last sync
];

protected $casts = [
    'column_map' => 'array',
    'last_synced_at' => 'datetime',
];
```

### Relations

```php
public function creator(): BelongsTo
public function schoolClass(): BelongsTo
public function subject(): BelongsTo
public function term(): BelongsTo
```

## 👤 Access Control

### Teacher Routes

All routes require:
1. User is authenticated (`middleware('auth')`)
2. User has 'teacher' role (`middleware('role:teacher')`)
3. Edit/Delete require authorization (`$this->authorize()` in controller)

### Teacher Can:
- View their own sheet sources list
- Create new sheet sources
- Edit/update their own sheet sources
- Delete their own sheet sources
- Trigger sync for their own sheet sources

### Teacher Cannot:
- View/edit/delete other teachers' sheet sources
- Sync another teacher's sheet sources

## 🎨 UI Components

All views use **DaisyUI** (Tailwind CSS component library):

- `card` - Main containers
- `form-control` - Form elements
- `select`, `input` - Form inputs
- `btn` - Buttons (primary, outline, success, error, etc.)
- `alert` - Messages and alerts
- `table` - Data table with borders
- `badge` - Status indicators
- `modal` - Can add for confirmations

## 📝 Setup Instructions

### 1. Database Already Has SheetSource Table

The `SheetSource` model and migration already exist. No database changes needed.

### 2. Verify Imports in Controller

Make sure these are imported in `SheetSourceController.php`:

```php
use App\Models\SheetSource;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Term;
```

### 3. Test Routes

```bash
# List sheet sources
GET /teacher/sheet-sources

# Create new sheet
GET /teacher/sheet-sources/create

# Store new sheet
POST /teacher/sheet-sources

# Edit sheet
GET /teacher/sheet-sources/{id}/edit

# Update sheet
PUT /teacher/sheet-sources/{id}

# Delete sheet
DELETE /teacher/sheet-sources/{id}
```

### 4. Test Authorization

```bash
# Teacher A tries to edit Teacher B's sheet
# Should get 403 Forbidden error
```

## 🐛 Troubleshooting

### "Route not found"
- Verify routes are added to `routes/web.php`
- Check Route cache: `php artisan route:clear`

### "Unauthorized" on edit/delete
- Check user `role_id` in users table
- Verify SheetSource `created_by` matches current user
- Check `SheetSourcePolicy` is registered in `AppServiceProvider`

### "Methods not found"
- Verify controller methods exist
- Check namespace matches: `App\Http\Controllers\Teacher\SheetSourceController`

### Views not loading
- Verify view paths:
  - `resources/views/teacher/sheet-sources/index.blade.php`
  - `resources/views/teacher/sheet-sources/create.blade.php`
  - `resources/views/teacher/sheet-sources/edit.blade.php`
- Check layout: `resources/views/layouts/app.blade.php` exists

## 🔗 Integration with Importer

The dashboard connects to the existing import system:

1. **Controller** stores sheet source config in database
2. **SheetSyncController** reads config from SheetSource
3. **GoogleSheetsClient** fetches data from Google Sheet
4. **ScoreSheetImporter** processes rows (updated to handle wide format)
5. **Results stored** in database
6. **Errors logged** in `ImportJobError` table

See [SCORE_IMPORT_SETUP_GUIDE.md](SCORE_IMPORT_SETUP_GUIDE.md) for full import guide.

## 📚 Example Usage

### Register a Score Import Sheet

```
1. Navigate: /teacher/sheet-sources
2. Click: "Register New Sheet"
3. Fill:
   - Type: "Scores (Wide Format)"
   - Sheet ID: "1A2B3C4D5E6F7G8H9I0J"
   - Tab Name: "Score12A-SCI(January)"
   - Class: "12A-SCI"
   - Subject: Leave empty (auto-detect)
   - Term: "January"
4. Submit
5. Share sheet with service account
6. Click "Sync" button
```

### Import Results

```
Success: 30 scores imported
Failed: 2 rows
Errors:
- Row 5: Student not found (STU99999)
- Row 12: Score out of range for Math (120 > 100)
```

## ✅ Checklist for Completion

- [x] SheetSourceController created with full CRUD
- [x] Routes added for teacher sheet-sources
- [x] Views created (index, create, edit)
- [x] Policy created for authorization
- [x] Policy registered in AppServiceProvider
- [x] Form validation implemented
- [x] Authorization checks in controller
- [x] Error handling
- [x] User feedback (success/error messages)

---

**Status:** ✅ Complete and Ready to Test

Test by visiting: `/teacher/sheet-sources` while logged in as a teacher
