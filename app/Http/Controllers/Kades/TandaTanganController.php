<?php

namespace App\Http\Controllers\Kades;

use App\Http\Controllers\Controller;
use App\Models\{PengajuanSurat, RiwayatStatus};

class TandaTanganController extends Controller
{
    public function approve($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->update(['status' => 'siap_dijemput']);

        RiwayatStatus::create([
            'pengajuan_id' => $pengajuan->id,
            'status' => 'siap_dijemput',
            'updated_by' => auth()->id(),
            'catatan' => 'Surat telah ditandatangani oleh Kepala Desa.'
        ]);

        return response()->json(['message' => 'Surat siap dijemput']);
    }
}
