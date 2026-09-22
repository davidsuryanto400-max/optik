<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Optik Rapi</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:      #1a73e8;
            --primary-dark: #1558b0;
            --primary-light:#e8f0fe;
            --accent:       #fbbc04;
            --text:         #1f2937;
            --text-muted:   #6b7280;
            --border:       #e5e7eb;
            --bg:           #f3f4f6;
            --white:        #ffffff;
            --danger:       #ef4444;
            --radius:       14px;
            --shadow:       0 20px 60px rgba(0,0,0,.12);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated background blobs */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .35;
            animation: float 8s ease-in-out infinite;
        }
        body::before {
            width: 500px; height: 500px;
            background: radial-gradient(circle, #1a73e8, #6200ee);
            top: -150px; left: -150px;
        }
        body::after {
            width: 400px; height: 400px;
            background: radial-gradient(circle, #fbbc04, #f06292);
            bottom: -100px; right: -100px;
            animation-delay: -4s;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-30px) scale(1.05); }
        }

        /* Card */
        .login-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(20px);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 48px 40px 40px;
            animation: slideUp .5s ease;
        }
        @keyframes slideUp {
            from { opacity:0; transform: translateY(30px); }
            to   { opacity:1; transform: translateY(0); }
        }

        /* Logo area */
        .logo-wrap {
            text-align: center;
            margin-bottom: 32px;
        }
        .logo-icon {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, var(--primary), #6200ee);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 8px 24px rgba(26,115,232,.35);
        }
        .logo-icon i {
            font-size: 30px;
            color: #fff;
        }
        .logo-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -.5px;
        }
        .logo-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Form */
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 15px;
            pointer-events: none;
            transition: color .2s;
        }
        .form-control {
            width: 100%;
            padding: 13px 44px 13px 46px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: var(--white);
            color: var(--text);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,115,232,.12);
        }
        .form-control:focus + .input-icon,
        .input-group:focus-within .input-icon { color: var(--primary); }

        .btn-toggle-pass {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 14px;
            padding: 4px;
            transition: color .2s;
        }
        .btn-toggle-pass:hover { color: var(--primary); }

        /* Remember me */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            font-size: 13px;
            color: var(--text-muted);
        }
        .remember-row input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary), #6200ee);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            letter-spacing: .3px;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 6px 20px rgba(26,115,232,.35);
        }
        .btn-login:hover {
            opacity: .92;
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(26,115,232,.4);
        }
        .btn-login:active { transform: translateY(0); }

        /* Error alert */
        .alert-error {
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 22px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13px;
            color: var(--danger);
        }
        .alert-error i { margin-top: 2px; flex-shrink: 0; }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Input error state */
        .form-control.is-invalid { border-color: var(--danger); }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            color: var(--text-muted);
            font-size: 12px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
    </style>
</head>
<body>

<div class="login-card">
    <!-- Logo -->
    <div class="logo-wrap">
        <div class="logo-icon">
            <i class="fas fa-eye"></i>
        </div>
        <div class="logo-title">Optik Rapi</div>
        <div class="logo-subtitle">Sistem Informasi Optik</div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Login Form -->
    <form action="{{ route('login') }}" method="POST" autocomplete="off">
        @csrf

        <div class="input-group">
            <label class="form-label">Username</label>
            <input type="text"
                   name="username"
                   id="username"
                   class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                   placeholder="Masukkan username"
                   value="{{ old('username') }}"
                   autocomplete="username"
                   required autofocus>
            <i class="fas fa-user input-icon" style="top:calc(50% + 14px);"></i>
        </div>

        <div class="input-group">
            <label class="form-label">Password</label>
            <input type="password"
                   name="password"
                   id="password"
                   class="form-control"
                   placeholder="Masukkan password"
                   autocomplete="current-password"
                   required>
            <i class="fas fa-lock input-icon" style="top:calc(50% + 14px);"></i>
            <button type="button" class="btn-toggle-pass" onclick="togglePassword()" style="top:calc(50% + 14px);">
                <i class="fas fa-eye" id="eye-icon"></i>
            </button>
        </div>

        <div class="remember-row">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember" style="cursor:pointer;">Ingat saya</label>
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt mr-2"></i> Masuk
        </button>
    </form>

    <div class="login-footer">
        &copy; {{ date('Y') }} Optik Rapi &mdash; Hak cipta dilindungi.
    </div>
</div>

<script>
    function togglePassword() {
        var input = document.getElementById('password');
        var icon  = document.getElementById('eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

</body>
</html>
