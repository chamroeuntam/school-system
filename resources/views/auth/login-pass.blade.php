<!doctype html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <title>ចូលប្រព័ន្ធ • ពាក្យសម្ងាត់</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts: Khmer-first -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@400;600;700;800&family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        :root{
            --bg1:#0b1020;
            --bg2:#111827;
            --card: rgba(15, 23, 42, .92);
            --border: rgba(255,255,255,.10);
            --text:#e5e7eb;
            --muted:#9ca3af;
            --primary:#4f46e5;
            --primary2:#06b6d4;
            --danger:#ef4444;
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
            max-width: 560px;
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
            max-width: 520px;
        }

        .card-body{ padding: 18px 22px 22px; }

        .alert{
            border-radius: 14px;
            padding: 12px 14px;
            border: 1px solid transparent;
            font-size: 13px;
            line-height: 1.6;
            background: rgba(255,255,255,.06);
            margin-bottom: 12px;
        }
        .alert-error{
            background: rgba(239,68,68,.12);
            border-color: rgba(239,68,68,.25);
            color: #ffd3d3;
        }

        label{
            display:block;
            font-size:12px;
            color:var(--muted);
            margin:10px 0 6px;
        }

        .input{
            width:100%;
            border:1px solid var(--border);
            background: rgba(2,6,23,.55);
            color:var(--text);
            border-radius: 12px;
            padding:12px 12px;
            outline:none;
            font-size:14px;
            font-family: inherit;
        }
        .input::placeholder{ color: rgba(156,163,175,.75); }
        .input:focus{
            border-color: rgba(79,70,229,.8);
            box-shadow: 0 0 0 4px rgba(79,70,229,.15);
        }

        .input-row{
            position:relative;
        }

        .toggle{
            position:absolute;
            right:10px;
            top:50%;
            transform: translateY(-50%);
            background: transparent;
            border:0;
            color: rgba(229,231,235,.8);
            cursor:pointer;
            padding:6px 8px;
        }

        .error-text{
            margin-top:6px;
            color: #fecaca;
            font-size:12px;
            min-height: 16px;
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
            margin-top: 10px;
        }

        .btn-primary{
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            color:white;
        }

        .row{
            display:flex;
            justify-content:space-between;
            gap:10px;
            align-items:center;
            margin-top:12px;
            font-size:12px;
            color: rgba(229,231,235,.75);
        }

        .link{
            color:#c7d2fe;
            text-decoration:none;
            font-weight:700;
        }

        @media (max-width: 860px){
            .card-body{ padding: 16px; }
            .card-header{ padding: 22px 16px 16px; }
        }
    </style>
</head>
<body>

@php
    $loginRoute = 'login.password';
    $loginUrl = Route::has($loginRoute) ? route($loginRoute) : url('/login/password');
    $otpUrl = route('login');
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

            <p class="page-title">ចូលប្រព័ន្ធដោយពាក្យសម្ងាត់</p>
            <p class="page-subtitle">សម្រាប់អ្នកគ្រប់គ្រង និងបុគ្គលិកដែលមានគណនី។</p>
        </div>

        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <b>កំហុស:</b>
                    <div>
                        {{ $errors->first() }}
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ $loginUrl }}" autocomplete="off">
                @csrf

                <label for="email">អ៊ីមែល</label>
                <input class="input" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="name@example.com" required>
                <div class="error-text">@error('email'){{ $message }}@enderror</div>

                <label for="password">ពាក្យសម្ងាត់</label>
                <div class="input-row">
                    <input class="input" id="password" name="password" type="password" placeholder="••••••••" required>
                    <button type="button" class="toggle" id="toggleBtn" aria-label="Show password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div class="error-text">@error('password'){{ $message }}@enderror</div>

                <button class="btn btn-primary" type="submit">
                    ចូលប្រព័ន្ធ
                </button>

                <div class="row">
                    <span>មិនមានគណនី? សូមទំនាក់ទំនងអ្នកគ្រប់គ្រង</span>
                    <a class="link" href="{{ $otpUrl }}">ចូលដោយ OTP</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('toggleBtn');
    const passwordInput = document.getElementById('password');

    toggleBtn.addEventListener('click', () => {
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';
        toggleBtn.innerHTML = isHidden
            ? '<i class="fa-solid fa-eye-slash"></i>'
            : '<i class="fa-solid fa-eye"></i>';
    });
</script>

</body>
</html>
