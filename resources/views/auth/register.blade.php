<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - ANGKUT</title>
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
            position: relative; z-index: 1; width: 100%; max-width: 500px;
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
        .auth-links { text-align: center; margin-top: 20px; font-size: 14px; }
        .auth-links a { color: #7ED957; text-decoration: none; font-weight: 600; }
        .auth-links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <main class="glass-card">
        <div class="form-header">
            <h1>Daftar Akun</h1>
            <p>Bergabunglah untuk menciptakan Palembang yang lebih bersih</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required autofocus>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email aktif" value="{{ old('email') }}" required>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="phone">Nomor HP / WhatsApp</label>
                <input type="text" name="phone" id="phone" class="form-control" placeholder="Contoh: 08123456789" value="{{ old('phone') }}" required>
                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password (Min. 8 Karakter)</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Buat password" required>
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ketik ulang password" required>
            </div>

            <button type="submit" class="btn-primary">Daftar Sekarang</button>

            <div class="auth-links">
                Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
            </div>
        </form>
    </main>
</body>
</html>