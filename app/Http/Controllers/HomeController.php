<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Report::count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'in_progress' => Report::whereIn('status', ['queued', 'in_progress'])->count(),
        ];

        $publicReports = Report::with('category')
            ->where('status', 'resolved')
            ->get(['id', 'latitude', 'longitude', 'category_id', 'status']);

        return view('home', compact('stats', 'publicReports'));
    }

    public function trackForm()
    {
        return view('track.index');
    }

    public function track(Request $request)
    {
        $request->validate(['phone' => 'required|string']);
        
        // Cari laporan berdasarkan nomor HP
        $report = Report::where('reporter_phone', $request->phone)->latest()->first();

        if (!$report) {
            return back()->withErrors(['phone' => 'Tidak ada laporan yang terdaftar dengan nomor HP tersebut.']);
        }

        return redirect()->route('track.result', $report->reporter_phone);
    }

    public function trackResult($phone)
    {
        // Tampilkan laporan terbaru dari nomor HP tersebut
        $report = Report::where('reporter_phone', $phone)
            ->with(['category', 'updates' => function($q) {
                $q->where('visibility', 'public')->orderBy('created_at', 'desc');
            }])
            ->latest()
            ->firstOrFail();

        return view('track.result', compact('report'));
    }
}