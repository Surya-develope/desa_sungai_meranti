<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\SuratTerbit;
use App\Services\DocumentGenerator;

class AdminPengajuanController extends Controller
{
    public function index()
    {
        $list = PengajuanSurat::with('pemohon','jenis')->orderBy('created_at','desc')->get();
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

        return response()->json(['message' => 'Pengajuan ditolak']);
    }

    public function approve($id)
    {
        $p = PengajuanSurat::findOrFail($id);
        $p->status = 'disetujui_verifikasi';
        $p->save();

        return response()->json(['message' => 'Pengajuan disetujui, siap digenerate']);
    }

    public function generate(Request $request, $id, DocumentGenerator $generator)
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
