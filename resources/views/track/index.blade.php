<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Laporan - ANGKUT</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body {
            background-image: url('https://images.unsplash.com/photo-1511497584788-876760111969?q=80&w=2000&auto=format&fit=crop');
            background-size: cover; background-position: center; background-attachment: fixed;
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; position: relative;
        }
        body::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(10, 36, 20, 0.5); z-index: 0; }
        .glass-card {
            position: relative; z-index: 1; width: 100%; max-width: 500px;
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 24px; padding: 40px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2); color: #FFFFFF; text-align: center;
        }
        h1 { font-size: 28px; margin-bottom: 10px; }
        p { font-size: 14px; color: rgba(255,255,255,0.8); margin-bottom: 25px; }
        .form-control {
            width: 100%; padding: 16px; background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.4); border-radius: 12px; font-size: 16px; color: #FFFFFF;
            transition: all 0.3s; text-align: center; font-weight: 500;
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.5); font-weight: 400; }
        .form-control:focus { background: rgba(255, 255, 255, 0.25); border-color: #7ED957; outline: none; }
        .btn-primary {
            background: linear-gradient(135deg, #7ED957 0%, #3B8C2A 100%); color: white; border: none;
            padding: 16px 24px; border-radius: 12px; font-weight: 600; font-size: 16px; cursor: pointer;
            width: 100%; transition: all 0.3s; margin-top: 15px;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(126, 217, 87, 0.4); }
        .text-danger { color: #FFB3B3; font-size: 12px; margin-top: 10px; display: block; }
        .back-link { display: inline-block; margin-top: 20px; color: #7ED957; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <main class="glass-card">
        <h1>Lacak Laporan</h1>
        <p>Masukkan Nomor HP/WhatsApp yang Anda gunakan saat melapor.</p>

        <form action="{{ route('track.process') }}" method="POST">
            @csrf
            <input type="text" name="phone" class="form-control" placeholder="Contoh: 08123456789" required autofocus>
            @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
            <button type="submit" class="btn-primary">Cari Laporan</button>
        </form>
        
        <a href="{{ route('home') }}" class="back-link">Kembali ke Beranda</a>
    </main>
</body>
</html>