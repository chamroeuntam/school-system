@extends('layouts.app')
@section('content')

<style>
  .card{
    border:1px solid rgba(255,255,255,.10);
    background: rgba(255,255,255,.05);
    border-radius: 18px;
    padding: 14px;
  }
  .form-group{ margin-bottom: 14px; }
  .form-group label{ display:block; font-weight:800; margin-bottom:6px; }
  .form-group input{
    width:100%; padding:10px 12px; border-radius:12px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.08); color:#eaf0ff; font-weight:700;
  }
  .muted{color: rgba(168,179,207,.95); font-weight:600;}
  .btn-soft{
    display:inline-flex; align-items:center; gap:8px;
    padding:10px 12px; border-radius:14px;
    border:1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.06);
    color: #eaf0ff; font-weight:800;
    text-decoration:none; cursor:pointer;
  }
  .btn-primary{border-color: rgba(79,70,229,.35); background: rgba(79,70,229,.22);}
  .error{ color:#ffd3d3; font-weight:700; font-size:12px; margin-top:4px; }
</style>

<div class="card">
  <h2 style="margin:0 0 10px; font-size:16px; font-weight:900;">កែប្រែគ្រូ</h2>
  <div class="muted" style="margin-bottom:12px;">Update teacher account</div>

  <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
      <label for="name">ឈ្មោះ</label>
      <input id="name" name="name" value="{{ old('name', $teacher->name) }}" required />
      @error('name') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="phone">លេខទូរសព្ទ</label>
      <input id="phone" name="phone" value="{{ old('phone', $teacher->phone) }}" required />
      @error('phone') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="email">Email (optional)</label>
      <input id="email" name="email" type="email" value="{{ old('email', $teacher->email) }}" />
      @error('email') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="telegram_chat_id">Telegram Chat ID (optional)</label>
      <input id="telegram_chat_id" name="telegram_chat_id" value="{{ old('telegram_chat_id', $teacher->telegram_chat_id) }}" />
      @error('telegram_chat_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="password">Password (leave blank to keep)</label>
      <input id="password" name="password" type="password" />
      @error('password') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div style="display:flex; gap:10px;">
      <button class="btn-soft btn-primary" type="submit">
        <i class="fas fa-save"></i> Update
      </button>
      <a class="btn-soft" href="{{ route('admin.teachers.index') }}">
        Back
      </a>
    </div>
  </form>
</div>

@endsection
