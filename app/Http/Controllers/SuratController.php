<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Services\SuratGeneratorService;

class SuratController extends Controller
{
    public function generate(Request $request, $id, SuratGeneratorService $generator)
    {
        $pengajuan = PengajuanSurat::find($id);
        if (!$pengajuan) {
            return response()->json(['error' => 'Data pengajuan tidak ditemukan'], 404);
        }

        try {
            $result = $generator->generateFromTemplate($pengajuan);
            $pengajuan->update(['status' => 'selesai', 'file_surat' => $result['pdf']]);
            return response()->json([
                'success' => true,
                'message' => 'Surat berhasil digenerate',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}