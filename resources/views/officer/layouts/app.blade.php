<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petugas - ANGKUT</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body {
            background-image: url('https://images.unsplash.com/photo-1511497584788-876760111969?q=80&w=2000&auto=format&fit=crop');
            background-size: cover; background-position: center; background-attachment: fixed;
            min-height: 100vh; padding: 30px 20px; position: relative;
        }
        body::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(10, 36, 20, 0.7); z-index: 0;
        }
        .container { position: relative; z-index: 1; max-width: 1000px; margin: 0 auto; }
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 16px; padding: 15px 30px;
            color: white; margin-bottom: 30px;
        }
        .navbar a { color: #7ED957; text-decoration: none; font-weight: 600; margin-left: 20px; }
        .glass-card {
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 24px; padding: 30px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2); color: #FFFFFF; margin-bottom: 20px;
        }
        .btn { padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 14px; text-decoration: none; cursor: pointer; border: none; transition: all 0.3s; }
        .btn-primary { background: linear-gradient(135deg, #7ED957 0%, #3B8C2A 100%); color: #FFFFFF; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(126, 217, 87, 0.4); }
        .btn-danger { background: rgba(220, 38, 38, 0.8); color: white; }
        .alert-success { background: rgba(126, 217, 87, 0.2); border: 1px solid #7ED957; padding: 16px; border-radius: 12px; margin-bottom: 25px; font-weight: 500; color: white; }
        .text-danger { color: #FFB3B3; font-size: 12px; margin-top: 6px; display: block; }
        .form-control {
            width: 100%; padding: 14px 16px; background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.4); border-radius: 12px; font-size: 14px; color: #FFFFFF;
            transition: all 0.3s; margin-bottom: 15px;
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.6); }
        .form-control:focus { background: rgba(255, 255, 255, 0.25); border-color: #7ED957; outline: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="navbar">
            <div style="font-size: 24px; font-weight: 700; color: #7ED957;">ANGKUT. Petugas</div>
            <div>
                <a href="{{ route('officer.dashboard') }}">Tugas Saya</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="margin-left: 20px;">Logout</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @error('error')
            <div class="alert-success" style="background: rgba(220, 38, 38, 0.2); border-color: #DC2626;">{{ $message }}</div>
        @enderror

        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>