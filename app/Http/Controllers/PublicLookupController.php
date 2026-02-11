<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Enrollment;
use Carbon\Carbon;

class PublicLookupController extends Controller
{
    public function show(Request $request)
    {
        $request->validate([
            'student_code' => 'required',
            'dob' => 'nullable|date'
        ]);

        // 🔍 Find student by student_code
        $student = Student::where('student_code', $request->student_code)->first();

        if (!$student) {
            return back()->with('error', 'មិនរកឃើញសិស្សនេះទេ។');
        }

        // 🔐 Optional DOB check for extra security
        if ($request->dob) {
            $dobInput = Carbon::parse($request->dob)->format('Y-m-d');

            if ($student->dob != $dobInput) {
                return back()->with('error', 'ថ្ងៃកំណើតមិនត្រឹមត្រូវទេ។');
            }
        }

        // 📚 Get student's current enrollment
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('status', 'active')
            ->with(['schoolClass', 'academicYear'])
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'សិស្សលេងមិនមាន enrollment ដែលសកម្មនោះទេ។');
        }

        // 📊 Get attendance (latest 30 days)
        $attendances = $enrollment->attendances()
            ->orderBy('attendance_date', 'desc')
            ->take(30)
            ->get();

        // 📈 Get scores by term
        $scores = $enrollment->scores()
            ->with(['subject', 'term'])
            ->orderBy('term_id', 'desc')
            ->get();

        // 📈 Calculate attendance %
        $totalDays = $attendances->count();
        $presentDays = $attendances->where('status', 'present')->count();
        $attendancePercent = $totalDays > 0
            ? round(($presentDays / $totalDays) * 100)
            : 0;

        return view('public.result', compact(
            'student',
            'enrollment',
            'attendances',
            'scores',
            'attendancePercent'
        ));
    }
}

