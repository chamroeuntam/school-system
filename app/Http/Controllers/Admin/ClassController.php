<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\GradeLevel;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $years = AcademicYear::orderByDesc('id')->get();
        $grades = GradeLevel::orderBy('name')->get();
        $streams = Stream::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $terms = Term::orderBy('academic_year_id')->orderBy('name')->get();

        $classes = SchoolClass::with(['academicYear','gradeLevel','stream'])
            ->orderByDesc('academic_year_id')
            ->orderBy('name')
            ->get();

        $assignmentsByClass = TeacherAssignment::with(['teacher','subject','term'])
            ->get()
            ->groupBy('school_class_id');

        return view('admin.classes.index', compact(
            'classes',
            'years',
            'grades',
            'streams',
            'teachers',
            'subjects',
            'terms',
            'assignmentsByClass'
        ));
    }

    public function create()
    {
        return redirect()->route('admin.classes.index', ['create' => 1]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'academic_year_id' => ['required','integer','exists:academic_years,id'],
            'grade_level_id' => ['required','integer','exists:grade_levels,id'],
            'stream_id' => ['nullable','integer','exists:streams,id'],
            'name' => ['required','string','max:20'],
        ]);

        $exists = SchoolClass::where('academic_year_id', $data['academic_year_id'])
            ->where('name', $data['name'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'Class name already exists in this academic year.']);
        }

        SchoolClass::create($data);

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function edit(SchoolClass $class)
    {
        return redirect()->route('admin.classes.index');
    }

    public function update(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'academic_year_id' => ['required','integer','exists:academic_years,id'],
            'grade_level_id' => ['required','integer','exists:grade_levels,id'],
            'stream_id' => ['nullable','integer','exists:streams,id'],
            'name' => ['required','string','max:20'],
        ]);

        $exists = SchoolClass::where('academic_year_id', $data['academic_year_id'])
            ->where('name', $data['name'])
            ->where('id', '!=', $class->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'Class name already exists in this academic year.']);
        }

        $class->update($data);

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Class deleted successfully.');
    }

    public function storeAssignment(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'teacher_user_id' => ['required','integer','exists:users,id'],
            'subject_id' => ['required','integer','exists:subjects,id'],
            'term_id' => ['nullable','integer','exists:terms,id'],
        ]);

        $exists = TeacherAssignment::where('teacher_user_id', $data['teacher_user_id'])
            ->where('school_class_id', $class->id)
            ->where('subject_id', $data['subject_id'])
            ->where('term_id', $data['term_id'] ?? null)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['teacher_user_id' => 'Assignment already exists for this class.']);
        }

        TeacherAssignment::create([
            'teacher_user_id' => $data['teacher_user_id'],
            'school_class_id' => $class->id,
            'subject_id' => $data['subject_id'],
            'term_id' => $data['term_id'] ?? null,
        ]);

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Teacher assigned successfully.');
    }

    public function destroyAssignment(TeacherAssignment $assignment)
    {
        $assignment->delete();

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Assignment removed successfully.');
    }
}
