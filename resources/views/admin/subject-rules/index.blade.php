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
  .muted{color: rgba(168,179,207,.95); font-weight:600; font-size:14px;}
  .header{
    display:flex; align-items:center; justify-content:space-between; gap:10px;
    margin-bottom: 12px;
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
    max-width: 560px;
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
    font-size: 18px;
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
  .form-group label{ display:block; font-weight:800; font-size:14px; margin-bottom:8px; }
  .form-group input,
  .form-group select{
    width:100%; padding:11px 12px; border-radius:12px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.08); color:#eaf0ff; font-weight:700; font-size:14px;
  }

  .error{ color:#ffd3d3; font-weight:700; font-size:13px; margin-top:6px; }

  @media (max-width: 900px){
    .table{ font-size:13px; }
  }
</style>

<div class="card">
  <div class="header">
    <div>
      <h2 style="margin:0; font-size:16px; font-weight:900;">ក្បួនពិន្ទុតាមមុខវិជ្ជា</h2>
      <div class="muted">កំណត់ពិន្ទុអតិបរមាតាមថ្នាក់ និងជំនាញ</div>
    </div>
    <button class="btn-soft btn-primary" type="button" onclick="openRuleModal('create')">
      <i class="fas fa-plus"></i> បន្ថែមក្បួន
    </button>
  </div>

  @if (session('success'))
    <div class="alert">{{ session('success') }}</div>
  @endif

  <div style="overflow:auto;">
    <table class="table">
      <thead>
        <tr>
          <th>ថ្នាក់</th>
          <th>ជំនាញ</th>
          <th>មុខវិជ្ជា</th>
          <th>ពិន្ទុអតិបរមា</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($rules as $rule)
          <tr>
            <td>{{ $rule->gradeLevel->name ?? '-' }}</td>
            <td>{{ $rule->stream->name ?? 'ទូទៅ' }}</td>
            <td>{{ $rule->subject->name ?? '-' }}</td>
            <td>{{ $rule->max_score }}</td>
            <td style="white-space:nowrap;">
              <a
                class="btn-soft btn-edit"
                href="{{ route('admin.subject-rules.edit', $rule) }}"
                data-id="{{ $rule->id }}"
                data-grade="{{ $rule->grade_level_id }}"
                data-stream="{{ $rule->stream_id }}"
                data-subject="{{ $rule->subject_id }}"
                data-max="{{ $rule->max_score }}"
                onclick="openRuleModal('edit', this); return false;"
              >
                <i class="fas fa-pen"></i> Edit
              </a>
              <form method="POST" action="{{ route('admin.subject-rules.destroy', $rule) }}" style="display:inline;" onsubmit="return confirm('Delete this rule?');">
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
            <td colspan="5" class="muted">No rules yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@php
  $formMode = old('_form_mode', 'create');
  $formRuleId = old('rule_id');
@endphp

<div class="modal-overlay" id="ruleModal" data-update-url="{{ url('/admin/subject-rules') }}/__ID__">
  <div class="modal-card">
    <div class="modal-header">
      <h2 id="modalTitle">បន្ថែមក្បួន</h2>
      <button class="modal-close" type="button" onclick="closeRuleModal()">&times;</button>
    </div>

    <form method="POST" id="ruleForm" action="{{ route('admin.subject-rules.store') }}">
      @csrf
      <input type="hidden" id="ruleMethod" name="_method" value="PUT" disabled>
      <input type="hidden" id="formMode" name="_form_mode" value="{{ $formMode }}">
      <input type="hidden" id="ruleId" name="rule_id" value="{{ $formRuleId }}">

      <div class="form-group">
        <label for="r_grade">ថ្នាក់</label>
        <select id="r_grade" name="grade_level_id" required>
          <option value="">-- ជ្រើសរើស --</option>
          @foreach($grades as $grade)
            <option value="{{ $grade->id }}" {{ old('grade_level_id') == $grade->id ? 'selected' : '' }}>
              {{ $grade->name }}
            </option>
          @endforeach
        </select>
        @error('grade_level_id') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="r_stream">ជំនាញ</label>
        <select id="r_stream" name="stream_id">
          <option value="">ទូទៅ</option>
          @foreach($streams as $stream)
            <option value="{{ $stream->id }}" {{ old('stream_id') == $stream->id ? 'selected' : '' }}>
              {{ $stream->name }}
            </option>
          @endforeach
        </select>
        @error('stream_id') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="r_subject">មុខវិជ្ជា</label>
        <select id="r_subject" name="subject_id" required>
          <option value="">-- ជ្រើសរើស --</option>
          @foreach($subjects as $subject)
            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
              {{ $subject->name }}
            </option>
          @endforeach
        </select>
        @error('subject_id') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="r_max">ពិន្ទុអតិបរមា</label>
        <input id="r_max" name="max_score" value="{{ old('max_score') }}" type="number" min="0" step="0.01" required />
        @error('max_score') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div style="display:flex; gap:10px;">
        <button class="btn-soft btn-primary" type="submit">
          <i class="fas fa-save"></i> Save
        </button>
        <button class="btn-soft" type="button" onclick="closeRuleModal()">Back</button>
      </div>
    </form>
  </div>
</div>

<script>
  const modal = document.getElementById('ruleModal');
  const form = document.getElementById('ruleForm');
  const methodInput = document.getElementById('ruleMethod');
  const formModeInput = document.getElementById('formMode');
  const ruleIdInput = document.getElementById('ruleId');
  const modalTitle = document.getElementById('modalTitle');
  const updateUrlTemplate = modal.dataset.updateUrl;

  const fields = {
    grade: document.getElementById('r_grade'),
    stream: document.getElementById('r_stream'),
    subject: document.getElementById('r_subject'),
    max: document.getElementById('r_max'),
  };

  function openRuleModal(mode, el) {
    if (mode === 'edit') {
      const id = el.dataset.id;
      modalTitle.textContent = 'កែប្រែក្បួន';
      form.action = updateUrlTemplate.replace('__ID__', id);
      methodInput.disabled = false;
      formModeInput.value = 'edit';
      ruleIdInput.value = id;

      fields.grade.value = el.dataset.grade || '';
      fields.stream.value = el.dataset.stream || '';
      fields.subject.value = el.dataset.subject || '';
      fields.max.value = el.dataset.max || '';
    } else {
      modalTitle.textContent = 'បន្ថែមក្បួន';
      form.action = '{{ route('admin.subject-rules.store') }}';
      methodInput.disabled = true;
      formModeInput.value = 'create';
      ruleIdInput.value = '';

      fields.grade.value = '';
      fields.stream.value = '';
      fields.subject.value = '';
      fields.max.value = '';
    }

    modal.classList.add('active');
  }

  function closeRuleModal() {
    modal.classList.remove('active');
  }

  const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
  if (hasErrors) {
    const oldMode = '{{ $formMode }}';
    const oldId = '{{ $formRuleId }}';

    if (oldMode === 'edit' && oldId) {
      modalTitle.textContent = 'កែប្រែក្បួន';
      form.action = updateUrlTemplate.replace('__ID__', oldId);
      methodInput.disabled = false;
      formModeInput.value = 'edit';
      ruleIdInput.value = oldId;
    } else {
      modalTitle.textContent = 'បន្ថែមក្បួន';
      form.action = '{{ route('admin.subject-rules.store') }}';
      methodInput.disabled = true;
      formModeInput.value = 'create';
      ruleIdInput.value = '';
    }

    modal.classList.add('active');
  }

  const params = new URLSearchParams(window.location.search);
  if (params.get('create') === '1') {
    openRuleModal('create');
  }
</script>

@endsection
