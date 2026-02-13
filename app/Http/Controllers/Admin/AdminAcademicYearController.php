<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AdminAcademicYearController extends Controller
{
    public function index()
    {
        $years = AcademicYear::orderByDesc('id')->paginate(10);
        return view('admin.academic-years', compact('years'));
    }

    public function create()
    {
        return view('admin.academic-years-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:academic_years,name',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_current' => 'boolean',
        ]);

        if ($validated['is_current'] ?? false) {
            AcademicYear::where('is_current', 1)->update(['is_current' => 0]);
        }

        $year = AcademicYear::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Academic year created successfully.', 'year' => $year]);
        }

        return redirect()->route('admin.academic-years.index')->with('success', 'Academic year created successfully.');
    }

    public function edit(AcademicYear $academicYear)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'id' => $academicYear->id,
                'name' => $academicYear->name,
                'start_date' => $academicYear->start_date?->format('Y-m-d'),
                'end_date' => $academicYear->end_date?->format('Y-m-d'),
                'is_current' => $academicYear->is_current,
            ]);
        }

        return view('admin.academic-years-form', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:academic_years,name,' . $academicYear->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_current' => 'boolean',
        ]);

        if ($validated['is_current'] ?? false) {
            AcademicYear::where('is_current', 1)
                ->where('id', '!=', $academicYear->id)
                ->update(['is_current' => 0]);
        }

        $academicYear->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Academic year updated successfully.', 'year' => $academicYear]);
        }

        return redirect()->route('admin.academic-years.index')->with('success', 'Academic year updated successfully.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();
        return redirect()->route('admin.academic-years.index')->with('success', 'Academic year deleted successfully.');
    }
}
