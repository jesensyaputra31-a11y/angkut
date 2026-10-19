@extends('officer.layouts.app')

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Kiri: Detail Laporan & Peta -->
        <div class="glass-card">
            <h2 style="color: #7ED957; margin-bottom: 5px;">{{ $report->report_code }}</h2>
            <p style="font-size: 14px; color: rgba(255,255,255,0.7); margin-bottom: 20px;">
                Dilaporkan pada: {{ $report->created_at->format('d M Y, H:i') }}
            </p>

            <div style="margin-bottom: 15px;">
                <h4 style="color: #7ED957; margin-bottom: 5px;">Deskripsi Masalah</h4>
                <p style="font-size: 14px;">{{ $report->description }}</p>
            </div>

            <div style="margin-bottom: 15px;">
                <h4 style="color: #7ED957; margin-bottom: 5px;">Alamat / Patokan</h4>
                <p style="font-size: 14px;">{{ $report->address }}</p>
            </div>

            <div style="margin-bottom: 15px;">
                <h4 style="color: #7ED957; margin-bottom: 5px;">Foto Kondisi Awal</h4>
                @if($report->photos->where('photo_type', 'initial')->isNotEmpty())
                    <img src="{{ asset('storage/' . $report->photos->where('photo_type', 'initial')->first()->file_path) }}" alt="Foto Awal" style="width: 100%; border-radius: 12px; max-height: 250px; object-fit: cover;">
                @endif
            </div>

            <div style="margin-bottom: 15px;">
                <h4 style="color: #7ED957; margin-bottom: 5px;">Peta Lokasi</h4>
                <div id="map" style="height: 200px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.4); z-index: 1;"></div>
            </div>
        </div>

        <!-- Kanan: Form Penyelesaian -->
        <div class="glass-card">
            <h3 style="margin-bottom: 15px;">Konfirmasi Penyelesaian</h3>
            <p style="font-size: 13px; color: rgba(255,255,255,0.7); margin-bottom: 20px;">
                Silakan unggah foto kondisi setelah sampah ditangani. Admin DLH akan memverifikasi laporan ini.
            </p>

            <form action="{{ route('officer.reports.complete', $report->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 14px; font-weight: 500;">Unggah Foto Bukti (After)</label>
                    <input type="file" name="after_photo" class="form-control" accept="image/jpeg,image/png,image/webp" required style="padding-top: 10px;">
                    <span style="font-size: 11px; color: rgba(255,255,255,0.6);">Format: JPG, PNG, WEBP. Maks 5 MB.</span>
                    @error('after_photo') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-size: 14px; font-weight: 500;">Catatan Petugas (Opsional)</label>
                    <textarea name="notes" class="form-control" rows="4" placeholder="Contoh: Sampah telah diangkut menggunakan truk..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Tandai Selesai & Kirim ke Admin</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var lat = {{ $report->latitude }};
        var lng = {{ $report->longitude }};
        
        var map = L.map('map').setView([lat, lng], 16);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
        L.marker([lat, lng]).addTo(map);
        
        setTimeout(function() { map.invalidateSize(); }, 500);
    </script>
@endpush