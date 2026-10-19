<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Lacak - ANGKUT</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body {
            background-image: url('https://images.unsplash.com/photo-1511497584788-876760111969?q=80&w=2000&auto=format&fit=crop');
            background-size: cover; background-position: center; background-attachment: fixed;
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; position: relative;
        }
        body::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(10, 36, 20, 0.6); z-index: 0; }
        .glass-card {
            position: relative; z-index: 1; width: 100%; max-width: 600px;
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 24px; padding: 40px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2); color: #FFFFFF;
        }
        .header { text-align: center; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { font-size: 24px; color: #7ED957; }
        .header p { font-size: 14px; color: rgba(255,255,255,0.7); margin-top: 5px; }
        
        .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        .info-label { color: rgba(255,255,255,0.7); }
        .info-value { font-weight: 600; }
        
        .timeline { margin-top: 25px; }
        .timeline-item { border-left: 2px solid #7ED957; padding-left: 15px; margin-bottom: 20px; position: relative; }
        .timeline-item::before { content: ''; position: absolute; left: -6px; top: 0; width: 10px; height: 10px; background: #7ED957; border-radius: 50%; }
        .timeline-date { font-size: 12px; color: rgba(255,255,255,0.6); }
        .timeline-status { font-weight: 700; font-size: 15px; margin: 2px 0; }
        .timeline-note { font-size: 13px; color: rgba(255,255,255,0.8); }
        
        .back-link { display: block; text-align: center; margin-top: 30px; color: #7ED957; text-decoration: none; font-size: 14px; font-weight: 600; }
    </style>
</head>
<body>
    <main class="glass-card">
        <div class="header">
            <h1>Laporan Terbaru Anda</h1>
            <p>Status Terkini: <strong>{{ strtoupper($report->status) }}</strong></p>
            <p style="font-size: 12px; margin-top: 10px; color: #A8FF78;">ID Laporan: {{ $report->report_code }}</p>
        </div>

        <div class="info-row">
            <span class="info-label">Kategori</span>
            <span class="info-value">{{ $report->category->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Lapor</span>
            <span class="info-value">{{ $report->created_at->format('d M Y, H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Alamat / Patokan</span>
            <span class="info-value" style="text-align: right; max-width: 60%;">{{ $report->address }}</span>
        </div>

        <div class="timeline">
            <h3 style="margin-bottom: 15px; font-size: 18px;">Riwayat Penanganan</h3>
            @foreach($report->updates as $update)
                <div class="timeline-item">
                    <div class="timeline-date">{{ $update->created_at->format('d M Y, H:i') }}</div>
                    <div class="timeline-status">{{ strtoupper($update->new_status) }}</div>
                    <div class="timeline-note">{{ $update->note }}</div>
                </div>
            @endforeach
        </div>

        <a href="{{ route('home') }}" class="back-link">Kembali ke Beranda</a>
    </main>
</body>
</html>