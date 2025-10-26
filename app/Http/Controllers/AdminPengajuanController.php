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

        $list = $query->paginate(15)->withQueryString(); // withQueryString appends the query params to pagination links

        return response()->json($list);
    }

    public function show($id)
    {
        return response()->json(PengajuanSurat::with('pemohon','jenis','suratTerbit')->findOrFail($id));
    }

    public function reject(Request $request, $id)
    {
        $p = PengajuanSurat::findOrFail($id);
        $request->validate(['alasan' => 'required|string']);
        $p->status = 'ditolak';
        $p->alasan_penolakan = $request->alasan;
        $p->save();

        return response()->json([
            'message' => 'Pengajuan ditolak',
            'data' => $p->load('pemohon', 'jenis', 'suratTerbit')
        ]);
    }

    public function approve($id)
    {
        $p = PengajuanSurat::findOrFail($id);
        $p->status = 'disetujui_verifikasi';
        $p->save();

        return response()->json([
            'message' => 'Pengajuan disetujui, siap digenerate',
            'data' => $p->load('pemohon', 'jenis', 'suratTerbit')
        ]);
    }

    public function generate(Request $request, $id, SuratGeneratorService $generator)
    {
        $p = PengajuanSurat::with('jenis','pemohon')->findOrFail($id);

        if ($p->status !== 'disetujui_verifikasi') {
            return response()->json(['message' => 'Pengajuan belum disetujui atau tidak dalam status yang benar'], 422);
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

        return response()->json(['message' => 'Dokumen dibuat', 'file' => $output['url']]);
    }
}
