<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDesa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function register(Request $r)
    {
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

            if ($r->filled('kode_rahasia')) {
                $kodeBenar = env('ADMIN_SECRET_CODE');
                if ($r->kode_rahasia !== $kodeBenar) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kode rahasia tidak valid, gagal membuat akun admin',
                    ], 403);
                }
                $roleId = 1;
            }

            try {
                $user = UserDesa::create([
                    'nik' => $r->nik,
                    'nama' => $r->nama,
                    'email' => $r->email,
                    'password' => $r->password,
                    'role_id' => $roleId,
                ]);
            } catch (QueryException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan data user. Pastikan data valid dan tidak melanggar batasan database.',
                ], 400);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data user.',
                ], 500);
            }

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

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Data validation error',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function login(Request $r)
    {
        if ($r->isJson() && count($r->all()) == 0) {
            return response()->json(['message' => 'Data tidak boleh kosong'], 400);
        }

        try {
            $validated = $r->validate([
                'nik' => 'required',
                'password' => 'required'
            ]);

            $user = UserDesa::with('role')->where('nik', $validated['nik'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'message' => 'NIK atau password salah',
                    'errors' => ['nik' => ['Credentials incorrect']]
                ], 422);
            }

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
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat login. Silakan coba lagi.',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
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
                'message' => 'Logout gagal, silakan coba lagi.',
            ], 500);
        }
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
                'message' => 'Gagal mengambil data user.',
            ], 500);
        }
    }
}
