<?php    

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\{PengajuanSurat, FileUpload, RiwayatStatus};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PengajuanController extends Controller
{
    public function index()
    {
        return response()->json(auth()->user()->pengajuans()->with('suratType')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'surat_type_id' => 'required|exists:surat_types,id',
            'files.*' => 'file|max:2048'
        ]);

        $pengajuan = PengajuanSurat::create([
            'user_id' => auth()->id(),
            'surat_type_id' => $request->surat_type_id,
            'status' => 'diajukan',
            'tracking_code' => strtoupper(Str::random(10))
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('uploads', 'public');
                FileUpload::create([
                    'pengajuan_id' => $pengajuan->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType()
                ]);
            }
        }

        RiwayatStatus::create([
            'pengajuan_id' => $pengajuan->id,
            'status' => 'diajukan',
            'updated_by' => auth()->id(),
            'catatan' => 'Pengajuan surat dibuat oleh warga.'
        ]);

        return response()->json(['message' => 'Pengajuan berhasil dibuat', 'data' => $pengajuan]);
    }
}
