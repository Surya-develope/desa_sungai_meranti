<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDesa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $r)
    {
        // Memeriksa jika request body kosong
        if ($r->isJson() && count($r->all()) == 0) {
            return response()->json(['message' => 'Data tidak boleh kosong'], 400);
        }

        try {
            $r->validate([
                'nik' => 'required|string|unique:user_desa,nik',
                'nama' => 'required|string',
                'email' => 'required|email|unique:user_desa,email',
                'password' => 'required|min:6'
            ]);

            $user = UserDesa::create([
                'nik' => $r->nik,
                'nama' => $r->nama,
                'email' => $r->email,
                'password' => $r->password, // Password akan di-hash otomatis oleh setPasswordAttribute
                'role_id' => 2 // misal 2 = warga
            ]);

            // Buat token sanctum untuk auto-login setelah register
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
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

        $r->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = UserDesa::where('email', $r->email)->first();

        if (!$user || !Hash::check($r->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah',
                'errors' => ['email' => ['Credentials incorrect']]
            ], 422);
        }

        // Buat token sanctum
        $token = $user->createToken('api-token')->plainTextToken;
        
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'email' => $user->email,
                'nik' => $user->nik,
                'role_id' => $user->role_id
            ]
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
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
                    'id' => $user->id,
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
