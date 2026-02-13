<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SheetSource;
use App\Models\ImportJob;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SheetSourceController extends Controller
{
    public function index()
    {
        $sheetSources = SheetSource::where('created_by', auth()->id())
            ->with(['schoolClass', 'subject', 'term', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        $syncJob = null;
        $syncJobId = session('sync_job_id');
        if ($syncJobId) {
            $syncJob = ImportJob::with(['errors', 'sheetSource'])->find($syncJobId);
        }

        return view('teacher.sheet-sources.index', compact('sheetSources', 'syncJob'));
    }

    public function create()
    {
        $classes = SchoolClass::with('gradeLevel', 'stream')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $terms = Term::orderBy('start_date')->get();

        return view('teacher.sheet-sources.create', compact('classes', 'subjects', 'terms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in('score', 'attendance')],
            'sheet_id' => ['required', 'string', 'max:255'],
            'tab_name' => ['required', 'string', 'max:255'],
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'term_id' => ['nullable', 'exists:terms,id'],
        ]);

        $data['created_by'] = auth()->id();
        $data['is_active'] = true;

        SheetSource::create($data);

        return redirect()
            ->route('admin.sheet-sources.index')
            ->with('success', 'Sheet source registered successfully. Share the sheet with the service account email and start syncing!');
    }

    public function edit(SheetSource $sheetSource)
    {
        $this->authorize('update', $sheetSource);

        $classes = SchoolClass::with('gradeLevel', 'stream')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $terms = Term::orderBy('start_date')->get();

        return view('teacher.sheet-sources.edit', compact('sheetSource', 'classes', 'subjects', 'terms'));
    }

    public function update(Request $request, SheetSource $sheetSource)
    {
        $this->authorize('update', $sheetSource);

        $data = $request->validate([
            'type' => ['required', Rule::in('score', 'attendance')],
            'sheet_id' => ['required', 'string', 'max:255'],
            'tab_name' => ['required', 'string', 'max:255'],
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'term_id' => ['nullable', 'exists:terms,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool)($request->input('is_active') ?? false);

        $sheetSource->update($data);

        return redirect()
            ->route('admin.sheet-sources.index')
            ->with('success', 'Sheet source updated successfully.');
    }

    public function destroy(SheetSource $sheetSource)
    {
        $this->authorize('delete', $sheetSource);

        $sheetSource->delete();

        return redirect()
            ->route('admin.sheet-sources.index')
            ->with('success', 'Sheet source deleted successfully.');
    }
}
