<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\JenisSurat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{

    public function index(Request $request)
    {
        try {
            $userNik = $request->user()->nik;
            $pengajuanList = PengajuanSurat::with('jenis')
                ->where('nik_pemohon', $userNik)
                ->orderByDesc('tanggal_pengajuan')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar pengajuan berhasil dimuat',
                'data' => $pengajuanList
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat daftar pengajuan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cancel(Request $request, $id)
    {
        try {
            $pengajuan = PengajuanSurat::findOrFail($id);

            if ($pengajuan->nik_pemohon !== $request->user()->nik) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak berhak membatalkan pengajuan ini'
                ], 403);
            }

            if ($pengajuan->status !== 'menunggu') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan hanya bisa dibatalkan jika status menunggu'
                ], 400);
            }

            $pengajuan->status = 'dibatalkan';
            $pengajuan->save();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dibatalkan',
                'data' => $pengajuan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan pengajuan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function AddPengajuan(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate the request
            $validated = $request->validate([
                'jenis_surat_id' => 'required|integer|exists:jenis_surat,id',
                'data_pemohon' => 'required|array',
                'data_pemohon.nama' => 'required|string',
                'data_pemohon.nik_pemohon' => 'required|string',
                'data_pemohon.alamat' => 'required|string',
                'keterangan' => 'required|string',
                'file_syarat.*' => 'sometimes|file|mimes:jpg,jpeg,png,pdf,docx|max:204', // 5MB, mendukung docx & xlsx
            ]);

            $nik = $validated['data_pemohon']['nik_pemohon'];
            $jenisSuratId = $validated['jenis_surat_id'];
            $dataPemohon = $validated['data_pemohon'];
            $keterangan = $validated['keterangan'];

            // Upload files
            $files = $this->uploadFiles($request, $jenisSuratId);

            // Create pengajuan
            $pengajuan = $this->createPengajuan($nik, $jenisSuratId, $dataPemohon, $files, $keterangan);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim',
                'data' => [
                    'id' => $pengajuan->id ?? $pengajuan->nik_pemohon,
                    'nik_pemohon' => $pengajuan->nik_pemohon,
                    'jenis_surat_id' => $pengajuan->jenis_surat_id,
                    'tanggal_pengajuan' => $pengajuan->tanggal_pengajuan,
                    'status' => $pengajuan->status,
                    'data_isian' => $pengajuan->data_isian,
                    'file_syarat' => $pengajuan->file_syarat
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Data validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengajukan surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
    private function uploadFiles(Request $request, $jenisSuratId)
    {
        $files = [];
        if ($request->hasFile('file_syarat')) {
            foreach ($request->file('file_syarat') as $file) {
                $ext = strtolower($file->getClientOriginalExtension());
                $allowedExt = ['jpg', 'jpeg', 'png', 'pdf', 'docx', 'xlsx'];
                if (!in_array($ext, $allowedExt)) {
                    continue; // skip file tidak valid
                }

                // simpan file ke folder berdasarkan jenis surat
                $folder = 'public/persyaratan/' . $jenisSuratId;
                $filename = Str::uuid() . '.' . $ext;
                $path = $file->storeAs($folder, $filename);

                // catat metadata file
                $files[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime' => $file->getMimeType(),
                    'size_kb' => round($file->getSize() / 1024, 2)
                ];
            }
        }
        return $files;
    }
  
    private function createPengajuan($nik, $jenisSuratId, $dataPemohon, $files, $keterangan)
    {
        return PengajuanSurat::create([
            'nik_pemohon' => $nik,
            'jenis_surat_id' => $jenisSuratId,
            'tanggal_pengajuan' => now()->toDateString(),
            'status' => 'menunggu',
            'data_isian' => [
                'data_pemohon' => $dataPemohon,
                'keterangan' => $keterangan
            ],
            'file_syarat' => $files,
        ]);
    }

    public function show($id)
    {
        try {
            $pengajuan = PengajuanSurat::with('jenis','suratTerbit','pemohon')->findOrFail($id);

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

    public function create(Request $request)
    {
        $jenisSuratList = JenisSurat::where('is_active', true)->get();
        $selectedJenisId = $request->query('jenis'); // Get selected jenis from URL parameter
        $user = $request->user(); // Get authenticated user
        return view('warga/pengajuan/form-pengajuan', compact('jenisSuratList', 'selectedJenisId', 'user'));
    }

    public function jenis()
    {
        $jenisSuratList = JenisSurat::where('is_active', true)->get();
        return view('warga.jenis-surat', compact('jenisSuratList'));
    }

    public function getFormStructure($jenisSuratId)
    {
        try {
            $jenisSurat = JenisSurat::findOrFail($jenisSuratId);
            
            $formStructure = [];
            
            if ($jenisSurat->form_structure) {
                // Check if it's already an array or needs to be decoded from JSON
                if (is_array($jenisSurat->form_structure)) {
                    $formStructure = $jenisSurat->form_structure;
                } else {
                    $formStructure = json_decode($jenisSurat->form_structure, true);
                }
            }
            
            // If no form structure, return empty array (frontend will show "no data" message)
            if (empty($formStructure)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Form structure berhasil dimuat',
                    'data' => []
                ]);
            } else {
                // Convert existing structure to match expected format
                $formStructure = array_map(function($field) {
                    return [
                        'key' => $field['name'] ?? $field['field_name'] ?? '',
                        'name' => $field['name'] ?? $field['field_name'] ?? '',
                        'label' => $field['label'] ?? $field['name'] ?? $field['field_name'] ?? '',
                        'type' => $field['type'] ?? 'text'
                    ];
                }, $formStructure);
            }

            return response()->json([
                'success' => true,
                'message' => 'Form structure berhasil dimuat',
                'data' => $formStructure
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat struktur form',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            // Handle web form submission - support both dynamic and legacy formats
            $rules = [
                'jenis_surat_id' => 'required|exists:jenis_surat,id',
                'keterangan' => 'required|string',
                'ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'dokumen_lainnya' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
                'agree_terms' => 'required',
            ];

            // Check if this is a dynamic form submission
            if ($request->has('data_pemohon') && is_array($request->data_pemohon)) {
                // Dynamic form - validate the data_pemohon array
                $dataPemohon = $request->data_pemohon;
                $rules['data_pemohon.*'] = 'required|string';
            } else {
                // Legacy form - validate individual fields
                $rules = array_merge($rules, [
                    'nama_pemohon' => 'required|string',
                    'nik_pemohon' => 'required|string|size:16',
                    'tempat_lahir' => 'required|string',
                    'tanggal_lahir' => 'required|date',
                    'alamat' => 'required|string',
                    'no_hp' => 'required|string',
                    'pekerjaan' => 'required|string',
                ]);
            }

            $validated = $request->validate($rules);

            // Prepare data for API method
            if ($request->has('data_pemohon') && is_array($request->data_pemohon)) {
                // Dynamic form data
                $webRequest = new Request([
                    'jenis_surat_id' => $validated['jenis_surat_id'],
                    'data_pemohon' => $validated['data_pemohon'],
                    'keterangan' => $validated['keterangan']
                ]);
            } else {
                // Legacy form data
                $webRequest = new Request([
                    'jenis_surat_id' => $validated['jenis_surat_id'],
                    'data_pemohon' => [
                        'nama' => $validated['nama_pemohon'],
                        'nik_pemohon' => $validated['nik_pemohon'],
                        'alamat' => $validated['alamat'],
                        'tempat_lahir' => $validated['tempat_lahir'],
                        'tanggal_lahir' => $validated['tanggal_lahir'],
                        'no_hp' => $validated['no_hp'],
                        'pekerjaan' => $validated['pekerjaan'],
                    ],
                    'keterangan' => $validated['keterangan']
                ]);
            }

            // Handle file uploads manually since we're converting from web form
            $webRequest->files->set('file_syarat', []);
            
            if ($request->hasFile('ktp')) {
                $webRequest->files->add([$request->file('ktp')]);
            }
            if ($request->hasFile('kk')) {
                $webRequest->files->add([$request->file('kk')]);
            }
            if ($request->hasFile('dokumen_lainnya')) {
                $webRequest->files->add([$request->file('dokumen_lainnya')]);
            }

            $response = $this->AddPengajuan($webRequest);
            $data = $response->getData();

            if ($data->success) {
                return redirect()->route('warga.dashboard')
                    ->with('success', 'Pengajuan berhasil dikirim! Nomor pengajuan: #' . $data->data->id);
            } else {
                return redirect()->back()
                    ->withErrors(['error' => $data->message])
                    ->withInput();
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Gagal mengirim pengajuan: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
