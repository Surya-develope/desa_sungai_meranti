<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDesa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function register(Request $r)
    {
        // Memeriksa jika request body kosong
        if ($r->isJson() && count($r->all()) == 0) {
            return response()->json(['message' => 'Data tidak boleh kosong'], 400);
        }

        try {
            $r->validate([
                'nik' => 'required|string|size:16|unique:user_desa,nik',
                'nama' => 'required|string',
                'email' => 'required|email|unique:user_desa,email',
                'password' => 'required|min:6'
            ]);

             $roleId = 2;

           // Jika user mengisi kode rahasia → cek validitas
        if ($r->filled('kode_rahasia')) {
            $kodeBenar = env('ADMIN_SECRET_CODE');
            if ($r->kode_rahasia !== $kodeBenar) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode rahasia tidak valid, gagal membuat akun admin',
                ], 403);
            }
            $roleId = 1; // role admin
        }

        // Buat user
        $user = UserDesa::create([
            'nik' => $r->nik,
            'nama' => $r->nama,
            'email' => $r->email,
            'password' => $r->password, // di-hash otomatis di model
            'role_id' => $roleId,
        ]);

            // Buat token sanctum untuk auto-login setelah register
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => $roleId === 1 ? 'Registrasi berhasil sebagai admin' : 'Registrasi berhasil sebagai warga',
                'token' => $token,
                'user' => [
                    'nik' => $user->nik,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'role_id' => $user->role_id
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Data validation error',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function login(Request $r)
    {
        // Memeriksa jika request body kosong
        if ($r->isJson() && count($r->all()) == 0) {
            return response()->json(['message' => 'Data tidak boleh kosong'], 400);
        }

        $expectsJson = $r->expectsJson() || $r->wantsJson() || $r->isJson();

        $validated = $r->validate([
            'nik' => 'required',
            'password' => 'required'
        ]);

        $user = UserDesa::with('role')->where('nik', $validated['nik'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            if ($expectsJson) {
                return response()->json([
                    'message' => 'NIK atau password salah',
                    'errors' => ['nik' => ['Credentials incorrect']]
                ], 422);
            }

            return back()
                ->withErrors(['nik' => 'NIK atau password salah'])
                ->withInput($r->only('nik'));
        }

        if ($expectsJson) {
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'token' => $token,
                'user' => [
                    'nik' => $user->nik,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'role_id' => $user->role_id
                ]
            ]);
        }

        Auth::login($user);

        $r->session()->regenerate();

        $roleName = optional($user->role)->nama_role;
        $redirectRoute = $roleName === 'admin' ? 'admin.dashboard' : 'warga.dashboard';

        return redirect()->route($redirectRoute);
    }

    public function logout(Request $request)
    {
        if ($request->expectsJson() || $request->wantsJson() || $request->isJson()) {
            try {
                $user = $request->user();
                if ($user && $user->currentAccessToken()) {
                    $user->currentAccessToken()->delete();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Logout berhasil'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Logout gagal',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }


    public function user(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'user' => [
                    'nik' => $user->nik,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'role_id' => $user->role_id
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error mengambil data user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
