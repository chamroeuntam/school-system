<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Term;
use App\Models\AcademicYear;

class TermController extends Controller
{
    public function index()
    {
        $terms = Term::with('academicYear')->orderBy('academic_year_id', 'desc')->orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        return view('admin.terms.index', compact('terms', 'academicYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:30',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        Term::create($validated);

        return back()->with('success', 'ឆមាសត្រូវបានបន្ថែម។');
    }

    public function update(Request $request, Term $term)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:30',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $term->update($validated);

        return back()->with('success', 'ឆមាសត្រូវបានកែប្រែ។');
    }

    public function destroy(Term $term)
    {
        $term->delete();

        return back()->with('success', 'ឆមាសត្រូវបានលុប។');
    }
}
