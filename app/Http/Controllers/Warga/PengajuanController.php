<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
// Import Model yang dibutuhkan untuk operasi CRUD pengajuan
use App\Models\{PengajuanSurat, FileUpload, RiwayatStatus};

class PengajuanController extends Controller
{
    // =========================================================================
    // BAGIAN 1: METODE OTENTIKASI (Untuk Route /login dan /logout)
    // =========================================================================

    /**
     * Tampilkan formulir login warga.
     * Dipanggil oleh Route::get('/login').
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses upaya login warga.
     * Dipanggil oleh Route::post('/login').
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nik' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Redirect ke halaman default '/warga/pengajuan'
            return redirect()->intended('/warga/pengajuan');
        }

        // Jika gagal, lemparkan error validasi
        throw ValidationException::withMessages([
            'nik' => 'NIK atau Kata Sandi yang Anda masukkan salah.',
        ]);
    }

    /**
     * Logout pengguna yang sedang aktif.
     * Dipanggil oleh Route::post('/logout').
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    
    // =========================================================================
    // BAGIAN 2: METODE APLIKASI UTAMA (Untuk Route /warga/pengajuan)
    // =========================================================================

    /**
     * Menampilkan daftar jenis surat untuk pengajuan online (Dashboard Warga).
     * Dipanggil oleh Route::get('/warga/pengajuan').
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengubah respons dari JSON menjadi view Blade
        return view('surat');
    }

    /**
     * Menyimpan data pengajuan surat baru.
     * Dipanggil oleh Route::post('/warga/pengajuan').
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'surat_type_id' => 'required|exists:surat_types,id',
            // Gunakan 'required' jika files wajib, atau biarkan seperti ini jika opsional
            'files.*' => 'file|max:2048' // Batas 2MB per file
        ]);

        // 1. Buat Pengajuan Surat
        $pengajuan = PengajuanSurat::create([
            'user_id' => auth()->id(),
            'surat_type_id' => $request->surat_type_id,
            'status' => 'diajukan', // Status awal
            'tracking_code' => strtoupper(Str::random(10)) // Generate kode unik
        ]);

        // 2. Upload dan Catat File
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Simpan file ke direktori 'uploads' di public disk
                $path = $file->store('uploads', 'public'); 
                
                FileUpload::create([
                    'pengajuan_id' => $pengajuan->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType()
                ]);
            }
        }

        // 3. Catat Riwayat Status Awal
        RiwayatStatus::create([
            'pengajuan_id' => $pengajuan->id,
            'status' => 'diajukan',
            'updated_by' => auth()->id(),
            'catatan' => 'Pengajuan surat dibuat oleh warga.'
        ]);

        // Berikan respons sukses (sesuai input Anda)
        return response()->json(['message' => 'Pengajuan berhasil dibuat', 'data' => $pengajuan]);
    }
}
