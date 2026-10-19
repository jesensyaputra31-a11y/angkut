@extends('admin.layouts.app')

@section('content')
    <!-- Tampilkan Error Validasi -->
    @if ($errors->any())
        <div style="background: rgba(220, 38, 38, 0.2); border: 1px solid #DC2626; padding: 16px; border-radius: 12px; margin-bottom: 20px; color: white;">
            <ul style="margin-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <!-- Kiri: Detail Laporan -->
        <div class="glass-card">
            <h2 style="color: #7ED957; margin-bottom: 5px;">{{ $report->report_code }}</h2>
            <p style="font-size: 14px; color: rgba(255,255,255,0.7); margin-bottom: 20px;">
                Dilaporkan oleh: {{ $report->user ? $report->user->name : 'Anonim' }} pada {{ $report->created_at->format('d M Y, H:i') }}
            </p>

            <div style="margin-bottom: 20px;">
                <h4 style="color: #7ED957; margin-bottom: 5px;">Kategori</h4>
                <p>{{ $report->category->name }}</p>
            </div>

            <div style="margin-bottom: 20px;">
                <h4 style="color: #7ED957; margin-bottom: 5px;">Deskripsi</h4>
                <p>{{ $report->description }}</p>
            </div>

            <div style="margin-bottom: 20px;">
                <h4 style="color: #7ED957; margin-bottom: 5px;">Alamat</h4>
                <p>{{ $report->address }}</p>
            </div>

            <div style="margin-bottom: 20px; display: flex; gap: 15px;">
                <div style="flex: 1;">
                    <h4 style="color: #7ED957; margin-bottom: 5px;">Foto Awal</h4>
                    @if($report->photos->where('photo_type', 'initial')->isNotEmpty())
                        <img src="{{ asset('storage/' . $report->photos->where('photo_type', 'initial')->first()->file_path) }}" style="width: 100%; border-radius: 12px; height: 200px; object-fit: cover;">
                    @endif
                </div>
                <div style="flex: 1;">
                    <h4 style="color: #7ED957; margin-bottom: 5px;">Foto Akhir (Petugas)</h4>
                    @if($report->photos->where('photo_type', 'after')->isNotEmpty())
                        <img src="{{ asset('storage/' . $report->photos->where('photo_type', 'after')->first()->file_path) }}" style="width: 100%; border-radius: 12px; height: 200px; object-fit: cover;">
                    @else
                        <p style="font-size: 12px; color: rgba(255,255,255,0.5);">Belum ada foto.</p>
                    @endif
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <h4 style="color: #7ED957; margin-bottom: 5px;">Riwayat Status</h4>
                <ul style="list-style: none; padding-left: 0;">
                    @foreach($report->updates as $update)
                        <li style="border-left: 2px solid #7ED957; padding-left: 15px; margin-bottom: 10px;">
                            <span style="font-size: 12px; color: rgba(255,255,255,0.6);">{{ $update->created_at ? $update->created_at->format('d M Y H:i') : '-' }}</span><br>
                            <strong>{{ strtoupper($update->new_status) }}</strong> - {{ $update->note }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Kanan: Form Aksi Admin -->
        <div>
            <!-- Jika status masih submitted -->
            @if($report->status === 'submitted')
            <div class="glass-card" style="margin-bottom: 20px;">
                <h3 style="margin-bottom: 15px;">Verifikasi Laporan</h3>
                <form action="{{ route('admin.reports.verify', $report->id) }}" method="POST">
                    @csrf
                    <label style="font-size: 14px;">Tindakan:</label>
                    <select name="action" class="form-control" required>
                        <option value="verify">Valid (Set ke QUEUED)</option>
                        <option value="needs_information">Minta Info Tambahan</option>
                        <option value="reject">Tolak Laporan</option>
                    </select>

                    <label style="font-size: 14px;">Prioritas (Jika Valid):</label>
                    <select name="priority" class="form-control">
                        <option value="low">Rendah</option>
                        <option value="medium" selected>Sedang</option>
                        <option value="high">Tinggi</option>
                    </select>

                    <label style="font-size: 14px;">Catatan (Wajib jika menolak):</label>
                    <textarea name="note" class="form-control" rows="3" placeholder="Alasan penolakan / instruksi..."></textarea>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan Keputusan</button>
                </form>
            </div>
            @endif

            <!-- Jika status queued (siap ditugaskan) -->
            @if($report->status === 'queued')
            <div class="glass-card">
                <h3 style="margin-bottom: 15px;">Tugaskan Petugas</h3>
                <form action="{{ route('admin.reports.assign', $report->id) }}" method="POST">
                    @csrf
                    <label style="font-size: 14px;">Pilih Petugas:</label>
                    <select name="officer_id" class="form-control" required>
                        <option value="">-- Pilih Petugas --</option>
                        @foreach($officers as $officer)
                            <option value="{{ $officer->id }}">{{ $officer->name }}</option>
                        @endforeach
                    </select>

                    <label style="font-size: 14px;">Catatan untuk Petugas:</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Bawa truk sampah..."></textarea>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Tugaskan Sekarang</button>
                </form>
            </div>
            @endif

            <!-- Jika status resolved (petugas sudah selesai, menunggu konfirmasi admin) -->
            @if($report->status === 'resolved')
            <div class="glass-card" style="border-color: #7ED957;">
                <h3 style="margin-bottom: 15px; color: #7ED957;">Konfirmasi Penyelesaian</h3>
                <p style="font-size: 13px; margin-bottom: 15px; color: rgba(255,255,255,0.8);">Petugas telah mengunggah foto bukti. Silakan periksa, lalu konfirmasi jika sudah sesuai.</p>
                <form action="{{ route('admin.reports.finalize', $report->id) }}" method="POST">
                    @csrf
                    <label style="font-size: 14px;">Catatan Tambahan (Opsional):</label>
                    <textarea name="note" class="form-control" rows="2" placeholder="Contoh: Pekerjaan sangat baik, area sudah bersih."></textarea>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Konfirmasi Selesai (Resolved)</button>
                </form>
            </div>
            @endif
        </div>
    </div>
@endsection