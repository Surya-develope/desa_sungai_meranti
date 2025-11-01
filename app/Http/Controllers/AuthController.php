<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDesa;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $r)
    {
        if ($r->isJson() && count($r->all()) === 0) {
            return response()->json(['message' => 'Data tidak boleh kosong'], 400);
        }

        try {
            $r->validate([
                'nik' => 'required|string|size:16|unique:user_desa,nik',
                'nama' => 'required|string',
                'email' => 'required|email|unique:user_desa,email',
                'password' => 'required|min:6'
            ]);

            // Default role "warga"
            $role = Role::where('nama_role', 'warga')->first();
            if (!$role) {
                return response()->json(['success' => false, 'message' => 'Role warga tidak ditemukan'], 500);
            }

            // Jika kode rahasia ada, set role admin
            if ($r->filled('kode_rahasia')) {
                $kodeBenar = env('ADMIN_SECRET_CODE');
                if ($r->kode_rahasia !== $kodeBenar) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kode rahasia tidak valid, gagal membuat akun admin',
                    ], 403);
                }
                $adminRole = Role::where('nama_role', 'admin')->first();
                if ($adminRole) {
                    $role = $adminRole;
                }
            }

            try {
                $user = UserDesa::create([
                    'nik' => $r->nik,
                    'nama' => $r->nama,
                    'email' => $r->email,
                    'password' => $r->password,
                    'role_id' => $role->id,
                ]);
            } catch (QueryException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan data user. Pastikan data valid dan tidak melanggar batasan database.',
                ], 400);
            }

            // For web registration, log the user in and redirect
            if (!$r->isJson()) {
                Auth::login($user);

                $roleName = $role->nama_role ?? 'warga';
                $dashboardRoute = $roleName === 'admin' ? 'admin.dashboard' : 'warga.dashboard';
                $successMessage = $roleName === 'admin'
                    ? 'Registrasi berhasil sebagai admin! Selamat datang ' . $user->nama
                    : 'Registrasi berhasil! Selamat datang ' . $user->nama;

                return redirect()->intended(route($dashboardRoute))->with('success', $successMessage);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => $role->nama_role === 'admin' ? 'Registrasi berhasil sebagai admin' : 'Registrasi berhasil sebagai warga',
                'token' => $token,
                'user' => [
                    'nik' => $user->nik,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'role_id' => $user->role_id
                ]
            ], 201);

        } catch (ValidationException $e) {
            if (!$r->isJson()) {
                return back()->withErrors($e->errors())->withInput();
            }
            return response()->json([
                'message' => 'Data validation error',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function login(Request $r)
    {
        if ($r->isJson() && count($r->all()) === 0) {
            return response()->json(['message' => 'Data tidak boleh kosong'], 400);
        }

        try {
            $validated = $r->validate([
                'nik' => 'required',
                'password' => 'required'
            ]);

            $user = UserDesa::with('role')->where('nik', $validated['nik'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                // Handle web form submission
                if (!$r->isJson()) {
                    return back()->withErrors([
                        'nik' => 'NIK atau password salah'
                    ])->withInput($r->only('nik'));
                }
                
                return response()->json([
                    'message' => 'NIK atau password salah',
                    'errors' => ['nik' => ['Credentials incorrect']]
                ], 422);
            }

            // Handle web form submission
            if (!$r->isJson()) {
                Auth::login($user);
                
                // Redirect based on user role
                $roleName = $user->role ? $user->role->nama_role : 'warga';
                
                if ($roleName === 'admin') {
                    return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang, ' . $user->nama . '!');
                } else {
                    return redirect()->intended(route('warga.dashboard'))->with('success', 'Selamat datang, ' . $user->nama . '!');
                }
            }

            // Handle API login
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
            if (!$r->isJson()) {
                return back()->withErrors($e->errors())->withInput();
            }
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Handle web logout
            if (!$request->isJson()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('success', 'Berhasil logout');
            }

            // Handle API logout
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

    // Password Reset Methods
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        // Placeholder implementation - replace with actual email sending
        return redirect()->route('login')->with('success', 'Link reset password telah dikirim ke email Anda');
    }

    public function showResetPasswordForm($token = null)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        // Placeholder implementation - replace with actual password reset logic
        return redirect()->route('login')->with('success', 'Password berhasil diupdate');
    }
}
