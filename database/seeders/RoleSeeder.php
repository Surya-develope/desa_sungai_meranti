<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ✅ ini yang penting

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('role')->insert([
            ['nama_role' => 'admin'],
            ['nama_role' => 'warga'],
        ]);
    }
}
