@extends('officer.layouts.app')

@section('content')
    <div class="glass-card">
        <h3 style="margin-bottom: 20px;">Daftar Tugas Penanganan Sampah</h3>
        
        @if($assignments->isEmpty())
            <p style="text-align: center; color: rgba(255,255,255,0.7); padding: 30px;">
                Saat ini tidak ada tugas yang diberikan kepada Anda. Kerja bagus!
            </p>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                @foreach($assignments as $assignment)
                    <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                            <h4 style="color: #7ED957; font-size: 18px;">{{ $assignment->report->report_code }}</h4>
                            <span style="background: rgba(255,255,255,0.2); padding: 4px 8px; border-radius: 6px; font-size: 11px;">{{ strtoupper($assignment->report->priority ?? 'NORMAL') }}</span>
                        </div>
                        <p style="font-size: 13px; color: rgba(255,255,255,0.7); margin-bottom: 5px;">Kategori: <strong>{{ $assignment->report->category->name }}</strong></p>
                        <p style="font-size: 13px; color: rgba(255,255,255,0.7); margin-bottom: 15px;">Alamat: {{ $assignment->report->address }}</p>
                        
                        <a href="{{ route('officer.reports.show', $assignment->report->id) }}" class="btn btn-primary" style="width: 100%; display: block; text-align: center;">Lihat Detail & Selesaikan</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection