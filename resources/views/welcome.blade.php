<!doctype html>
<html lang="km">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>វិទ្យាល័យ ផ្គាំ • ប្រព័ន្ធសាលា</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@400;600;700;800&family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    :root{
      --bg:#0b1020;
      --card: rgba(255,255,255,.06);
      --border: rgba(255,255,255,.10);
      --text:#eaf0ff;
      --muted:#a8b3cf;
      --primary:#4f46e5;
      --primary2:#06b6d4;
      --shadow: 0 18px 50px rgba(0,0,0,.45);
      --radius: 18px;
    }
    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    html{
      background: #0b1020;
    }
    body{
      margin:0;
      font-family:"Google Sans",system-ui,Arial,sans-serif;
      color:var(--text);
      background:
        radial-gradient(1200px 700px at 15% 10%, rgba(79,70,229,.35) 0%, transparent 60%),
        radial-gradient(900px 600px at 95% 25%, rgba(6,182,212,.25) 0%, transparent 60%),
        linear-gradient(180deg, #0b1020 0%, #0b1020 100%);
      background-repeat: no-repeat;
      min-height:100vh;
      min-height:100svh;
      min-height:100dvh;
    }

    .container{ max-width:1100px; margin:0 auto; padding:22px 18px 34px; }

    /* Topbar */
    .topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      padding:14px;
      border-radius:22px;
      border:1px solid var(--border);
      background: rgba(255,255,255,.05);
      box-shadow: var(--shadow);
      margin-top: 14px;
    }
    .brand{
      display:flex; align-items:center; gap:12px; min-width: 240px;
    }
    .logo{
      width:44px; height:44px; border-radius:14px;
      background: linear-gradient(135deg, var(--primary), var(--primary2));
      overflow:hidden; display:grid; place-items:center;
      border:1px solid rgba(255,255,255,.12);
    }
    .logo img{ width:100%; height:100%; object-fit:cover; }
    .brand h1{ margin:0; font-size:15px; font-weight:900; }
    .brand p{ margin:4px 0 0; font-size:12px; color:var(--muted); font-weight:700; }

    .actions{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .btn{
      display:inline-flex; align-items:center; justify-content:center; gap:10px;
      padding:10px 12px;
      border-radius:14px;
      border:1px solid rgba(255,255,255,.12);
      background: rgba(255,255,255,.06);
      color: var(--text);
      text-decoration:none;
      font-weight:900;
      transition: transform .15s ease, background .15s ease;
      white-space:nowrap;
    }
    .btn:hover{ background: rgba(255,255,255,.10); transform: translateY(-1px); }
    .btn.primary{
      border-color: rgba(79,70,229,.35);
      background: linear-gradient(135deg, rgba(79,70,229,.7), rgba(124,58,237,.55));
    }

    /* Hero */
    .hero{
      display:grid;
      grid-template-columns: .9fr 1.1fr;
      gap:14px;
      margin-top:14px;
      align-items:stretch;
    }
    .card{
      border:1px solid var(--border);
      background: rgba(255,255,255,.05);
      border-radius: 22px;
      box-shadow: var(--shadow);
      padding:18px;
    }
    .headline{
      margin:0;
      font-size:22px;
      font-weight:900;
      line-height:1.25;
      letter-spacing:.2px;
    }
    .sub{
      margin:10px 0 0;
      color: var(--muted);
      font-weight:700;
      line-height:1.7;
      font-size:13px;
      max-width: 48ch;
    }

    .feature-grid{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap:10px;
      margin-top:14px;
    }
    .feature{
      border:1px solid rgba(255,255,255,.10);
      background: rgba(255,255,255,.04);
      border-radius: 18px;
      padding:12px;
      display:flex;
      gap:10px;
      align-items:flex-start;
    }
    .feature i{ margin-top:2px; color: rgba(234,240,255,.9); }
    .feature b{ display:block; font-weight:900; font-size:13px; }
    .feature small{ display:block; margin-top:4px; color: var(--muted); font-weight:700; line-height:1.6; }

    /* Search card */
    .title{
      display:flex; align-items:center; justify-content:space-between; gap:10px;
      margin-bottom: 10px;
    }
    .title h2{ margin:0; font-size:14px; font-weight:900; }
    .title span{ font-size:11px; color: var(--muted); font-weight:800; }

    label{ display:block; margin:10px 0 6px; color: rgba(168,179,207,.95); font-size:12px; font-weight:800; }
    .input{
        font-family: "Google Sans", system-ui, Arial, sans-serif;
      width:100%;
      border-radius: 14px;
      border:1px solid rgba(255,255,255,.12);
      background: rgba(2,6,23,.55);
      padding:12px;
      color: var(--text);
      outline:none;
      font-weight:800;
      font-size:14px;
    }
    .input:focus{
      border-color: rgba(79,70,229,.85);
      box-shadow: 0 0 0 4px rgba(79,70,229,.18);
    }
    .date-row{
      font-family: "Google Sans", system-ui, Arial, sans-serif;
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap:10px;
      margin-bottom:8px;
    }
    .date-row .input{ min-height:40px; font-family: "Google Sans", system-ui, Arial, sans-serif;}
    .help{
      margin:10px 0 0;
      color: var(--muted);
      font-weight:700;
      font-size:12.5px;
      line-height:1.6;
    }
    .btn-wide{
      width:100%;
      margin-top: 12px;
      padding:12px 14px;
      border-radius: 14px;
      border:1px solid rgba(255,255,255,.14);
      background: linear-gradient(135deg, rgba(6,182,212,.65), rgba(79,70,229,.55));
      color: var(--text);
      font-weight:900;
      cursor:pointer;
      transition: transform .15s ease, opacity .2s ease;
    }
    .btn-wide:active{ transform: translateY(1px); }

    .alert{
      border-radius: 14px;
      padding: 12px 14px;
      border: 1px solid transparent;
      font-size: 13px;
      line-height: 1.6;
      background: rgba(255,255,255,.06);
    }
    .alert-success{
      background: rgba(34,197,94,.12);
      border-color: rgba(34,197,94,.25);
      color: #c7f9d4;
    }
    .alert-error{
      background: rgba(239,68,68,.12);
      border-color: rgba(239,68,68,.25);
      color: #ffd3d3;
    }

    .footer{
      margin-top:14px;
      color: rgba(168,179,207,.85);
      font-size:12px;
      font-weight:700;
      text-align:center;
      padding: 10px 0 0;
    }

    .announcements-section{
      margin-top: 22px;
      display: grid;
      gap: 12px;
    }
    .announcements-title{
      font-size: 20px;
      font-weight: 900;
      color: var(--text);
      margin: 0 0 14px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .announcements-grid{
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 14px;
      align-items: start;
    }
    .announcement-card{
      border: 1px solid var(--border);
      background: linear-gradient(135deg, rgba(79,70,229,.12), rgba(6,182,212,.08)), rgba(255,255,255,.04);
      border-radius: 18px;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      height: auto;
      transition: transform .2s ease, box-shadow .2s ease;
    }
    .announcement-card:hover{
      transform: translateY(-2px);
      box-shadow: 0 12px 36px rgba(0,0,0,.25);
    }
    .announcement-header{
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
    }
    .announcement-title{
      font-size: 15px;
      font-weight: 900;
      color: var(--text);
      margin: 0;
      flex: 1;
      line-height: 1.3;
    }
    .announcement-badge{
      font-size: 11px;
      font-weight: 800;
      padding: 4px 8px;
      border-radius: 999px;
      background: rgba(79,70,229,.2);
      border: 1px solid rgba(79,70,229,.35);
      color: rgba(234,240,255,.95);
      white-space: nowrap;
    }
    .announcement-content{
      font-size: 13px;
      color: rgba(168,179,207,.95);
      line-height: 1.6;
      flex: 0 0 auto;
    }
    .announcement-footer{
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      padding-top: 8px;
      border-top: 1px solid rgba(255,255,255,.08);
      font-size: 12px;
      color: rgba(168,179,207,.75);
    }
    .announcement-author{
      font-weight: 700;
      color: rgba(234,240,255,.85);
    }
    .announcement-time{
      font-size: 11px;
    }

    @media (max-width: 980px){
      .hero{ grid-template-columns: 1fr; }
      .feature-grid{ grid-template-columns: 1fr; }
      .topbar{ flex-direction:column; align-items:flex-start; }
      .actions{ width:100%; }
      .btn{ flex:1; }
      .date-row{ grid-template-columns: 1fr; }
      .announcements-grid{ grid-template-columns: 1fr; }
    }
  </style>
</head>

<body>
  <div class="container">

    <!-- Topbar -->
    <header class="topbar">
      <div class="brand">
        <div class="logo">
          @if(file_exists(public_path('storage/asset/school-logo.png')))
            <img src="{{ asset('storage/asset/school-logo.png') }}" alt="School Logo">
          @else
            <span style="font-weight:900;">P</span>
          @endif
        </div>
        <div>
          <h1>វិទ្យាល័យ ផ្គាំ</h1>
          <p>ប្រព័ន្ធត្រួតពិនិត្យវត្តមាន និងពិន្ទុសិស្ស</p>
        </div>
      </div>

      <div class="actions">
        <a class="btn" href="{{ route('login.option') }}">
          <i class="fas fa-right-to-bracket"></i> ចូលប្រព័ន្ធ
        </a>
        <a class="btn primary" href="{{ route('admin.dashboard') }}">
          <i class="fas fa-gauge-high"></i> ផ្ទាំងគ្រប់គ្រង
        </a>
      </div>
    </header>

    <!-- Hero -->
    <section class="hero">
      <!-- Right: Search by Student ID -->
      <div class="card">
        <div class="title">
          <h2><i class="fas fa-magnifying-glass"></i> ស្វែងរកវត្តមាន / ពិន្ទុ</h2>
          <span>សម្រាប់អាណាព្យាបាល</span>
        </div>

        @if (session('error'))
          <div class="alert alert-error" style="margin-bottom: 12px;">
            {{ session('error') }}
          </div>
        @endif
        @if (session('success'))
          <div class="alert alert-success" style="margin-bottom: 12px;">
            {{ session('success') }}
          </div>
        @endif

        <form method="GET" action="{{ route('public.lookup') }}">
          <label for="student_code">លេខសម្គាល់សិស្ស (Student Code)</label>
          <input
            id="student_code"
            name="student_code"
            class="input"
            placeholder="ឧ: 001245"
            value="{{ request('student_code') }}"
            required
          />

          <label for="dob">ថ្ងៃ-ខែ-ឆ្នាំកំណើត (ជាជម្រើស)</label>
          <div class="date-row" id="dobPicker">
            <select id="dobYear" class="input" aria-label="ឆ្នាំ">
              <option value="">ឆ្នាំ</option>
            </select>
            <select id="dobMonth" class="input" aria-label="ខែ" disabled>
              <option value="">ខែ</option>
            </select>
            <select id="dobDay" class="input" aria-label="ថ្ងៃ" disabled>
              <option value="">ថ្ងៃ</option>
            </select>
          </div>
          <input type="hidden" id="dob" name="dob" />

          <button class="btn-wide" type="submit">
            <i class="fas fa-search"></i> ស្វែងរកឥឡូវនេះ
          </button>

          <p class="help">
            * បញ្ចូលលេខសម្គាល់សិស្ស (ឧ: 00001)។ ប្រសិនបើចាំបាច់ ឯកសារលេខសម្គាល់ផ្សេងទៀត ដូចជាថ្ងៃកំណើត។
          </p>
        </form>

        <div class="help" style="margin-top:12px;">
          <b>ចំណាំ:</b> ប្រសិនបើមិនចាំ ID សូមទាក់ទងការិយាល័យសាលា។
        </div>


      </div>
      <!-- Left: Intro -->
      <div class="card">
        <h2 class="headline">សូមស្វាគមន៍ 👋</h2>
        <p class="sub">
          អាណាព្យាបាលអាចស្វែងរក <b>វត្តមាន</b> និង <b>ពិន្ទុ</b> សិស្សបានយ៉ាងងាយស្រួល ដោយប្រើលេខសម្គាល់ (ID) របស់សិស្ស។
        </p>

        <div class="feature-grid">
          <div class="feature">
            <i class="fas fa-shield-halved"></i>
            <div>
              <b>មានសុវត្ថិភាព</b>
              <small>តែ admin/teacher អាចគ្រប់គ្រងទិន្នន័យ</small>
            </div>
          </div>
          <div class="feature">
            <i class="fas fa-bolt"></i>
            <div>
              <b>ឆាប់រហ័ស</b>
              <small>ស្វែងរកលទ្ធផលក្នុងពេលខ្លី</small>
            </div>
          </div>
          <div class="feature">
            <i class="fas fa-mobile-screen"></i>
            <div>
              <b>ប្រើបានលើទូរសព្ទ</b>
              <small>Responsive សម្រាប់ mobile & PC</small>
            </div>
          </div>
          <div class="feature">
            <i class="fas fa-paper-plane"></i>
            <div>
              <b>ជូនដំណឹង Telegram</b>
              <small>PIN/OTP អាចផ្ញើតាម Telegram Bot</small>
            </div>
          </div>
        </div>

        <div class="footer">
          © {{ date('Y') }} វិទ្យាល័យ ផ្គាំ • School System
        </div>
      </div>

    </section>

    <!-- Announcements -->
    @if($announcements->count() > 0)
    <section class="announcements-section">
      <h2 class="announcements-title">
        <i class="fas fa-bullhorn"></i> មតិប្រកាស
      </h2>
      <div class="announcements-grid">
        @foreach($announcements as $announcement)
        <div class="announcement-card">
          <div class="announcement-header">
            <h3 class="announcement-title">{{ $announcement->title }}</h3>
            <span class="announcement-badge"><i class="fas fa-star"></i> ថ្មី</span>
          </div>
          <p class="announcement-content">
            {!! nl2br(e($announcement->message)) !!}
          </p>
          <div class="announcement-footer">
            <span class="announcement-author">{{ $announcement->user->name ?? 'Admin' }}</span>
            <span class="announcement-time">
              <i class="fas fa-clock"></i> {{ $announcement->published_at->diffForHumans() }}
            </span>
          </div>
        </div>
        @endforeach
      </div>
    </section>
    @endif

  </div>

  <script>
    (function () {
      const yearEl = document.getElementById('dobYear');
      const monthEl = document.getElementById('dobMonth');
      const dayEl = document.getElementById('dobDay');
      const hiddenEl = document.getElementById('dob');

      if (!yearEl || !monthEl || !dayEl || !hiddenEl) return;

      const now = new Date();
      const currentYear = now.getFullYear();
      const startYear = 1950;

      function pad2(v) {
        return String(v).padStart(2, '0');
      }

      function setHiddenDate() {
        const y = yearEl.value;
        const m = monthEl.value;
        const d = dayEl.value;
        if (y && m && d) {
          hiddenEl.value = `${y}-${pad2(m)}-${pad2(d)}`;
        } else {
          hiddenEl.value = '';
        }
      }

      function rebuildDays() {
        const y = parseInt(yearEl.value, 10);
        const m = parseInt(monthEl.value, 10);
        dayEl.innerHTML = '<option value="">ថ្ងៃ</option>';
        if (!y || !m) {
          dayEl.disabled = true;
          setHiddenDate();
          return;
        }
        const daysInMonth = new Date(y, m, 0).getDate();
        for (let d = 1; d <= daysInMonth; d++) {
          const opt = document.createElement('option');
          opt.value = d;
          opt.textContent = pad2(d);
          dayEl.appendChild(opt);
        }
        dayEl.disabled = false;
        setHiddenDate();
      }

      // build years
      for (let y = currentYear; y >= startYear; y--) {
        const opt = document.createElement('option');
        opt.value = y;
        opt.textContent = y;
        yearEl.appendChild(opt);
      }

      // build months
      for (let m = 1; m <= 12; m++) {
        const opt = document.createElement('option');
        opt.value = m;
        opt.textContent = pad2(m);
        monthEl.appendChild(opt);
      }

      yearEl.addEventListener('change', () => {
        monthEl.value = '';
        dayEl.value = '';
        monthEl.disabled = !yearEl.value;
        dayEl.disabled = true;
        setHiddenDate();
      });

      monthEl.addEventListener('change', () => {
        dayEl.value = '';
        rebuildDays();
      });

      dayEl.addEventListener('change', () => {
        setHiddenDate();
      });
    })();
  </script>
</body>
</html>
