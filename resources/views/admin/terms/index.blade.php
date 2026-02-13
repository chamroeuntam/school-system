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
  .muted{color: rgba(168,179,207,.95); font-weight:600;}
  .header{
    display:flex; align-items:center; justify-content:space-between; gap:10px;
    margin-bottom: 12px;
  }
  .btn-soft{
    display:inline-flex; align-items:center; gap:8px;
    padding:10px 12px; border-radius:14px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.06);
    color: #eaf0ff; font-weight:800;
    text-decoration:none;
    transition: transform .15s ease, background .15s ease;
    cursor: pointer;
  }
  .btn-soft:hover{background: rgba(255,255,255,.10); transform: translateY(-1px);}
  .btn-primary{border-color: rgba(79,70,229,.35); background: rgba(79,70,229,.22);}
  .btn-danger{border-color: rgba(239,68,68,.35); background: rgba(239,68,68,.12);}
  .btn-edit{border-color: rgba(34,197,94,.35); background: rgba(34,197,94,.12);}

  table{width:100%; border-collapse: collapse; font-size:13px;}
  th, td{text-align:left; padding:12px 10px; border-bottom: 1px solid rgba(255,255,255,.08);}
  th{color: rgba(168,179,207,.95); font-weight:800;}
  tr:hover{background: rgba(255,255,255,.03);}

  /* Modal */
  .modal-overlay{
    position:fixed; inset:0; z-index:999;
    background: rgba(0,0,0,.75);
    backdrop-filter: blur(4px);
    display:none; align-items:center; justify-content:center;
  }
  .modal-overlay.show{display:flex;}
  .modal-content{
    background: rgba(15,23,42,.98);
    border:1px solid rgba(255,255,255,.15);
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(0,0,0,.6);
    width:90%; max-width: 500px;
    padding: 24px;
  }
  .modal-content h2{margin:0 0 16px; font-size:18px; font-weight:900;}
  .form-group{margin-bottom: 16px;}
  .form-group label{display:block; margin-bottom:6px; font-weight:700; font-size:13px; color: rgba(255,255,255,.85);}
  .form-group input, .form-group select{
    width:100%; padding:10px 12px; border-radius:10px;
    border:1px solid rgba(255,255,255,.15);
    background: rgba(255,255,255,.08);
    color: #fff; font:inherit; font-weight:600;
  }
  .form-actions{display:flex; gap:10px; margin-top:20px;}
</style>

<div class="grid">
  <div class="card">
    <div class="header">
      <div>
        <h2 style="margin:0; font-size:16px; font-weight:900;">ឆមាស (Semesters/Terms)</h2>
        <div class="muted">គ្រប់គ្រងឆមាសសិក្សា</div>
      </div>
      <button class="btn-soft btn-primary" onclick="openModal()">
        <i class="fas fa-plus"></i> បន្ថែមឆមាស
      </button>
    </div>

    @if(session('success'))
      <div style="background: rgba(34,197,94,.15); border:1px solid rgba(34,197,94,.3); border-radius:12px; padding:12px; margin-bottom:12px;">
        <span style="color:#22c55e; font-weight:700;">✓ {{ session('success') }}</span>
      </div>
    @endif

    @if($terms->isEmpty())
      <div style="text-align:center; padding:40px; color:rgba(168,179,207,.9);">
        <i class="fas fa-calendar" style="font-size:48px; margin-bottom:12px; opacity:.3;"></i>
        <p style="margin:0; font-weight:700;">មិនមានឆមាសនៅឡើយទេ។ សូមបន្ថែមឆមាសថ្មី។</p>
      </div>
    @else
      <table>
        <thead>
          <tr>
            <th>ឆ្នាំសិក្សា</th>
            <th>ឈ្មោះឆមាស</th>
            <th>ថ្ងៃចាប់ផ្តើម</th>
            <th>ថ្ងៃបញ្ចប់</th>
            <th style="text-align:right;">សកម្មភាព</th>
          </tr>
        </thead>
        <tbody>
          @foreach($terms as $term)
            <tr>
              <td style="font-weight:700;">{{ $term->academicYear->name ?? 'N/A' }}</td>
              <td style="font-weight:700;">{{ $term->name }}</td>
              <td>{{ $term->start_date ? $term->start_date->format('Y-m-d') : '-' }}</td>
              <td>{{ $term->end_date ? $term->end_date->format('Y-m-d') : '-' }}</td>
              <td style="text-align:right;">
                <button class="btn-soft btn-edit" onclick="editTerm({{ $term->id }}, '{{ $term->name }}', {{ $term->academic_year_id }}, '{{ $term->start_date ? $term->start_date->format('Y-m-d') : '' }}', '{{ $term->end_date ? $term->end_date->format('Y-m-d') : '' }}')" style="font-size:12px; padding:8px 10px;">
                  <i class="fas fa-edit"></i> កែ
                </button>
                <form method="POST" action="{{ route('admin.terms.destroy', $term) }}" style="display:inline;" onsubmit="return confirm('លុបឆមាសនេះ?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-soft btn-danger" style="font-size:12px; padding:8px 10px;">
                    <i class="fas fa-trash"></i> លុប
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
</div>

<!-- Modal -->
<div class="modal-overlay" id="termModal">
  <div class="modal-content">
    <h2 id="modalTitle">បន្ថែមឆមាស</h2>

    <form method="POST" action="{{ route('admin.terms.store') }}" id="termForm">
      @csrf
      <input type="hidden" name="_method" value="POST" id="formMethod">
      <input type="hidden" name="term_id" id="termId">

      <div class="form-group">
        <label>ឆ្នាំសិក្សា *</label>
        <select name="academic_year_id" required>
          <option value="">-- ជ្រើសរើស --</option>
          @foreach($academicYears as $year)
            <option value="{{ $year->id }}">{{ $year->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>ឈ្មោះឆមាស *</label>
        <input type="text" name="name" placeholder="ឆមាសទី១, Semester 1, Term 1" required>
      </div>

      <div class="form-group">
        <label>ថ្ងៃចាប់ផ្តើម</label>
        <input type="date" name="start_date">
      </div>

      <div class="form-group">
        <label>ថ្ងៃបញ្ចប់</label>
        <input type="date" name="end_date">
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-soft btn-primary" style="flex:1;">
          <i class="fas fa-save"></i> រក្សាទុក
        </button>
        <button type="button" class="btn-soft" onclick="closeModal()" style="flex:1;">
          <i class="fas fa-times"></i> បោះបង់
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal() {
  document.getElementById('termModal').classList.add('show');
  document.getElementById('modalTitle').textContent = 'បន្ថែមឆមាស';
  document.getElementById('termForm').reset();
  document.getElementById('formMethod').value = 'POST';
  document.getElementById('termForm').action = '{{ route("admin.terms.store") }}';
}

function closeModal() {
  document.getElementById('termModal').classList.remove('show');
}

function editTerm(id, name, academicYearId, startDate, endDate) {
  document.getElementById('termModal').classList.add('show');
  document.getElementById('modalTitle').textContent = 'កែប្រែឆមាស';

  const form = document.getElementById('termForm');
  form.action = `/admin/terms/${id}`;
  document.getElementById('formMethod').value = 'PUT';

  form.querySelector('[name="name"]').value = name;
  form.querySelector('[name="academic_year_id"]').value = academicYearId;
  form.querySelector('[name="start_date"]').value = startDate || '';
  form.querySelector('[name="end_date"]').value = endDate || '';
}

// Close modal on outside click
document.getElementById('termModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeModal();
  }
});

// Auto-reopen modal if there are validation errors
@if($errors->any())
  openModal();
@endif
</script>

@endsection
