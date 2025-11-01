<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\SuratTerbit;
use App\Services\SuratGeneratorService;

class AdminPengajuanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = PengajuanSurat::with('pemohon', 'jenis', 'suratTerbit')->orderBy('created_at', 'desc');

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->whereHas('pemohon', function ($q) use ($searchTerm) {
                    $q->where('nama', 'like', "%{$searchTerm}%")
                      ->orWhere('nik', 'like', "%{$searchTerm}%");
                });
            }

            $list = $query->paginate(15)->withQueryString();

            // Check if this is an AJAX request (for data loading)
            if ($request->header('Accept') === 'application/json' || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data pengajuan berhasil dimuat',
                    'data' => $list
                ]);
            }

            // For web requests, return the view with data
            return view('admin.pengajuan.index', compact('list'));
        } catch (\Exception $e) {
            if ($request->header('Accept') === 'application/json' || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat data pengajuan',
                    'error' => $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    public function show($id, Request $request)
    {
        try {
            $pengajuan = PengajuanSurat::with('pemohon','jenis','suratTerbit')->findOrFail($id);
            
            // Check if this is a web request or API request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data pengajuan berhasil dimuat',
                    'data' => $pengajuan
                ]);
            }

            // For web requests, return the view with data
            return view('admin.pengajuan.detail', compact('pengajuan'));
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan tidak ditemukan',
                    'error' => $e->getMessage()
                ], 404);
            }
            abort(404);
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $p = PengajuanSurat::findOrFail($id);
            $request->validate(['alasan' => 'required|string']);
            $p->status = 'ditolak';
            $p->alasan_penolakan = $request->alasan;
            $p->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan ditolak',
                'data' => $p->load('pemohon', 'jenis', 'suratTerbit')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak pengajuan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function approve($id)
    {
        try {
            $p = PengajuanSurat::findOrFail($id);
            $p->status = 'disetujui_verifikasi';
            $p->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan disetujui, siap digenerate',
                'data' => $p->load('pemohon', 'jenis', 'suratTerbit')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui pengajuan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function generate(Request $request, $id, SuratGeneratorService $generator)
    {
        try {
            $p = PengajuanSurat::with('jenis','pemohon')->findOrFail($id);
    
            if ($p->status !== 'disetujui_verifikasi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan belum disetujui atau tidak dalam status yang benar'
                ], 422);
            }
    
            $output = $generator->generateFromTemplate($p);
    
            $surat = SuratTerbit::create([
                'pengajuan_id' => $p->id,
                'file_surat' => $output['path'],
                'tanggal_terbit' => now(),
                'status_cetak' => 'menunggu_tanda_tangan'
            ]);
    
            $p->status = 'menunggu_tanda_tangan';
            $p->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dibuat',
                'file' => $output['url']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat dokumen',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function dashboardSummary()
    {
        try {
            $jumlahPengajuanBaru = PengajuanSurat::where('status', 'menunggu')->count();
            $jumlahSuratDisetujui = PengajuanSurat::where('status', 'disetujui_verifikasi')->count();
            $jumlahSuratDitolak = PengajuanSurat::where('status', 'ditolak')->count();
            $jumlahSuratTerbitHariIni = SuratTerbit::whereDate('tanggal_terbit', now()->toDateString())->count();

            return response()->json([
                'success' => true,
                'message' => 'Summary dashboard berhasil dimuat',
                'data' => [
                    'jumlah_pengajuan_baru' => $jumlahPengajuanBaru,
                    'jumlah_surat_disetujui' => $jumlahSuratDisetujui,
                    'jumlah_surat_ditolak' => $jumlahSuratDitolak,
                    'jumlah_surat_terbit_hari_ini' => $jumlahSuratTerbitHariIni,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat summary dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
