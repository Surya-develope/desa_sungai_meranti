<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{PengajuanSurat, RiwayatStatus};
use Illuminate\Http\Request;


class ValidasiController extends Controller
{
    public function index()
    {
        return response()->json(PengajuanSurat::where('status', 'diajukan')->with('user')->get());
    }

    public function verify($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->update(['status' => 'diverifikasi']);

        RiwayatStatus::create([
            'pengajuan_id' => $pengajuan->id,
            'status' => 'diverifikasi',
            'updated_by' => auth()->id(),
            'catatan' => 'Pengajuan diverifikasi oleh admin.'
        ]);

        return response()->json(['message' => 'Surat telah diverifikasi']);
    }

    public function reject(Request $request, $id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->update(['status' => 'ditolak']);

        RiwayatStatus::create([
            'pengajuan_id' => $pengajuan->id,
            'status' => 'ditolak',
            'updated_by' => auth()->id(),
            'catatan' => $request->input('catatan', 'Dokumen tidak valid.')
        ]);

        return response()->json(['message' => 'Pengajuan ditolak']);
    }
}
