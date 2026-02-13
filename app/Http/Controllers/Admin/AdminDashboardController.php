<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\SheetSource;
use App\Models\Score;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $month = now()->month;
        $year = now()->year;

        $totalStudents = Student::count();
        $studentsNewThisMonth = Student::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        $totalTeachers = User::where('role', 'teacher')->count();

        $attendanceToday = Attendance::whereDate('attendance_date', $today)
            ->with('enrollment.schoolClass')
            ->get();
        $attendanceTodayTotal = $attendanceToday->count();
        $attendanceTodayPresent = $attendanceToday->where('status', 'present')->count();
        $attendanceTodayPercent = $attendanceTodayTotal > 0
            ? round(($attendanceTodayPresent / $attendanceTodayTotal) * 100)
            : 0;

        $pendingGrades = Score::whereNull('grade_letter')->count();

        $recentAttendance = Attendance::whereDate('attendance_date', '>=', now()->subDays(30)->toDateString())
            ->get();
        $recentTotal = $recentAttendance->count();
        $recentPresent = $recentAttendance->where('status', 'present')->count();
        $avgAttendance = $recentTotal > 0
            ? round(($recentPresent / $recentTotal) * 100)
            : 0;

        $riskStudents = 0;
        $attendanceByEnrollment = Attendance::select(
                'enrollment_id',
                DB::raw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count"),
                DB::raw('COUNT(*) as total_count')
            )
            ->whereDate('attendance_date', '>=', now()->subDays(30)->toDateString())
            ->groupBy('enrollment_id')
            ->get();
        if ($attendanceByEnrollment->count() > 0) {
            $riskStudents = $attendanceByEnrollment->filter(function ($row) {
                return $row->total_count > 0 && ($row->present_count / $row->total_count) < 0.7;
            })->count();
        }

        $attendanceByClass = [];
        $groupedByClass = $attendanceToday->groupBy(function ($att) {
            return optional($att->enrollment->schoolClass)->name ?? 'Unknown';
        });
        foreach ($groupedByClass as $className => $items) {
            $present = $items->where('status', 'present')->count();
            $absent = $items->where('status', 'absent')->count();
            $attendanceByClass[] = [
                'name' => $className,
                'present' => $present,
                'absent' => $absent,
            ];
        }

        $sheetSource = SheetSource::where('is_active', true)
            ->orderByDesc('id')
            ->first();

        // Get recent announcements
        $recentAnnouncements = Announcement::where('is_published', true)
            ->orderByDesc('published_at')
            ->limit(2)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'studentsNewThisMonth',
            'totalTeachers',
            'attendanceTodayPercent',
            'pendingGrades',
            'avgAttendance',
            'riskStudents',
            'attendanceByClass',
            'sheetSource',
            'recentAnnouncements'
        ));
    }
}
