<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\Assignment;
use App\Models\ReportUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total' => Report::count(),
            'submitted' => Report::where('status', 'submitted')->count(),
            'in_progress' => Report::whereIn('status', ['queued', 'in_progress'])->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
        ];

        $recentReports = Report::with('category')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentReports'));
    }

    public function index()
    {
        $reports = Report::with(['category', 'user'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load(['category', 'user', 'photos', 'updates', 'assignments.officer']);
        $officers = User::where('role', 'officer')->where('is_active', true)->get();
        return view('admin.reports.show', compact('report', 'officers'));
    }

    public function verify(Request $request, Report $report)
    {
        $request->validate([
            'action' => 'required|in:verify,reject,needs_information',
            'note' => 'nullable|string|max:1000',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $oldStatus = $report->status;
        $newStatus = $oldStatus;

        if ($request->action === 'verify') {
            $newStatus = 'queued';
            $report->priority = $request->priority ?? 'medium';
        } elseif ($request->action === 'reject') {
            $newStatus = 'rejected';
        } elseif ($request->action === 'needs_information') {
            $newStatus = 'needs_information';
        }

        DB::beginTransaction();
        try {
            $report->status = $newStatus;
            $report->save();

            ReportUpdate::create([
                'report_id' => $report->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'note' => $request->note ?? 'Status diperbarui oleh Admin.',
                'visibility' => 'public',
                'updated_by' => Auth::id(),
            ]);

            DB::commit();
            return back()->with('success', 'Status laporan berhasil diperbarui menjadi: ' . strtoupper($newStatus));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui status.']);
        }
    }

    public function assign(Request $request, Report $report)
    {
        $request->validate([
            'officer_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($report->status !== 'queued') {
            return back()->withErrors(['error' => 'Laporan harus berstatus QUEUED (Terverifikasi) sebelum ditugaskan.']);
        }

        DB::beginTransaction();
        try {
            Assignment::create([
                'report_id' => $report->id,
                'officer_id' => $request->officer_id,
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
                'notes' => $request->notes,
            ]);

            $oldStatus = $report->status;
            $report->status = 'in_progress';
            $report->save();

            ReportUpdate::create([
                'report_id' => $report->id,
                'old_status' => $oldStatus,
                'new_status' => 'in_progress',
                'note' => 'Laporan telah ditugaskan ke petugas lapangan.',
                'visibility' => 'public',
                'updated_by' => Auth::id(),
            ]);

            DB::commit();
            return back()->with('success', 'Laporan berhasil ditugaskan ke petugas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menugaskan laporan.']);
        }
    }

    public function finalize(Request $request, Report $report)
    {
        $request->validate(['note' => 'nullable|string|max:1000']);

        DB::beginTransaction();
        try {
            $oldStatus = $report->status;
            $report->status = 'resolved';
            $report->public_approved_at = now(); // Tandai sudah disetujui untuk publik
            $report->save();

            ReportUpdate::create([
                'report_id' => $report->id,
                'old_status' => $oldStatus,
                'new_status' => 'resolved',
                'note' => $request->note ?? 'Laporan telah dikonfirmasi selesai dan diverifikasi oleh DLH.',
                'visibility' => 'public',
                'updated_by' => Auth::id(),
            ]);

            DB::commit();
            return back()->with('success', 'Laporan berhasil diselesaikan dan dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengonfirmasi laporan.']);
        }
    }
}