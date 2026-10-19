<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ANGKUT</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body {
            background-image: url('https://images.unsplash.com/photo-1511497584788-876760111969?q=80&w=2000&auto=format&fit=crop');
            background-size: cover; background-position: center; background-attachment: fixed;
            min-height: 100vh; padding: 40px 20px; position: relative;
        }
        body::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(10, 36, 20, 0.6); z-index: 0;
        }
        .container { position: relative; z-index: 1; max-width: 900px; margin: 0 auto; }
        
        /* Header & Navbar */
        .top-nav {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px; color: white;
        }
        .brand { font-size: 28px; font-weight: 700; color: #7ED957; letter-spacing: 1px; }
        .nav-actions { display: flex; gap: 15px; }
        
        /* Glass Card General */
        .glass-card {
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 24px; padding: 30px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2); color: #FFFFFF; margin-bottom: 25px;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 14px;
            text-decoration: none; cursor: pointer; border: none; transition: all 0.3s;
        }
        .btn-primary { background: linear-gradient(135deg, #7ED957 0%, #3B8C2A 100%); color: #FFFFFF; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(126, 217, 87, 0.4); }
        .btn-outline { background: transparent; border: 1px solid rgba(255,255,255,0.5); color: white; }
        .btn-outline:hover { background: rgba(255,255,255,0.2); }
        .btn-danger { background: rgba(220, 38, 38, 0.8); color: white; }
        .btn-danger:hover { background: rgba(220, 38, 38, 1); }

        /* Table */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.2); font-size: 14px; }
        th { font-weight: 600; color: #7ED957; }
        
        /* Badges */
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-submitted { background: rgba(255, 255, 255, 0.2); }
        .badge-resolved { background: rgba(126, 217, 87, 0.3); color: #7ED957; border: 1px solid #7ED957; }
        .badge-rejected { background: rgba(220, 38, 38, 0.3); color: #FCA5A5; border: 1px solid #FCA5A5; }

        .alert-success { background: rgba(126, 217, 87, 0.2); border: 1px solid #7ED957; padding: 16px; border-radius: 12px; margin-bottom: 25px; font-weight: 500; }
        .empty-state { text-align: center; padding: 40px; color: rgba(255,255,255,0.7); }
    </style>
</head>
<body>
    <div class="container">
        <!-- Top Navigation -->
        <div class="top-nav">
            <div class="brand">ANGKUT.</div>
            <div class="nav-actions">
                <a href="{{ route('reports.create') }}" class="btn btn-primary">+ Buat Laporan Baru</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>

        <!-- Alert Success -->
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <!-- Main Card -->
        <div class="glass-card">
            <h2 style="margin-bottom: 5px;">Halo, {{ Auth::user()->name }}!</h2>
            <p style="color: rgba(255,255,255,0.8); font-size: 14px;">Berikut adalah daftar laporan sampah yang pernah Anda kirimkan.</p>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Kode Laporan</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td style="font-weight: 600; color: #7ED957;">{{ $report->report_code }}</td>
                                <td>{{ $report->category->name ?? '-' }}</td>
                                <td>{{ $report->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    @php
                                        $badgeClass = 'badge-submitted';
                                        if($report->status == 'resolved') $badgeClass = 'badge-resolved';
                                        if(in_array($report->status, ['rejected', 'out_of_scope', 'duplicate'])) $badgeClass = 'badge-rejected';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ strtoupper(str_replace('_', ' ', $report->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state">
                                    Belum ada laporan. Ayo mulai jaga kebersihan Palembang dengan membuat laporan pertama Anda!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>