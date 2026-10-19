<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Sampah - ANGKUT</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body {
            background-image: url('https://images.unsplash.com/photo-1511497584788-876760111969?q=80&w=2000&auto=format&fit=crop');
            background-size: cover; background-position: center; background-attachment: fixed;
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; position: relative;
        }
        body::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(10, 36, 20, 0.4); z-index: 0; }
        .glass-card {
            position: relative; z-index: 1; width: 100%; max-width: 700px;
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 24px; padding: 40px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2); color: #FFFFFF;
        }
        .form-header { text-align: center; margin-bottom: 30px; }
        .form-header h1 { font-size: 32px; font-weight: 700; margin-bottom: 8px; }
        .form-header p { font-size: 14px; color: rgba(255, 255, 255, 0.8); }
        .nav-links { margin-top: 15px; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; }
        .nav-links a { color: #7ED957; text-decoration: none; font-weight: 600; font-size: 13px; border: 1px solid #7ED957; padding: 8px 16px; border-radius: 8px; transition: all 0.3s; }
        .nav-links a:hover { background: rgba(126, 217, 87, 0.2); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 14px 16px; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.4); border-radius: 12px; font-size: 14px; color: #FFFFFF; transition: all 0.3s; }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.6); }
        .form-control:focus { background: rgba(255, 255, 255, 0.25); border-color: #7ED957; outline: none; }
        select.form-control option { background-color: #0A2414; color: #FFFFFF; }
        textarea.form-control { resize: vertical; min-height: 100px; }
        .btn-primary { background: linear-gradient(135deg, #7ED957 0%, #3B8C2A 100%); color: #FFFFFF; border: none; padding: 16px 24px; border-radius: 12px; font-weight: 600; font-size: 16px; cursor: pointer; width: 100%; transition: all 0.3s; margin-top: 10px; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(126, 217, 87, 0.4); }
        .btn-secondary { background: rgba(255, 255, 255, 0.2); color: #FFFFFF; border: 1px solid rgba(255, 255, 255, 0.4); padding: 10px 16px; border-radius: 10px; font-weight: 500; font-size: 13px; cursor: pointer; margin-bottom: 12px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.3); border-color: #7ED957; }
        #map { height: 250px; border-radius: 12px; margin-bottom: 15px; border: 1px solid rgba(255, 255, 255, 0.4); z-index: 1; }
        .text-danger { color: #FFB3B3; font-size: 12px; margin-top: 6px; display: block; }
        .alert-success { background: rgba(126, 217, 87, 0.2); border: 1px solid #7ED957; color: #FFFFFF; padding: 16px; border-radius: 12px; margin-bottom: 25px; font-weight: 500; }
        .file-help { color: rgba(255, 255, 255, 0.6); font-size: 11px; margin-top: 4px; display: block; }
    </style>
</head>
<body>
    <main class="glass-card">
        <div class="form-header">
            <h1>Lapor Sampah</h1>
            <p>Bantu wujudkan Palembang yang lebih bersih.</p>
            <div class="nav-links">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('track.form') }}">Lacak Laporan</a>
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard Saya</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @error('error')
            <div class="alert-success" style="background: rgba(220, 38, 38, 0.2); border-color: #DC2626;">{{ $message }}</div>
        @enderror

        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="category_id">Kategori Masalah Sampah</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- TAMBAHAN INPUT NOMOR HP -->
            <div class="form-group">
                <label for="reporter_phone">Nomor HP / WhatsApp Anda</label>
                <input type="text" name="reporter_phone" id="reporter_phone" class="form-control" placeholder="Contoh: 08123456789" value="{{ old('reporter_phone', Auth::check() ? Auth::user()->phone : '') }}" required>
                <span class="file-help">Gunakan nomor ini untuk melacak status laporan Anda nanti.</span>
                @error('reporter_phone') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="description">Deskripsi Masalah</label>
                <textarea name="description" id="description" class="form-control" placeholder="Ceritakan kondisi sampah secara detail..." required>{{ old('description') }}</textarea>
                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="address">Alamat / Patokan Lokasi</label>
                <input type="text" name="address" id="address" class="form-control" placeholder="Contoh: Jl. Merdeka No. 10, depan pasar..." value="{{ old('address') }}" required>
                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Titik Lokasi Peta</label>
                <button type="button" class="btn-secondary" id="btn-lokasi">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    Gunakan Lokasi Saya
                </button>
                <div id="map"></div>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                @error('latitude') <span class="text-danger">{{ $message }}</span> @enderror
                @error('longitude') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="photo">Unggah Foto Kondisi</label>
                <input type="file" name="photo" id="photo" class="form-control" accept="image/jpeg,image/png,image/webp" required style="padding-top: 10px;">
                <span class="file-help">Format: JPG, PNG, WEBP. Maksimal 5 MB.</span>
                @error('photo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-primary">Kirim Laporan</button>
            <p style="text-align: center; font-size: 11px; color: rgba(255,255,255,0.6); margin-top: 20px;">ANGKUT adalah prototipe independen dan belum terintegrasi secara resmi dengan DLH Kota Palembang.</p>
        </form>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-2.9909, 104.7566], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
        var marker;
        function setMarker(lat, lng) {
            if (marker) { map.removeLayer(marker); }
            marker = L.marker([lat, lng]).addTo(map);
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        }
        map.on('click', function(e) { setMarker(e.latlng.lat, e.latlng.lng); });
        document.getElementById('btn-lokasi').addEventListener('click', function() {
            if (navigator.geolocation) {
                this.innerText = 'Mengambil lokasi...';
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    setMarker(lat, lng);
                    map.setView([lat, lng], 16);
                    document.getElementById('btn-lokasi').innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Gunakan Lokasi Saya';
                }, function(error) {
                    alert('Gagal mengambil lokasi.');
                    document.getElementById('btn-lokasi').innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Gunakan Lokasi Saya';
                });
            }
        });
        var oldLat = document.getElementById('latitude').value;
        var oldLng = document.getElementById('longitude').value;
        if (oldLat && oldLng) { setMarker(oldLat, oldLng); map.setView([oldLat, oldLng], 15); }
        setTimeout(function() { map.invalidateSize(); }, 500);
    </script>
</body>
</html>