@extends('layouts.app')

@section('content')
<div style="padding: 0;">
    <div style="padding: 24px; background: transparent;">
        <h1 style="font-size: 26px; font-weight: 900; margin: 0 0 8px 0; color: #eaf0ff;">គ្រប់គ្រងពិន្ទុ</h1>
        <p style="margin: 0; color: rgba(255,255,255,.5); font-size: 14px; font-weight: 700;">បញ្ចូលពិន្ទុប្រចាំខែ និងប្រឡងឆមាស</p>
    </div>

    @if(session('success'))
        <div style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 12px; padding: 14px 18px; margin: 0 24px 20px 24px;">
            <span style="color: #22c55e; font-weight: 700; font-size: 13px;">✓ {{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 14px 18px; margin: 0 24px 20px 24px;">
            <span style="color: #ef4444; font-weight: 700; font-size: 13px;">✕ {{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter Section -->
    <div style="background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.1); border-radius: 16px; padding: 20px; margin: 0 24px 20px 24px;">
        <form method="GET" action="{{ route('admin.scores.index') }}" id="filterForm">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <!-- Academic Year -->
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; font-size: 13px; color: rgba(255,255,255,.7);">ឆ្នាំសិក្សា</label>
                    <select name="academic_year_id" onchange="document.getElementById('filterForm').submit()" style="width: 100%; padding: 10px 12px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15); border-radius: 10px; color: #fff; font-weight: 700; font-size: 13px;">
                        <option value="">-- ជ្រើសរើស --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $academicYearId == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Class -->
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; font-size: 13px; color: rgba(255,255,255,.7);">ថ្នាក់</label>
                    <select name="class_id" onchange="document.getElementById('filterForm').submit()" style="width: 100%; padding: 10px 12px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15); border-radius: 10px; color: #fff; font-weight: 700; font-size: 13px;">
                        <option value="">-- ជ្រើសរើស --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Term -->
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; font-size: 13px; color: rgba(255,255,255,.7);">ឆមាស</label>
                    <select name="term_id" onchange="document.getElementById('filterForm').submit()" style="width: 100%; padding: 10px 12px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15); border-radius: 10px; color: #fff; font-weight: 700; font-size: 13px;">
                        <option value="">-- ជ្រើសរើស --</option>
                        @foreach($terms as $term)
                            <option value="{{ $term->id }}" {{ $termId == $term->id ? 'selected' : '' }}>{{ $term->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Subject -->
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 700; font-size: 13px; color: rgba(255,255,255,.7);">មុខវិជ្ជា</label>
                    <select name="subject_id" onchange="document.getElementById('filterForm').submit()" style="width: 100%; padding: 10px 12px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15); border-radius: 10px; color: #fff; font-weight: 700; font-size: 13px;">
                        <option value="">-- ជ្រើសរើស --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $subjectId == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($termId && $subjectId)
                <div style="margin-top: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 700; font-size: 13px; color: rgba(255,255,255,.9);">ប្រភេទប្រឡង</label>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" onclick="showExamType('month_1')" id="btn_month_1" class="exam-type-btn" style="padding: 10px 16px; background: rgba(79,70,229,.2); border: 1px solid rgba(79,70,229,.4); border-radius: 10px; color: #4f46e5; font-weight: 800; font-size: 12px; cursor: pointer;">ខែទី១</button>
                        <button type="button" onclick="showExamType('month_2')" id="btn_month_2" class="exam-type-btn" style="padding: 10px 16px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15); border-radius: 10px; color: rgba(255,255,255,.7); font-weight: 800; font-size: 12px; cursor: pointer;">ខែទី២</button>
                        <button type="button" onclick="showExamType('month_3')" id="btn_month_3" class="exam-type-btn" style="padding: 10px 16px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15); border-radius: 10px; color: rgba(255,255,255,.7); font-weight: 800; font-size: 12px; cursor: pointer;">ខែទី៣</button>
                        <button type="button" onclick="showExamType('semester_exam')" id="btn_semester_exam" class="exam-type-btn" style="padding: 10px 16px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15); border-radius: 10px; color: rgba(255,255,255,.7); font-weight: 800; font-size: 12px; cursor: pointer;">ប្រឡងឆមាស</button>
                    </div>
                </div>
            @endif
        </form>
    </div>

    @if($termId && $subjectId && $enrollments->isNotEmpty())
        <!-- Score Entry Form -->
        <div style="background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.1); border-radius: 16px; overflow: hidden; margin: 0 24px 24px 24px;">
            <form method="POST" action="{{ route('admin.scores.batch-store') }}" id="scoreForm">
                @csrf
                <input type="hidden" name="term_id" value="{{ $termId }}">
                <input type="hidden" name="subject_id" value="{{ $subjectId }}">
                <input type="hidden" name="exam_type" value="month_1" id="currentExamType">

                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: rgba(255,255,255,.05);">
                                <th style="padding: 14px 12px; text-align: left; font-weight: 800; font-size: 12px; border-bottom: 1px solid rgba(255,255,255,.1); white-space: nowrap;">លេខរៀង</th>
                                <th style="padding: 14px 12px; text-align: left; font-weight: 800; font-size: 12px; border-bottom: 1px solid rgba(255,255,255,.1); white-space: nowrap;">ឈ្មោះសិស្ស</th>
                                <th id="exam-header" style="padding: 14px 12px; text-align: center; font-weight: 800; font-size: 12px; border-bottom: 1px solid rgba(255,255,255,.1); white-space: nowrap; background: rgba(79,70,229,.15);">ខែទី១</th>
                                <th style="padding: 14px 12px; text-align: center; font-weight: 800; font-size: 12px; border-bottom: 1px solid rgba(255,255,255,.1); white-space: nowrap;">ខែទី២</th>
                                <th style="padding: 14px 12px; text-align: center; font-weight: 800; font-size: 12px; border-bottom: 1px solid rgba(255,255,255,.1); white-space: nowrap;">ខែទី៣</th>
                                <th style="padding: 14px 12px; text-align: center; font-weight: 800; font-size: 12px; border-bottom: 1px solid rgba(255,255,255,.1); white-space: nowrap;">ប្រឡងឆមាស</th>
                                <th style="padding: 14px 12px; text-align: center; font-weight: 800; font-size: 12px; border-bottom: 1px solid rgba(255,255,255,.1); white-space: nowrap; background: rgba(6,182,212,.15);">សរុបឆមាស</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $index => $enrollment)
                                @php
                                    $month1Key = $enrollment->id . '_month_1';
                                    $month2Key = $enrollment->id . '_month_2';
                                    $month3Key = $enrollment->id . '_month_3';
                                    $semesterKey = $enrollment->id . '_semester_exam';

                                    $month1Score = $scores->get($month1Key);
                                    $month2Score = $scores->get($month2Key);
                                    $month3Score = $scores->get($month3Key);
                                    $semesterScore = $scores->get($semesterKey);

                                    $total = ($month1Score->score ?? 0) + ($month2Score->score ?? 0) + ($month3Score->score ?? 0) + ($semesterScore->score ?? 0);
                                @endphp
                                <tr style="border-bottom: 1px solid rgba(255,255,255,.05);">
                                    <td style="padding: 12px; font-weight: 700; font-size: 13px;">{{ $enrollment->roll_no ?? ($index + 1) }}</td>
                                    <td style="padding: 12px; font-weight: 700; font-size: 13px;">{{ $enrollment->student->full_name ?? 'N/A' }}</td>

                                    <!-- Editable cell for current exam type -->
                                    <td style="padding: 12px; text-align: center; background: rgba(79,70,229,.08);" class="editable-cell" data-exam-type="month_1">
                                        <input type="number" name="scores[{{ $index }}][score]" value="{{ $month1Score->score ?? '' }}" min="0" max="{{ $maxScore }}" step="0.01" placeholder="-" style="width: 80px; padding: 8px; background: rgba(255,255,255,.12); border: 1px solid rgba(79,70,229,.3); border-radius: 8px; color: #fff; font-weight: 700; font-size: 13px; text-align: center;">
                                        <input type="hidden" name="scores[{{ $index }}][enrollment_id]" value="{{ $enrollment->id }}">
                                    </td>

                                    <!-- Display-only cells for other exam types -->
                                    <td style="padding: 12px; text-align: center;" class="display-cell" data-exam-type="month_2">
                                        <span style="font-weight: 700; font-size: 13px; color: rgba(255,255,255,.5);">{{ $month2Score->score ?? '-' }}</span>
                                    </td>
                                    <td style="padding: 12px; text-align: center;" class="display-cell" data-exam-type="month_3">
                                        <span style="font-weight: 700; font-size: 13px; color: rgba(255,255,255,.5);">{{ $month3Score->score ?? '-' }}</span>
                                    </td>
                                    <td style="padding: 12px; text-align: center;" class="display-cell" data-exam-type="semester_exam">
                                        <span style="font-weight: 700; font-size: 13px; color: rgba(255,255,255,.5);">{{ $semesterScore->score ?? '-' }}</span>
                                    </td>
                                    <td style="padding: 12px; text-align: center; background: rgba(6,182,212,.08);">
                                        <span class="total-score" style="font-weight: 900; font-size: 15px; color: #06b6d4;">{{ number_format($total, 2) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="padding: 20px; background: rgba(255,255,255,.03); border-top: 1px solid rgba(255,255,255,.1);">
                    <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #4f46e5, #06b6d4); border: none; border-radius: 10px; color: #fff; font-weight: 800; font-size: 13px; cursor: pointer; box-shadow: 0 8px 20px rgba(79, 70, 229, 0.25);">
                        រក្សាទុកពិន្ទុ <span id="save-exam-type">ខែទី១</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div style="background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.1); border-radius: 16px; padding: 40px; text-align: center; margin: 0 24px 24px 24px;">
            <p style="color: rgba(255,255,255,.5); font-weight: 700; font-size: 14px;">សូមជ្រើសរើស ឆ្នាំសិក្សា, ថ្នាក់, ឆមាស និងមុខវិជ្ជា ដើម្បីបញ្ចូលពិន្ទុ។</p>
        </div>
    @endif
</div>

<script>
const examTypeNames = {
    'month_1': 'ខែទី១',
    'month_2': 'ខែទី២',
    'month_3': 'ខែទី៣',
    'semester_exam': 'ប្រឡងឆមាស'
};

let currentExamType = 'month_1';

function showExamType(examType) {
    currentExamType = examType;

    // Update hidden input
    document.getElementById('currentExamType').value = examType;

    // Update button styles
    document.querySelectorAll('.exam-type-btn').forEach(btn => {
        if (btn.id === 'btn_' + examType) {
            btn.style.background = 'rgba(79,70,229,.2)';
            btn.style.borderColor = 'rgba(79,70,229,.4)';
            btn.style.color = '#4f46e5';
        } else {
            btn.style.background = 'rgba(255,255,255,.08)';
            btn.style.borderColor = 'rgba(255,255,255,.15)';
            btn.style.color = 'rgba(255,255,255,.7)';
        }
    });

    // Update header highlight
    const headers = document.querySelectorAll('thead th');
   const headerIndex = examType === 'month_1' ? 2 : examType === 'month_2' ? 3 : examType === 'month_3' ? 4 : 5;
    headers.forEach((th, index) => {
        if (index === headerIndex) {
            th.style.background = 'rgba(79,70,229,.15)';
        } else if (index === 6) {
            th.style.background = 'rgba(6,182,212,.15)';
        } else {
            th.style.background = '';
        }
    });

    // Update editable/display cells
    document.querySelectorAll('.editable-cell, .display-cell').forEach(cell => {
        if (cell.dataset.examType === examType) {
            cell.classList.remove('display-cell');
            cell.classList.add('editable-cell');
            cell.style.background = 'rgba(79,70,229,.08)';
        } else {
            cell.classList.remove('editable-cell');
            cell.classList.add('display-cell');
            cell.style.background = '';
        }
    });

    // Update save button text
    document.getElementById('save-exam-type').textContent = examTypeNames[examType];
}

// Initial setup
window.addEventListener('load', function() {
    showExamType('month_1');
});
</script>
@endsection
