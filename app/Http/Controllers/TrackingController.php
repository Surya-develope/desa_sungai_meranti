<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;

class TrackingController extends Controller
{
    public function check($kode)
    {
        $data = PengajuanSurat::where('tracking_code', $kode)
            ->with(['user', 'suratType', 'riwayat' => fn($q) => $q->orderBy('created_at')])
            ->firstOrFail();

        return response()->json($data);
    }
}
