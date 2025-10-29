<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;

class TrackingController extends Controller
{
    public function index()
    {
        return view('warga.tracking');
    }

    public function show(Request $request, $id = null)
    {
        // Remove # from ID if present
        $id = str_replace('#', '', $id);
        
        if (!$id || !is_numeric($id)) {
            return view('warga.tracking', [
                'pengajuan' => null,
                'search' => $request->get('id')
            ]);
        }

        $pengajuan = PengajuanSurat::with(['user', 'jenis_surat', 'suratTerbit'])
            ->where('id', $id)
            ->first();

        return view('warga.tracking', [
            'pengajuan' => $pengajuan,
            'search' => $id
        ]);
    }
}
