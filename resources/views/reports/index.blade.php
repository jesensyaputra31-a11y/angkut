@extends('admin.layouts.app')

@section('content')
    <div class="glass-card">
        <h3 style="margin-bottom: 20px;">Semua Laporan Masyarakat</h3>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.3);">
                    <th style="padding: 10px;">Kode</th>
                    <th style="padding: 10px;">Pelapor</th>
                    <th style="padding: 10px;">Kategori</th>
                    <th style="padding: 10px;">Tanggal</th>
                    <th style="padding: 10px;">Status</th>
                    <th style="padding: 10px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $r)
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <td style="padding: 10px; font-weight: 600; color: #7ED957;">{{ $r->report_code }}</td>
                    <td style="padding: 10px;">{{ $r->user ? $r->user->name : 'Anonim' }}</td>
                    <td style="padding: 10px;">{{ $r->category->name }}</td>
                    <td style="padding: 10px;">{{ $r->created_at->format('d/m/Y') }}</td>
                    <td style="padding: 10px;"><span style="background: rgba(255,255,255,0.2); padding: 4px 8px; border-radius: 6px; font-size: 12px;">{{ strtoupper($r->status) }}</span></td>
                    <td style="padding: 10px;"><a href="{{ route('admin.reports.show', $r->id) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Detail</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top: 20px;">{{ $reports->links() }}</div>
    </div>
@endsection