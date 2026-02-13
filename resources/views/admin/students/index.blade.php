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

  .filter{
    display:flex;
    align-items:center;
    gap:8px;
    flex-wrap:wrap;
  }
  .filter label{ font-weight:800; font-size:12px; color: rgba(168,179,207,.95); }
  .filter select{
    padding:8px 10px;
    border-radius:12px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.08);
    color:#eaf0ff;
    font-weight:700;
  }

  .table{
    width:100%; border-collapse: collapse; font-size:14px;
    border:1px solid rgba(255,255,255,.10);
    border-radius: 12px; overflow:hidden;
  }
  .table th, .table td{ padding:10px 12px; text-align:left; }
  .table thead{ background: rgba(0,0,0,.12); }
  .table tbody tr{ border-top:1px solid rgba(255,255,255,.08); }
  .table tbody tr:hover{ background: rgba(255,255,255,.04); }

  .badge{
    font-size:12px; font-weight:800;
    padding:6px 10px; border-radius:999px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.06);
    display:inline-block;
  }
  .badge.success{border-color: rgba(34,197,94,.35); background: rgba(34,197,94,.12);}
  .badge.warn{border-color: rgba(245,158,11,.35); background: rgba(245,158,11,.12);}

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

  .modal-close:hover{ color: #f87171; }

  .form-group{ margin-bottom: 14px; }
  .form-group label{ display:block; font-weight:800; margin-bottom:6px; }
  .form-group input, .form-group select{
    width:100%; padding:10px 12px; border-radius:12px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.08); color:#eaf0ff; font-weight:700;
  }

  .date-row{
    display:grid;
    grid-template-columns: repeat(3, 1fr);
    gap:10px;
  }
  .date-row select{ min-height:40px; }

  .checkbox-group{ display:flex; align-items:center; gap:8px; }
  .checkbox-group input{ width:18px; height:18px; }

  .error{ color:#ffd3d3; font-weight:700; font-size:12px; margin-top:4px; }

  @media (max-width: 900px){
    .table{ font-size:13px; }
  }
</style>

<div class="card">
  <div class="header">
    <div>
      <h2 style="margin:0; font-size:16px; font-weight:900;">សិស្ស</h2>
      <div class="muted">បញ្ជីសិស្សទាំងអស់</div>
    </div>
    <div class="filter">
      <label for="class_id">Class</label>
      <form method="GET" action="{{ route('admin.students.index') }}">
        <select id="class_id" name="class_id" onchange="this.form.submit()">
          <option value="">All</option>
          @foreach ($classes as $class)
            <option value="{{ $class->id }}" {{ (string) $classId === (string) $class->id ? 'selected' : '' }}>
              {{ $class->name }}
            </option>
          @endforeach
        </select>
      </form>
      <button class="btn-soft btn-primary" type="button" onclick="openStudentModal('create')">
        <i class="fas fa-user-plus"></i> បន្ថែមសិស្ស
      </button>
    </div>
  </div>

  @if (session('success'))
    <div class="alert">{{ session('success') }}</div>
  @endif

  <div style="overflow:auto;">
    <table class="table">
      <thead>
        <tr>
          <th>Student Code</th>
          <th>ឈ្មោះ</th>
          <th>Class</th>
          <th>Gender</th>
          <th>DOB</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($students as $student)
          <tr>
            <td>{{ $student->student_code }}</td>
            <td>{{ $student->full_name }}</td>
            <td>{{ $student->class_name ?? '-' }}</td>
            <td>{{ $student->gender ?? '-' }}</td>
            <td>{{ $student->dob ?? '-' }}</td>
            <td>
              <span class="badge {{ $student->is_active ? 'success' : 'warn' }}">
                {{ $student->is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td style="white-space:nowrap;">
              <a
                class="btn-soft btn-edit"
                href="{{ route('admin.students.edit', $student) }}"
                data-id="{{ $student->id }}"
                data-code="{{ $student->student_code }}"
                data-name="{{ $student->full_name }}"
                data-class-id="{{ $student->class_id }}"
                data-gender="{{ $student->gender }}"
                data-dob="{{ $student->dob }}"
                data-active="{{ $student->is_active ? '1' : '0' }}"
                onclick="openStudentModal('edit', this); return false;"
              >
                <i class="fas fa-pen"></i> Edit
              </a>
              <form method="POST" action="{{ route('admin.students.destroy', $student) }}" style="display:inline;" onsubmit="return confirm('Delete this student?');">
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
            <td colspan="6" class="muted">No students yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@php
  $formMode = old('_form_mode', 'create');
  $formStudentId = old('student_id');
@endphp

<div class="modal-overlay" id="studentModal" data-update-url="{{ url('/admin/students') }}/__ID__">
  <div class="modal-card">
    <div class="modal-header">
      <h2 id="modalTitle">បន្ថែមសិស្ស</h2>
      <button class="modal-close" type="button" onclick="closeStudentModal()">&times;</button>
    </div>

    <form method="POST" id="studentForm" action="{{ route('admin.students.store') }}">
      @csrf
      <input type="hidden" id="studentMethod" name="_method" value="PUT" disabled>
      <input type="hidden" id="formMode" name="_form_mode" value="{{ $formMode }}">
      <input type="hidden" id="studentId" name="student_id" value="{{ $formStudentId }}">

      <div class="form-group">
        <label for="s_code">Student Code</label>
        <input id="s_code" name="student_code" value="{{ old('student_code') }}" required />
        @error('student_code') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="s_name">ឈ្មោះ</label>
        <input id="s_name" name="full_name" value="{{ old('full_name') }}" required />
        @error('full_name') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="s_class_id">Class</label>
        <select id="s_class_id" name="class_id">
          <option value="">--</option>
          @foreach ($classes as $class)
            <option value="{{ $class->id }}" {{ (string) old('class_id') === (string) $class->id ? 'selected' : '' }}>
              {{ $class->name }}
            </option>
          @endforeach
        </select>
        @error('class_id') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="s_gender">Gender</label>
        <select id="s_gender" name="gender">
          <option value="">--</option>
          <option value="M" {{ old('gender') === 'M' ? 'selected' : '' }}>M</option>
          <option value="F" {{ old('gender') === 'F' ? 'selected' : '' }}>F</option>
          <option value="O" {{ old('gender') === 'O' ? 'selected' : '' }}>O</option>
        </select>
        @error('gender') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="s_dob">DOB</label>
        <div class="date-row" id="dobPicker">
          <select id="s_dobYear" aria-label="ឆ្នាំ">
            <option value="">ឆ្នាំ</option>
          </select>
          <select id="s_dobMonth" aria-label="ខែ" disabled>
            <option value="">ខែ</option>
          </select>
          <select id="s_dobDay" aria-label="ថ្ងៃ" disabled>
            <option value="">ថ្ងៃ</option>
          </select>
        </div>
        <input type="hidden" id="s_dob" name="dob" value="{{ old('dob') }}" />
        @error('dob') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label>Status</label>
        <div class="checkbox-group">
          <input type="hidden" name="is_active" value="0">
          <input id="s_active" name="is_active" type="checkbox" value="1" {{ old('is_active', '1') ? 'checked' : '' }} />
          <label for="s_active">Active</label>
        </div>
        @error('is_active') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div style="display:flex; gap:10px;">
        <button class="btn-soft btn-primary" type="submit">
          <i class="fas fa-save"></i> Save
        </button>
        <button class="btn-soft" type="button" onclick="closeStudentModal()">Back</button>
      </div>
    </form>
  </div>
</div>

<script>
  const modal = document.getElementById('studentModal');
  const form = document.getElementById('studentForm');
  const methodInput = document.getElementById('studentMethod');
  const formModeInput = document.getElementById('formMode');
  const studentIdInput = document.getElementById('studentId');
  const modalTitle = document.getElementById('modalTitle');
  const updateUrlTemplate = modal.dataset.updateUrl;

  const fields = {
    code: document.getElementById('s_code'),
    name: document.getElementById('s_name'),
    classId: document.getElementById('s_class_id'),
    gender: document.getElementById('s_gender'),
    dob: document.getElementById('s_dob'),
    active: document.getElementById('s_active'),
  };

  const dobPicker = {
    yearEl: document.getElementById('s_dobYear'),
    monthEl: document.getElementById('s_dobMonth'),
    dayEl: document.getElementById('s_dobDay'),
    hiddenEl: document.getElementById('s_dob'),
  };

  function pad2(v) {
    return String(v).padStart(2, '0');
  }

  function setHiddenDate() {
    const y = dobPicker.yearEl.value;
    const m = dobPicker.monthEl.value;
    const d = dobPicker.dayEl.value;
    if (y && m && d) {
      dobPicker.hiddenEl.value = `${y}-${pad2(m)}-${pad2(d)}`;
    } else {
      dobPicker.hiddenEl.value = '';
    }
  }

  function rebuildDays() {
    const y = parseInt(dobPicker.yearEl.value, 10);
    const m = parseInt(dobPicker.monthEl.value, 10);
    dobPicker.dayEl.innerHTML = '<option value="">ថ្ងៃ</option>';
    if (!y || !m) {
      dobPicker.dayEl.disabled = true;
      setHiddenDate();
      return;
    }

    const daysInMonth = new Date(y, m, 0).getDate();
    for (let d = 1; d <= daysInMonth; d += 1) {
      const opt = document.createElement('option');
      opt.value = String(d);
      opt.textContent = String(d).padStart(2, '0');
      dobPicker.dayEl.appendChild(opt);
    }
    dobPicker.dayEl.disabled = false;
  }

  function initDobPicker() {
    const yearEl = dobPicker.yearEl;
    if (!yearEl) return;

    const now = new Date();
    const currentYear = now.getFullYear();
    const startYear = 1950;

    yearEl.innerHTML = '<option value="">ឆ្នាំ</option>';
    for (let y = currentYear; y >= startYear; y -= 1) {
      const opt = document.createElement('option');
      opt.value = String(y);
      opt.textContent = String(y);
      yearEl.appendChild(opt);
    }

    dobPicker.monthEl.innerHTML = '<option value="">ខែ</option>';
    for (let m = 1; m <= 12; m += 1) {
      const opt = document.createElement('option');
      opt.value = String(m);
      opt.textContent = String(m).padStart(2, '0');
      dobPicker.monthEl.appendChild(opt);
    }

    yearEl.addEventListener('change', () => {
      dobPicker.monthEl.disabled = !yearEl.value;
      rebuildDays();
      setHiddenDate();
    });
    dobPicker.monthEl.addEventListener('change', () => {
      rebuildDays();
      setHiddenDate();
    });
    dobPicker.dayEl.addEventListener('change', setHiddenDate);
  }

  function setDobFromHidden(value) {
    if (!value) {
      dobPicker.yearEl.value = '';
      dobPicker.monthEl.value = '';
      dobPicker.dayEl.value = '';
      dobPicker.monthEl.disabled = true;
      dobPicker.dayEl.disabled = true;
      setHiddenDate();
      return;
    }

    const parts = value.split('-');
    if (parts.length !== 3) return;

    dobPicker.yearEl.value = parts[0];
    dobPicker.monthEl.disabled = false;
    dobPicker.monthEl.value = String(parseInt(parts[1], 10));
    rebuildDays();
    dobPicker.dayEl.value = String(parseInt(parts[2], 10));
    dobPicker.dayEl.disabled = false;
    setHiddenDate();
  }

  function openStudentModal(mode, el) {
    if (mode === 'edit') {
      const id = el.dataset.id;
      modalTitle.textContent = 'កែប្រែសិស្ស';
      form.action = updateUrlTemplate.replace('__ID__', id);
      methodInput.disabled = false;
      formModeInput.value = 'edit';
      studentIdInput.value = id;

      fields.code.value = el.dataset.code || '';
      fields.name.value = el.dataset.name || '';
      fields.classId.value = el.dataset.classId || '';
      fields.gender.value = el.dataset.gender || '';
      fields.dob.value = el.dataset.dob || '';
      setDobFromHidden(fields.dob.value);
      fields.active.checked = el.dataset.active === '1';
    } else {
      modalTitle.textContent = 'បន្ថែមសិស្ស';
      form.action = '{{ route('admin.students.store') }}';
      methodInput.disabled = true;
      formModeInput.value = 'create';
      studentIdInput.value = '';

      fields.code.value = '';
      fields.name.value = '';
      fields.classId.value = '';
      fields.gender.value = '';
      fields.dob.value = '';
      setDobFromHidden('');
      fields.active.checked = true;
    }

    modal.classList.add('active');
  }

  function closeStudentModal() {
    modal.classList.remove('active');
  }

  const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
  if (hasErrors) {
    const oldMode = '{{ $formMode }}';
    const oldId = '{{ $formStudentId }}';

    if (oldMode === 'edit' && oldId) {
      modalTitle.textContent = 'កែប្រែសិស្ស';
      form.action = updateUrlTemplate.replace('__ID__', oldId);
      methodInput.disabled = false;
      formModeInput.value = 'edit';
      studentIdInput.value = oldId;
    } else {
      modalTitle.textContent = 'បន្ថែមសិស្ស';
      form.action = '{{ route('admin.students.store') }}';
      methodInput.disabled = true;
      formModeInput.value = 'create';
      studentIdInput.value = '';
    }

    modal.classList.add('active');
    setDobFromHidden(fields.dob.value);
  }

  const params = new URLSearchParams(window.location.search);
  if (params.get('create') === '1') {
    openStudentModal('create');
  }

  initDobPicker();
</script>

@endsection
