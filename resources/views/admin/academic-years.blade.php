@extends('layouts.app')
@section('content')

<style>
  .grid{display:grid; gap:14px;}
  .grid-2{grid-template-columns: repeat(2, minmax(0,1fr));}
  .card{
    border:1px solid rgba(255,255,255,.10);
    background: rgba(255,255,255,.05);
    border-radius: 18px;
    padding: 14px;
  }
  .card h3{margin:0; font-size:14px; font-weight:800;}
  .muted{color: rgba(168,179,207,.95); font-weight:600;}

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
  .btn-primary{
    border-color: rgba(79,70,229,.35);
    background: rgba(79,70,229,.22);
  }
  .btn-danger{
    border-color: rgba(239,68,68,.35);
    background: rgba(239,68,68,.12);
  }
  .btn-edit{
    border-color: rgba(34,197,94,.35);
    background: rgba(34,197,94,.12);
  }

  .modal-overlay{
    display:none;
    position:fixed; top:0; left:0; right:0; bottom:0;
    background: rgba(146, 143, 192, 0.6);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index:999;
    align-items:center; justify-content:center;
  }
  .modal-overlay.active{
    display:flex;
  }

  .modal-card{
    background: rgba(28, 29, 35, 0.5);
    border: 1px solid rgba(255,255,255,.10);
    border-radius: 18px;
    padding: 20px;
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    animation: slideUp .3s ease;
  }

  @keyframes slideUp{
    from{ transform: translateY(20px); opacity: 0; }
    to{ transform: translateY(0); opacity: 1; }
  }

  .modal-header{
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255,255,255,.10);
  }

  .modal-header h2{
    margin: 0;
    font-size: 16px;
    font-weight: 900;
  }

  .modal-close{
    background: none;
    border: none;
    color: #eaf0ff;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .modal-close:hover{
    color: #f87171;
  }

  .form-group{
    margin-bottom: 16px;
  }

  .form-group label{
    display: block;
    font-weight: 800;
    margin-bottom: 6px;
    color: #eaf0ff;
  }

  .form-group input{
    width: 100%;
    padding: 10px 12px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 12px;
    color: #eaf0ff;
    font-size: 14px;
  }

  .form-group input:focus{
    outline: none;
    background: rgba(255,255,255,.12);
    border-color: rgba(79,70,229,.5);
    box-shadow: 0 0 0 3px rgba(79,70,229,.1);
  }

  .form-group small{
    display: block;
    margin-top: 4px;
    color: rgba(168,179,207,.95);
  }

  .checkbox-group{
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .checkbox-group input[type="checkbox"]{
    width: 18px;
    height: 18px;
    cursor: pointer;
  }

  .checkbox-group label{
    margin: 0;
    cursor: pointer;
  }

  .error-text{
    color: #ef4444;
    font-size: 12px;
    margin-top: 4px;
    font-weight: 600;
  }

  .form-actions{
    display: flex;
    gap: 12px;
    margin-top: 20px;
  }

  .table{
    width:100%;
    border-collapse: separate;
    border-spacing: 0 10px;
  }
  .table th{
    text-align:left;
    font-size:12px;
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

  .badge{
    font-size:12px; font-weight:800;
    padding:6px 10px; border-radius:999px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.06);
    display:inline-block;
  }
  .badge.success{border-color: rgba(34,197,94,.35); background: rgba(34,197,94,.12);}
  .badge.warn{border-color: rgba(245,158,11,.35); background: rgba(245,158,11,.12);}

  .section-title{
    display:flex; align-items:center; justify-content:space-between; gap:10px;
    margin-bottom:10px;
  }
  .section-title h2{margin:0; font-size:14px; font-weight:900;}
</style>

<div class="card">
  <div class="section-title">
    <h2><i class="fas fa-calendar"></i> Academic Years</h2>
    <button type="button" class="btn-soft btn-primary" onclick="openModal()">
      <i class="fas fa-plus"></i> New Year
    </button>
  </div>

  @if(session('success'))
    <div style="padding:12px; background: rgba(34,197,94,.12); border: 1px solid rgba(34,197,94,.35); border-radius:12px; margin-bottom:12px; color:#22c55e;">
      {{ session('success') }}
    </div>
  @endif

  <table class="table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($years as $year)
        <tr>
          <td><b>{{ $year->name }}</b></td>
          <td>{{ optional($year->start_date)->format('M d, Y') ?? 'N/A' }}</td>
          <td>{{ optional($year->end_date)->format('M d, Y') ?? 'N/A' }}</td>
          <td>
            @if($year->is_current)
              <span class="badge success">Current</span>
            @else
              <span class="badge warn">Inactive</span>
            @endif
          </td>
          <td style="width:120px;">
            <button type="button" class="btn-soft btn-edit" style="font-size:11px; padding:6px 8px; border:none; background:inherit; cursor:pointer;" onclick="editYear({{ $year->id }})">
              <i class="fas fa-edit"></i> Edit
            </button>
            <form method="POST" action="{{ route('admin.academic-years.destroy', $year) }}" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-soft btn-danger" style="font-size:11px; padding:6px 8px; border:none; background:inherit; cursor:pointer;" onclick="return confirm('Delete this year?')">
                <i class="fas fa-trash"></i> Delete
              </button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" style="text-align:center; color:rgba(168,179,207,.95);">
            No academic years found. <button type="button" onclick="openModal()" style="background:none; border:none; color:#4f46e5; cursor:pointer; font-weight:800; text-decoration:underline;">Create one</button>
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if($years->hasPages())
    <div style="margin-top:12px; display:flex; justify-content:center; gap:8px;">
      {{ $years->links() }}
    </div>
  @endif
</div>

<!-- MODAL -->
<div id="modalOverlay" class="modal-overlay" onclick="closeModal(event)">
  <div class="modal-card" onclick="event.stopPropagation()">
    <div class="modal-header">
      <h2 id="modalTitle"><i class="fas fa-calendar"></i> New Academic Year</h2>
      <button type="button" class="modal-close" onclick="closeModal()">×</button>
    </div>

    @if ($errors->any())
      <div style="padding:12px; background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.35); border-radius:12px; margin-bottom:16px;">
        <ul style="margin:0; padding-left:20px;">
          @foreach ($errors->all() as $error)
            <li style="color:#f87171;">{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form id="modalForm" method="POST" action="{{ route('admin.academic-years.store') }}" onsubmit="handleFormSubmit(event)">
      @csrf
      <input type="hidden" name="_method" id="methodField" value="POST">

      <div class="form-group">
        <label for="name">Name <span style="color:#f87171;">*</span></label>
        <input type="text" id="name" name="name"
          value="{{ old('name', '') }}"
          placeholder="e.g., 2025-2026" required>
        <small>Academic year name or period</small>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div class="form-group">
          <label for="start_date">Start Date <span style="color:#f87171;">*</span></label>
          <input type="date" id="start_date" name="start_date"
            value="{{ old('start_date', '') }}"
            required>
        </div>

        <div class="form-group">
          <label for="end_date">End Date <span style="color:#f87171;">*</span></label>
          <input type="date" id="end_date" name="end_date"
            value="{{ old('end_date', '') }}"
            required>
        </div>
      </div>

      <div class="form-group">
        <div class="checkbox-group">
          <input type="checkbox" id="is_current" name="is_current" value="1"
            {{ old('is_current', false) ? 'checked' : '' }}>
          <label for="is_current">Set as current academic year</label>
        </div>
        <small style="display:block; margin-top:6px;">
          Only one year can be current. Checking this will unset other years.
        </small>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-soft btn-primary">
          <i class="fas fa-save"></i> <span id="submitText">Create</span>
        </button>
        <button type="button" class="btn-soft" onclick="closeModal()">
          <i class="fas fa-times"></i> Cancel
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal() {
  document.getElementById('modalOverlay').classList.add('active');
  document.getElementById('modalTitle').innerHTML = '<i class="fas fa-calendar"></i> New Academic Year';
  document.getElementById('modalForm').action = "{{ route('admin.academic-years.store') }}";
  document.getElementById('methodField').value = 'POST';
  document.getElementById('submitText').innerText = 'Create';
  document.getElementById('modalForm').reset();

  document.getElementById('name').value = '';
  document.getElementById('start_date').value = '';
  document.getElementById('end_date').value = '';
  document.getElementById('is_current').checked = false;
}

function closeModal(e) {
  if (e && e.target.id !== 'modalOverlay') return;
  document.getElementById('modalOverlay').classList.remove('active');
}

function editYear(yearId) {
  fetch(`{{ route('admin.academic-years.index') }}/${yearId}/edit`, {
    headers: {
      'Accept': 'application/json'
    }
  })
    .then(r => {
      if (!r.ok) throw new Error('Failed to load year');
      return r.json();
    })
    .then(data => {
      document.getElementById('modalTitle').innerHTML = '<i class="fas fa-calendar"></i> Edit Academic Year';
      document.getElementById('modalForm').action = `{{ route('admin.academic-years.index') }}/${yearId}`;
      document.getElementById('methodField').value = 'PUT';
      document.getElementById('submitText').innerText = 'Update';

      document.getElementById('name').value = data.name || '';
      document.getElementById('start_date').value = data.start_date || '';
      document.getElementById('end_date').value = data.end_date || '';
      document.getElementById('is_current').checked = data.is_current == 1;

      openModal();
    })
    .catch(err => {
      alert('Error loading year data: ' + err.message);
    });
}

function handleFormSubmit(e) {
  e.preventDefault();
  const form = document.getElementById('modalForm');
  const formData = new FormData(form);

  fetch(form.action, {
    method: form.method === 'POST' ? 'POST' : 'POST',
    body: formData,
    headers: {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
    .then(r => {
      if (r.status === 422) {
        return r.json().then(data => {
          alert('Validation error: ' + Object.values(data.errors).flat().join(', '));
          throw new Error('Validation failed');
        });
      }
      if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`);
      return r.json();
    })
    .then(data => {
      closeModal();
      setTimeout(() => {
        window.location.reload();
      }, 300);
    })
    .catch(err => {
      if (!err.message.includes('Validation')) {
        console.error('Error:', err);
        alert('Error: ' + err.message);
      }
    });
}

// Close modal when pressing Escape
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') closeModal();
});
</script>

@endsection
