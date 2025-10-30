<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserDesa;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Get roles
        $adminRole = Role::where('nama_role', 'admin')->first();
        $wargaRole = Role::where('nama_role', 'warga')->first();

        if (!$adminRole || !$wargaRole) {
            $this->command->error('Roles not found. Please run RoleSeeder first.');
            return;
        }

        // Create Admin User
        UserDesa::firstOrCreate(
            ['nik' => '1234567890123456'],
            [
                'nama' => 'Budi Admin',
                'email' => 'admin@desasungaimeranti.id',
                'password' => 'admin123',
                'alamat' => 'Desa Sungai Meranti, Kec. Pinggir, Kab. Bengkalis',
                'no_hp' => '081234567890',
                'role_id' => $adminRole->id,
            ]
        );

        // Create Warga User
        UserDesa::firstOrCreate(
            ['nik' => '6543210987654321'],
            [
                'nama' => 'Siti Warga',
                'email' => 'siti@email.com',
                'password' => 'user123',
                'alamat' => 'RT 001 RW 001, Desa Sungai Meranti',
                'no_hp' => '087654321098',
                'role_id' => $wargaRole->id,
            ]
        );

        // Create Additional Test Users
        UserDesa::firstOrCreate(
            ['nik' => '1111222233334444'],
            [
                'nama' => 'Ahmad Sukma',
                'email' => 'ahmad@email.com',
                'password' => 'user123',
                'alamat' => 'RT 002 RW 001, Desa Sungai Meranti',
                'no_hp' => '085123456789',
                'role_id' => $wargaRole->id,
            ]
        );

        UserDesa::firstOrCreate(
            ['nik' => '5555666677778888'],
            [
                'nama' => 'Rina Melati',
                'email' => 'rina@email.com',
                'password' => 'user123',
                'alamat' => 'RT 003 RW 002, Desa Sungai Meranti',
                'no_hp' => '087812345678',
                'role_id' => $wargaRole->id,
            ]
        );

        UserDesa::firstOrCreate(
            ['nik' => '9999000011112222'],
            [
                'nama' => 'Pak Joko',
                'email' => 'joko@email.com',
                'password' => 'user123',
                'alamat' => 'RT 001 RW 003, Desa Sungai Meranti',
                'no_hp' => '081987654321',
                'role_id' => $wargaRole->id,
            ]
        );

        $this->command->info('5 test users created successfully:');
        $this->command->info('Admin: NIK: 1234567890123456, Password: admin123');
        $this->command->info('Warga: NIK: 6543210987654321, Password: user123');
        $this->command->info('Warga: NIK: 1111222233334444, Password: user123');
        $this->command->info('Warga: NIK: 5555666677778888, Password: user123');
        $this->command->info('Warga: NIK: 9999000011112222, Password: user123');
    }
}