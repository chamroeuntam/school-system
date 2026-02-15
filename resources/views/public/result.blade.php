<!doctype html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <title>លទ្ធផលសិស្ស</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts: Khmer-first -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@400;600;700;800&family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        :root{
            --bg1:#0b1020;
            --bg2:#111827;
            --card: rgba(15, 23, 42, .92);
            --border: rgba(255,255,255,.10);
            --text:#e5e7eb;
            --muted:#9ca3af;
            --primary:#4f46e5;
            --primary2:#06b6d4;
            --success:#22c55e;
            --danger:#ef4444;
            --shadow: 0 18px 50px rgba(0,0,0,.45);
            --radius: 18px;
        }

        *{ box-sizing:border-box; }
        html,body{ height:100%; }
        body{
            margin:0;
            font-family: "Google Sans", Arial, sans-serif;
            color:var(--text);
            background:
                radial-gradient(1200px 700px at 15% 10%, rgba(79,70,229,.35) 0%, transparent 60%),
                radial-gradient(900px 600px at 95% 25%, rgba(6,182,212,.25) 0%, transparent 60%),
                linear-gradient(180deg, var(--bg1) 0%, var(--bg2) 100%);
            min-height:100vh;
            padding:40px 20px;
        }

        .wrap{
            width:100%;
            max-width: 1100px;
            margin: 0 auto 40px auto;
        }

        .card{
            width:100%;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow:hidden;
            backdrop-filter: blur(10px);
        }

        .card-header{
            padding: 22px 22px 16px;
            border-bottom: 1px solid var(--border);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            flex-wrap:wrap;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:12px;
        }

        .logo{
            width:52px;
            height:52px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary), var(--primary2));
            box-shadow: 0 10px 22px rgba(0,0,0,.25);
            overflow:hidden;
            display:flex;
            align-items:center;
            justify-content:center;
        }
        .logo img{ width:100%; height:100%; object-fit: cover; display:block; }
        .logo .initial{ color:#fff; font-weight:800; font-size:20px; }

        .title h1{
            margin:0;
            font-size:16px;
            font-weight:800;
        }
        .title p{
            margin:4px 0 0;
            font-size:12px;
            color: var(--muted);
            font-weight:700;
        }

        .toolbar{
            display:flex;
            gap:10px;
            align-items:center;
            flex-wrap:wrap;
        }

        .btn{
            border:1px solid rgba(255,255,255,.12);
            background: rgba(255,255,255,.06);
            color: var(--text);
            font-weight:800;
            padding:10px 12px;
            border-radius: 12px;
            text-decoration:none;
            display:inline-flex;
            align-items:center;
            gap:8px;
            transition: transform .15s ease, background .15s ease;
        }
        .btn:hover{ background: rgba(255,255,255,.10); transform: translateY(-1px); }

        .card-body{ padding: 18px 22px 22px; }

        .grid{
            display:grid;
            grid-template-columns: 1.2fr .8fr;
            gap:16px;
        }

        .section{
            border:1px solid var(--border);
            border-radius: 16px;
            padding:16px;
            background: rgba(255,255,255,.03);
        }

        .section h2{
            margin:0 0 10px;
            font-size:14px;
            font-weight:800;
        }

        .meta{
            display:grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap:10px;
        }

        .meta-item{
            border:1px solid rgba(255,255,255,.10);
            background: rgba(255,255,255,.04);
            border-radius: 14px;
            padding:10px 12px;
        }
        .meta-item small{
            display:block;
            color: var(--muted);
            font-size:11px;
            font-weight:700;
        }
        .meta-item b{
            display:block;
            margin-top:4px;
            font-size:13px;
        }

        .badge{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 10px;
            border-radius: 999px;
            border:1px solid var(--border);
            font-size:11px;
            font-weight:800;
            background: rgba(255,255,255,.06);
        }

        .stat{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            margin: 10px 0 12px;
        }
        .stat .value{
            font-size:24px;
            font-weight:900;
        }
        .stat .label{
            color: var(--muted);
            font-size:12px;
            font-weight:700;
        }

        table{
            width:100%;
            border-collapse: collapse;
            font-size:12.5px;
        }
        th, td{
            text-align:left;
            padding:10px 8px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        th{ color: var(--muted); font-weight:800; }

        .empty{
            color: var(--muted);
            font-size:12px;
            font-weight:700;
            text-align:center;
            padding:12px 0;
        }

        .term-block{
            border:1px solid rgba(255,255,255,.10);
            border-radius: 14px;
            padding:12px;
            margin-bottom:12px;
            background: rgba(255,255,255,.03);
        }
        .term-title{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:10px;
            margin-bottom:8px;
            font-size:13px;
            font-weight:800;
        }

        @media (max-width: 960px){
            .grid{ grid-template-columns: 1fr; }
            .meta{ grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

@php
    $fullName = $student->full_name ?? 'N/A';
    $studentCode = $student->student_code ?? 'N/A';
    $gender = $student->gender ?? 'N/A';
    $dob = $student->dob ? \Carbon\Carbon::parse($student->dob)->format('Y-m-d') : 'N/A';

    $className = optional($enrollment->schoolClass)->name ?? 'N/A';
    $yearName = optional($enrollment->academicYear)->name ?? 'N/A';
    $rollNo = $enrollment->roll_no ?? 'N/A';
    $status = $enrollment->status ?? 'N/A';

    $scoreGroups = $scores->groupBy(function ($score) {
            return optional($score->term)->name ?? 'Term';
    });
@endphp

<div class="wrap">
    <div class="card">
        <div class="card-header">
            <div class="brand">
                <div class="logo">
                    @if(file_exists(public_path('storage/asset/school-logo.png')))
                        <img src="{{ asset('storage/asset/school-logo.png') }}" alt="School Logo">
                    @else
                        <span class="initial">{{ strtoupper(substr(config('app.name'), 0, 1)) }}</span>
                    @endif
                </div>
                <div class="title">
                    <h1>លទ្ធផលសិស្ស</h1>
                    <p>ពិនិត្យការសិក្សា និងវត្តមាន</p>
                </div>
            </div>

            <div class="toolbar">
                <a class="btn" href="{{ route('public.lookup') }}">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    ស្វែងរកម្ដងទៀត
                </a>
                <a class="btn primary" href="{{ url('/') }}">
                    <i class="fa-solid fa-house"></i>
                    ត្រលប់ទៅទំព័រដើម
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="grid">

                <div class="section">
                    <h2>ព័ត៌មានសិស្ស</h2>
                    <div class="meta">
                        <div class="meta-item">
                            <small>ឈ្មោះពេញ</small>
                            <b>{{ $fullName }}</b>
                        </div>
                        <div class="meta-item">
                            <small>លេខសិស្ស</small>
                            <b>{{ $studentCode }}</b>
                        </div>
                        <div class="meta-item">
                            <small>ភេទ</small>
                            <b>{{ $gender }}</b>
                        </div>
                        <div class="meta-item">
                            <small>ថ្ងៃកំណើត</small>
                            <b>{{ $dob }}</b>
                        </div>
                        <div class="meta-item">
                            <small>ថ្នាក់</small>
                            <b>{{ $className }}</b>
                        </div>
                        <div class="meta-item">
                            <small>ឆ្នាំសិក្សា</small>
                            <b>{{ $yearName }}</b>
                        </div>
                        <div class="meta-item">
                            <small>លេខរៀង</small>
                            <b>{{ $rollNo }}</b>
                        </div>
                        <div class="meta-item">
                            <small>ស្ថានភាព</small>
                            <b>{{ ucfirst($status) }}</b>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <h2 style="margin: 0;">វត្តមាន 30 ថ្ងៃចុងក្រោយ</h2>
                        <div style="position: relative;">
                            <select id="attendanceSubjectFilter" style="
                                background: rgba(255,255,255,.08);
                                border: 1px solid rgba(255,255,255,.15);
                                color: var(--text);
                                padding: 8px 32px 8px 12px;
                                border-radius: 10px;
                                font-size: 12px;
                                font-weight: 700;
                                cursor: pointer;
                                appearance: none;
                                outline: none;
                            ">
                                <option value="">គ្រប់មុខវិជ្ជា</option>
                                @php
                                    $attendanceSubjects = $attendances->pluck('subject.name', 'subject_id')->unique()->filter();
                                @endphp
                                @foreach($attendanceSubjects as $subjectId => $subjectName)
                                    <option value="{{ $subjectId }}">{{ $subjectName }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 10px; color: var(--muted);"></i>
                        </div>
                    </div>
                    <div class="stat">
                        <div>
                            <div class="label">អត្រាវត្តមាន</div>
                            <div class="value">{{ $attendancePercent }}%</div>
                        </div>
                        <div class="badge">
                            <i class="fa-solid fa-calendar-check"></i>
                            {{ $attendances->count() }} ថ្ងៃ
                        </div>
                    </div>

                    @if($attendances->count() > 0)
                        <div style="max-height: 350px; overflow-y: auto; border: 1px solid rgba(255,255,255,.08); border-radius: 12px; background: rgba(255,255,255,.02);">
                            <table>
                                <thead style="position: sticky; top: 0; background: rgba(15, 23, 42, .95); backdrop-filter: blur(8px); z-index: 1;">
                                    <tr>
                                        <th>កាលបរិច្ឆេទ</th>
                                        <th>វេនសិក្សា</th>
                                        <th>មុខវិជ្ជា</th>
                                        <th>ស្ថានភាព</th>
                                        <th>ចំណាំ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $att)
                                        @php
                                            $statusMap = [
                                                'present' => 'វត្តមាន',
                                                'absent' => 'អវត្តមាន',
                                                'late' => 'យឺត',
                                                'excused' => 'មានច្បាប់'
                                            ];
                                            $sessionMap = [
                                                'morning' => 'ព្រឹក',
                                                'evening' => 'ល្ងាច'
                                            ];
                                            $statusKh = $statusMap[$att->status] ?? ucfirst($att->status);
                                            $sessionKh = $sessionMap[$att->session] ?? ucfirst($att->session);
                                        @endphp
                                        <tr class="attendance-row" data-subject-id="{{ $att->subject_id ?? '' }}">
                                            <td>{{ $att->attendance_date?->format('Y-m-d') }}</td>
                                            <td>{{ $sessionKh }}</td>
                                            <td>{{ $att->subject?->name ?? '-' }}</td>
                                            <td>{{ $statusKh }}</td>
                                            <td>{{ $att->note ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty">មិនមានទិន្នន័យវត្តមាន។</div>
                    @endif
                </div>

            </div>

            <div class="section" style="margin-top:16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <h2 style="margin: 0;">ពិន្ទុតាមត្រីមាស</h2>
                    <div style="position: relative;">
                        <select id="subjectFilter" style="
                            background: rgba(255,255,255,.08);
                            border: 1px solid rgba(255,255,255,.15);
                            color: var(--text);
                            padding: 8px 32px 8px 12px;
                            border-radius: 10px;
                            font-size: 12px;
                            font-weight: 700;
                            cursor: pointer;
                            appearance: none;
                            outline: none;
                        ">
                            <option value="">គ្រប់មុខវិជ្ជា</option>
                            @php
                                $allSubjects = $scores->pluck('subject.name', 'subject_id')->unique()->filter();
                            @endphp
                            @foreach($allSubjects as $subjectId => $subjectName)
                                <option value="{{ $subjectId }}">{{ $subjectName }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 10px; color: var(--muted);"></i>
                    </div>
                </div>

                @if($scores->count() === 0)
                    <div class="empty">មិនមានទិន្នន័យពិន្ទុ។</div>
                @else
                    @foreach($scoreGroups as $termName => $items)
                        <div class="term-block" data-term="{{ $termName }}">
                            <div class="term-title">
                                <span>{{ $termName }}</span>
                                <span class="badge term-count">
                                    <i class="fa-solid fa-list"></i>
                                    <span class="count-value">{{ $items->count() }}</span> មុខវិជ្ជា
                                </span>
                            </div>

                            <table>
                                <thead>
                                    <tr>
                                        <th>មុខវិជ្ជា</th>
                                        <th>ពិន្ទុ</th>
                                        <th>Grade</th>
                                        <th>ចំណាំ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $score)
                                        <tr class="score-row" data-subject-id="{{ $score->subject_id }}">
                                            <td>{{ optional($score->subject)->name ?? 'N/A' }}</td>
                                            <td>{{ $score->score ?? '-' }}</td>
                                            <td>{{ $score->grade_letter ?? '-' }}</td>
                                            <td>{{ $score->remark ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('subjectFilter')?.addEventListener('change', function() {
    const selectedSubjectId = this.value;
    const termBlocks = document.querySelectorAll('.term-block');

    termBlocks.forEach(block => {
        const rows = block.querySelectorAll('.score-row');
        let visibleCount = 0;

        rows.forEach(row => {
            if (selectedSubjectId === '' || row.dataset.subjectId === selectedSubjectId) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update count badge
        const countValue = block.querySelector('.count-value');
        if (countValue) {
            countValue.textContent = visibleCount;
        }

        // Hide term block if no visible rows
        if (visibleCount === 0) {
            block.style.display = 'none';
        } else {
            block.style.display = '';
        }
    });
});

document.getElementById('attendanceSubjectFilter')?.addEventListener('change', function() {
    const selectedSubjectId = this.value;
    const rows = document.querySelectorAll('.attendance-row');

    rows.forEach(row => {
        if (selectedSubjectId === '' || row.dataset.subjectId === selectedSubjectId) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

</body>
</html>
