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
            $query = PengajuanSurat::with('pemohon', 'jenis')->orderBy('created_at', 'desc');

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Search by applicant name or NIK
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->whereHas('pemohon', function ($q) use ($searchTerm) {
                    $q->where('nama', 'like', "%{$searchTerm}%")
                      ->orWhere('nik', 'like', "%{$searchTerm}%");
                });
            }

            $list = $query->paginate(15)->withQueryString();

            return response()->json([
                'success' => true,
                'message' => 'Data pengajuan berhasil dimuat',
                'data' => $list
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data pengajuan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $pengajuan = PengajuanSurat::with('pemohon','jenis','suratTerbit')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Data pengajuan berhasil dimuat',
                'data' => $pengajuan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
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

            // panggil service generator
            $output = $generator->generateFromTemplate($p);

            // simpan record surat_terbit
            $surat = SuratTerbit::create([
                'pengajuan_id' => $p->id,
                'file_surat' => $output['path'],
                'tanggal_terbit' => now(),
                'status_cetak' => 'menunggu_tanda_tangan'
            ]);

            // update pengajuan
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
}
