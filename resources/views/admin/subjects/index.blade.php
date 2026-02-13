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

  .table{
    width:100%; border-collapse: collapse; font-size:14px;
    border:1px solid rgba(255,255,255,.10);
    border-radius: 12px; overflow:hidden;
  }
  .table th, .table td{ padding:10px 12px; text-align:left; }
  .table thead{ background: rgba(0,0,0,.12); }
  .table tbody tr{ border-top:1px solid rgba(255,255,255,.08); }
  .table tbody tr:hover{ background: rgba(255,255,255,.04); }

  .alert{
    padding:10px 12px; border-radius:12px; margin-bottom:12px;
    border:1px solid rgba(34,197,94,.35); background: rgba(34,197,94,.12);
    color: #c7f9d4; font-weight:700;
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
  .modal-overlay.active{ display:flex; }

  .modal-card{
    background: rgba(28, 29, 35, 0.5);
    border: 1px solid rgba(255,255,255,.10);
    border-radius: 18px;
    padding: 20px;
    max-width: 520px;
    width: 92%;
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

  .modal-close:hover{ color: #f87171; }

  .form-group{ margin-bottom: 14px; }
  .form-group label{ display:block; font-weight:800; margin-bottom:6px; }
  .form-group input{
    width:100%; padding:10px 12px; border-radius:12px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.08); color:#eaf0ff; font-weight:700;
  }

  .error{ color:#ffd3d3; font-weight:700; font-size:12px; margin-top:4px; }

  @media (max-width: 900px){
    .table{ font-size:13px; }
  }
</style>

<div class="card">
  <div class="header">
    <div>
      <h2 style="margin:0; font-size:16px; font-weight:900;">មុខវិជ្ជា</h2>
      <div class="muted">គ្រប់គ្រងមុខវិជ្ជាទាំងអស់</div>
    </div>
    <button class="btn-soft btn-primary" type="button" onclick="openSubjectModal('create')">
      <i class="fas fa-plus"></i> បន្ថែមមុខវិជ្ជា
    </button>
  </div>

  @if (session('success'))
    <div class="alert">{{ session('success') }}</div>
  @endif

  <div style="overflow:auto;">
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($subjects as $subject)
          <tr>
            <td>{{ $subject->name }}</td>
            <td style="white-space:nowrap;">
              <a
                class="btn-soft btn-edit"
                href="{{ route('admin.subjects.edit', $subject) }}"
                data-id="{{ $subject->id }}"
                data-name="{{ $subject->name }}"
                onclick="openSubjectModal('edit', this); return false;"
              >
                <i class="fas fa-pen"></i> Edit
              </a>
              <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" style="display:inline;" onsubmit="return confirm('Delete this subject?');">
                @csrf
                @method('DELETE')
                <button class="btn-soft btn-danger" type="submit">
                  <i class="fas fa-trash"></i> Delete
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="2" class="muted">No subjects yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@php
  $formMode = old('_form_mode', 'create');
  $formSubjectId = old('subject_id');
@endphp

<div class="modal-overlay" id="subjectModal" data-update-url="{{ url('/admin/subjects') }}/__ID__">
  <div class="modal-card">
    <div class="modal-header">
      <h2 id="modalTitle">បន្ថែមមុខវិជ្ជា</h2>
      <button class="modal-close" type="button" onclick="closeSubjectModal()">&times;</button>
    </div>

    <form method="POST" id="subjectForm" action="{{ route('admin.subjects.store') }}">
      @csrf
      <input type="hidden" id="subjectMethod" name="_method" value="PUT" disabled>
      <input type="hidden" id="formMode" name="_form_mode" value="{{ $formMode }}">
      <input type="hidden" id="subjectId" name="subject_id" value="{{ $formSubjectId }}">

      <div class="form-group">
        <label for="s_name">Name</label>
        <input id="s_name" name="name" value="{{ old('name') }}" required />
        @error('name') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div style="display:flex; gap:10px;">
        <button class="btn-soft btn-primary" type="submit">
          <i class="fas fa-save"></i> Save
        </button>
        <button class="btn-soft" type="button" onclick="closeSubjectModal()">Back</button>
      </div>
    </form>
  </div>
</div>

<script>
  const modal = document.getElementById('subjectModal');
  const form = document.getElementById('subjectForm');
  const methodInput = document.getElementById('subjectMethod');
  const formModeInput = document.getElementById('formMode');
  const subjectIdInput = document.getElementById('subjectId');
  const modalTitle = document.getElementById('modalTitle');
  const updateUrlTemplate = modal.dataset.updateUrl;

  const fields = {
    name: document.getElementById('s_name'),
  };

  function openSubjectModal(mode, el) {
    if (mode === 'edit') {
      const id = el.dataset.id;
      modalTitle.textContent = 'កែប្រែមុខវិជ្ជា';
      form.action = updateUrlTemplate.replace('__ID__', id);
      methodInput.disabled = false;
      formModeInput.value = 'edit';
      subjectIdInput.value = id;

      fields.name.value = el.dataset.name || '';
    } else {
      modalTitle.textContent = 'បន្ថែមមុខវិជ្ជា';
      form.action = '{{ route('admin.subjects.store') }}';
      methodInput.disabled = true;
      formModeInput.value = 'create';
      subjectIdInput.value = '';

      fields.name.value = '';
    }

    modal.classList.add('active');
  }

  function closeSubjectModal() {
    modal.classList.remove('active');
  }

  const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
  if (hasErrors) {
    const oldMode = '{{ $formMode }}';
    const oldId = '{{ $formSubjectId }}';

    if (oldMode === 'edit' && oldId) {
      modalTitle.textContent = 'កែប្រែមុខវិជ្ជា';
      form.action = updateUrlTemplate.replace('__ID__', oldId);
      methodInput.disabled = false;
      formModeInput.value = 'edit';
      subjectIdInput.value = oldId;
    } else {
      modalTitle.textContent = 'បន្ថែមមុខវិជ្ជា';
      form.action = '{{ route('admin.subjects.store') }}';
      methodInput.disabled = true;
      formModeInput.value = 'create';
      subjectIdInput.value = '';
    }

    modal.classList.add('active');
  }

  const params = new URLSearchParams(window.location.search);
  if (params.get('create') === '1') {
    openSubjectModal('create');
  }
</script>

@endsection
