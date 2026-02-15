<!doctype html>
<html lang="km">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>វិទ្យាល័យផ្គាំ</title>

  <!-- Fonts (Khmer-first) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@400;600;700;800&family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
    :root{
      --bg: #0b1020;
        font-family:"Google Sans","Noto Sans Khmer",system-ui,Arial,sans-serif;
      --card2: rgba(255,255,255,.08);
      --border: rgba(255,255,255,.10);
      --text:#eaf0ff;
      --muted:#a8b3cf;
      --primary:#4f46e5;
      --primary2:#06b6d4;
      --shadow: 0 18px 50px rgba(0,0,0,.45);
      --radius: 18px;
    }

      input, select, textarea, button{ font: inherit; }
    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    html{
      background: #070a14;
    }
    body{
      margin:0;
      font-family:"Google Sans",system-ui,Arial,sans-serif;
      color:var(--text);
      font-size: 15px;
      line-height: 1.5;
      background:
        radial-gradient(1200px 700px at 15% 10%, rgba(79,70,229,.35) 0%, transparent 60%),
        radial-gradient(900px 600px at 95% 25%, rgba(6,182,212,.25) 0%, transparent 60%),
        linear-gradient(180deg, #070a14 0%, #070a14 100%);
      background-repeat: no-repeat;
      min-height:100vh;
      min-height:100svh;
      min-height:100dvh;
    }

    /* ====== Font Size Utilities ====== */
    h1, h2, h3, h4, h5, h6 {
      line-height: 1.2;
      letter-spacing: -0.3px;
      margin: 0;
    }
    .text-xs { font-size: 11px; line-height: 1.4; }
    .text-sm { font-size: 13px; line-height: 1.4; }
    .text-base { font-size: 15px; line-height: 1.5; }
    .text-lg { font-size: 16px; line-height: 1.5; }
    .text-xl { font-size: 18px; line-height: 1.5; }
    .text-2xl { font-size: 22px; line-height: 1.3; font-weight: 800; }
    .text-3xl { font-size: 28px; line-height: 1.2; font-weight: 900; }

    /* ====== Layout ====== */
    .app{
      display:flex;
      min-height:100vh;
      padding:18px;
      gap:18px;
    }

    /* ====== Sidebar ====== */
    .sidebar{
      width:280px;
      background: linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.05));
      border:1px solid var(--border);
      border-radius: 22px;
      box-shadow: var(--shadow);
      overflow:hidden;
      position:sticky;
      top:18px;
      height: calc(100vh - 36px);
      display:flex;
      flex-direction:column;
    }

    .brand{
      padding:18px 16px 14px;
      display:flex;
      align-items:center;
      gap:12px;
    }
    .brand .logo{
      width:46px; height:46px;
      border-radius: 14px;
      background: rgba(255,255,255,.10);
      border: 1px solid rgba(255,255,255,.12);
      display:grid;
      place-items:center;
      overflow:hidden;
      flex: 0 0 auto;
    }
    .brand .logo img{ width:100%; height:100%; object-fit:cover; }

    .brand .title{
      line-height:1.2;
    }
    .brand .title h1{
      font-size:15px;
      margin:0;
      font-weight:800;
      letter-spacing:.2px;
    }
    .brand .title p{
      margin:4px 0 0;
      font-size:12px;
      color:var(--muted);
      font-weight:600;
    }

    .divider{
      height:1px;
      background: var(--border);
      margin: 0 14px;
    }

    .nav{
      padding: 12px 10px 14px;
      overflow:auto;
      scrollbar-width: none; /* Firefox */
      -ms-overflow-style: none; /* IE and Edge */
    }

    .nav::-webkit-scrollbar {
      display: none; /* Chrome, Safari, Opera */
    }

    .nav-section{
      margin: 10px 8px 6px;
      font-size:11px;
      color: var(--muted);
      letter-spacing:.3px;
      font-weight:700;
      text-transform: uppercase;
    }

    .nav a{
      display:flex;
      align-items:center;
      gap:10px;
      padding: 11px 12px;
      border-radius: 14px;
      text-decoration:none;
      color: var(--text);
      border: 1px solid transparent;
      transition: transform .15s ease, background .15s ease, border-color .15s ease;
      font-weight:700;
      font-size:14px;
    }

    .nav a i{
      width:22px;
      text-align:center;
      color: rgba(234,240,255,.9);
      opacity:.95;
    }

    .nav a:hover{
      background: rgba(255,255,255,.08);
      border-color: rgba(255,255,255,.10);
      transform: translateY(-1px);
    }

    .nav a.active{
      background: linear-gradient(135deg, rgba(79,70,229,.45), rgba(6,182,212,.25));
      border-color: rgba(255,255,255,.16);
      box-shadow: 0 10px 28px rgba(0,0,0,.25);
    }

    .sidebar-footer{
      margin-top:auto;
      padding: 14px 14px 16px;
      border-top: 1px solid var(--border);
      background: rgba(0,0,0,.12);
    }

    .mini-card{
      display:flex;
      align-items:center;
      gap:10px;
      padding:12px;
      border-radius: 16px;
      border:1px solid rgba(255,255,255,.10);
      background: rgba(255,255,255,.06);
    }
    .mini-card .dot{
      width:10px; height:10px;
      border-radius: 999px;
      background: var(--success);
      box-shadow: 0 0 0 6px rgba(0, 255, 94, 0.949);
    }
    .mini-card .txt{
      line-height:1.2;
    }
    .mini-card .txt b{ font-size:13px; }
    .mini-card .txt small{ display:block; margin-top:4px; color:var(--muted); font-weight:600; }

    /* ====== Content ====== */
    .content{
      flex:1;
      min-width:0;
      background: rgba(255,255,255,.05);
      border:1px solid var(--border);
      border-radius: 22px;
      box-shadow: var(--shadow);
      overflow:hidden;
      display:flex;
      flex-direction:column;
    }

    .topbar{
      padding: 14px 16px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      background: rgba(0,0,0,.12);
      border-bottom: 1px solid var(--border);
    }

    .topbar .left{
      display:flex;
      flex-direction:column;
      gap:4px;
    }
    .topbar h2{
      margin:0;
      font-size: 18px;
      font-weight:800;
      letter-spacing:.2px;
    }
    .topbar p{
      margin:0;
      color:var(--muted);
      font-size: 13px;
      font-weight:600;
    }

    .actions{
      display:flex;
      gap:10px;
      align-items:center;
    }

    .search{
      display:flex;
      align-items:center;
      gap:8px;
      padding:10px 12px;
      border-radius: 14px;
      border:1px solid rgba(255,255,255,.10);
      background: rgba(255,255,255,.06);
      min-width: 260px;
    }
    .search i{ color: var(--muted); }
    .search input{
      width:100%;
      border:0;
      outline:0;
      background: transparent;
      color: var(--text);
      font: inherit;
      font-weight:600;
      font-size: 14px;
    }
    .search input::placeholder{ color: rgba(168,179,207,.75); }

    .btn{
      border: 1px solid rgba(255,255,255,.12);
      background: rgba(255,255,255,.06);
      color: var(--text);
      font-weight:800;
      font-size: 14px;
      padding:10px 12px;
      border-radius: 14px;
      cursor:pointer;
      transition: transform .15s ease, background .15s ease;
    }
    .btn:hover{ background: rgba(255,255,255,.10); transform: translateY(-1px); }

    .page{
      padding: 16px;
      overflow:auto;
    }

    /* ====== Modern Selects ====== */
    .content select,
    .content .select{
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background:
        linear-gradient(135deg, rgba(79,70,229,.12), rgba(6,182,212,.08)),
        rgba(255,255,255,.06);
      border: 1px solid rgba(255,255,255,.16);
      color: var(--text);
      padding: 10px 38px 10px 12px;
      border-radius: 14px;
      font-weight: 800;
      letter-spacing: .2px;
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.02);
      background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none'><path d='M5 7l5 6 5-6' stroke='%23eaf0ff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/></svg>");
      background-repeat: no-repeat;
      background-position: right 12px center;
      background-size: 16px 16px;
    }

    .content select:focus,
    .content .select:focus{
      outline: none;
      border-color: rgba(99,102,241,.7);
      box-shadow:
        0 0 0 3px rgba(99,102,241,.18),
        inset 0 0 0 1px rgba(255,255,255,.04);
    }

    .content select:disabled,
    .content .select:disabled{
      opacity: .6;
      cursor: not-allowed;
    }

    .content select option,
    .content .select option{
      background: #0e1426;
      color: #eaf0ff;
    }

    /* ====== Mobile ====== */
    .mobile-toggle{
      display:none;
      gap:10px;
      align-items:center;
    }
    .hamburger{
      width:42px; height:42px;
      border-radius: 14px;
      border:1px solid rgba(255,255,255,.12);
      background: rgba(255,255,255,.06);
      color: var(--text);
      cursor:pointer;
      display:grid;
      place-items:center;
    }

    @media (max-width: 960px){
      .app{ padding:12px; }
      .sidebar{
        position:fixed;
        left:12px; top:12px;
        height: calc(100vh - 24px);
        transform: translateX(-110%);
        transition: transform .2s ease;
        z-index: 50;
      }
      .sidebar.open{ transform: translateX(0); }
      .content{ width:100%; }
      .mobile-toggle{ display:flex; }
      .search{ min-width: 180px; }
      .overlay{
        position:fixed;
        inset:0;
        background: rgba(0,0,0,.55);
        backdrop-filter: blur(2px);
        display:none;
        z-index: 40;
      }
      .overlay.show{ display:block; }
      .topbar .left{
        display:none;

        gap:4px;
        }
    }
  </style>
</head>

<body>
  <div class="overlay" id="overlay"></div>

  <div class="app">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div class="brand">
        <div class="logo">
          <img src="{{ asset('storage/asset/school-logo.png') }}" alt="School Logo">
        </div>
        <div class="title">
          <h1>វិទ្យាល័យផ្គាំ</h1>
          <p>School Management Panel</p>
        </div>
      </div>

      <div class="divider"></div>

      <nav class="nav">
        <div class="nav-section">Menu</div>

        <a href="/" class="nav-link" data-path="/">
          <i class="fas fa-home"></i>
          <span>ទំព័រដើម</span>
        </a>

        <a href="{{ route('dashboard') }}" class="nav-link" data-path="/admin/dashboard">
          <i class="fas fa-tachometer-alt"></i>
          <span>ផ្ទាំងគ្រប់គ្រង</span>
        </a>

        @if(auth()->user()->isAdmin())
        <a href="/admin/teachers" class="nav-link" data-path="/admin/teachers">
          <i class="fas fa-user-tie"></i>
          <span>គ្រូបង្រៀន</span>
        </a>

         <a href="/admin/students" class="nav-link" data-path="/admin/students">
          <i class="fas fa-user-graduate"></i>
          <span>ផ្ទាំងគ្រប់គ្រងសិស្ស</span>
        </a>

        <a href="/admin/classes" class="nav-link" data-path="/admin/classes">
            <i class="fas fa-chalkboard"></i>
            <span>ផ្ទាំងគ្រប់គ្រងថ្នាក់</span>
        </a>

        <a href="/admin/subjects" class="nav-link" data-path="/admin/subjects">
          <i class="fas fa-book"></i>
          <span>មុខវិជ្ជា</span>
        </a>

        <a href="/admin/subject-rules" class="nav-link" data-path="/admin/subject-rules">
          <i class="fas fa-list-check"></i>
          <span>កំណត់ពិន្ទុ</span>
        </a>

        <a href="{{ route('admin.terms.index') }}" class="nav-link" data-path="/admin/terms">
          <i class="fas fa-calendar-alt"></i>
          <span>ឆមាសសិក្សា</span>
        </a>

        <a href="{{ route('admin.scores.index') }}" class="nav-link" data-path="/admin/scores">
          <i class="fas fa-chart-bar"></i>
          <span>គ្រប់គ្រងពិន្ទុ</span>
        </a>

        <a href="{{ route('admin.sheet-sources.index') }}" class="nav-link" data-path="/admin/sheet-sources">
          <i class="fas fa-file-lines"></i>
          <span>Connect Sheet Sources</span>
        </a>

        <a href="{{ route('admin.announcements.index') }}" class="nav-link" data-path="/admin/announcements">
          <i class="fas fa-bullhorn"></i>
          <span>Post Announcements</span>
        </a>

        <a href="{{ route('admin.academic-years.index') }}" class="nav-link" data-path="/admin/academic-years">
            <i class="fas fa-calendar"></i>
            <span>ឆ្នាំសិក្សា</span>
        </a>

        <a href="/admin/reset" class="nav-link" data-path="/admin/reset">
          <i class="fas fa-key"></i>
          <span>កំណត់ពាក្យសម្ងាត់ / PIN</span>
        </a>
        @endif


        <div class="nav-section">System</div>

        <a href="/admin/settings" class="nav-link" data-path="/admin/settings">
          <i class="fas fa-gear"></i>
          <span>ការកំណត់</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" style="display: none;" id="logoutForm">
          @csrf
        </form>
        <a href="#" class="nav-link" onclick="document.getElementById('logoutForm').submit(); return false;">
          <i class="fas fa-right-from-bracket"></i>
          <span>ចាកចេញ</span>
        </a>
      </nav>

      <div class="sidebar-footer">
        <div class="mini-card">
          <span class="dot"></span>
          <div class="txt">
            <b>Loged by: {{ auth()->user()->name }}</b>
            <small>Ready for use</small>
          </div>
        </div>
      </div>
    </aside>

    <!-- Content -->
    <main class="content">
      <header class="topbar">
        <div class="mobile-toggle">
          <button class="hamburger" id="toggleBtn" aria-label="Open menu">
            <i class="fas fa-bars"></i>
          </button>
        </div>

        <div class="left">
          <h2 id="pageTitle">ផ្ទាំងគ្រប់គ្រង</h2>
          <p>ស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រងសាលា</p>
        </div>

        <div class="actions">
          <div class="search">
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" placeholder="ស្វែងរក..." />
          </div>
          <button class="btn">
            <i class="fas fa-bell"></i>
          </button>
        </div>
      </header>

      <section class="page">

        @yield('content')

      </section>
    </main>
  </div>

  <script>
    // Active link highlight (auto)
    (function(){
      const path = location.pathname.replace(/\/+$/,'') || "/";
      document.querySelectorAll(".nav-link").forEach(a=>{
        const p = (a.getAttribute("data-path") || "").replace(/\/+$/,'') || "/";
        if(p === path) a.classList.add("active");
      });

      // Optional: set page title by active link text
      const active = document.querySelector(".nav-link.active span");
      if(active) document.getElementById("pageTitle").textContent = active.textContent;
    })();

    // Mobile sidebar toggle
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");
    const toggleBtn = document.getElementById("toggleBtn");

    function openMenu(){
      sidebar.classList.add("open");
      overlay.classList.add("show");
    }
    function closeMenu(){
      sidebar.classList.remove("open");
      overlay.classList.remove("show");
    }

    if(toggleBtn){
      toggleBtn.addEventListener("click", ()=> {
        sidebar.classList.contains("open") ? closeMenu() : openMenu();
      });
    }
    overlay.addEventListener("click", closeMenu);
    window.addEventListener("keydown", (e)=> { if(e.key === "Escape") closeMenu(); });
  </script>
</body>
</html>
