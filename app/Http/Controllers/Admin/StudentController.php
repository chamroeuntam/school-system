<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $classId = $request->query('class_id');

        $classes = SchoolClass::orderBy('name')->get();

        $studentsQuery = Student::query()
            ->leftJoin('enrollments', function ($join) {
                $join->on('students.id', '=', 'enrollments.student_id')
                    ->where('enrollments.status', 'active');
            })
            ->leftJoin('school_classes', 'enrollments.school_class_id', '=', 'school_classes.id')
            ->select('students.*', 'school_classes.id as class_id', 'school_classes.name as class_name')
            ->orderByRaw('CASE WHEN school_classes.name IS NULL THEN 1 ELSE 0 END')
            ->orderBy('school_classes.name')
            ->orderBy('students.full_name');

        if (!empty($classId)) {
            $studentsQuery->where('school_classes.id', $classId);
        }

        $students = $studentsQuery->get();

        return view('admin.students.index', compact('students', 'classes', 'classId'));
    }

    public function create()
    {
        return redirect()->route('admin.students.index', ['create' => 1]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_code' => ['required','string','max:50','unique:students,student_code'],
            'full_name' => ['required','string','max:150'],
            'gender' => ['nullable', Rule::in(['M','F','O'])],
            'dob' => ['nullable','date'],
            'is_active' => ['nullable','boolean'],
            'class_id' => ['nullable','integer','exists:school_classes,id'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $classId = $data['class_id'] ?? null;
        unset($data['class_id']);

        $student = Student::create($data);

        if ($classId) {
            $class = SchoolClass::find($classId);
            if ($class) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $class->academic_year_id,
                    'school_class_id' => $class->id,
                    'status' => 'active',
                ]);
            }
        }

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        return redirect()->route('admin.students.index');
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'student_code' => ['required','string','max:50', Rule::unique('students', 'student_code')->ignore($student->id)],
            'full_name' => ['required','string','max:150'],
            'gender' => ['nullable', Rule::in(['M','F','O'])],
            'dob' => ['nullable','date'],
            'is_active' => ['nullable','boolean'],
            'class_id' => ['nullable','integer','exists:school_classes,id'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $classId = $data['class_id'] ?? null;
        unset($data['class_id']);

        $student->update($data);

        if ($classId) {
            $class = SchoolClass::find($classId);
            if ($class) {
                $enrollment = Enrollment::where('student_id', $student->id)
                    ->where('status', 'active')
                    ->first();

                if ($enrollment) {
                    $enrollment->update([
                        'academic_year_id' => $class->academic_year_id,
                        'school_class_id' => $class->id,
                    ]);
                } else {
                    Enrollment::create([
                        'student_id' => $student->id,
                        'academic_year_id' => $class->academic_year_id,
                        'school_class_id' => $class->id,
                        'status' => 'active',
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
