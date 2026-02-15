{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app') {{-- ប្តូរឈ្មោះ layout តាម project អ្នក --}}
@section('content')

<style>
  /* Dashboard-only styles */
  .grid{display:grid; gap:14px;}
  .grid-4{grid-template-columns: repeat(4, minmax(0,1fr));}
  .grid-2{grid-template-columns: repeat(2, minmax(0,1fr));}
  .card{
    border:1px solid rgba(255,255,255,.10);
    background: rgba(255,255,255,.05);
    border-radius: 18px;
    padding: 14px;
  }
  .card h3{margin:0; font-size:15px; font-weight:800;}
  .muted{color: rgba(168,179,207,.95); font-weight:600; font-size:14px;}
  .kpi{
    display:flex; justify-content:space-between; align-items:flex-start; gap:10px;
  }
  .kpi .value{font-size:28px; font-weight:900; letter-spacing:.3px; margin-top:8px;}
  .kpi .badge{
    font-size:13px; font-weight:800;
    padding:6px 10px; border-radius:999px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.06);
  }
  .badge.success{border-color: rgba(34,197,94,.35); background: rgba(34,197,94,.12);}
  .badge.warn{border-color: rgba(245,158,11,.35); background: rgba(245,158,11,.12);}
  .badge.info{border-color: rgba(6,182,212,.35); background: rgba(6,182,212,.12);}
  .badge.danger{border-color: rgba(239,68,68,.35); background: rgba(239,68,68,.12);}

  .row{display:flex; align-items:center; justify-content:space-between; gap:12px;}
  .btn-soft{
    display:inline-flex; align-items:center; gap:8px;
    padding:11px 14px; border-radius:14px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.06);
    color: #eaf0ff; font-weight:800; font-size:14px;
    text-decoration:none;
    transition: transform .15s ease, background .15s ease;
  }
  .btn-soft:hover{background: rgba(255,255,255,.10); transform: translateY(-1px);}
  .btn-primary{
    border-color: rgba(79,70,229,.35);
    background: rgba(79,70,229,.22);
  }

  .table{
    width:100%;
    border-collapse: separate;
    border-spacing: 0 10px;
  }
  .table th{
    text-align:left;
    font-size:13px;
    color: rgba(168,179,207,.95);
    font-weight:900;
    padding: 0 10px;
  }
  .table td{
    padding: 12px 10px;
    background: rgba(255,255,255,.05);
    border:1px solid rgba(255,255,255,.10);
    border-left:none; border-right:none;
    font-weight:700;
    font-size: 15px;
  }
  .table tr td:first-child{
    border-left:1px solid rgba(255,255,255,.10);
    border-top-left-radius:14px;
    border-bottom-left-radius:14px;
  }
  .table tr td:last-child{
    border-right:1px solid rgba(255,255,255,.10);
    border-top-right-radius:14px;
    border-bottom-right-radius:14px;
  }

  .progress{
    height:10px; border-radius:999px;
    background: rgba(255,255,255,.08);
    overflow:hidden;
    border:1px solid rgba(255,255,255,.10);
  }
  .progress > div{
    height:100%;
    background: linear-gradient(90deg, rgba(6,182,212,.7), rgba(79,70,229,.7));
    border-radius:999px;
    width:0%;
  }

  .list{display:flex; flex-direction:column; gap:10px;}
  .item{
    display:flex; gap:12px; align-items:flex-start;
    padding:12px; border-radius:16px;
    border:1px solid rgba(255,255,255,.10);
    background: rgba(255,255,255,.05);
  }
  .dot{
    width:10px; height:10px; border-radius:999px; margin-top:4px;
    background: rgba(6,182,212,1);
    box-shadow: 0 0 0 6px rgba(6,182,212,.12);
  }
  .item b{font-weight:900; font-size:15px;}
  .item small{display:block; margin-top:4px; color: rgba(168,179,207,.95); font-weight:600; font-size:13px;}

  .section-title{
    display:flex; align-items:center; justify-content:space-between; gap:10px;
    margin-bottom:14px;
  }
  .section-title h2{margin:0; font-size:16px; font-weight:900;}
  .section-title a{font-size:13px; color: rgba(234,240,255,.9); font-weight:800; text-decoration:none; opacity:.9;}
  .section-title a:hover{opacity:1; text-decoration:underline;}

  @media (max-width: 1100px){
    .grid-4{grid-template-columns: repeat(2, minmax(0,1fr));}
    .grid-2{grid-template-columns: 1fr;}
  }
  @media (max-width: 560px){
    .grid-4{grid-template-columns: 1fr;}
  }
</style>

{{-- KPI Cards --}}
<div class="grid grid-4">
  <div class="card">
    <div class="kpi">
      <div>
        <h3><i class="fas fa-user-graduate"></i> សិស្សសរុប</h3>
        <div class="value">{{ $totalStudents ?? 0 }}</div>
        <div class="muted">បច្ចុប្បន្នឆ្នាំសិក្សា</div>
      </div>
      <span class="badge info">+{{ $studentsNewThisMonth ?? 0 }} ខែនេះ</span>
    </div>
  </div>

  <div class="card">
    <div class="kpi">
      <div>
        <h3><i class="fas fa-chalkboard-teacher"></i> គ្រូបង្រៀន</h3>
        <div class="value">{{ $totalTeachers ?? 0 }}</div>
        <div class="muted">គ្រូ/បុគ្គលិក</div>
      </div>
      <span class="badge success">Active</span>
    </div>
  </div>

  <div class="card">
    <div class="kpi">
      <div>
        <h3><i class="fas fa-calendar-check"></i> វត្តមានថ្ងៃនេះ</h3>
        <div class="value">{{ $attendanceTodayPercent ?? 0 }}%</div>
        <div class="muted">សិស្សមករៀន</div>
      </div>
      <span class="badge {{ ($attendanceTodayPercent ?? 0) >= 90 ? 'success' : 'warn' }}">
        {{ ($attendanceTodayPercent ?? 0) >= 90 ? 'ល្អ' : 'ត្រូវពិនិត្យ' }}
      </span>
    </div>
    <div style="margin-top:10px" class="progress">
      <div style="width: {{ $attendanceTodayPercent ?? 0 }}%"></div>
    </div>
  </div>

  <div class="card">
    <div class="kpi">
      <div>
        <h3><i class="fas fa-clipboard-list"></i> ពិន្ទុកំពុងបញ្ចូល</h3>
        <div class="value">{{ $pendingGrades ?? 0 }}</div>
        <div class="muted">ចាំបញ្ចប់/ពិនិត្យ</div>
      </div>
      <span class="badge warn">Pending</span>
    </div>
  </div>
</div>

{{-- Quick actions + Status --}}
<div class="grid grid-2" style="margin-top:14px;">
  <div class="card">
    <div class="section-title">
      <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
      <a href="/admin/settings">ទៅការកំណត់ →</a>
    </div>

    <div style="display:flex; flex-wrap:wrap; gap:10px;">
      <a class="btn-soft btn-primary" href="/admin/students?create=1">
        <i class="fas fa-user-plus"></i> បន្ថែមសិស្ស
      </a>
      <a class="btn-soft" href="/admin/teachers/create">
        <i class="fas fa-user-tie"></i> បន្ថែមគ្រូ
      </a>
      <a class="btn-soft" href="/admin/reset">
        <i class="fas fa-key"></i> Reset PIN/Password
      </a>
      <a class="btn-soft" href="/admin/attendance">
        <i class="fas fa-calendar-check"></i> វត្តមាន
      </a>
      <a class="btn-soft" href="/admin/grades">
        <i class="fas fa-square-poll-vertical"></i> ពិន្ទុ
      </a>
      <a class="btn-soft" href="/admin/reports">
        <i class="fas fa-file-lines"></i> របាយការណ៍
      </a>
      @isset($sheetSource)
        <form method="POST" action="{{ route('admin.sheet.sync', $sheetSource) }}">
          @csrf
          <button class="btn-soft btn-primary" type="submit">
            <i class="fas fa-rotate"></i> Sync Sheet
          </button>
        </form>
      @endisset
    </div>

    <div style="margin-top:12px" class="muted">
      💡 Tip: អ្នកអាចភ្ជាប់ Google Sheet Import សម្រាប់វត្តមាន/ពិន្ទុ នៅម៉ឺនុយ “វត្តមាន/ពិន្ទុ”
    </div>
  </div>

  <div class="card">
    <div class="section-title">
      <h2><i class="fas fa-chart-line"></i> ស្ថានភាពសិក្សា (Sample)</h2>
      <a href="/admin/reports">មើលរបាយការណ៍ →</a>
    </div>

    <div class="grid" style="grid-template-columns: 1fr 1fr; gap:12px;">
      <div class="card" style="padding:12px; background: rgba(255,255,255,.04);">
        <h3 style="margin-bottom:8px;">វត្តមានមធ្យម</h3>
        <div class="value" style="font-size:22px;">{{ $avgAttendance ?? 91 }}%</div>
        <div class="progress" style="margin-top:8px;">
          <div style="width: {{ $avgAttendance ?? 91 }}%"></div>
        </div>
      </div>

      <div class="card" style="padding:12px; background: rgba(255,255,255,.04);">
        <h3 style="margin-bottom:8px;">សិស្សមានហានិភ័យ</h3>
        <div class="value" style="font-size:22px;">{{ $riskStudents ?? 18 }}</div>
        <div class="muted">វត្តមានទាប/ពិន្ទុធ្លាក់</div>
      </div>
    </div>

    <div style="margin-top:10px" class="muted">
      *នេះជា sample UI។ តម្លៃពិតអាចទាញពី DB ឬ import ពី Google Sheet បាន។
    </div>
  </div>
</div>

{{-- Announcements + Recent Activity --}}
<div class="grid grid-2" style="margin-top:14px;">
  <div class="card">
    <div class="section-title">
      <h2><i class="fas fa-bullhorn"></i> មតិប្រកាស (Today)</h2>
      <a href="/admin/announcements">គ្រប់គ្រង →</a>
    </div>

    <div style="overflow-x:auto;">
      <table class="table" style="margin:0;">
        <thead>
          <tr>
            <th style="font-size:13px; padding:8px 10px;">ឈ្មោះ</th>
            <th style="font-size:13px; padding:8px 10px;">មតិប្រកាស</th>
            <th style="font-size:13px; padding:8px 10px;">ពីកោ</th>
            <th style="font-size:13px; padding:8px 10px;">វេលាយ</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentAnnouncements as $ann)
            <tr>
              <td style="font-size:13px; padding:8px 10px;"><b>-</b></td>
              <td style="font-size:13px; padding:8px 10px;">{{ Str::limit($ann->message, 50) }}</td>
              <td style="font-size:13px; padding:8px 10px;">{{ $ann->user->name ?? 'Admin' }}</td>
              <td style="font-size:13px; padding:8px 10px;"><small>{{ $ann->published_at->diffForHumans() }}</small></td>
            </tr>
          @empty
            <tr>
              <td colspan="4" style="text-align:center; padding:20px; font-size:13px;">
                <span style="color: rgba(168,179,207,.6);">មិនទានមតិប្រកាស</span>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="section-title">
      <h2><i class="fas fa-clock"></i> Recent Activity</h2>
      <a href="/admin/logs">មើលទាំងអស់ →</a>
    </div>

    <div class="list">
      @forelse($recentActivities as $activity)
        <div class="item">
          <span class="dot"></span>
          <div>
            <b>{{ $activity->title }}</b>
            <small>{{ $activity->created_at?->diffForHumans() }}</small>
          </div>
        </div>
      @empty
        <div class="item">
          <span class="dot"></span>
          <div>
            <b>No recent activity yet</b>
            <small>Activity will appear here as users work</small>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</div>

{{-- Attendance Overview + by Class --}}
<div class="grid grid-2" style="margin-top:14px;">
  <div class="card">
    <div class="section-title">
      <h2><i class="fas fa-users"></i> វត្តមានតាមថ្នាក់ (Today)</h2>
      <a href="/admin/attendance">ទៅវត្តមាន →</a>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>ថ្នាក់</th>
          <th>មករៀន</th>
          <th>អវត្តមាន</th>
          <th>ភាគរយ</th>
        </tr>
      </thead>
      <tbody>
        @php
          $rows = $attendanceByClass ?? [
            ['name'=>'10A', 'present'=>38, 'absent'=>2],
            ['name'=>'10B', 'present'=>35, 'absent'=>5],
            ['name'=>'11A', 'present'=>40, 'absent'=>0],
            ['name'=>'12A', 'present'=>33, 'absent'=>3],
          ];
        @endphp

        @foreach($rows as $r)
          @php
            $total = max(1, ($r['present'] + $r['absent']));
            $pct = round(($r['present'] / $total) * 100);
          @endphp
          <tr>
            <td><b>{{ $r['name'] }}</b></td>
            <td>{{ $r['present'] }}</td>
            <td>{{ $r['absent'] }}</td>
            <td>
              <span class="badge {{ $pct >= 90 ? 'success' : ($pct >= 80 ? 'warn' : 'danger') }}">
                {{ $pct }}%
              </span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

@endsection
