<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder RoleSeeder
        $this->call(RoleSeeder::class);

        // Kalau kamu ingin tambahkan seeder lain nanti, tinggal panggil di sini.
        // Contoh:
        // $this->call(UserDesaSeeder::class);
    }
}
