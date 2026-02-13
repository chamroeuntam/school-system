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
  .form-group input{
    width:100%; padding:10px 12px; border-radius:12px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.08); color:#eaf0ff; font-weight:700;
  }

  .error{ color:#ffd3d3; font-weight:700; font-size:12px; margin-top:4px; }

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

  @media (max-width: 900px){
    .table{ font-size:13px; }
  }
</style>

<div class="card">
  <div class="header">
    <div>
      <h2 style="margin:0; font-size:16px; font-weight:900;">គ្រូបង្រៀន</h2>
      <div class="muted">បញ្ជីគ្រូបង្រៀនទាំងអស់</div>
    </div>
    <button class="btn-soft btn-primary" type="button" onclick="openTeacherModal('create')">
      <i class="fas fa-user-plus"></i> បន្ថែមគ្រូ
    </button>
  </div>

  @if (session('success'))
    <div class="alert">{{ session('success') }}</div>
  @endif

  <div style="overflow:auto;">
    <table class="table">
      <thead>
        <tr>
          <th>ឈ្មោះ</th>
          <th>លេខទូរសព្ទ</th>
          <th>Email</th>
          <th>Telegram</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($teachers as $teacher)
          <tr>
            <td>{{ $teacher->name }}</td>
            <td>{{ $teacher->phone }}</td>
            <td>{{ $teacher->email ?? '-' }}</td>
            <td>{{ $teacher->telegram_chat_id ?? '-' }}</td>
            <td>{{ $teacher->created_at?->format('Y-m-d') }}</td>
            <td style="white-space:nowrap;">
              <a
                class="btn-soft btn-edit"
                href="{{ route('admin.teachers.edit', $teacher) }}"
                data-id="{{ $teacher->id }}"
                data-name="{{ $teacher->name }}"
                data-phone="{{ $teacher->phone }}"
                data-email="{{ $teacher->email }}"
                data-telegram="{{ $teacher->telegram_chat_id }}"
                onclick="openTeacherModal('edit', this); return false;"
              >
                <i class="fas fa-pen"></i> Edit
              </a>
              <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" style="display:inline;" onsubmit="return confirm('Delete this teacher?');">
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
            <td colspan="6" class="muted">No teachers yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@php
  $formMode = old('_form_mode', 'create');
  $formTeacherId = old('teacher_id');
@endphp

<div class="modal-overlay" id="teacherModal" data-update-url="{{ url('/admin/teachers') }}/__ID__">
  <div class="modal-card">
    <div class="modal-header">
      <h2 id="modalTitle">បន្ថែមគ្រូ</h2>
      <button class="modal-close" type="button" onclick="closeTeacherModal()">&times;</button>
    </div>

    <form method="POST" id="teacherForm" action="{{ route('admin.teachers.store') }}">
      @csrf
      <input type="hidden" id="teacherMethod" name="_method" value="PUT" disabled>
      <input type="hidden" id="formMode" name="_form_mode" value="{{ $formMode }}">
      <input type="hidden" id="teacherId" name="teacher_id" value="{{ $formTeacherId }}">

      <div class="form-group">
        <label for="t_name">ឈ្មោះ</label>
        <input id="t_name" name="name" value="{{ old('name') }}" required />
        @error('name') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="t_phone">លេខទូរសព្ទ</label>
        <input id="t_phone" name="phone" value="{{ old('phone') }}" required />
        @error('phone') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="t_email">Email (optional)</label>
        <input id="t_email" name="email" type="email" value="{{ old('email') }}" />
        @error('email') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="t_telegram">Telegram Chat ID (optional)</label>
        <input id="t_telegram" name="telegram_chat_id" value="{{ old('telegram_chat_id') }}" />
        @error('telegram_chat_id') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="t_password" id="passwordLabel">Password</label>
        <input id="t_password" name="password" type="password" />
        @error('password') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div style="display:flex; gap:10px;">
        <button class="btn-soft btn-primary" type="submit">
          <i class="fas fa-save"></i> Save
        </button>
        <button class="btn-soft" type="button" onclick="closeTeacherModal()">Back</button>
      </div>
    </form>
  </div>
</div>

<script>
  const modal = document.getElementById('teacherModal');
  const form = document.getElementById('teacherForm');
  const methodInput = document.getElementById('teacherMethod');
  const formModeInput = document.getElementById('formMode');
  const teacherIdInput = document.getElementById('teacherId');
  const modalTitle = document.getElementById('modalTitle');
  const passwordLabel = document.getElementById('passwordLabel');
  const updateUrlTemplate = modal.dataset.updateUrl;

  const fields = {
    name: document.getElementById('t_name'),
    phone: document.getElementById('t_phone'),
    email: document.getElementById('t_email'),
    telegram: document.getElementById('t_telegram'),
    password: document.getElementById('t_password'),
  };

  function openTeacherModal(mode, el) {
    if (mode === 'edit') {
      const id = el.dataset.id;
      modalTitle.textContent = 'កែប្រែគ្រូ';
      form.action = updateUrlTemplate.replace('__ID__', id);
      methodInput.disabled = false;
      formModeInput.value = 'edit';
      teacherIdInput.value = id;
      passwordLabel.textContent = 'Password (leave blank to keep)';

      fields.name.value = el.dataset.name || '';
      fields.phone.value = el.dataset.phone || '';
      fields.email.value = el.dataset.email || '';
      fields.telegram.value = el.dataset.telegram || '';
      fields.password.value = '';
    } else {
      modalTitle.textContent = 'បន្ថែមគ្រូ';
      form.action = '{{ route('admin.teachers.store') }}';
      methodInput.disabled = true;
      formModeInput.value = 'create';
      teacherIdInput.value = '';
      passwordLabel.textContent = 'Password';

      fields.name.value = '';
      fields.phone.value = '';
      fields.email.value = '';
      fields.telegram.value = '';
      fields.password.value = '';
    }

    modal.classList.add('active');
  }

  function closeTeacherModal() {
    modal.classList.remove('active');
  }

  // Auto-open modal on validation errors
  const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
  if (hasErrors) {
    const oldMode = '{{ $formMode }}';
    const oldId = '{{ $formTeacherId }}';

    if (oldMode === 'edit' && oldId) {
      modalTitle.textContent = 'កែប្រែគ្រូ';
      form.action = updateUrlTemplate.replace('__ID__', oldId);
      methodInput.disabled = false;
      formModeInput.value = 'edit';
      teacherIdInput.value = oldId;
      passwordLabel.textContent = 'Password (leave blank to keep)';
    } else {
      modalTitle.textContent = 'បន្ថែមគ្រូ';
      form.action = '{{ route('admin.teachers.store') }}';
      methodInput.disabled = true;
      formModeInput.value = 'create';
      teacherIdInput.value = '';
      passwordLabel.textContent = 'Password';
    }

    modal.classList.add('active');
  }
</script>

@endsection
