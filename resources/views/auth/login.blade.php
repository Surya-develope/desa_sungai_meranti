@extends('layouts.app')

@section('title', 'Login Pengajuan Surat')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 bg-gray-50">
    <div class="w-full max-w-md">
        <!-- Card Login -->
        <div class="bg-white p-8 md:p-10 rounded-3xl shadow-2xl border-t-8 border-green-700 transition-all duration-300 transform hover:shadow-3xl">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-green-800">Masuk Layanan Warga</h1>
                <p class="text-gray-600 mt-2">Gunakan NIK dan Kata Sandi Anda untuk mengakses layanan administrasi.</p>
            </div>

            <!-- Pesan Error Global -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4" role="alert">
                    <strong class="font-bold">Gagal Masuk!</strong>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="nik" class="block text-sm font-semibold text-gray-700 mb-1">NIK (Nomor Induk Kependudukan)</label>
                    <input id="nik" type="text" name="nik" value="{{ old('nik') }}" required autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-green-500 focus:border-green-500 transition duration-150 @error('nik') border-red-500 @enderror"
                           placeholder="Contoh: 33xxxxxxxxxxxxxx">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Kata Sandi</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-green-500 focus:border-green-500 transition duration-150 @error('password') border-red-500 @enderror"
                           placeholder="Masukkan Kata Sandi Anda">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Ingat Saya
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-lg font-bold text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200 transform hover:scale-[1.01]">
                        Masuk
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <a href="#" class="text-sm text-green-600 hover:text-green-700 hover:underline font-medium transition duration-150">
                    Lupa Kata Sandi? Hubungi Admin.
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
