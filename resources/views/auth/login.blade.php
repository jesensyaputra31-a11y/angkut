<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ANGKUT</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body {
            background-image: url('https://images.unsplash.com/photo-1511497584788-876760111969?q=80&w=2000&auto=format&fit=crop');
            background-size: cover; background-position: center; background-attachment: fixed;
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;
            position: relative;
        }
        body::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(10, 36, 20, 0.5); z-index: 0;
        }
        .glass-card {
            position: relative; z-index: 1; width: 100%; max-width: 450px;
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 24px; padding: 40px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2); color: #FFFFFF;
        }
        .form-header { text-align: center; margin-bottom: 30px; }
        .form-header h1 { font-size: 32px; font-weight: 700; margin-bottom: 8px; }
        .form-header p { font-size: 14px; color: rgba(255, 255, 255, 0.8); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px; }
        .form-control {
            width: 100%; padding: 14px 16px; background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.4); border-radius: 12px; font-size: 14px; color: #FFFFFF;
            transition: all 0.3s;
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.6); }
        .form-control:focus { background: rgba(255, 255, 255, 0.25); border-color: #7ED957; outline: none; box-shadow: 0 0 0 3px rgba(126, 217, 87, 0.3); }
        .btn-primary {
            background: linear-gradient(135deg, #7ED957 0%, #3B8C2A 100%); color: #FFFFFF; border: none;
            padding: 16px 24px; border-radius: 12px; font-weight: 600; font-size: 16px; cursor: pointer;
            width: 100%; transition: all 0.3s; margin-top: 10px;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(126, 217, 87, 0.4); }
        .text-danger { color: #FFB3B3; font-size: 12px; margin-top: 6px; display: block; }
        .alert-success { background: rgba(126, 217, 87, 0.2); border: 1px solid #7ED957; padding: 16px; border-radius: 12px; margin-bottom: 25px; font-weight: 500; }
        .auth-links { text-align: center; margin-top: 20px; font-size: 14px; }
        .auth-links a { color: #7ED957; text-decoration: none; font-weight: 600; }
        .auth-links a:hover { text-decoration: underline; }
        .remember-group { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; font-size: 14px; }
    </style>
</head>
<body>
    <main class="glass-card">
        <div class="form-header">
            <h1>Login</h1>
            <p>Selamat datang kembali di ANGKUT</p>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email Anda" value="{{ old('email') }}" required autofocus>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="remember-group">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="margin-bottom: 0; cursor: pointer;">Ingat Saya</label>
            </div>

            <button type="submit" class="btn-primary">Login</button>

            <div class="auth-links">
                Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
            </div>
        </form>
    </main>
</body>
</html>