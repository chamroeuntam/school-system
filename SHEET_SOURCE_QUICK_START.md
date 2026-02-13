# Quick Start: Testing Sheet Source Dashboard

## 🚀 Quick Setup (5 minutes)

### Step 1: Access Dashboard (as Teacher)

1. Login as a teacher user
2. Go to: `/teacher/sheet-sources`
3. You should see:
   - "Register New Sheet" button
   - Empty state message if first time

### Step 2: Register a Test Sheet

1. Click **"Register New Sheet"**
2. Fill form:
   ```
   Type: Scores (Wide Format)
   Sheet ID: [Get from step 3]
   Tab Name: TestScores
   Class: [Select any available class]
   Subject: (Leave empty)
   Term: (Leave empty)
   ```
3. Click **"Register Sheet Source"**
4. System shows:
   - ✅ Success message
   - 🔑 Instructions to share sheet

### Step 3: Create Test Google Sheet

1. Create new Google Sheet: https://docs.google.com/spreadsheets/new

2. Rename sheet tab to: `TestScores`

3. Add headers (row 1):
   ```
   A | ល.រ (or just: no)
   B | student_code
   C | student_name
   D | gender
   E | dob
   F | អក្សរសាស្រខ្មែរ (or any subject name)
   G | គណិតវិទ្យា (or any subject name)
   H | ពិន្ទុសរុប (optional, for totals)
   ```

4. Add sample data (row 2+):
   ```
   1 | STU00001 | Student Name 1 | M | 2010-01-15 | 85 | 90
   2 | STU00002 | Student Name 2 | F | 2010-02-20 | 78 | 88
   ```

5. Copy Sheet ID from URL:
   - URL: `https://docs.google.com/spreadsheets/d/[SHEET_ID]/edit`
   - Get your `SHEET_ID`

### Step 4: Share Sheet with Service Account

1. Find service account email in admin panel or `.env`
   - File: `storage/app/google-sheets.json`
   - Look for: `"client_email": "..."`

2. Open your Google Sheet
3. Click **Share** (top right)
4. Add service account email with **Editor** permission
5. Save

### Step 5: Test Sync

1. Go back to dashboard: `/teacher/sheet-sources`
2. Your sheet source should appear with:
   - Sheet ID: `[SHEET_ID]`
   - Tab: `TestScores`
   - Type: `Scores`
   - Last Synced: `Never`
3. Click **Sync** button
4. System will:
   - Fetch data from Google Sheet
   - Import scores to database
   - Show results

## ✅ Expected Results

### Success Case

```
Sync done: 2 rows imported, 0 failed.
```

Students `STU00001` and `STU00002` will have scores in your database.

### Error Cases

| Error | Reason | Fix |
|-------|--------|-----|
| "Sheet has no data rows" | No data in sheet | Add data rows starting row 2 |
| "Unknown subject columns: XYZ" | Subject not in database | Add subject "XYZ" to subjects table |
| "Student not found" | Student code doesn't match | Use existing student code from database |
| "Enrollment not found" | Student not enrolled in class | Enroll student in selected class |
| "No subject rule" | No grade/stream/subject rule | Add subject rules for the class |
| "Score out of range (0-100)" | Score validation failed | Use 0-100 range |

## 🧪 Test Scenarios

### Scenario 1: Basic Import (Happy Path)

**Setup:**
- Sheet with 2 students
- Both students enrolled in class
- Valid scores 0-100

**Expected:** ✅ 2 rows imported, 0 failed

### Scenario 2: Missing Student

**Setup:**
- Sheet with invalid student code

**Expected:** ❌ 0 rows imported, 1 failed
- Error: "Student not found"

### Scenario 3: Score Out of Range

**Setup:**
- Valid student, score > subject max

**Expected:** ⚠️ Row partially failed
- Error: "Score out of range"

### Scenario 4: Multiple Subjects

**Setup:**
- Sheet with student code + 5 subject columns

**Expected:** ✅ All subjects imported for each student

## 🔧 Debugging

### Check Sync History

```bash
# Via database
SELECT * FROM import_jobs WHERE type='score' ORDER BY created_at DESC;

# View errors for last job
SELECT * FROM import_job_errors 
WHERE import_job_id = [LAST_JOB_ID];
```

### Check Logs

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Filter for sync
grep "SYNC\|IMPORT" storage/logs/laravel.log
```

### Test via Artisan

```bash
# Test import directly
php artisan tinker

$importer = app(\App\Services\Imports\ScoreSheetImporter::class);
$rows = [
  ['student_code', 'Math'],
  ['STU00001', 85],
];
$result = $importer->import($rows, auth()->id(), 1);
dd($result);
```

## 📋 Dashboard Features Checklist

After setup, verify all features work:

- [ ] View sheet sources list
- [ ] Register new sheet source
- [ ] Edit sheet source settings
- [ ] Delete sheet source
- [ ] Sync sheet (one-click)
- [ ] View sync results
- [ ] See error messages for failures
- [ ] Last synced timestamp updates
- [ ] Multiple sheet sources work
- [ ] Can't access other teacher's sheets

## 🎓 Full Workflow

```
1. Teacher logs in
   ↓
2. Go to /teacher/sheet-sources
   ↓
3. Click "Register New Sheet"
   ↓
4. Fill form with sheet info
   ↓
5. Submit form
   ↓
6. Get success message with instructions
   ↓
7. Share Google Sheet with service account
   ↓
8. Click "Sync" button
   ↓
9. Get import results:
   - ✅ X rows imported
   - ❌ Y rows failed
   ↓
10. Check dashboard for:
    - Last synced time
    - Edit/delete options
    - Re-sync capability
```

## 📞 Support

### If Dashboard Won't Load

```bash
# Check routes
php artisan route:list | grep sheet

# Clear route cache
php artisan route:clear

# Verify views exist
ls -la resources/views/teacher/sheet-sources/
```

### If Authorization Fails

```bash
# Check user role
SELECT roles.name FROM users 
JOIN roles ON users.role_id = roles.id 
WHERE users.id = YOUR_USER_ID;

# Should return: "teacher"
```

### If Sync Fails

1. Check sheet is shared with service account
2. Verify sheet tab name matches exactly
3. Check students exist in database
4. Check subject rules exist
5. Review logs: `storage/logs/laravel.log`

## ✨ Next: Production Setup

Once testing passes:

1. Create real Google Sheets for each term
2. Register sheet sources in system
3. Train teachers on dashboard usage
4. Set up sync schedule (optional: artisan schedule)
5. Monitor import history

---

**Ready to test?** Start with Step 1! 🚀
