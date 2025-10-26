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
        $r->validate([
            'nik'=>'required|string|unique:user_desa,nik',
            'nama'=>'required|string',
            'email'=>'nullable|email|unique:user_desa,email',
            'password'=>'required|min:6'
        ]);

        $user = UserDesa::create([
            'nik'=>$r->nik,
            'nama'=>$r->nama,
            'email'=>$r->email,
            'password'=>$r->password, // Hashing akan ditangani oleh mutator di model
            'role_id'=>2 // misal 2 = warga
        ]);

        return response()->json($user,201);
    }

    public function login(Request $r)
    {
        $r->validate(['email'=>'required|email','password'=>'required']);

        $user = UserDesa::where('email', $r->email)->first();
        if (!$user || !Hash::check($r->password, $user->password)) {
            throw ValidationException::withMessages(['email' => ['Credentials incorrect']]);
        }

        // buat token sanctum
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['token'=>$token, 'user'=>$user]);
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
