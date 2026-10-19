@extends('admin.layouts.app')

@section('content')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="glass-card" style="text-align: center;">
            <h3 style="color: rgba(255,255,255,0.7); font-size: 14px;">Total Laporan</h3>
            <h1 style="font-size: 42px; color: #7ED957; margin-top: 10px;">{{ $stats['total'] }}</h1>
        </div>
        <div class="glass-card" style="text-align: center;">
            <h3 style="color: rgba(255,255,255,0.7); font-size: 14px;">Perlu Verifikasi</h3>
            <h1 style="font-size: 42px; color: #FFB3B3; margin-top: 10px;">{{ $stats['submitted'] }}</h1>
        </div>
        <div class="glass-card" style="text-align: center;">
            <h3 style="color: rgba(255,255,255,0.7); font-size: 14px;">Sedang Ditangani</h3>
            <h1 style="font-size: 42px; color: #FCD34D; margin-top: 10px;">{{ $stats['in_progress'] }}</h1>
        </div>
        <div class="glass-card" style="text-align: center;">
            <h3 style="color: rgba(255,255,255,0.7); font-size: 14px;">Selesai</h3>
            <h1 style="font-size: 42px; color: #7ED957; margin-top: 10px;">{{ $stats['resolved'] }}</h1>
        </div>
    </div>

    <div class="glass-card">
        <h3 style="margin-bottom: 20px;">Laporan Terbaru</h3>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.3);">
                    <th style="padding: 10px;">Kode</th>
                    <th style="padding: 10px;">Kategori</th>
                    <th style="padding: 10px;">Status</th>
                    <th style="padding: 10px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentReports as $r)
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <td style="padding: 10px; font-weight: 600; color: #7ED957;">{{ $r->report_code }}</td>
                    <td style="padding: 10px;">{{ $r->category->name }}</td>
                    <td style="padding: 10px;"><span style="background: rgba(255,255,255,0.2); padding: 4px 8px; border-radius: 6px; font-size: 12px;">{{ strtoupper($r->status) }}</span></td>
                    <td style="padding: 10px;"><a href="{{ route('admin.reports.show', $r->id) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Detail</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection