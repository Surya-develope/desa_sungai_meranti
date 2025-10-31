<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use App\Models\SuratTerbit;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        try {
            // Get statistics
            $stats = [
                'total_pengajuan' => PengajuanSurat::count(),
                'pending' => PengajuanSurat::where('status', 'menunggu')->count(),
                'disetujui' => PengajuanSurat::where('status', 'disetujui_verifikasi')->count(),
                'ditolak' => PengajuanSurat::where('status', 'ditolak')->count(),
            ];

            // Get recent pengajuan
            $recentPengajuan = PengajuanSurat::with('pemohon', 'jenis')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view('admin.dashboard', compact('stats', 'recentPengajuan'));
        } catch (\Exception $e) {
            // If there's an error, still show the page with default values
            $stats = [
                'total_pengajuan' => 0,
                'pending' => 0,
                'disetujui' => 0,
                'ditolak' => 0,
            ];
            $recentPengajuan = collect();
            return view('admin.dashboard', compact('stats', 'recentPengajuan'));
        }
    }

    public function dashboardStats(Request $request)
    {
        try {
            $jumlahPengajuanBaru = PengajuanSurat::where('status', 'menunggu')->count();
            $jumlahSuratDisetujui = PengajuanSurat::where('status', 'disetujui_verifikasi')->count();
            $jumlahSuratDitolak = PengajuanSurat::where('status', 'ditolak')->count();
            $jumlahSuratTerbitHariIni = SuratTerbit::whereDate('tanggal_terbit', now()->toDateString())->count();

            $data = [
                'total_pengajuan' => PengajuanSurat::count(),
                'pending' => $jumlahPengajuanBaru,
                'disetujui' => $jumlahSuratDisetujui,
                'ditolak' => $jumlahSuratDitolak,
                'surat_terbit_hari_ini' => $jumlahSuratTerbitHariIni,
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Summary dashboard berhasil dimuat',
                    'data' => $data
                ]);
            }

            // For web requests, return the data for the view
            return $data;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat summary dashboard',
                    'error' => $e->getMessage()
                ], 500);
            }
            // For web requests, return default values
            return [
                'total_pengajuan' => 0,
                'pending' => 0,
                'disetujui' => 0,
                'ditolak' => 0,
                'surat_terbit_hari_ini' => 0,
            ];
        }
    }

    public function recentPengajuan()
    {
        try {
            $recentPengajuan = PengajuanSurat::with('pemohon', 'jenis')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json($recentPengajuan);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data recent pengajuan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}