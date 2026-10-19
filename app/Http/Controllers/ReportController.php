<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Category;
use App\Models\ReportPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('reports.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Tambahkan validasi reporter_phone
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:3000'],
            'address' => ['required', 'string', 'max:1000'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'reporter_phone' => ['required', 'string', 'max:20'], // Validasi Nomor HP
        ]);

        try {
            DB::beginTransaction();

            $date = date('Ymd');
            $random = strtoupper(Str::random(6));
            $reportCode = "ANG-{$date}-{$random}";

            $report = Report::create([
                'report_code' => $reportCode,
                'user_id' => Auth::id(),
                'reporter_phone' => $validated['reporter_phone'], // Simpan Nomor HP
                'category_id' => $validated['category_id'],
                'description' => $validated['description'],
                'address' => $validated['address'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'status' => 'submitted',
            ]);

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $path = $file->store('reports', 'public');

                ReportPhoto::create([
                    'report_id' => $report->id,
                    'uploaded_by' => Auth::id(),
                    'photo_type' => 'initial',
                    'file_path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }

            DB::commit();

            if (Auth::check()) {
                return redirect()->route('dashboard')->with('success', "Laporan berhasil dikirim!");
            } else {
                // Instruksikan pengguna untuk mengingat Nomor HP yang digunakan
                return redirect()->route('reports.create')->with('success', "Laporan berhasil dikirim! Gunakan nomor HP yang Anda daftarkan untuk melacak laporan ini.");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem saat menyimpan laporan.'])->withInput();
        }
    }

    public function dashboard()
    {
        $reports = Report::with('category')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.citizen', compact('reports'));
    }
}