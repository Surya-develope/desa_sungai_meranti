<?php

namespace App\Http\Controllers\Sekdes;

use App\Http\Controllers\Controller;
use App\Models\{PengajuanSurat, RiwayatStatus};
use App\Services\SuratGeneratorService;

class ProsesSuratController extends Controller
{
    public function generate($id, SuratGeneratorService $generator)
    {
        $pengajuan = PengajuanSurat::with('details','suratType')->findOrFail($id);
        $path = $generator->generate($pengajuan);

        $pengajuan->update(['status' => 'diproses', 'keterangan' => $path]);

        RiwayatStatus::create([
            'pengajuan_id' => $pengajuan->id,
            'status' => 'diproses',
            'updated_by' => auth()->id(),
            'catatan' => 'Surat digenerate oleh Sekdes.'
        ]);

        return response()->json(['message' => 'Surat berhasil digenerate', 'path' => $path]);
    }
}
