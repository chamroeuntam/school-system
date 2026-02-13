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
  .btn-info{border-color: rgba(59,130,246,.35); background: rgba(59,130,246,.18);}

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
    max-width: 620px;
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
  .form-group input, .form-group select{
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
      <h2 style="margin:0; font-size:16px; font-weight:900;">ថ្នាក់</h2>
      <div class="muted">គ្រប់គ្រងថ្នាក់ទាំងអស់</div>
    </div>
    <button class="btn-soft btn-primary" type="button" onclick="openClassModal('create')">
      <i class="fas fa-plus"></i> បន្ថែមថ្នាក់
    </button>
  </div>

  @if (session('success'))
    <div class="alert">{{ session('success') }}</div>
  @endif

  <div style="overflow:auto;">
    <table class="table">
      <thead>
        <tr>
          <th>Academic Year</th>
          <th>Grade</th>
          <th>Stream</th>
          <th>Class</th>
          <th>Assignments</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($classes as $class)
          <tr>
            <td>{{ $class->academicYear?->name ?? '-' }}</td>
            <td>{{ $class->gradeLevel?->name ?? '-' }}</td>
            <td>{{ $class->stream?->name ?? '-' }}</td>
            <td>{{ $class->name }}</td>
            <td>
              <button
                class="btn-soft btn-info"
                type="button"
                data-id="{{ $class->id }}"
                data-name="{{ $class->name }}"
                data-list-id="assignments-{{ $class->id }}"
                onclick="openAssignModal(this)"
              >
                <i class="fas fa-user-plus"></i> Assign
              </button>
            </td>
            <td style="white-space:nowrap;">
              <a
                class="btn-soft btn-edit"
                href="{{ route('admin.classes.edit', $class) }}"
                data-id="{{ $class->id }}"
                data-year-id="{{ $class->academic_year_id }}"
                data-grade-id="{{ $class->grade_level_id }}"
                data-stream-id="{{ $class->stream_id }}"
                data-name="{{ $class->name }}"
                onclick="openClassModal('edit', this); return false;"
              >
                <i class="fas fa-pen"></i> Edit
              </a>
              <form method="POST" action="{{ route('admin.classes.destroy', $class) }}" style="display:inline;" onsubmit="return confirm('Delete this class?');">
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
            <td colspan="6" class="muted">No classes yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@foreach ($classes as $class)
  <div id="assignments-{{ $class->id }}" style="display:none;">
    @php
      $classAssignments = $assignmentsByClass[$class->id] ?? collect();
    @endphp
    @if ($classAssignments->isEmpty())
      <div class="muted" style="margin-bottom:8px;">No assignments yet.</div>
    @else
      <div style="overflow:auto; margin-bottom:8px;">
        <table class="table">
          <thead>
            <tr>
              <th>Teacher</th>
              <th>Subject</th>
              <th>Term</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($classAssignments as $assignment)
              <tr>
                <td>{{ $assignment->teacher?->name ?? '-' }}</td>
                <td>{{ $assignment->subject?->name ?? '-' }}</td>
                <td>{{ $assignment->term?->name ?? '-' }}</td>
                <td>
                  <form method="POST" action="{{ route('admin.classes.assignments.destroy', $assignment) }}" onsubmit="return confirm('Remove this assignment?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn-soft btn-danger" type="submit">
                      <i class="fas fa-trash"></i> Remove
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
@endforeach

@php
  $formMode = old('_form_mode', 'create');
  $formClassId = old('class_id');
@endphp

<div class="modal-overlay" id="classModal" data-update-url="{{ url('/admin/classes') }}/__ID__">
  <div class="modal-card">
    <div class="modal-header">
      <h2 id="modalTitle">បន្ថែមថ្នាក់</h2>
      <button class="modal-close" type="button" onclick="closeClassModal()">&times;</button>
    </div>

    <form method="POST" id="classForm" action="{{ route('admin.classes.store') }}">
      @csrf
      <input type="hidden" id="classMethod" name="_method" value="PUT" disabled>
      <input type="hidden" id="formMode" name="_form_mode" value="{{ $formMode }}">
      <input type="hidden" id="classId" name="class_id" value="{{ $formClassId }}">

      <div class="form-group">
        <label for="c_year">Academic Year</label>
        <select id="c_year" name="academic_year_id" required>
          <option value="">--</option>
          @foreach ($years as $year)
            <option value="{{ $year->id }}" {{ (string) old('academic_year_id') === (string) $year->id ? 'selected' : '' }}>
              {{ $year->name }}
            </option>
          @endforeach
        </select>
        @error('academic_year_id') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="c_grade">Grade Level</label>
        <select id="c_grade" name="grade_level_id" required>
          <option value="">--</option>
          @foreach ($grades as $grade)
            <option value="{{ $grade->id }}" {{ (string) old('grade_level_id') === (string) $grade->id ? 'selected' : '' }}>
              {{ $grade->name }}
            </option>
          @endforeach
        </select>
        @error('grade_level_id') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="c_stream">Stream (optional)</label>
        <select id="c_stream" name="stream_id">
          <option value="">--</option>
          @foreach ($streams as $stream)
            <option value="{{ $stream->id }}" {{ (string) old('stream_id') === (string) $stream->id ? 'selected' : '' }}>
              {{ $stream->name }}
            </option>
          @endforeach
        </select>
        @error('stream_id') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="c_name">Class Name</label>
        <input id="c_name" name="name" value="{{ old('name') }}" required />
        @error('name') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div style="display:flex; gap:10px;">
        <button class="btn-soft btn-primary" type="submit">
          <i class="fas fa-save"></i> Save
        </button>
        <button class="btn-soft" type="button" onclick="closeClassModal()">Back</button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="assignModal" data-action-template="{{ url('/admin/classes') }}/__ID__/assignments">
  <div class="modal-card">
    <div class="modal-header">
      <h2 id="assignTitle">Assign Teacher</h2>
      <button class="modal-close" type="button" onclick="closeAssignModal()">&times;</button>
    </div>

    <div id="assignmentsList" style="margin-bottom:12px;"></div>

    <form method="POST" id="assignForm" action="{{ url('/admin/classes') }}/__ID__/assignments">
      @csrf
      <input type="hidden" id="assignClassId" name="class_id" value="{{ old('class_id') }}">
      <div class="form-group">
        <label for="a_teacher">Teacher</label>
        <select id="a_teacher" name="teacher_user_id" required>
          <option value="">--</option>
          @foreach ($teachers as $teacher)
            <option value="{{ $teacher->id }}" {{ (string) old('teacher_user_id') === (string) $teacher->id ? 'selected' : '' }}>
              {{ $teacher->name }}
            </option>
          @endforeach
        </select>
        @error('teacher_user_id') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="a_subject">Subject</label>
        <select id="a_subject" name="subject_id" required>
          <option value="">--</option>
          @foreach ($subjects as $subject)
            <option value="{{ $subject->id }}" {{ (string) old('subject_id') === (string) $subject->id ? 'selected' : '' }}>
              {{ $subject->name }}
            </option>
          @endforeach
        </select>
        @error('subject_id') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="a_term">Term (optional)</label>
        <select id="a_term" name="term_id">
          <option value="">--</option>
          @foreach ($terms as $term)
            <option value="{{ $term->id }}" {{ (string) old('term_id') === (string) $term->id ? 'selected' : '' }}>
              {{ $term->name }}
            </option>
          @endforeach
        </select>
        @error('term_id') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div style="display:flex; gap:10px; margin-top:12px;">
        <button class="btn-soft btn-primary" type="submit">
          <i class="fas fa-save"></i> Save
        </button>
        <button class="btn-soft" type="button" onclick="closeAssignModal()">Back</button>
      </div>
    </form>
  </div>
</div>

<script>
  const modal = document.getElementById('classModal');
  const form = document.getElementById('classForm');
  const methodInput = document.getElementById('classMethod');
  const formModeInput = document.getElementById('formMode');
  const classIdInput = document.getElementById('classId');
  const modalTitle = document.getElementById('modalTitle');
  const updateUrlTemplate = modal.dataset.updateUrl;

  const fields = {
    yearId: document.getElementById('c_year'),
    gradeId: document.getElementById('c_grade'),
    streamId: document.getElementById('c_stream'),
    name: document.getElementById('c_name'),
  };

  function openClassModal(mode, el) {
    if (mode === 'edit') {
      const id = el.dataset.id;
      modalTitle.textContent = 'កែប្រែថ្នាក់';
      form.action = updateUrlTemplate.replace('__ID__', id);
      methodInput.disabled = false;
      formModeInput.value = 'edit';
      classIdInput.value = id;

      fields.yearId.value = el.dataset.yearId || '';
      fields.gradeId.value = el.dataset.gradeId || '';
      fields.streamId.value = el.dataset.streamId || '';
      fields.name.value = el.dataset.name || '';
    } else {
      modalTitle.textContent = 'បន្ថែមថ្នាក់';
      form.action = '{{ route('admin.classes.store') }}';
      methodInput.disabled = true;
      formModeInput.value = 'create';
      classIdInput.value = '';

      fields.yearId.value = '';
      fields.gradeId.value = '';
      fields.streamId.value = '';
      fields.name.value = '';
    }

    modal.classList.add('active');
  }

  function closeClassModal() {
    modal.classList.remove('active');
  }

  const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
  if (hasErrors) {
    const oldMode = '{{ $formMode }}';
    const oldId = '{{ $formClassId }}';

    if (oldMode === 'edit' && oldId) {
      modalTitle.textContent = 'កែប្រែថ្នាក់';
      form.action = updateUrlTemplate.replace('__ID__', oldId);
      methodInput.disabled = false;
      formModeInput.value = 'edit';
      classIdInput.value = oldId;
    } else {
      modalTitle.textContent = 'បន្ថែមថ្នាក់';
      form.action = '{{ route('admin.classes.store') }}';
      methodInput.disabled = true;
      formModeInput.value = 'create';
      classIdInput.value = '';
    }

    modal.classList.add('active');
  }

  const params = new URLSearchParams(window.location.search);
  if (params.get('create') === '1') {
    openClassModal('create');
  }

  const assignModal = document.getElementById('assignModal');
  const assignForm = document.getElementById('assignForm');
  const assignTitle = document.getElementById('assignTitle');
  const assignList = document.getElementById('assignmentsList');
  const assignClassId = document.getElementById('assignClassId');
  const actionTemplate = assignModal.dataset.actionTemplate;

  function openAssignModal(el) {
    const classId = el.dataset.id;
    const className = el.dataset.name || '';
    const listId = el.dataset.listId;
    const listSource = document.getElementById(listId);

    assignTitle.textContent = `Assign Teacher • ${className}`;
    assignForm.action = actionTemplate.replace('__ID__', classId);
    assignClassId.value = classId;
    assignList.innerHTML = listSource ? listSource.innerHTML : '';

    assignModal.classList.add('active');
  }

  function closeAssignModal() {
    assignModal.classList.remove('active');
  }

  const hasAssignErrors = {{ $errors->any() ? 'true' : 'false' }};
  if (hasAssignErrors && '{{ old('teacher_user_id') }}') {
    const classId = '{{ old('class_id') }}';
    if (classId) {
      const trigger = document.querySelector(`[data-id="${classId}"][data-list-id]`);
      if (trigger) {
        openAssignModal(trigger);
      }
    }
  }
</script>

@endsection
