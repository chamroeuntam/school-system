<!doctype html>
<html lang="km">
<head>
  <meta charset="utf-8">
  <title>កំណត់ឡើងវិញ • ពាក្យសម្ងាត់ & PIN</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@400;600;700;800&family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    :root{
      --bg:#0b1020;
      --card: rgba(255,255,255,.06);
      --card2: rgba(255,255,255,.08);
      --border: rgba(255,255,255,.10);
      --text:#eaf0ff;
      --muted:#a8b3cf;
      --primary:#4f46e5;
      --primary2:#06b6d4;
      --success:#22c55e;
      --danger:#ef4444;
      --info:#3b82f6;
      --shadow: 0 18px 50px rgba(0,0,0,.45);
      --radius: 18px;
    }

    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    body{
      margin:0;
      font-family: "Google Sans",system-ui,Arial,sans-serif;
      color:var(--text);
      background:
        radial-gradient(1200px 700px at 15% 10%, rgba(79,70,229,.35) 0%, transparent 60%),
        radial-gradient(900px 600px at 95% 25%, rgba(6,182,212,.25) 0%, transparent 60%),
        linear-gradient(180deg, #070a14 0%, #0b1020 100%);
      min-height:100vh;
      display:flex;
      align-items:flex-start;
      justify-content:center;
      padding: 34px 18px;
    }

    .wrap{ width:100%; max-width: 980px; }

    .card{
      width:100%;
      background: rgba(255,255,255,.06);
      border: 1px solid var(--border);
      border-radius: 22px;
      box-shadow: var(--shadow);
      overflow:hidden;
      backdrop-filter: blur(10px);
      position: relative;
    }

    .card-header{
      padding: 22px 18px 16px;
      border-bottom: 1px solid var(--border);
      display:flex;
      gap:14px;
      align-items:center;
      justify-content:space-between;
      flex-wrap:wrap;
      background: rgba(0,0,0,.10);
    }

    .brand{
      display:flex;
      align-items:center;
      gap:12px;
      min-width: 260px;
    }

    .logo{
      width:54px; height:54px;
      border-radius: 16px;
      background: linear-gradient(135deg, var(--primary), var(--primary2));
      box-shadow: 0 10px 22px rgba(0,0,0,.25);
      overflow:hidden;
      display:grid;
      place-items:center;
      flex: 0 0 auto;
    }
    .logo img{ width:100%; height:100%; object-fit:cover; display:block; }
    .logo .initial{
      color:#fff;
      font-weight:900;
      font-size:20px;
      text-shadow: 0 2px 10px rgba(0,0,0,.35);
    }

    .title h1{
      margin:0;
      font-size:16px;
      font-weight:900;
      letter-spacing:.2px;
      line-height:1.2;
    }
    .title p{
      margin:5px 0 0;
      color: var(--muted);
      font-size:12px;
      font-weight:700;
      line-height:1.4;
    }

    .back{
      display:inline-flex;
      align-items:center;
      gap:10px;
      padding:10px 12px;
      border-radius: 14px;
      border: 1px solid rgba(255,255,255,.12);
      background: rgba(255,255,255,.06);
      color: var(--text);
      text-decoration:none;
      font-weight:900;
      transition: transform .15s ease, background .15s ease;
      white-space:nowrap;
    }
    .back:hover{ background: rgba(255,255,255,.10); transform: translateY(-1px); }

    .card-body{ padding: 16px 18px 18px; }

    .grid{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap:14px;
      align-items:start;
    }

    .section{
      border:1px solid var(--border);
      border-radius: 18px;
      padding:16px;
      background: rgba(255,255,255,.04);
    }

    .section-head{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
      margin-bottom: 10px;
    }
    .section h2{
      margin:0;
      font-size:14px;
      font-weight:900;
      letter-spacing:.2px;
    }
    .hint{
      margin:6px 0 0;
      color:var(--muted);
      font-size:12.5px;
      font-weight:650;
      line-height:1.6;
    }
    .pill{
      font-size:11px;
      font-weight:900;
      padding:6px 10px;
      border-radius: 999px;
      border:1px solid rgba(255,255,255,.12);
      background: rgba(255,255,255,.06);
      color: rgba(234,240,255,.95);
      white-space:nowrap;
    }
    .pill.info{ border-color: rgba(59,130,246,.35); background: rgba(59,130,246,.12); }
    .pill.warn{ border-color: rgba(245,158,11,.35); background: rgba(245,158,11,.12); }

    label{
      display:block;
      font-size:12px;
      color: rgba(168,179,207,.95);
      margin:10px 0 6px;
      font-weight:800;
    }

    .input{
      width:100%;
      border:1px solid rgba(255,255,255,.12);
      background: rgba(2,6,23,.55);
      color:var(--text);
      border-radius: 14px;
      padding:12px 12px;
      outline:none;
      font-size:14px;
      font-weight:700;
    }
    .input::placeholder{ color: rgba(168,179,207,.75); }
    .input:focus{
      border-color: rgba(79,70,229,.85);
      box-shadow: 0 0 0 4px rgba(79,70,229,.18);
    }

    .error-text{
      margin:6px 0 0;
      color: #fecaca;
      font-size:12px;
      font-weight:700;
      min-height: 16px;
    }

    .btn{
      border: 1px solid rgba(255,255,255,.14);
      border-radius: 14px;
      padding:12px 14px;
      cursor:pointer;
      font-weight:900;
      font-size:14px;
      transition: transform .08s ease, opacity .2s ease, background .2s ease;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:10px;
      width:100%;
      font-family: inherit;
      margin-top: 12px;
      background: rgba(255,255,255,.06);
      color: var(--text);
    }
    .btn:active{ transform: translateY(1px); }
    .btn:disabled{ opacity:.55; cursor:not-allowed; }

    .btn-primary{
      border-color: rgba(79,70,229,.35);
      background: linear-gradient(135deg, rgba(79,70,229,.65), rgba(124,58,237,.55));
    }
    .btn-success{
      border-color: rgba(34,197,94,.35);
      background: linear-gradient(135deg, rgba(34,197,94,.75), rgba(22,163,74,.55));
      color: #052e14;
    }

    .msg{ margin-bottom: 12px; }

    .alert{
      border-radius: 16px;
      padding: 12px 14px;
      border: 1px solid rgba(255,255,255,.12);
      font-size: 13px;
      line-height: 1.6;
      background: rgba(255,255,255,.06);
      font-weight:700;
    }
    .alert-success{ background: rgba(34,197,94,.12); border-color: rgba(34,197,94,.25); color: #c7f9d4; }
    .alert-error{ background: rgba(239,68,68,.12); border-color: rgba(239,68,68,.25); color: #ffd3d3; }
    .alert-info{ background: rgba(59,130,246,.12); border-color: rgba(59,130,246,.25); color: #dbeafe; }

    .note{
      margin-top: 12px;
      padding: 12px 14px;
      border-radius: 16px;
      background: rgba(255,255,255,.05);
      border: 1px solid rgba(255,255,255,.10);
      color: rgba(168,179,207,.95);
      font-weight:700;
      font-size:12.5px;
      line-height:1.6;
    }
    .note i{ margin-right:8px; color: rgba(234,240,255,.9); }

    .two-col{ display:grid; grid-template-columns: 1fr 1fr; gap:10px; }

    @media (max-width: 900px){
      .grid{ grid-template-columns: 1fr; }
      .card-body{ padding: 14px; }
      .card-header{ padding: 18px 14px; }
      .brand{ min-width: 0; }
    }
  </style>
</head>
<body>

<div class="wrap">
  <div class="card">

    <div class="card-header">
      <div class="brand">
        <div class="logo">
          @if(file_exists(public_path('storage/asset/school-logo.png')))
            <img src="{{ asset('storage/asset/school-logo.png') }}" alt="School Logo">
          @else
            <span class="initial">{{ strtoupper(substr(config('app.name'), 0, 1)) }}</span>
          @endif
        </div>
        <div class="title">
          <h1>វិទ្យាល័យ ផ្គាំ • កំណត់ឡើងវិញ</h1>
          <p>កំណត់ពាក្យសម្ងាត់ ឬ PIN សម្រាប់អាណាព្យាបាល/អ្នកប្រើប្រាស់</p>
        </div>
      </div>

      <a class="back" href="{{ route('admin.dashboard') }}">
        <i class="fas fa-arrow-left"></i> ត្រឡប់ទៅផ្ទាំងគ្រប់គ្រង
      </a>
    </div>

    <div class="card-body">

      {{-- Messages --}}
      <div class="msg" id="message">
        @if (session('success'))
          <div class="alert alert-success">
            <i class="fas fa-circle-check"></i> {!! session('success') !!}
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-error" style="margin-top:10px;">
            <i class="fas fa-triangle-exclamation"></i> {{ session('error') }}
          </div>
        @endif
      </div>

      <div class="grid">

        <!-- Reset Password -->
        <div class="section">
          <div class="section-head">
            <div>
              <h2><i class="fas fa-key"></i> កំណត់ពាក្យសម្ងាត់ថ្មី</h2>
              <p class="hint">បញ្ចូលលេខទូរសព្ទ និងពាក្យសម្ងាត់ថ្មី រួចចុច “កំណត់ពាក្យសម្ងាត់”</p>
            </div>
            <span class="pill info">សម្រាប់ Login ជា Password</span>
          </div>

          <form method="POST" action="{{ route('admin.reset.password') }}" autocomplete="off">
            @csrf

            <label for="phone1">លេខទូរសព្ទ (បានចុះឈ្មោះក្នុងប្រព័ន្ធ)</label>
            <input
              class="input"
              id="phone1"
              name="phone"
              inputmode="numeric"
              placeholder="ឧ: 012345678"
              value="{{ old('phone') }}"
            >
            @error('phone')
              <div class="error-text">{{ $message }}</div>
            @enderror

            <label for="new_password">ពាក្យសម្ងាត់ថ្មី</label>
            <input
              class="input"
              id="new_password"
              name="new_password"
              type="password"
              placeholder="បញ្ចូលពាក្យសម្ងាត់ថ្មី"
            >
            @error('new_password')
              <div class="error-text">{{ $message }}</div>
            @enderror

            <button class="btn btn-primary" type="submit">
              <i class="fas fa-rotate"></i> កំណត់ពាក្យសម្ងាត់
            </button>
          </form>

          <div class="note">
            <i class="fas fa-circle-info"></i>
            សូមប្រាកដថា “លេខទូរសព្ទ” នេះមាននៅក្នុងប្រព័ន្ធ។ ប្រសិនបើមិនមាន សូមបង្កើតគណនីឲ្យអាណាព្យាបាលជាមុន។
          </div>
        </div>

        <!-- Reset PIN -->
        <div class="section">
          <div class="section-head">
            <div>
              <h2><i class="fas fa-shield-halved"></i> បង្កើត PIN ថ្មី (៦ខ្ទង់)</h2>
              <p class="hint">ប្រព័ន្ធនឹងបង្កើត PIN ថ្មី និងផ្ញើទៅ Telegram របស់អ្នកប្រើប្រាស់</p>
            </div>
            <span class="pill warn">សម្រាប់ Login ជា OTP/PIN</span>
          </div>

          <form method="POST" action="{{ route('admin.reset.pin') }}" autocomplete="off">
            @csrf

            <label for="phone2">លេខទូរសព្ទ (បានចុះឈ្មោះក្នុងប្រព័ន្ធ)</label>
            <input
              class="input"
              id="phone2"
              name="phone"
              inputmode="numeric"
              placeholder="ឧ: 012345678"
            >
            @error('phone')
              <div class="error-text">{{ $message }}</div>
            @enderror

            <button class="btn btn-success" type="submit">
              <i class="fas fa-wand-magic-sparkles"></i> បង្កើត PIN ថ្មី
            </button>
          </form>

          <div class="note">
            <i class="fas fa-paper-plane"></i>
            PIN ថ្មីនឹងត្រូវផ្ញើទៅ Telegram (តាម bot)។ សូមប្រាកដថា user បាន bind Telegram រួច។
          </div>
        </div>

      </div>

    </div>
  </div>
</div>

</body>
</html>
