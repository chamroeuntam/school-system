@extends('layouts.app')

@section('content')
<style>
  .sheet-form{ display:grid; gap:18px; }
  .form-hero{
    padding:18px;
    border-radius: 18px;
    border:1px solid rgba(255,255,255,.10);
    background:
      linear-gradient(135deg, rgba(79,70,229,.18), rgba(6,182,212,.10)),
      rgba(255,255,255,.04);
  }
  .hero-row{ display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
  .hero-title{ font-size:28px; font-weight:900; margin:0; line-height:1.2; }
  .hero-sub{ margin:8px 0 0; color: rgba(168,179,207,.95); font-weight:700; font-size:14px; }
  .btn-hero{
    padding:11px 14px; border-radius:14px; border:1px solid rgba(255,255,255,.16);
    background: rgba(255,255,255,.08); color:#eaf0ff; font-weight:800; font-size:14px; text-decoration:none;
    transition: transform .15s ease, background .15s ease;
  }
  .btn-primary{
    border-color: rgba(79,70,229,.45);
    background: linear-gradient(135deg, rgba(79,70,229,.45), rgba(6,182,212,.25));
  }
  .panel{
    border-radius: 18px; border:1px solid rgba(255,255,255,.10);
    background: rgba(255,255,255,.04); padding:16px;
  }
  .panel h2{ margin:0 0 12px; font-size:18px; font-weight:900; }
  .field{ display:grid; gap:8px; }
  .field label{ font-weight:800; font-size:14px; color: rgba(234,240,255,.92); }
  .field small{ color: rgba(168,179,207,.85); font-weight:600; font-size:13px; }
  .input, .textarea, .select{
    width:100%; padding:11px 12px; border-radius:12px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.08); color:#eaf0ff; font-weight:700; font-size:14px;
  }
  .textarea{
    font-family: 'Google Sans', 'Noto Sans Khmer', system-ui, Arial, sans-serif;
    min-height: 160px;
    resize: vertical;
  }
  .error{ color:#ffd3d3; font-weight:700; font-size:13px; margin-top:6px; }
  .actions{ display:flex; gap:10px; flex-wrap:wrap; }
  .btn{ padding:11px 14px; border-radius:14px; border:1px solid rgba(255,255,255,.14); background: rgba(255,255,255,.08); color:#eaf0ff; font-weight:800; font-size:14px; cursor:pointer; transition: transform .15s ease, background .15s ease; }
  .btn:hover{ background: rgba(255,255,255,.12); transform: translateY(-1px); }
  .btn-outline{ background: transparent; }
  .checkbox-group{
    display:flex; align-items:center; gap:10px; padding:12px;
    border-radius:12px; border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.06); cursor:pointer; transition: background .15s ease;
  }
  .checkbox-group:hover{ background: rgba(255,255,255,.10); }
  .checkbox-group input{ cursor:pointer; accent-color: rgba(79,70,229,1); }
  .info-item{ display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid rgba(255,255,255,.10); }
  .info-item:last-child{ border-bottom:none; }
  .info-label{ color: rgba(168,179,207,.95); font-weight:600; font-size:13px; }
  .info-value{ font-weight:700; font-size:14px; }
</style>

<div class="sheet-form">
  <section class="form-hero">
    <div class="hero-row">
      <div>
        <h1 class="hero-title">កែប្រែមតិប្រកាស</h1>
        <div class="hero-sub">នៅពេលកែប្រែមតិប្រកាស ប្រទាក់ដែលបានផ្សាយប្រកាសនឹងឃើញការផ្លាស់ប្តូរ</div>
      </div>
      <a class="btn-hero" href="{{ route('admin.announcements.index') }}">← ត្រឡប់ ក្រោយ</a>
    </div>
  </section>

  @if ($errors->any())
    <div class="panel" style="border-color: rgba(239,68,68,.35); background: rgba(239,68,68,.08);">
      <div style="font-weight:800; color:#fecaca;">សូមពិនិត្យកំហុស:</div>
      <ul style="margin:10px 0 0; padding-left:20px; color:#fecaca;">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" class="sheet-form">
    @csrf
    @method('PUT')

    <section class="panel">
      <h2><i class="fas fa-edit"></i> ព័ត៌មាននមូលដ្ឋាន</h2>

      <div class="field">
        <label for="title">ចំណងជើង *</label>
        <input id="title" type="text" name="title" placeholder="ឧ. ប្រឡងសម័យទី១ចាប់ផ្តើមសប្ដាហ៍ក្រោយ" value="{{ old('title', $announcement->title) }}" class="input" required>
        @error('title') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="field" style="margin-top:14px;">
        <label for="message">មតិប្រកាស *</label>
        <textarea id="message" name="message" class="textarea" placeholder="ពិពណ៌នាលម្អិតបន្ថែមលម្អិត..." required>{{ old('message', $announcement->message) }}</textarea>
        @error('message') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div style="margin-top:16px;">
        <label class="checkbox-group">
          <input type="checkbox" name="is_published" value="1" {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}>
          <div>
            <b style="font-size:14px;">ផ្សាយប្រកាស</b>
            <div style="font-size:13px; color: rgba(168,179,207,.9);">សរុប ឬមន្ទិរទិដ្ឋបាននឹងបាននិយមខ្លាំង</div>
          </div>
        </label>
      </div>
    </section>

    <section class="panel">
      <h2><i class="fas fa-info-circle"></i> ព័ត៌មានលម្អិត</h2>

      <div class="info-item">
        <span class="info-label">ក្រុមគ្រូបង្រៀន</span>
        <span class="info-value">{{ $announcement->user->name ?? 'Admin' }}</span>
      </div>

      <div class="info-item">
        <span class="info-label">បង្កើត</span>
        <span class="info-value">{{ $announcement->created_at->format('M d, Y H:i') }}</span>
      </div>

      <div class="info-item">
        <span class="info-label">ប្រូបង្រើង</span>
        <span class="info-value">{{ $announcement->updated_at->format('M d, Y H:i') }}</span>
      </div>

      @if($announcement->is_published && $announcement->published_at)
        <div class="info-item">
          <span class="info-label">ផ្សាយប្រកាសលើ</span>
          <span class="info-value">{{ $announcement->published_at->format('M d, Y H:i') }}</span>
        </div>
      @endif
    </section>

    <div class="actions">
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> រក្សាការផ្លាស់ប្តូរ
      </button>
      <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline">
        <i class="fas fa-times"></i> បោះបង់ចោល
      </a>
    </div>
  </form>
</div>

@endsection
