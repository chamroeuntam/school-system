@extends('layouts.app')

@section('content')
<style>
    .sheet-form{ display:grid; gap:18px; }
    .form-hero{
        padding:18px;
        border-radius: 18px;
        border:1px solid rgba(255,255,255,.10);
        background:
            linear-gradient(135deg, rgba(79,70,229,.18), rgba(6,182,212,.10)),
            rgba(255,255,255,.04);
    }
    .hero-row{ display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
    .hero-title{ font-size:28px; font-weight:900; margin:0; line-height:1.2; }
    .hero-sub{ margin:8px 0 0; color: rgba(168,179,207,.95); font-weight:700; font-size:14px; }
    .btn-hero{
        padding:11px 14px; border-radius:14px; border:1px solid rgba(255,255,255,.16);
        background: rgba(255,255,255,.08); color:#eaf0ff; font-weight:800; font-size:14px; text-decoration:none;
    }
    .btn-primary{
        border-color: rgba(79,70,229,.45);
        background: linear-gradient(135deg, rgba(79,70,229,.45), rgba(6,182,212,.25));
    }
    .panel{
        border-radius: 18px; border:1px solid rgba(255,255,255,.10);
        background: rgba(255,255,255,.04); padding:16px;
    }
    .panel h2{ margin:0 0 12px; font-size:18px; font-weight:900; }
    .grid-2{ display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:14px; }
    .field{ display:grid; gap:6px; }
    .field label{ font-weight:800; font-size:14px; color: rgba(234,240,255,.92); }
    .field small{ color: rgba(168,179,207,.85); font-weight:600; font-size:13px; }
    .input, .select{
        width:100%; padding:11px 12px; border-radius:12px;
        border:1px solid rgba(255,255,255,.12);
        background: rgba(255,255,255,.08); color:#eaf0ff; font-weight:700; font-size:14px;
    }
    .error{ color:#ffd3d3; font-weight:700; font-size:13px; margin-top:6px; }
    .info{
        border-radius: 18px; border:1px solid rgba(255,255,255,.10);
        background: rgba(255,255,255,.04); padding:16px;
    }
    .info h3{ margin:0 0 10px; font-size:15px; font-weight:900; }
    .info ul{ margin:0; padding-left:18px; color: rgba(234,240,255,.88); font-weight:600; font-size:14px; }
    .actions{ display:flex; gap:10px; flex-wrap:wrap; }
    .btn{ padding:11px 14px; border-radius:14px; border:1px solid rgba(255,255,255,.14); background: rgba(255,255,255,.08); color:#eaf0ff; font-weight:800; font-size:14px; cursor:pointer; }
    .btn-outline{ background: transparent; }
    .toggle{
        display:flex; align-items:center; gap:10px; padding:10px 12px;
        border-radius:12px; border:1px solid rgba(255,255,255,.12); background: rgba(255,255,255,.06);
    }
    @media (max-width: 900px){
        .grid-2{ grid-template-columns: 1fr; }
    }
</style>

<div class="sheet-form">
    <section class="form-hero">
        <div class="hero-row">
            <div>
                <h1 class="hero-title">Edit Sheet Source</h1>
                <div class="hero-sub">Update mapping, status, or sheet details</div>
            </div>
            <a class="btn-hero" href="{{ route('admin.sheet-sources.index') }}">Back to list</a>
        </div>
    </section>

    @if ($errors->any())
        <div class="panel" style="border-color: rgba(239,68,68,.35); background: rgba(239,68,68,.08);">
            <div style="font-weight:800; color:#fecaca;">Please fix the errors below.</div>
        </div>
    @endif

    <form action="{{ route('admin.sheet-sources.update', $sheetSource) }}" method="POST" class="sheet-form">
        @csrf
        @method('PUT')

        <section class="panel">
            <h2>Sheet Information</h2>
            <div class="grid-2">
                <div class="field">
                    <label for="type">Import Type *</label>
                    <select id="type" name="type" class="select" required>
                        <option value="">-- Select Type --</option>
                        <option value="score" {{ $sheetSource->type === 'score' ? 'selected' : '' }}>Scores (Wide Format)</option>
                        <option value="attendance" {{ $sheetSource->type === 'attendance' ? 'selected' : '' }}>Attendance</option>
                    </select>
                    @error('type') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="tab_name">Sheet Tab Name *</label>
                    <input id="tab_name" type="text" name="tab_name" placeholder="Score12A-SCI(January)" value="{{ old('tab_name', $sheetSource->tab_name) }}" class="input" required>
                    @error('tab_name') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="field" style="margin-top:10px;">
                <label for="sheet_id">Sheet ID *</label>
                <input id="sheet_id" type="text" name="sheet_id" placeholder="1A2B3C4D5E6F7G8H9I0J" value="{{ old('sheet_id', $sheetSource->sheet_id) }}" class="input" required>
                <small>Get from: https://docs.google.com/spreadsheets/d/SHEET_ID/edit</small>
                @error('sheet_id') <div class="error">{{ $message }}</div> @enderror
            </div>
        </section>

        <section class="panel">
            <h2>Class & Mapping</h2>
            <div class="grid-2">
                <div class="field">
                    <label for="school_class_id">Class *</label>
                    <select id="school_class_id" name="school_class_id" class="select" required>
                        <option value="">-- Select Class --</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" {{ $sheetSource->school_class_id == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                                @if ($class->gradeLevel)
                                    ({{ $class->gradeLevel->level }})
                                @endif
                                @if ($class->stream)
                                    - {{ $class->stream->name }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('school_class_id') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field" id="subject-field">
                    <label for="subject_id">Subject</label>
                    <select id="subject_id" name="subject_id" class="select">
                        <option value="">-- Auto-detect (Recommended) --</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $sheetSource->subject_id == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    <small>Leave empty for wide format (multiple subjects)</small>
                    @error('subject_id') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid-2" style="margin-top:10px;">
                <div class="field">
                    <label for="term_id">Term</label>
                    <select id="term_id" name="term_id" class="select">
                        <option value="">-- Not Specified --</option>
                        @foreach ($terms as $term)
                            <option value="{{ $term->id }}" {{ $sheetSource->term_id == $term->id ? 'selected' : '' }}>
                                {{ $term->name }}
                                @if ($term->academic_year_id)
                                    ({{ $term->academicYear?->name ?? 'Year' }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('term_id') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label>Active Status</label>
                    <label class="toggle">
                        <input type="checkbox" name="is_active" value="1" {{ $sheetSource->is_active ? 'checked' : '' }}>
                        <span>Enable this sheet source</span>
                    </label>
                </div>
            </div>
        </section>

        <section class="info">
            <h3>Info</h3>
            <ul>
                <li><strong>Created by:</strong> {{ $sheetSource->creator?->name ?? 'Unknown' }}</li>
                <li><strong>Created at:</strong> {{ $sheetSource->created_at->format('M d, Y H:i') }}</li>
                <li><strong>Last synced:</strong> {{ $sheetSource->last_synced_at?->diffForHumans() ?? 'Never' }}</li>
            </ul>
        </section>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Update Sheet Source</button>
            <a href="{{ route('admin.sheet-sources.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<script>
    // Show/hide subject field based on type
    document.querySelector('select[name="type"]').addEventListener('change', function () {
        const subjectField = document.getElementById('subject-field');
        if (this.value === 'score') {
            subjectField.style.display = 'block';
        } else {
            subjectField.style.display = 'none';
        }
    });

    // Trigger on page load
    document.querySelector('select[name="type"]').dispatchEvent(new Event('change'));
</script>
@endsection
