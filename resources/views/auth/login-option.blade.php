<!doctype html>
<html lang="km">
<head>
  <meta charset="utf-8">
  <title>ជម្រើសចូលប្រព័ន្ធ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Fonts: Khmer-first -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@400;600;700;800&family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
    :root{
      --bg1:#111827;
      --bg2:#0b1020;
      --card: rgba(15, 23, 42, .92);
      --border: rgba(255,255,255,.10);
      --text:#e5e7eb;
      --muted:#9ca3af;
      --primary:#4f46e5;
      --primary2:#06b6d4;
      --success:#22c55e;
      --shadow: 0 18px 50px rgba(0,0,0,.45);
      --radius: 18px;
    }

    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    body{
      margin:0;
      font-family: "Noto Sans Khmer","Google Sans", Arial, sans-serif;
      color:var(--text);
      background:
        radial-gradient(1200px 700px at 15% 10%, rgba(79,70,229,.35) 0%, transparent 60%),
        radial-gradient(900px 600px at 95% 25%, rgba(6,182,212,.25) 0%, transparent 60%),
        linear-gradient(180deg, var(--bg1) 0%, var(--bg2) 100%);
      display:flex;
      align-items:flex-start;
      justify-content:center;
      min-height:100vh;
      padding:40px 20px;
    }

    .wrap{
      width:100%;
      max-width: 980px;
      margin: 0 auto 40px auto;
    }

    .card{
      width:100%;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow:hidden;
      backdrop-filter: blur(10px);
    }

    .card-header{
      padding: 26px 22px 18px;
      border-bottom: 1px solid var(--border);
      text-align:center;
      display:flex;
      flex-direction:column;
      align-items:center;
      gap:10px;
    }

    .brand{
      display:flex;
      flex-direction:column;
      align-items:center;
      gap:10px;
    }

    .logo{
      width:72px;
      height:72px;
      border-radius: 18px;
      background: linear-gradient(135deg, var(--primary), var(--primary2));
      box-shadow: 0 10px 22px rgba(0,0,0,.25);
      overflow:hidden;
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .logo img{
      width:100%;
      height:100%;
      object-fit: cover;
      display:block;
    }
    .logo .initial{
      color: #fff;
      font-weight:800;
      font-size:28px;
      line-height:1;
      padding:6px;
      text-shadow: 0 2px 8px rgba(0,0,0,.35);
    }

    .school-name{
      margin:0;
      font-size:18px;
      font-weight:800;
      letter-spacing:.2px;
      line-height:1.25;
    }

    .page-title{
      margin:0;
      font-size:16px;
      font-weight:700;
      color: rgba(229,231,235,.95);
      line-height:1.35;
    }

    .page-subtitle{
      margin:0;
      font-size:13px;
      color: var(--muted);
      line-height:1.6;
      max-width: 720px;
    }

    .card-body{ padding: 18px 22px 22px; }

    .grid{
      display:grid;
      grid-template-columns: repeat(2, 1fr);
      gap:16px;
    }

    .option{
      border:1px solid var(--border);
      border-radius: 16px;
      padding:16px;
      background: rgba(255,255,255,.03);
      display:flex;
      flex-direction:column;
      gap:12px;
      min-height: 210px;
    }

    .option-top{
      display:flex;
      align-items:center;
      gap:12px;
    }

    .option-icon{
      width:46px;
      height:46px;
      border-radius: 14px;
      display:grid;
      place-items:center;
      background: rgba(79,70,229,.18);
      border: 1px solid rgba(79,70,229,.35);
      color:#c7d2fe;
      font-size:18px;
    }

    .option h2{
      margin:0;
      font-size:15px;
      font-weight:800;
    }

    .option p{
      margin:0;
      font-size:12.5px;
      line-height:1.7;
      color: var(--muted);
    }

    .pill{
      font-size:11px;
      padding:6px 10px;
      border-radius: 999px;
      background: rgba(255,255,255,.08);
      border:1px solid var(--border);
      color: #dbeafe;
      font-weight:700;
      display:inline-flex;
      align-items:center;
      gap:6px;
    }

    .btn{
      border:0;
      border-radius: 12px;
      padding:12px 14px;
      cursor:pointer;
      font-weight:800;
      font-size:14px;
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:10px;
      user-select:none;
      width:100%;
      font-family: inherit;
    }

    .btn-primary{
      background: linear-gradient(135deg, var(--primary), #7c3aed);
      color:white;
    }

    .btn-success{
      background: linear-gradient(135deg, var(--success), #16a34a);
      color:#052e14;
    }

    .btn-disabled{
      background: rgba(255,255,255,.06);
      color: rgba(229,231,235,.6);
      border: 1px solid var(--border);
      cursor:not-allowed;
    }

    .note{
      margin-top:12px;
      font-size:12px;
      color: var(--muted);
      text-align:center;
    }

    @media (max-width: 860px){
      .grid{ grid-template-columns: 1fr; }
      .card-body{ padding: 16px; }
      .card-header{ padding: 22px 16px 16px; }
    }
  </style>
</head>
<body>

@php
  $otpUrl = route('login');
  $passwordRoute = 'login.password.show';
  $passwordUrl = Route::has($passwordRoute) ? route($passwordRoute) : null;
@endphp

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
        <p class="school-name">វិទ្យាល័យ ផ្គាំ</p>
      </div>

      <p class="page-title">ជ្រើសរើសវិធីចូលប្រើប្រព័ន្ធ</p>
      <p class="page-subtitle">សម្រាប់សុវត្ថិភាព និងភាពងាយស្រួល សូមជ្រើសរើសវិធីដែលសមស្រប។</p>
    </div>

    <div class="card-body">
      <div class="grid">

        <div class="option">
          <div class="option-top">
            <div class="option-icon">
              <i class="fa-solid fa-paper-plane"></i>
            </div>
            <div>
              <h2>OTP តាម Telegram</h2>
              <span class="pill"><i class="fa-solid fa-shield"></i> សុវត្ថិភាពខ្ពស់</span>
            </div>
          </div>
          <p>ផ្ញើលេខ OTP ទៅ Telegram ហើយបញ្ចូលលេខកូដដើម្បីចូលប្រព័ន្ធ។ សមស្របសម្រាប់អាណាព្យាបាល និងគ្រូ។</p>
          <a class="btn btn-primary" href="{{ $otpUrl }}">
            ចូលដោយ OTP
          </a>
        </div>

        <div class="option">
          <div class="option-top">
            <div class="option-icon">
              <i class="fa-solid fa-key"></i>
            </div>
            <div>
              <h2>ពាក្យសម្ងាត់</h2>
              <span class="pill"><i class="fa-solid fa-user-shield"></i> សម្រាប់ Admin</span>
            </div>
          </div>
          <p>ចូលដោយ Email និងពាក្យសម្ងាត់។ សម្រាប់អ្នកគ្រប់គ្រង ឬបុគ្គលិកដែលមានគណនី។</p>

          @if($passwordUrl)
            <a class="btn btn-success" href="{{ $passwordUrl }}">
              ចូលដោយពាក្យសម្ងាត់
            </a>
          @else
            <span class="btn btn-disabled" aria-disabled="true">កំពុងរៀបចំ</span>
          @endif
        </div>

      </div>

      <div class="note">
        ត្រូវការជំនួយ? សូមទំនាក់ទំនងអ្នកគ្រប់គ្រងប្រព័ន្ធ។
      </div>
    </div>
  </div>
</div>

</body>
</html>
