@extends('layouts.app')

@section('content')
@php
    $totalSources = $sheetSources->count();
    $activeSources = $sheetSources->where('is_active', true)->count();
@endphp

<style>
    .sheet-page{ display:grid; gap:18px; }
    .sheet-hero{
        padding:18px;
        border-radius: 18px;
        border:1px solid rgba(255,255,255,.10);
        background:
            linear-gradient(135deg, rgba(79,70,229,.18), rgba(6,182,212,.10)),
            rgba(255,255,255,.04);
        box-shadow: 0 18px 45px rgba(0,0,0,.35);
    }
    .hero-row{ display:flex; gap:16px; align-items:center; justify-content:space-between; flex-wrap:wrap; }
    .hero-title{ font-size:28px; font-weight:900; margin:0; line-height:1.2; }
    .hero-sub{ margin:8px 0 0; color: rgba(168,179,207,.95); font-weight:700; font-size:14px; }
    .hero-actions{ display:flex; gap:10px; align-items:center; }
    .btn-hero{
        padding:11px 14px; border-radius:14px; border:1px solid rgba(255,255,255,.16);
        background: rgba(255,255,255,.08); color:#eaf0ff; font-weight:800; font-size:14px; text-decoration:none;
        transition: transform .15s ease, background .15s ease;
    }
    .btn-hero:hover{ transform: translateY(-1px); background: rgba(255,255,255,.14); }
    .btn-primary{
        border-color: rgba(79,70,229,.45);
        background: linear-gradient(135deg, rgba(79,70,229,.45), rgba(6,182,212,.25));
    }

    .stat-grid{ display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; margin-top:14px; }
    .stat-card{
        border-radius: 16px; border:1px solid rgba(255,255,255,.10);
        background: rgba(255,255,255,.05); padding:12px 14px;
        display:flex; align-items:center; gap:12px;
    }
    .stat-dot{ width:10px; height:10px; border-radius:999px; background:#22c55e; box-shadow:0 0 0 6px rgba(34,197,94,.2); }
    .stat-title{ font-size:13px; color: rgba(168,179,207,.9); font-weight:700; }
    .stat-value{ font-size:20px; font-weight:900; }

    .sheet-card{
        border-radius: 18px; border:1px solid rgba(255,255,255,.10);
        background: rgba(255,255,255,.04); padding:16px;
    }
    .sheet-table{ width:100%; border-collapse: collapse; font-size:15px; }
    .sheet-table th, .sheet-table td{ padding:12px 10px; text-align:left; }
    .sheet-table thead th{
        position: sticky; top:0; background: rgba(0,0,0,.25);
        text-transform: uppercase; font-size:11px; letter-spacing:.4px; color: rgba(234,240,255,.85);
    }
    .sheet-table tbody tr{ border-top:1px solid rgba(255,255,255,.08); }
    .sheet-table tbody tr:hover{ background: rgba(255,255,255,.04); }
    .sheet-id{
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size:12px;
    }
    .pill{ display:inline-flex; align-items:center; gap:6px; padding:4px 10px; border-radius:999px; font-weight:800; font-size:11px; }
    .pill-score{ background: rgba(79,70,229,.2); color:#b7b9ff; border:1px solid rgba(79,70,229,.35); }
    .pill-att{ background: rgba(34,197,94,.16); color:#b7f7d1; border:1px solid rgba(34,197,94,.35); }
    .pill-active{ background: rgba(6,182,212,.16); color:#9ae6ff; border:1px solid rgba(6,182,212,.3); }
    .pill-inactive{ background: rgba(148,163,184,.15); color:#d2d9e6; border:1px solid rgba(148,163,184,.25); }

    .action-row{ display:flex; gap:8px; flex-wrap:wrap; }
    .btn-chip{
        padding:6px 10px; border-radius:12px; border:1px solid rgba(255,255,255,.14);
        background: rgba(255,255,255,.06); color:#eaf0ff; font-weight:800; text-decoration:none; cursor:pointer;
    }
    .btn-chip:hover{ background: rgba(255,255,255,.12); }
    .btn-sync{ border-color: rgba(6,182,212,.3); }
    .btn-edit{ border-color: rgba(34,197,94,.3); }
    .btn-del{ border-color: rgba(239,68,68,.35); color:#fecaca; }

    .guide{
        border-radius: 18px; border:1px solid rgba(255,255,255,.10);
        background: rgba(255,255,255,.04); padding:16px;
    }
    .guide h2{ margin:0 0 10px; font-size:16px; font-weight:900; }
    .guide ol{ margin:0; padding-left:18px; color: rgba(234,240,255,.88); font-weight:600; }
    .guide code{ background: rgba(255,255,255,.08); padding:2px 6px; border-radius:6px; }

    @media (max-width: 900px){
        .sheet-table{ font-size:13px; }
        .hero-title{ font-size:22px; }
    }
</style>

<div class="sheet-page">
    <section class="sheet-hero">
        <div class="hero-row">
            <div>
                <h1 class="hero-title">Google Sheet Sources</h1>
                <div class="hero-sub">Register, sync, and manage Google Sheet imports</div>
            </div>
            <div class="hero-actions">
                <a href="{{ route('admin.sheet-sources.create') }}" class="btn-hero btn-primary">+ Register New Sheet</a>
            </div>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <span class="stat-dot" style="background:#6366f1; box-shadow:0 0 0 6px rgba(99,102,241,.22);"></span>
                <div>
                    <div class="stat-title">Total Sources</div>
                    <div class="stat-value">{{ $totalSources }}</div>
                </div>
            </div>
            <div class="stat-card">
                <span class="stat-dot"></span>
                <div>
                    <div class="stat-title">Active Sources</div>
                    <div class="stat-value">{{ $activeSources }}</div>
                </div>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="sheet-card" style="border-color: rgba(34,197,94,.35); background: rgba(34,197,94,.10);">
            <div style="font-weight:800; color:#c7f9d4;">{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="sheet-card" style="border-color: rgba(239,68,68,.35); background: rgba(239,68,68,.10);">
            <div style="font-weight:800; color:#fecaca;">{{ session('error') }}</div>
        </div>
    @endif

    @if ($syncJob)
        <div class="sheet-card" style="border-color: rgba(6,182,212,.35); background: rgba(6,182,212,.08);">
            <div style="font-weight:800; color:#9ae6ff;">Last Sync Details</div>
            <div style="margin-top:6px; color: rgba(234,240,255,.9); font-weight:700;">
                Sheet: {{ $syncJob->sheetSource?->tab_name ?? 'N/A' }}
                | Status: {{ ucfirst($syncJob->status) }}
                | Imported: {{ $syncJob->success_rows }} / {{ $syncJob->total_rows }}
                | Failed: {{ $syncJob->failed_rows }}
            </div>

            @if ($syncJob->errors->count() > 0)
                <div style="margin-top:10px;">
                    <div style="font-weight:800; color:#fecaca;">Errors (showing up to 10)</div>
                    <div style="overflow:auto; margin-top:8px;">
                        <table class="sheet-table">
                            <thead>
                                <tr>
                                    <th>Row</th>
                                    <th>Student Code</th>
                                    <th>Error</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($syncJob->errors->take(10) as $err)
                                    <tr>
                                        <td>{{ $err->row_number }}</td>
                                        <td>{{ $err->student_code ?? '-' }}</td>
                                        <td>{{ $err->error }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($syncJob->errors->count() > 10)
                        <div style="margin-top:6px; color: rgba(168,179,207,.9); font-size:12px;">
                            More errors exist ({{ $syncJob->errors->count() }} total). Check logs or re-sync after fixing the sheet.
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif

    @if ($sheetSources->count() > 0)
        <section class="sheet-card" style="overflow:auto; max-height: 560px;">
            <table class="sheet-table">
                <thead>
                    <tr>
                        <th>Sheet ID</th>
                        <th>Sheet Tab</th>
                        <th>Type</th>
                        <th>Class</th>
                        <th>Subject/Term</th>
                        <th>Status</th>
                        <th>Last Synced</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sheetSources as $source)
                        <tr>
                            <td class="sheet-id">
                                {{ Str::limit($source->sheet_id, 22) }}
                                <button class="btn-chip" onclick="navigator.clipboard.writeText('{{ $source->sheet_id }}')">Copy</button>
                            </td>
                            <td>{{ $source->tab_name }}</td>
                            <td>
                                <span class="pill {{ $source->type === 'score' ? 'pill-score' : 'pill-att' }}">
                                    {{ ucfirst($source->type) }}
                                </span>
                            </td>
                            <td>{{ $source->schoolClass?->name ?? 'N/A' }}</td>
                            <td>
                                @if ($source->type === 'score')
                                    <div style="font-weight:700;">{{ $source->subject?->name ?? 'Any Subject' }}</div>
                                    <div style="color: rgba(168,179,207,.85); font-size:12px;">{{ $source->term?->name ?? 'Any Term' }}</div>
                                @else
                                    <div style="color: rgba(168,179,207,.85); font-size:12px;">{{ $source->term?->name ?? 'Any Term' }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="pill {{ $source->is_active ? 'pill-active' : 'pill-inactive' }}">
                                    {{ $source->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div style="color: rgba(168,179,207,.85); font-size:12px;">
                                    {{ $source->last_synced_at ? $source->last_synced_at->diffForHumans() : 'Never' }}
                                </div>
                            </td>
                            <td>
                                <div class="action-row">
                                    @if ($source->is_active)
                                        <form action="{{ route('admin.sheet.sync', $source) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-chip btn-sync" onclick="return confirm('Sync this sheet?')">Sync</button>
                                        </form>
                                    @endif

                                    <a href="{{ route('admin.sheet-sources.edit', $source) }}" class="btn-chip btn-edit">Edit</a>

                                    <form action="{{ route('admin.sheet-sources.destroy', $source) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-chip btn-del" onclick="return confirm('Delete this sheet source?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    @else
        <section class="sheet-card" style="text-align:center; padding:28px;">
            <div style="font-weight:800;">No sheet sources registered yet.</div>
            <div style="margin-top:8px; color: rgba(168,179,207,.9);">
                <a href="{{ route('admin.sheet-sources.create') }}" class="btn-hero" style="display:inline-flex;">Register your first Google Sheet</a>
            </div>
        </section>
    @endif

    <section class="guide">
        <h2>How to Set Up a Sheet</h2>
        <ol>
            <li>Create Google Sheet with headers: <code>student_code, [subjects], ...</code></li>
            <li>Share Sheet with service account email (check documentation)</li>
            <li>Click "Register New Sheet" and fill in the form</li>
            <li>Click Sync to import data</li>
            <li>Check results in the sync history</li>
        </ol>
        <div style="margin-top:10px; font-size:12px; color: rgba(168,179,207,.85);">
            See <a href="{{ asset('SCORE_IMPORT_SETUP_GUIDE.md') }}" target="_blank" style="color:#9ae6ff;">full setup guide</a> for details.
        </div>
    </section>
</div>
@endsection
