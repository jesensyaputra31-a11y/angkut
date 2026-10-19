<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Assignment;
use App\Models\ReportPhoto;
use App\Models\ReportUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfficerController extends Controller
{
    public function dashboard()
    {
        // Ambil laporan yang ditugaskan ke petugas ini dan statusnya masih in_progress
        $assignments = Assignment::with(['report.category', 'report.user'])
            ->where('officer_id', Auth::id())
            ->whereNull('completed_at')
            ->orderBy('assigned_at', 'desc')
            ->get();

        return view('officer.dashboard', compact('assignments'));
    }

    public function show(Report $report)
    {
        // Pastikan petugas hanya bisa melihat laporan yang ditugaskan kepadanya
        $assignment = Assignment::where('report_id', $report->id)
            ->where('officer_id', Auth::id())
            ->firstOrFail();

        $report->load(['category', 'user', 'photos', 'updates']);
        
        return view('officer.reports.show', compact('report', 'assignment'));
    }

    public function complete(Request $request, Report $report)
    {
        // Validasi kepemilikan tugas
        $assignment = Assignment::where('report_id', $report->id)
            ->where('officer_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'after_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::beginTransaction();
        try {
            // Simpan foto 'after'
            if ($request->hasFile('after_photo')) {
                $file = $request->file('after_photo');
                $path = $file->store('reports', 'public');

                ReportPhoto::create([
                    'report_id' => $report->id,
                    'uploaded_by' => Auth::id(),
                    'photo_type' => 'after',
                    'file_path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }

            // Update status assignment
            $assignment->completed_at = now();
            $assignment->notes = $request->notes ?? $assignment->notes;
            $assignment->save();

            // Ubah status laporan menjadi 'resolved' (menunggu konfirmasi admin)
            $oldStatus = $report->status;
            $report->status = 'resolved'; 
            $report->save();

            // Catat riwayat
            ReportUpdate::create([
                'report_id' => $report->id,
                'old_status' => $oldStatus,
                'new_status' => 'resolved',
                'note' => $request->notes ? $request->notes : 'Pekerjaan selesai. Menunggu konfirmasi admin DLH.',
                'visibility' => 'public',
                'updated_by' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('officer.dashboard')->with('success', 'Laporan berhasil diselesaikan dan bukti foto telah diunggah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem. Silakan coba lagi.'])->withInput();
        }
    }
}