<!doctype html>
<html lang="km">
<head>
  <meta charset="utf-8">
  <title>ចូលប្រព័ន្ធ • OTP តាម Telegram</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Fonts: Khmer-first -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@400;600;700;800&family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg1:#1c47d6;
      --bg2:#223c91;
      --card: rgba(15, 23, 42, .92);
      --border: rgba(255,255,255,.10);
      --text:#e5e7eb;
      --muted:#9ca3af;
      --primary:#4f46e5;
      --primary2:#06b6d4;
      --success:#22c55e;
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
      /* simpler gradient for better contrast and readability */
      background: linear-gradient(180deg, #1f45cd 0%, #1f45cd 100%);
      display:flex;
      align-items:flex-start; /* allow page to scroll on small screens */
      justify-content:center;
      min-height:100vh;
      padding:40px 20px; /* extra top/bottom space for mobile */
    }

    /* Layout container */
    .wrap{
      width:100%;
      max-width: 960px;
      display:flex;
      justify-content:center;
      margin: 0 auto 40px auto;
    }

    .card{
      width:100%;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow:visible;
      backdrop-filter: blur(10px);
      margin-bottom: 20px;
    }

    /* Header centered */
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
      object-fit: cover; /* prevents distortion */
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
      line-height:1.55;
      max-width: 720px;
    }

    .card-body{
      padding: 18px 22px 22px;
    }

    /* Sticky message */
    .msg{
      position: sticky;
      top: 12px;
      z-index: 10;
      margin-bottom: 14px;
    }

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
    .alert-info{
      background: rgba(59,130,246,.12);
      border-color: rgba(59,130,246,.25);
      color: #dbeafe;
    }

    /* Form grid */
    .grid{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap:16px;
      align-items:start;
    }

    .section{
      border:1px solid var(--border);
      border-radius: 16px;
      padding:16px;
      background: rgba(255,255,255,.03);
    }

    .section h2{
      margin:0 0 10px;
      font-size:14px;
      color:#fff;
      font-weight:800;
      letter-spacing:.2px;
    }
    .hint{
      margin:0 0 14px;
      color:var(--muted);
      font-size:12.5px;
      line-height:1.6;
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
    }
    .input::placeholder{ color: rgba(156,163,175,.75); }
    .input:focus{
      border-color: rgba(79,70,229,.8);
      box-shadow: 0 0 0 4px rgba(79,70,229,.15);
    }

    /* OTP nicer spacing */
    .otp-input{
      letter-spacing: 6px;
      text-align:center;
      font-weight:800;
      font-size:16px;
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
      transition: transform .06s ease, opacity .2s ease;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:10px;
      user-select:none;
      width:100%;
      font-family: inherit;
    }
    .btn:active{ transform: translateY(1px); }
    .btn:disabled{ opacity:.55; cursor:not-allowed; }

    .btn-primary{
      background: linear-gradient(135deg, var(--primary), #7c3aed);
      color:white;
    }
    .btn-success{
      background: linear-gradient(135deg, var(--success), #16a34a);
      color:#052e14;
    }
    .btn-ghost{
      width:auto;
      background: transparent;
      color: var(--text);
      border: 1px solid var(--border);
      padding:8px 10px;
      font-size:12px;
      font-weight:700;
    }

    .row{
      display:flex;
      justify-content:space-between;
      gap:10px;
      align-items:center;
      flex-wrap:wrap;
      margin-top:12px;
      color: rgba(229,231,235,.75);
      font-size:12px;
    }

    .divider{
      height:1px;
      background: var(--border);
      margin:14px 0;
    }

    .spinner{
      width:16px; height:16px;
      border-radius: 50%;
      border:2px solid rgba(255,255,255,.35);
      border-top-color: rgba(255,255,255,1);
      animation: spin 0.8s linear infinite;
      display:none;
    }
    .is-loading .spinner{ display:inline-block; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Mobile */
    @media (max-width: 860px){
      .grid{ grid-template-columns: 1fr; }
      .card-body{ padding: 16px; }
      .card-header{ padding: 22px 16px 16px; }
      /* reduce heavy background on small screens for better contrast */
      body{
        /* mobile: simplified background to improve readability */
        background: linear-gradient(180deg, #1f45cd 0%, #1f45cd 100%);
      }
    }
  </style>
</head>
<body>

@php
  $sendUrl = route('login.otp.send');
  $verifyUrl = route('login.otp.verify');
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

      <p class="page-title">ចូលប្រព័ន្ធដោយលេខទូរសព្ទ (OTP តាម Telegram)</p>
      <p class="page-subtitle">ចូលប្រើប្រព័ន្ធដោយសុវត្ថិភាព និងងាយស្រួលសម្រាប់អាណាព្យាបាល/គ្រូ។</p>
    </div>

    <div class="card-body">

      <div class="msg" id="message"></div>

      <div class="grid">

        <!-- Send OTP -->
        <div class="section">
          <h2>ផ្ញើលេខកូដ OTP</h2>
          <p class="hint">បញ្ចូលលេខទូរសព្ទ រួចចុច “ផ្ញើ OTP”។</p>

          <form id="sendForm" autocomplete="off">
            <label for="phone">លេខទូរសព្ទ</label>
            <input class="input" id="phone" name="phone" inputmode="numeric" placeholder="012345678">
            <div id="phoneError" class="error-text"></div>

            <button class="btn btn-primary" type="submit" id="sendBtn">
              <span class="spinner" aria-hidden="true"></span>
              <span id="sendBtnText">ផ្ញើ OTP</span>
            </button>

            <div class="row">
              <span id="timerText">មិនទាន់បាន OTP? អ្នកអាចផ្ញើម្ដងទៀត។</span>
              <button type="button" class="btn btn-ghost" id="clearBtn">សម្អាត</button>
            </div>
          </form>

          <div class="divider"></div>
          <div class="alert alert-info">
            សូមប្រាកដថា Telegram របស់អ្នកបានភ្ជាប់រួច (Bind) មុនពេលស្នើ OTP។
          </div>
        </div>

        <!-- Verify OTP -->
        <div class="section">
          <h2>បញ្ចូល OTP</h2>
          <p class="hint">OTP មាន ៦ ខ្ទង់ (ឧ. 123456)។</p>

          <form id="verifyForm" autocomplete="off">
            <label for="otp">OTP (៦ ខ្ទង់)</label>
            <input class="input otp-input" id="otp" name="otp" inputmode="numeric" maxlength="6" placeholder="••••••">
            <div id="otpError" class="error-text"></div>

            <button class="btn btn-success" type="submit" id="verifyBtn">
              <span class="spinner" aria-hidden="true"></span>
              <span id="verifyBtnText">ផ្ទៀងផ្ទាត់ និងចូលប្រើ</span>
            </button>
          </form>

          <div class="divider"></div>
          <div class="alert alert-info">
            ⚠️ សុវត្ថិភាព៖ កុំផ្តល់ OTP ឲ្យអ្នកដទៃ។ OTP នឹងផុតកំណត់ក្នុងរយៈពេលខ្លី។
          </div>
        </div>

      </div>

    </div>
  </div>
</div>

<script>
  const SEND_URL = @json($sendUrl);
  const VERIFY_URL = @json($verifyUrl);
  const CSRF_TOKEN = @json(csrf_token());

  const messageDiv = document.getElementById('message');
  const phoneInput = document.getElementById('phone');
  const otpInput = document.getElementById('otp');

  const phoneError = document.getElementById('phoneError');
  const otpError = document.getElementById('otpError');

  const sendForm = document.getElementById('sendForm');
  const verifyForm = document.getElementById('verifyForm');

  const sendBtn = document.getElementById('sendBtn');
  const verifyBtn = document.getElementById('verifyBtn');

  const timerText = document.getElementById('timerText');
  const clearBtn = document.getElementById('clearBtn');

  let resendCooldown = 0;
  let cooldownInterval = null;

  function showMessage(text, type = 'info') {
    messageDiv.innerHTML = `<div class="alert alert-${type}">${text}</div>`;
  }

  function setLoading(button, isLoading, loadingText) {
    const card = button.closest('.card');
    card.classList.toggle('is-loading', isLoading);

    // disable button while loading or cooldown
    button.disabled = isLoading || (button === sendBtn && resendCooldown > 0);

    const labelSpan = button.querySelector('span:nth-child(2)');
    if (!labelSpan) return;

    if (button === sendBtn) {
      labelSpan.textContent = isLoading ? loadingText : 'ផ្ញើ OTP';
    } else {
      labelSpan.textContent = isLoading ? loadingText : 'ផ្ទៀងផ្ទាត់ និងចូលប្រើ';
    }
  }

  function normalizePhone(phone) {
    return (phone || '').trim().replace(/\s+/g, '');
  }

  function onlyDigits(value) {
    return (value || '').replace(/\D/g, '');
  }

  function startCooldown(seconds) {
    resendCooldown = seconds;
    timerText.textContent = `អ្នកអាចផ្ញើ OTP ម្តងទៀតក្នុង ${resendCooldown} វិនាទី`;
    sendBtn.disabled = true;

    clearInterval(cooldownInterval);
    cooldownInterval = setInterval(() => {
      resendCooldown--;
      if (resendCooldown <= 0) {
        clearInterval(cooldownInterval);
        resendCooldown = 0;
        timerText.textContent = 'មិនទាន់បាន OTP? អ្នកអាចផ្ញើម្ដងទៀត។';
        sendBtn.disabled = false;
      } else {
        timerText.textContent = `អ្នកអាចផ្ញើ OTP ម្តងទៀតក្នុង ${resendCooldown} វិនាទី`;
      }
    }, 1000);
  }

  // Inputs
  phoneInput.addEventListener('input', () => {
    phoneInput.value = normalizePhone(phoneInput.value);
    phoneError.textContent = '';
  });

  otpInput.addEventListener('input', () => {
    otpInput.value = onlyDigits(otpInput.value).slice(0, 6);
    otpError.textContent = '';

    // UX: when reach 6 digits, focus verify button
    if (otpInput.value.length === 6) {
      verifyBtn.focus();
    }
  });

  clearBtn.addEventListener('click', () => {
    phoneInput.value = '';
    otpInput.value = '';
    phoneError.textContent = '';
    otpError.textContent = '';
    messageDiv.innerHTML = '';
    phoneInput.focus();
  });

  async function postJson(url, payload) {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN
      },
      body: JSON.stringify(payload)
    });

    const text = await res.text();
    let data = null;
    try { data = JSON.parse(text); }
    catch (e) { data = { message: text || 'ការឆ្លើយតបមិនត្រឹមត្រូវ' }; }

    return { ok: res.ok, status: res.status, data };
  }

  // Send OTP
  sendForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    phoneError.textContent = '';
    otpError.textContent = '';

    const phone = normalizePhone(phoneInput.value);
    const digits = onlyDigits(phone);

    if (!phone) {
      phoneError.textContent = 'សូមបញ្ចូលលេខទូរសព្ទ។';
      phoneInput.focus();
      return;
    }
    if (!/^\d{8,12}$/.test(digits)) {
      phoneError.textContent = 'លេខទូរសព្ទត្រូវមាន 8 ដល់ 12 ខ្ទង់។';
      phoneInput.focus();
      return;
    }

    setLoading(sendBtn, true, 'កំពុងផ្ញើ...');
    try {
      const { ok, status, data } = await postJson(SEND_URL, { phone });

      if (ok) {
        showMessage(data.message || 'បានផ្ញើ OTP ជោគជ័យ! សូមពិនិត្យ Telegram។', 'success');
        otpInput.focus();
        startCooldown(30);
      } else {
        if (data?.errors?.phone?.length) {
          phoneError.textContent = data.errors.phone[0];
        } else if (status === 419) {
          showMessage('សម័យ (Session) ផុតកំណត់។ សូម Refresh ទំព័រ ហើយសាកល្បងម្ដងទៀត។', 'error');
        } else {
          showMessage(data.message || 'មិនអាចផ្ញើ OTP បានទេ។ សូមសាកល្បងម្ដងទៀត។', 'error');
        }
      }
    } catch (error) {
      showMessage('កំហុស៖ ' + error.message, 'error');
    } finally {
      setLoading(sendBtn, false, '');
    }
  });

  // Verify OTP
  verifyForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    phoneError.textContent = '';
    otpError.textContent = '';

    const otp = onlyDigits(otpInput.value);

    if (!otp) {
      otpError.textContent = 'សូមបញ្ចូល OTP។';
      otpInput.focus();
      return;
    }
    if (!/^\d{6}$/.test(otp)) {
      otpError.textContent = 'OTP ត្រូវមាន ៦ ខ្ទង់។';
      otpInput.focus();
      return;
    }

    setLoading(verifyBtn, true, 'កំពុងផ្ទៀងផ្ទាត់...');
    try {
      const { ok, status, data } = await postJson(VERIFY_URL, { otp });

      if (ok) {
        showMessage(data.message || 'ចូលប្រើបានជោគជ័យ! កំពុងបញ្ជូនទៅផ្ទាំងគ្រប់គ្រង...', 'success');
        setTimeout(() => {
          window.location.href = data.redirect || '/dashboard';
        }, 900);
      } else {
        if (data?.errors?.otp?.length) {
          otpError.textContent = data.errors.otp[0];
        } else if (status === 419) {
          showMessage('សម័យ (Session) ផុតកំណត់។ សូម Refresh ទំព័រ ហើយសាកល្បងម្ដងទៀត។', 'error');
        } else {
          showMessage(data.message || 'OTP មិនត្រឹមត្រូវ។ សូមសាកល្បងម្ដងទៀត។', 'error');
        }
      }
    } catch (error) {
      showMessage('កំហុស៖ ' + error.message, 'error');
    } finally {
      setLoading(verifyBtn, false, '');
    }
  });

  // default focus
  phoneInput.focus();
</script>

</body>
</html>
