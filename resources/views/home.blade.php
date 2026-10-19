<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANGKUT - Sistem Pelaporan Sampah Palembang</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* =========================================
           RESET & BASE STYLES
           ========================================= */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        html { scroll-behavior: smooth; }
        body {
            background-image: url('https://images.unsplash.com/photo-1511497584788-876760111969?q=80&w=2000&auto=format&fit=crop');
            background-size: cover; background-position: center; background-attachment: fixed;
            min-height: 100vh; position: relative; color: white; overflow-x: hidden;
        }
        /* Overlay dengan efek cahaya radial di tengah */
        body::before {
            content: ''; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at center, rgba(10, 36, 20, 0.5) 0%, rgba(10, 36, 20, 0.95) 100%);
            z-index: 0; pointer-events: none;
        }
        
        /* Container Utama */
        .container { position: relative; z-index: 1; max-width: 1200px; margin: 0 auto; padding: 100px 20px 50px 20px; }
        
        /* =========================================
           NAVBAR GLASSMORPHISM (FULL WIDTH & COMPACT)
           ========================================= */
        .navbar-fixed {
            position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;
            background: rgba(255, 255, 255, 0.05); 
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            padding: 10px 0; 
            transition: all 0.3s;
        }
        .navbar-content {
            max-width: 1200px; margin: 0 auto; padding: 0 20px;
            display: flex; justify-content: space-between; align-items: center;
        }
        
        /* LOGO NAVBAR */
        .brand { display: flex; align-items: center; text-decoration: none; }
        .brand img {
            height: 40px; width: auto;
            filter: brightness(0) invert(1) drop-shadow(0px 2px 5px rgba(0,0,0,0.5));
            transition: all 0.3s ease;
        }
        .brand:hover img {
            transform: scale(1.05);
            filter: brightness(0) invert(1) drop-shadow(0px 4px 10px rgba(126,217,87,0.6));
        }

        .nav-links { display: flex; align-items: center; gap: 30px; }
        .nav-links a { color: white; text-decoration: none; font-weight: 500; font-size: 14px; transition: 0.3s; }
        .nav-links a:hover { color: #7ED957; }
        
        /* TOMBOL NAVBAR */
        .btn-nav { 
            background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            color: white !important; padding: 8px 18px; border-radius: 8px; font-weight: 600; 
            border: 1px solid rgba(255, 255, 255, 0.4); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); transition: 0.3s;
        }
        .btn-nav:hover { 
            background: rgba(255, 255, 255, 0.25); border-color: rgba(255, 255, 255, 0.8);
            transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2); 
        }

        /* =========================================
           HERO SECTION
           ========================================= */
        .hero { 
            text-align: center; min-height: calc(100vh - 100px);
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            padding-bottom: 80px; position: relative;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero h1 { 
            font-size: 64px; font-weight: 700; line-height: 1.1; margin-bottom: 25px; 
            text-shadow: 0 10px 30px rgba(0,0,0,0.5); animation: fadeInUp 0.8s ease-out forwards;
        }
        .hero h1 span { 
            background: linear-gradient(135deg, #A8FF78 0%, #78FFD6 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }
        .hero p { 
            font-size: 18px; color: rgba(255,255,255,0.9); max-width: 750px; 
            margin: 0 auto 45px auto; line-height: 1.6; text-shadow: 0 2px 10px rgba(0,0,0,0.5);
            animation: fadeInUp 0.8s ease-out 0.2s forwards; opacity: 0;
        }
        
        .hero-buttons { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; animation: fadeInUp 0.8s ease-out 0.4s forwards; opacity: 0; }
        .btn { padding: 18px 40px; border-radius: 50px; font-weight: 600; font-size: 16px; text-decoration: none; cursor: pointer; transition: all 0.4s; }
        
        .btn-primary { 
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            color: white; border: 1px solid rgba(255, 255, 255, 0.3); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); 
        }
        .btn-primary:hover { 
            background: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.6);
            transform: translateY(-5px) scale(1.02); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3); 
        }

        .scroll-indicator {
            position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%);
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            color: rgba(255,255,255,0.6); font-size: 12px; animation: bounce 2s infinite; cursor: pointer; text-decoration: none;
        }
        .scroll-indicator:hover { color: #7ED957; }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0) translateX(-50%); }
            40% { transform: translateY(-10px) translateX(-50%); }
            60% { transform: translateY(-5px) translateX(-50%); }
        }

        /* =========================================
           ABOUT US SECTION
           ========================================= */
        .about-section {
            display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; margin-bottom: 70px;
        }
        .about-text h2 { font-size: 36px; font-weight: 700; margin-bottom: 20px; line-height: 1.2; }
        .about-text h2 span { color: #7ED957; }
        .about-text p { font-size: 16px; color: rgba(255,255,255,0.8); line-height: 1.8; margin-bottom: 20px; }
        .about-text p.highlight { font-weight: 500; color: white; border-left: 3px solid #7ED957; padding-left: 15px; }

        .about-card {
            background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 24px; padding: 40px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .feature-item { display: flex; gap: 15px; align-items: flex-start; margin-bottom: 25px; }
        .feature-item:last-child { margin-bottom: 0; }
        .feature-icon {
            background: rgba(126, 217, 87, 0.15);
            min-width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;
            border-radius: 12px;
        }
        .feature-text h4 { font-size: 18px; margin-bottom: 5px; color: white; }
        .feature-text p { font-size: 14px; color: rgba(255,255,255,0.6); line-height: 1.5; }

        /* =========================================
           HOW IT WORKS (ALUR)
           ========================================= */
        .section-title { text-align: center; font-size: 36px; font-weight: 700; margin-bottom: 40px; }
        .section-title span { color: #7ED957; }
        .steps-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 70px; }
        .step-card {
            background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 30px;
            position: relative; overflow: hidden; transition: transform 0.3s, border-color 0.3s;
        }
        .step-card:hover { transform: translateY(-5px); border-color: rgba(126, 217, 87, 0.4); }
        .step-icon { margin-bottom: 15px; display: flex; }
        .step-card h4 { font-size: 20px; margin-bottom: 10px; color: #7ED957; }
        .step-card p { font-size: 14px; color: rgba(255,255,255,0.7); line-height: 1.6; }

        /* =========================================
           GALLERY SECTION
           ========================================= */
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 70px; }
        .gallery-item { border-radius: 20px; overflow: hidden; position: relative; height: 250px; border: 1px solid rgba(255,255,255,0.2); }
        .gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .gallery-item:hover img { transform: scale(1.1); }
        .gallery-overlay {
            position: absolute; bottom: 0; left: 0; right: 0;
            background: linear-gradient(to top, rgba(10,36,20,0.9), transparent);
            padding: 20px; color: white;
        }
        .gallery-overlay h4 { font-size: 18px; margin-bottom: 5px; }
        .gallery-overlay p { font-size: 13px; color: rgba(255,255,255,0.7); }

        /* =========================================
           MAP SECTION (KIRI-KANAN TANPA CARD)
           ========================================= */
        .map-layout {
            display: grid;
            grid-template-columns: 1.2fr 1fr; 
            gap: 40px;
            align-items: center;
            margin-bottom: 70px;
        }
        
        .map-container { width: 100%; }
        #publicMap { 
            height: 350px; 
            width: 100%;
            border-radius: 20px; 
            z-index: 1; 
            border: 1px solid rgba(255, 255, 255, 0.2); 
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .map-info { padding-left: 10px; }
        .map-info h2 { font-size: 32px; font-weight: 700; margin-bottom: 15px; line-height: 1.3; }
        .map-info h2 span { color: #7ED957; }
        .map-info p { font-size: 15px; color: rgba(255,255,255,0.8); line-height: 1.7; margin-bottom: 25px; }
        
        .map-features { list-style: none; }
        .map-features li {
            display: flex; align-items: flex-start; gap: 12px;
            margin-bottom: 15px; font-size: 14px; color: rgba(255,255,255,0.7);
        }
        .map-features svg { flex-shrink: 0; margin-top: 2px; }

        /* =========================================
           FOOTER
           ========================================= */
        .footer {
            background: #07190e; border-top: 1px solid rgba(126, 217, 87, 0.2);
            padding: 60px 20px 20px 20px; position: relative; z-index: 1; width: 100%;
        }
        .footer-content { 
            max-width: 1200px; margin: 0 auto; 
            display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 40px; 
        }
        .footer-brand { display: flex; flex-direction: column; align-items: flex-start; }
        
        .footer-logo { height: 45px; width: auto; filter: brightness(0) invert(1); margin-bottom: 20px; }

        .footer-brand p { font-size: 14px; color: rgba(255,255,255,0.6); line-height: 1.6; margin-bottom: 15px; }
        .footer-disclaimer { 
            font-size: 12px; color: #FCA5A5; background: rgba(220, 38, 38, 0.1); 
            padding: 10px; border-radius: 8px; border-left: 3px solid #FCA5A5; 
        }
        .footer-col h4 { font-size: 16px; margin-bottom: 20px; color: white; font-weight: 600; }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 12px; display: flex; align-items: center; gap: 10px; }
        .footer-col ul li a { color: rgba(255,255,255,0.6); text-decoration: none; font-size: 14px; transition: 0.3s; }
        .footer-col ul li a:hover { color: #7ED957; padding-left: 5px; }
        
        .footer-contact-text { color: rgba(255,255,255,0.6); font-size: 14px; }
        
        .footer-bottom { 
            max-width: 1200px; margin: 0 auto; padding-top: 20px; 
            border-top: 1px solid rgba(255,255,255,0.1); 
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;
            font-size: 13px; color: rgba(255,255,255,0.5); 
        }

        /* Responsive */
        @media (max-width: 992px) {
            .footer-content { grid-template-columns: 1fr 1fr; }
            .about-section { grid-template-columns: 1fr; } 
            .about-text { text-align: center; }
            .about-text p.highlight { border-left: none; border-top: 3px solid #7ED957; padding-left: 0; padding-top: 10px; }
            
            .map-layout { grid-template-columns: 1fr; gap: 30px; }
            .map-info { padding-left: 0; text-align: center; }
            .map-features li { justify-content: center; }
        }
        @media (max-width: 768px) {
            .hero h1 { font-size: 42px; }
            .hero { min-height: calc(100vh - 120px); padding-bottom: 50px; }
            .navbar-content { flex-direction: column; gap: 15px; padding: 10px; }
            .brand img { height: 35px; }
            .nav-links { flex-wrap: wrap; justify-content: center; gap: 15px; }
            .container { padding-top: 130px; }
            .hero-buttons { flex-direction: column; width: 100%; }
            .btn { width: 100%; }
            .footer-content { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR FULL WIDTH FIXED TOP (GLASSMORPHISM) -->
    <nav class="navbar-fixed">
        <div class="navbar-content">
            <a href="{{ route('home') }}" class="brand">
                <img src="{{ asset('images/logo.png') }}" alt="ANGKUT Logo">
            </a>
            <div class="nav-links">
                <a href="#beranda">Beranda</a>
                <a href="#tentang-kami">Tentang Kami</a>
                <a href="#cara-kerja">Cara Kerja</a>
                <a href="#galeri">Galeri</a>
                <a href="#peta-publik">Peta Publik</a>
                <a href="{{ route('track.form') }}">Lacak Laporan</a>
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn-nav">Dashboard Admin</a>
                    @elseif(Auth::user()->role === 'officer')
                        <a href="{{ route('officer.dashboard') }}" class="btn-nav">Dashboard Petugas</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-nav">Dashboard Saya</a>
                    @endauth
                @else
                    <a href="{{ route('login') }}" class="btn-nav">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <div class="container">
        
        <!-- HERO SECTION -->
        <header class="hero" id="beranda">
            <h1>Laporkan Sampah,<br><span>Palembang Bersih.</span></h1>
            <p>Platform digital untuk melaporkan masalah sampah di Kota Palembang. Pantau penanganannya secara transparan dan real-time bersama DLH.</p>
            <div class="hero-buttons">
                <a href="{{ route('reports.create') }}" class="btn btn-primary">Buat Laporan Sekarang</a>
            </div>

            <a href="#tentang-kami" class="scroll-indicator">
                <span>Scroll ke bawah</span>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 13l5 5 5-5M7 6l5 5 5-5"/></svg>
            </a>
        </header>

        <!-- ABOUT US SECTION -->
        <section id="tentang-kami" class="about-section">
            <!-- Kiri: Teks Penjelasan -->
            <div class="about-text">
                <h2>Tentang <span>ANGKUT</span></h2>
                <p class="highlight">
                    ANGKUT adalah platform digital inovatif yang dirancang untuk memfasilitasi masyarakat Kota Palembang dalam melaporkan masalah sampah secara cepat dan terstruktur.
                </p>
                <p>
                    Kami percaya bahwa lingkungan yang bersih adalah hak semua orang. Dengan menghubungkan masyarakat langsung dengan instansi terkait (DLH) melalui teknologi peta dan pelaporan real-time, kami berkomitmen untuk menciptakan Palembang yang lebih asri, sehat, dan bebas dari tumpukan sampah liar.
                </p>
            </div>

            <!-- Kanan: Kartu Keunggulan (Glassmorphism) -->
            <div class="about-card">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7ED957" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    </div>
                    <div class="feature-text">
                        <h4>Pelaporan Cepat</h4>
                        <p>Laporkan tumpukan sampah dalam hitungan menit tanpa perlu prosedur yang berbelit.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7ED957" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </div>
                    <div class="feature-text">
                        <h4>Lokasi Akurat</h4>
                        <p>Gunakan fitur peta interaktif dan GPS untuk menandai titik lokasi sampah secara presisi.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7ED957" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </div>
                    <div class="feature-text">
                        <h4>Transparan</h4>
                        <p>Pantau status laporan Anda dari mulai diverifikasi hingga selesai ditangani secara real-time.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- HOW IT WORKS SECTION -->
        <section id="cara-kerja">
            <h2 class="section-title">Bagaimana <span>Cara Kerjanya?</span></h2>
            <div class="steps-grid">
                <div class="step-card">
                    <!-- Angka dihapus -->
                    <div class="step-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#7ED957" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </div>
                    <h4>Laporkan</h4>
                    <p>Masyarakat mengisi formulir, menandai lokasi di peta, dan mengunggah foto kondisi sampah.</p>
                </div>
                <div class="step-card">
                    <!-- Angka dihapus -->
                    <div class="step-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#7ED957" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </div>
                    <h4>Verifikasi</h4>
                    <p>Admin DLH memeriksa laporan. Jika valid, laporan akan diteruskan ke petugas lapangan.</p>
                </div>
                <div class="step-card">
                    <!-- Angka dihapus -->
                    <div class="step-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#7ED957" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    </div>
                    <h4>Penanganan</h4>
                    <p>Petugas mendatangi lokasi, membersihkan sampah, dan mengunggah foto bukti penyelesaian.</p>
                </div>
                <div class="step-card">
                    <!-- Angka dihapus -->
                    <div class="step-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#7ED957" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <h4>Selesai</h4>
                    <p>Admin mengonfirmasi penyelesaian. Status laporan berubah menjadi Selesai dan tampil di peta publik.</p>
                </div>
            </div>
        </section>

        <!-- GALLERY SECTION -->
        <section id="galeri">
            <h2 class="section-title">Galeri <span>Aksi Nyata</span></h2>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=800&auto=format&fit=crop" alt="Petugas Membersihkan Sampah">
                    <div class="gallery-overlay">
                        <h4>Pembersihan Sungai</h4>
                        <p>Aksi bersama membersihkan aliran sungai dari sampah plastik.</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?q=80&w=800&auto=format&fit=crop" alt="Tempat Sampah Bersih">
                    <div class="gallery-overlay">
                        <h4>Fasilitas Umum Bersih</h4>
                        <p>Pemeliharaan tempat sampah di area publik agar tetap nyaman.</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=800&auto=format&fit=crop" alt="Menanam Pohon">
                    <div class="gallery-overlay">
                        <h4>Penghijauan Kota</h4>
                        <p>Mewujudkan Palembang yang asri dan bebas dari tumpukan sampah.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- PUBLIC MAP SECTION -->
        <section id="peta-publik" class="map-layout">
            <!-- Kiri: Peta -->
            <div class="map-container">
                <div id="publicMap"></div>
            </div>
            
            <!-- Kanan: Penjelasan -->
            <div class="map-info">
                <h2>Peta Persebaran <span>Sampah</span></h2>
                <p>Pantau secara langsung titik-titik lokasi sampah yang telah berhasil ditangani oleh petugas lapangan DLH di seluruh penjuru Kota Palembang.</p>
                
                <ul class="map-features">
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7ED957" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>Hanya menampilkan laporan berstatus <strong>Selesai Ditangani</strong>.</span>
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7ED957" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <span>Privasi pelapor terjaga (tanpa data pribadi).</span>
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7ED957" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <span>Pembaruan data secara real-time.</span>
                    </li>
                </ul>
            </div>
        </section>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-brand">
                <img src="{{ asset('images/logo.png') }}" alt="ANGKUT Logo" class="footer-logo">
                <p>Sistem Pelaporan dan Pemantauan Masalah Sampah di Kota Palembang. Mari bersama-sama ciptakan lingkungan yang lebih bersih dan sehat.</p>
                <div class="footer-disclaimer">
                    <strong>Penting:</strong> ANGKUT merupakan prototipe independen dan belum terintegrasi atau disetujui secara resmi oleh DLH Kota Palembang.
                </div>
            </div>
            
            <div class="footer-col">
                <h4>Tautan Cepat</h4>
                <ul>
                    <li><a href="{{ route('reports.create') }}">Buat Laporan</a></li>
                    <li><a href="{{ route('track.form') }}">Lacak Laporan</a></li>
                    <li><a href="#cara-kerja">Cara Kerja</a></li>
                    <li><a href="#galeri">Galeri</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Bantuan</h4>
                <ul>
                    <li><a href="#">Pusat Bantuan</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                    <li><a href="#">Kontak Kami</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Kontak</h4>
                <ul>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7ED957" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span class="footer-contact-text">halo@angkut.id</span>
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7ED957" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <span class="footer-contact-text">(0711) 123-4567</span>
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7ED957" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span class="footer-contact-text">Palembang, Sumsel</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div>&copy; {{ date('Y') }} ANGKUT - Prototipe Sistem Pelaporan Sampah.</div>
            <div>Dibuat untuk kebaikan lingkungan.</div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('publicMap').setView([-2.9909, 104.7566], 12);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);

        var reports = @json($publicReports);

        reports.forEach(function(report) {
            if (report.latitude && report.longitude) {
                var categoryName = report.category ? report.category.name : 'Umum';
                var marker = L.marker([report.latitude, report.longitude]).addTo(map);
                marker.bindPopup("<b>Laporan Selesai</b><br>Kategori: " + categoryName + "<br>Status: Selesai Ditangani");
            }
        });

        setTimeout(function() { map.invalidateSize(); }, 500);
    </script>
</body>
</html>