<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin Reset</title></head>
<body style="font-family: Arial; max-width: 600px; margin: 40px auto;">
    <h2>Admin Reset Password / PIN</h2>

    @if (session('success'))
        <div style="padding:10px; background:#d6ffe0; margin-bottom:10px;">
            {!! session('success') !!}
        </div>
    @endif
    @if (session('error'))
        <div style="padding:10px; background:#ffd6d6; margin-bottom:10px;">
            {{ session('error') }}
        </div>
    @endif

    <h3>Reset Password</h3>
    <form method="POST" action="/admin/reset-password">
        @csrf
        <input name="phone" placeholder="phone" style="width:100%; padding:10px;"><br><br>
        <input name="new_password" placeholder="new password" style="width:100%; padding:10px;"><br><br>
        <button style="padding:10px 15px;">Reset Password</button>
    </form>

    <hr>

    <h3>Reset PIN (6 digits)</h3>
    <form method="POST" action="/admin/reset-pin">
        @csrf
        <input name="phone" placeholder="phone" style="width:100%; padding:10px;"><br><br>
        <button style="padding:10px 15px;">Generate New PIN</button>
    </form>
</body>
</html>
