<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\TelegramWebhookController;
use App\Http\Controllers\Auth\OtpLoginController;
use App\Http\Controllers\Auth\PasswordLoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminResetController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AdminAcademicYearController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\ClassController as AdminClassController;
use App\Http\Controllers\Admin\SubjectController as AdminSubjectController;
use App\Http\Controllers\Admin\SubjectRuleController as AdminSubjectRuleController;
use App\Http\Controllers\Admin\ScoreController as AdminScoreController;
use App\Http\Controllers\Admin\TermController as AdminTermController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;

use App\Http\Controllers\Teacher\SheetSyncController;
use App\Http\Controllers\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Teacher\ScoreController as TeacherScoreController;

use App\Http\Controllers\Parent\ParentDashboardController;
use App\Http\Controllers\Student\StudentDashboardController;

use App\Http\Controllers\Public\PublicCheckController;
use App\Http\Controllers\design;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (No auth)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $announcements = App\Models\Announcement::where('is_published', true)
        ->orderByDesc('published_at')
        ->limit(6)
        ->get();
    return view('welcome', compact('announcements'));
})->name('home');

Route::get('/lookup', [App\Http\Controllers\PublicLookupController::class, 'show'])
    ->name('public.lookup');


// Telegram webhook (POST only)
Route::post('/telegram/webhook', [TelegramWebhookController::class, 'webhook'])
    ->name('telegram.webhook')
    ->withoutMiddleware('App\Http\Middleware\VerifyCsrfToken');

// OTP Login pages
Route::get('/login', [OtpLoginController::class, 'show'])->name('login');

Route::post('/login/send', [OtpLoginController::class, 'send'])->name('login.otp.send');
Route::post('/login/verify', [OtpLoginController::class, 'verify'])->name('login.otp.verify');

// Password Login pages
Route::get('/login/password', function () {
    return view('auth.login-pass');
})->name('login.password.show');
Route::post('/login/password', [PasswordLoginController::class, 'login'])->name('login.password');

// Logout
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
//Loginoption
Route::get('/login-option', function () {
    return view('auth.login-option');
})->name('login.option');
// OPTIONAL: public check by student_code (if school allows)
//ute::get('/check', [PublicCheckController::class, 'show'])->name('public.check');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // One dashboard route that redirects by role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Reset password / PIN
        Route::get('/reset', [AdminResetController::class, 'show'])->name('reset.show');
        Route::post('/reset-password', [AdminResetController::class, 'resetPassword'])->name('reset.password');
        Route::post('/reset-pin', [AdminResetController::class, 'resetPin'])->name('reset.pin');

        // Academic Years CRUD
        Route::resource('academic-years', AdminAcademicYearController::class);

        // Teachers CRUD
        Route::resource('teachers', AdminTeacherController::class)->except(['show']);

        // Students CRUD
        Route::resource('students', AdminStudentController::class)->except(['show']);

        // Classes CRUD
        Route::resource('classes', AdminClassController::class)->except(['show']);
        Route::post('classes/{class}/assignments', [AdminClassController::class, 'storeAssignment'])
            ->name('classes.assignments.store');
        Route::delete('classes/assignments/{assignment}', [AdminClassController::class, 'destroyAssignment'])
            ->name('classes.assignments.destroy');

        // Subjects CRUD
        Route::resource('subjects', AdminSubjectController::class)->except(['show']);

        // Subject Rules CRUD
        Route::resource('subject-rules', AdminSubjectRuleController::class)->except(['show']);

        // Terms CRUD
        Route::resource('terms', AdminTermController::class)->except(['show']);

        // Announcements CRUD
        Route::resource('announcements', AdminAnnouncementController::class)->except(['show']);

        // Scores Management
        Route::get('scores', [AdminScoreController::class, 'index'])->name('scores.index');
        Route::post('scores/batch-store', [AdminScoreController::class, 'batchStore'])->name('scores.batch-store');
        Route::post('scores', [AdminScoreController::class, 'store'])->name('scores.store');
        Route::put('scores/{score}', [AdminScoreController::class, 'update'])->name('scores.update');
        Route::delete('scores/{score}', [AdminScoreController::class, 'destroy'])->name('scores.destroy');

        // Sheet Sources (admin only)
        Route::resource('sheet-sources', \App\Http\Controllers\Teacher\SheetSourceController::class)->except(['show']);
        Route::post('sheet-sources/{sheetSource}/sync', [SheetSyncController::class, 'sync'])
            ->name('sheet.sync');

        // Users CRUD (optional)
        // Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        // Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        // Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        // Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        // Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        // Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

        // // Link parent -> student
        // Route::post('/parents/{user}/link-student', [UserManagementController::class, 'linkStudent'])
        //     ->name('parents.linkStudent');
    });

    /*
    |--------------------------------------------------------------------------
    | TEACHER
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function () {
        // Manual Attendance
        // Route::get('/attendance', [TeacherAttendanceController::class, 'index'])->name('attendance.index');
        // Route::post('/attendance', [TeacherAttendanceController::class, 'store'])->name('attendance.store');
        // Route::put('/attendance/{attendance}', [TeacherAttendanceController::class, 'update'])->name('attendance.update');
        // Route::delete('/attendance/{attendance}', [TeacherAttendanceController::class, 'destroy'])->name('attendance.destroy');

        // Manual Scores
        // Route::get('/scores', [TeacherScoreController::class, 'index'])->name('scores.index');
        // Route::post('/scores', [TeacherScoreController::class, 'store'])->name('scores.store');
        // Route::put('/scores/{score}', [TeacherScoreController::class, 'update'])->name('scores.update');
        // Route::delete('/scores/{score}', [TeacherScoreController::class, 'destroy'])->name('scores.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | PARENT
    |--------------------------------------------------------------------------
    */
    // Route::middleware('role:parent')->prefix('parent')->name('parent.')->group(function () {

    //     Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('dashboard');

    //     Route::get('/children', [ParentDashboardController::class, 'children'])->name('children');
    //     Route::get('/children/{student}/attendance', [ParentDashboardController::class, 'attendance'])->name('children.attendance');
    //     Route::get('/children/{student}/scores', [ParentDashboardController::class, 'scores'])->name('children.scores');
    // });

    /*
    |--------------------------------------------------------------------------
    | STUDENT
    |--------------------------------------------------------------------------
    */
    // Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {

    //     Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    //     Route::get('/attendance', [StudentDashboardController::class, 'attendance'])->name('attendance');
    //     Route::get('/scores', [StudentDashboardController::class, 'scores'])->name('scores');
    // });
});
