<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeLevel;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\SubjectRule;
use Illuminate\Http\Request;

class SubjectRuleController extends Controller
{
    public function index()
    {
        $rules = SubjectRule::with(['gradeLevel', 'stream', 'subject'])
            ->orderBy('grade_level_id')
            ->orderBy('stream_id')
            ->orderBy('subject_id')
            ->get();

        $grades = GradeLevel::orderBy('name')->get();
        $streams = Stream::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.subject-rules.index', compact('rules', 'grades', 'streams', 'subjects'));
    }

    public function create()
    {
        return redirect()->route('admin.subject-rules.index', ['create' => 1]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'grade_level_id' => ['required', 'integer', 'exists:grade_levels,id'],
            'stream_id' => ['nullable', 'integer', 'exists:streams,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'max_score' => ['required', 'numeric', 'min:0'],
        ]);

        $streamId = $data['stream_id'] ?? null;
        $exists = SubjectRule::where('grade_level_id', $data['grade_level_id'])
            ->where('subject_id', $data['subject_id'])
            ->when($streamId === null, function ($q) {
                $q->whereNull('stream_id');
            }, function ($q) use ($streamId) {
                $q->where('stream_id', $streamId);
            })
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['subject_id' => 'Rule already exists for this grade/stream/subject.']);
        }

        SubjectRule::create([
            'grade_level_id' => $data['grade_level_id'],
            'stream_id' => $streamId,
            'subject_id' => $data['subject_id'],
            'max_score' => $data['max_score'],
        ]);

        return redirect()
            ->route('admin.subject-rules.index')
            ->with('success', 'Subject rule created successfully.');
    }

    public function edit(SubjectRule $subjectRule)
    {
        return redirect()->route('admin.subject-rules.index');
    }

    public function update(Request $request, SubjectRule $subjectRule)
    {
        $data = $request->validate([
            'grade_level_id' => ['required', 'integer', 'exists:grade_levels,id'],
            'stream_id' => ['nullable', 'integer', 'exists:streams,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'max_score' => ['required', 'numeric', 'min:0'],
        ]);

        $streamId = $data['stream_id'] ?? null;
        $exists = SubjectRule::where('grade_level_id', $data['grade_level_id'])
            ->where('subject_id', $data['subject_id'])
            ->when($streamId === null, function ($q) {
                $q->whereNull('stream_id');
            }, function ($q) use ($streamId) {
                $q->where('stream_id', $streamId);
            })
            ->where('id', '!=', $subjectRule->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['subject_id' => 'Rule already exists for this grade/stream/subject.']);
        }

        $subjectRule->update([
            'grade_level_id' => $data['grade_level_id'],
            'stream_id' => $streamId,
            'subject_id' => $data['subject_id'],
            'max_score' => $data['max_score'],
        ]);

        return redirect()
            ->route('admin.subject-rules.index')
            ->with('success', 'Subject rule updated successfully.');
    }

    public function destroy(SubjectRule $subjectRule)
    {
        $subjectRule->delete();

        return redirect()
            ->route('admin.subject-rules.index')
            ->with('success', 'Subject rule deleted successfully.');
    }
}
