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
            'password' => Hash::make($r->password), // Pastikan password di-hash
            'role_id' => 2 // misal 2 = warga
        ]);

        return response()->json($user, 201);
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
            throw ValidationException::withMessages(['email' => ['Credentials incorrect']]);
        }

        // buat token sanctum
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}
