@extends('layouts.app')

@section('content')
<style>
  .grid{display:grid; gap:14px;}
  .card{
    border:1px solid rgba(255,255,255,.10);
    background: rgba(255,255,255,.05);
    border-radius: 18px;
    padding: 14px;
  }
  .header{
    display:flex; align-items:center; justify-content:space-between; gap:10px;
    margin-bottom: 14px;
  }
  .btn-soft{
    display:inline-flex; align-items:center; gap:8px;
    padding:11px 14px; border-radius:14px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.06);
    color: #eaf0ff; font-weight:800; font-size:14px;
    text-decoration:none;
    transition: transform .15s ease, background .15s ease;
    cursor: pointer;
  }
  .btn-soft:hover{background: rgba(255,255,255,.10); transform: translateY(-1px);}
  .btn-primary{border-color: rgba(79,70,229,.35); background: rgba(79,70,229,.22);}
  .btn-danger{border-color: rgba(239,68,68,.35); background: rgba(239,68,68,.12);}
  .btn-edit{border-color: rgba(34,197,94,.35); background: rgba(34,197,94,.12);}

  .table{
    width:100%; border-collapse: collapse; font-size:15px;
    border:1px solid rgba(255,255,255,.10);
    border-radius: 12px; overflow:hidden;
  }
  .table th, .table td{ padding:12px; text-align:left; }
  .table thead{ background: rgba(0,0,0,.12); }
  .table tbody tr{ border-top:1px solid rgba(255,255,255,.08); }
  .table tbody tr:hover{ background: rgba(255,255,255,.04); }

  .badge{
    font-size:13px; font-weight:800;
    padding:6px 10px; border-radius:999px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.06);
  }
  .badge.published{border-color: rgba(34,197,94,.35); background: rgba(34,197,94,.12); color:#c7f9d4;}
  .badge.draft{border-color: rgba(245,158,11,.35); background: rgba(245,158,11,.12); color:#fef3c7;}

  .alert{
    padding:12px 14px; border-radius:14px; margin-bottom:14px;
    border:1px solid rgba(34,197,94,.35); background: rgba(34,197,94,.12);
    color: #c7f9d4; font-weight:700; font-size:14px;
  }

  .muted{color: rgba(168,179,207,.95); font-weight:600; font-size:14px;}
  h2{margin:0; font-size:22px; font-weight:900;}
  .text-sm{font-size:13px;}

  @media (max-width: 900px){
    .table{ font-size:14px; }
    .table th, .table td{ padding:10px; }
  }
</style>

<div class="card">
  <div class="header">
    <div>
      <h2><i class="fas fa-bullhorn"></i> គ្រប់គ្រងមតិប្រកាស</h2>
      <div class="muted">បង្កើត និងកែប្រែប្រកាសសម្រាប់សិស្ស និងគ្រូ</div>
    </div>
    <a class="btn-soft btn-primary" href="{{ route('admin.announcements.create') }}">
      <i class="fas fa-plus"></i> បង្កើតមតិប្រកាសថ្មី
    </a>
  </div>

  @if (session('success'))
    <div class="alert">{{ session('success') }}</div>
  @endif

  <div style="overflow-x:auto;">
    <table class="table">
      <thead>
        <tr>
          <th>ចំណងជើង</th>
          <th>ក្រុមគ្រូបង្រៀន</th>
          <th>ស្ថានភាព</th>
          <th>បង្កើត</th>
          <th>ដំណើរការ</th>
        </tr>
      </thead>
      <tbody>
        @forelse($announcements as $ann)
          <tr>
            <td>
              <b style="font-size:15px;">{{ $ann->title }}</b>
              <div class="text-sm" style="margin-top:4px; color: rgba(168,179,207,.9); max-width:400px;">
                {{ Str::limit($ann->message, 80) }}
              </div>
            </td>
            <td>
              <div class="text-sm">
                <b>{{ $ann->user->name ?? 'Admin' }}</b>
                <div style="color: rgba(168,179,207,.9);">{{ $ann->user->role ?? 'admin' }}</div>
              </div>
            </td>
            <td>
              @if($ann->is_published)
                <span class="badge published">
                  <i class="fas fa-check-circle"></i> ផ្សាយប្រកាស
                </span>
              @else
                <span class="badge draft">
                  <i class="fas fa-file-alt"></i> ព្រាង
                </span>
              @endif
            </td>
            <td class="text-sm">
              <div>{{ $ann->created_at->format('M d, Y') }}</div>
              <div style="color: rgba(168,179,207,.9);">{{ $ann->created_at->format('H:i') }}</div>
            </td>
            <td>
              <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a class="btn-soft btn-edit" style="text-decoration:none;" href="{{ route('admin.announcements.edit', $ann) }}">
                  <i class="fas fa-edit"></i> កែប្រែ
                </a>
                <form method="POST" action="{{ route('admin.announcements.destroy', $ann) }}" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button class="btn-soft btn-danger" type="submit" onclick="return confirm('Are you sure?')">
                    <i class="fas fa-trash"></i> លុប
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align:center; padding:30px; color: rgba(168,179,207,.6);">
              <i class="fas fa-inbox" style="font-size:24px; margin-bottom:10px; display:block;"></i>
              មិនមានមតិប្រកាស ចូលនិង<a href="{{ route('admin.announcements.create') }}" style="color: rgba(79,70,229,1);">បង្កើតមួយ</a>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($announcements->hasPages())
    <div style="margin-top:14px; text-align:center;">
      {{ $announcements->links() }}
    </div>
  @endif
</div>

@endsection
